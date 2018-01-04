$(document).ready(function() {
    console.log("Loaded");

    //gets a list of the graded exams for the current user
    //then iterates through the list and adds each exam as an
    //individual button
    function getGraded(){
	$.ajax({
	    url: 'managedb.php',
	    data: {action: 'getTakenExams'},
	    type: 'post',
	    success: function(response) {console.log("Got Graded Table")},
	    error: function(response) {
		console.log(response);
	    }
	}).done(function (data) {
				 //parses the sql return into a json table
				 var tests = JSON.parse(data);
				 var injection = "";
				 if(tests.length > 0){
				     injection = injection + "<h1>Pick the exam to see your grade</h1>";
				 }
				 else {
				     injection = injection + "<h1>No graded exams to view</h1>";
                        }
                        injection = injection + "<u1>";
                        //iterates for each exam grade and adds them as buttons
                        for(var i = 0; i < tests.length; i++)
                        {
                                injection = injection + "<li><input type='button' class='pick-exam' value='"
                                        + test[i] + "'></li>";
                        }
                        injection = injection + "</ul>";
                        var list = $('#examList');
                        //adds the necessary html script
                        list.append(injection);

                        $('.choose-exam').on('click', function(e) {
                                getExam(e.target.value);
                        });
                });
    }
    //gets the actual exam grade when the funciton is run
    function getExam(name)
    {
	$.ajax({
	    url: 'managedb.php',
	    data: {action: 'getGraded', examname: name},
	    type: 'post',
	    success: function(response){console.log("Got Exam")},
	    error: function(response) {
		console.log(response);
	    }
	}).done(function(data) {
	    $("#examList").hide();
	    //adds the data for the form
	    $("#examBody").append(data);
	});
    }

    //makes the buttons load in when the page loads
    getGraded();
});
