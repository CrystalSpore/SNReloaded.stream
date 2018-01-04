<?php

require_once check.php;

$database = new mysqli("classdb.it.mtu.edu", "cjholmes", "buddy", "cjholmes");

if($database->connect_errno) {
  echo "Could not connect to database";
}

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

?>