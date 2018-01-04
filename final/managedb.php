<?php

require_once "check.php";

if(isset($_POST['action']) && !empty($_POST['action'])) {
	$action = $_POST['action'];
	echo "<script>console.log('inside');</script>";
	//makes a new connection
	$database = new mysqli("classdb.it.mtu.edu", "cjholmes", "buddy", "cjholmes");
	
	if($database->connect_errno) {
		echo "Could not connect to database";
	}

	if($action === 'getTakenExams') {
		getTakenExams($database); 
	}
	else if($action === 'getExam') {
		getExam($database, $_POST['examname']);
	}
	else if($action === 'getGraded') {
		getGraded($database, $_POST['examName']);
	}
	else if($action === 'getExamList') {
		getExamList($database);
	}
	else if($action === 'gradeQuestion') {
		gradeQuestion($database, $_POST['questionNumber'], $_POST['examName']);
	}
	else if($action === 'updateGrade') {
		updateGrade($database, $_POST['examName']);
	}
} else {
	header("Location: home.php");
	exit();
}
	
function getGraded($database, $examName) {

	$username = $_SESSION['username'];
	
	//get all the information for the given exam
        $statement = "SELECT * FROM exams WHERE name = '$examName';";
        $exam = $database->query($statement);

        $statement = "SELECT * FROM questions WHERE examname = '$examName';";
        $questions = $database->query($statement);

        $statement = "SELECT * FROM options where examname = '$examName';";
        $options = $database->query($statement);

	$statement = "SELECT * FROM grades WHERE id = 
		(SELECT id FROM exams WHERE name = '$examName') AND username = $username";
	$grade = $database->query($statement);  

        //check that there is the needed information
        if($exam->num_rows == 0){
                return json_encode(array("response"=>"This Exam Does Not Exist"));
        }
        if($questions->num_rows == 0){
                return json_encode(array("response"=>"There Are No Questions Made For This Exam"));
        }
        if($options->num_rows == 0){
                return json_encode(array("response"=>"There Are No Options Created For This Exam"));
        }
	if($grade->num_rows == 0){
		return json_encode(array("response"=>"No Grades Available For The Selected Exam"));
	}

	$eRow = $exam->fetch_assoc();
	$gradeRow = $grade->fetch_assoc();
	$gradeData = json_decode($gradeRow['data']);
	$gradeQ = $gradeData->questions;

	$optionsArray = array();
        while($row = $choices->fetch_assoc()){
                $newOption = new \stdClass;
                $newOption->number = $row['questionnum'];
                $newOption->id = $row['id'];
                $newOption->text = $row['text'];
                $newOption->correct = $row['correct'];
                $optionArray[] = $newOption;
        }
	
	$injection = "<h2>" . $examName . " ({$gradeData->pointsEarned}/{$gradeData->totalPoints})</h4>";
	$injection .= "<div id='examContainer'>";
	while($row = $question->fetch_assoc()){
		$question = $row['questionnumber'];	
		$points = $row['pointVal'];
		$pointsEarned = $gradeQ[$question - 1]->earnedPoints;

		$injection .= "<div class='question' id='q" . $row['questionnumber'] . "'>";
		$injection .= "<span style='display: block' class='questionText'>" . $row['question'] . " ($pointsEarned/$points)</span>";
		
		$right = new \stdClass;
		$user = new \stdClass;

		for($i = 0; i < count($opitonArray); $i++){
			if($optionArray[$i]){
				if($optionArray[$i]->number == $row['questionnumber'] && 
						$opitonArray[$i]->correct){
					$right = $optionsArray[$i];
				}
				if($opitonArray[$i]->number == $row'questionnumber' &&
						$optionsArray[$i]->id == $gradeQ[$question - 1]->studentAnswer){
					$user = $opitonArray[$i];
				}
			}
		}

		$injection .= "<span class='questionOptions'>Correct: </span>
					<span style='display: inline' class='rightAnswer'>" . $right->id . ". " . $right->text . "</span><br/>";
		$injection .= "<span class='questionOptions'>Your Answer: </span>
				<span style='display: inline' class='" . ($right->id == $user->id ? 'rightAnswer' : 'wrongAnswer') . "'>" . $user->id . ". " . $user->text . "</span>";
		$injection .= "</div>";
	}
	$injection .= "</div>";
	echo $injection;
}

function getTakenExams($database) {
	$username = $_SESSION['username'];

	//selects the names from the exam table where the id in graded exams by username is in exam
	$statement = "SELECT name FROM exams WHERE id IN 
		(SELECT exam FROM grades WHERE username = '$username');";
	//runs the statement and then iterates through all of the exams and adds them
	$examList = $database->query($statement);
	$examArray = array();
	while($row = $examList->fetch_assoc()) {
		$examArray[] = $row['name'];
	}

	$json = json_encode($examArray);
	echo $json; 
}

function getExamList($database) {
	//gets the username of the current session
	$username = $_SESSION['username'];	
        echo "<script>console.log('TESTgetExamList1');</script>";
	//selects the names of exams that haven't been taken
	$statement = "SELECT name FROM exams WHERE id NOT IN 
		(SELECT exam FROM grades WHERE username = '$username');";
	//runs the statement and then iterates through all of the exams and adds them
	$examList = $database->query($statement);
        $examArray = array();
        while($examList && ($row = $examList->fetch_assoc())) {
                $examArray[] = $row['name'];
        }

        $json = json_encode($examArray);
        echo $json;
}

function getExam($database, $examName){

	//get all the information for the given exam
	$statement = "SELECT * FROM exams WHERE name = '$examName';";
	$exam = $database->query($statement);
	
	$statement = "SELECT * FROM questions WHERE examname = '$examName';";
	$questions = $database->query($statement);
	
	$statement = "SELECT * FROM options where examname = '$examName';";
	$options = $database->query($statement);

	//check that there is the needed information
	if($exam->num_rows == 0){
		return json_encode(array("response"=>"This Exam Does Not Exist"));
	}
	if($questions->num_rows == 0){
		return json_encode(array("response"=>"There Are No Questions Made For This Exam"));
	}
	if($options->num_rows == 0){
		return json_encode(array("response"=>"There Are No Options Created For This Exam"));
	}

	//add the informaiton to an object to output
	$newObject = new \stdClass;

	$eRow = $exam->fetch_assoc();
	$newObject->name = $eRow['name'];
	$newObject->pointTotal = $eRow['total'];

	//add all the options
	$optionsArray = array();
	while($row = $choices->fetch_assoc()){
		$newOption = new \stdClass;
		$newOption->num = $row['questionnum'];
		$newOption->id = $row['id'];
		$newOption->text = $row['text'];
		$newOption->correct = $row['correct'];
		$optionArray[] = $newOption;
	}
	
	//add all the quesiton data and add the option data under it
	$newObject->questions = array();
	while($row = $questions->fetch_assoc()){
		$newQuestion = new \stdClass;
		$newQuesiton->number = $row['questionnumber'];
		$newQuestion->question = $row['question'];
		$newQuestion->pointValue = $row['pointVal'];
		
		$options = array();
		foreach($optionsArray as $curOption){
			if($curOption->num == $newQuestion->number){
				$options[] = $curOption;
			}
		}
		$newQuestion->option = $options;
		$newObject->questions[] = $newQuestion;
	}
	//output the json of the information
	$json = json_encode($newObject);
	echo $json;
}
