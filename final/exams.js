$(document).ready(function() {
    console.log('Page loaded');
    function getExamList()
    {
	console.log("test1");
	$.post('getExamList.php', function(res) {    
	var result = JSON.parse(res);
	var html = "";
	if (res.length !== 0) {
	    html += "<h1>Available exams to take.</h1>";
	} else {
	    html += "<h1>There are zero available exams.</h1>";
	}
	html += "<ul class='exam-selection-list'>";
	for (var i = 0; i < res.length; i++) {
	    html += "<li><input type='button' class='exam-select' value='" + result[i] + "'></li>";
	}
	html += "</ul>";
	var div = $('#examList');
	div.append(html);
        $('.exam-select').on('click', function(e) {
	    getExam(e.target.value);
	});
	});
    }

    function getExam(examName)
    {
	console.log("test3");
	$.ajax({
	    url: 'managedb.php',
	    data: {action: 'getExam', exam_name: examName},
	    type: 'post',
	    success: function(reponse) {
	    },
	    error: function(response) {
		console.log(response);
	    }
	}).done(function(data) {
	    console.log("test4");
	    var result = JSON.parse(data);
	    var questions = res.questions;
	    var html = "<form action='managedb.php' method='POST'>";
	    html += "<h1>" + examName + "</h1>";
	    html += "<input type='hidden' name='exam_name' value='" + examName + "'>";
	    html += "<input type='hidden' name='action' value='gradeExam'>";
	    for(var i = 0; i < questions.length; i++) {
		var question = questions[i];
		html += "<div id='q" + question.num + "'>";
		html += "<h3>" + question.question + "</h3>";

		for (var j = 0; j < question.choices.length; j++) {
		    var choice = question.choices[j];
		    var id = "'q" + question.num + "choice" + choice.identifier + "'";
		    html += "<input type='radio' id='" + id + "' name='q" + question.num + "' value='" + choice.indentifier + "'>";
		    html += "<label for='" + id + "'>" + choice.indentifier + ". " + choice.text + "</label><br />";
		}
		html += "</div>";
	    }
	    html += "<br><button type='submit'>Submit</button>";
	    html += "</form>";


	    $("#examList").hide();
	    $("#examBody").apend(html);
	});
    }

    getExamList();
});