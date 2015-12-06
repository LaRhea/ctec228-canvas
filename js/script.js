	$(document).ready(function(){
		var url ='';
		var course ='';
		var location ='';
		$('#courses').change(function(){
			course = $(this).val();
			console.log(course);
			$('#course-content').html('');
			if (course != '--') {
				callSess(course,'course-sess');
				url = "php/functions.php?action=home";
				location = '#course-content';
				callAjax(url,location);
			} else {
				$('#course-nav').html('<p>Please Select Which Course You Want </p>');
			}
		});

		$(document).on('click', '.assignments', function(){
			url = "php/functions.php?action=assignments";
			location = '#course-content';
			callAjax(url,location);
		}); //end assignments

		$(document).on('click', '.quizzes', function(){
			url = "php/functions.php?action=quizzes";
			location = '#course-content';
			callAjax(url,location);
		}); //end 

		$(document).on('click', '.discussions', function(){
			url = "php/functions.php?action=discussions";
			location = '#course-content';
			callAjax(url,location);
		}); //end 

		$(document).on('click', '.home', function(){
			url = "php/functions.php?action=home";
			location = '#course-content';
			callAjax(url,location);
		}); //end 

	}); //end ready

	function callAjax(url,location){
		// $(location).fadeOut(800);
		$.ajax({
		url: url,
		method: "GET",
    	datatype: "text/html"
		})
		// do this when you have retrieved the data
		.done(function(data) {
			$(location).html('');
			$(location).html(data);
			
		}); //ajax 
	}

	function callSess(course,action){
		url = "php/functions.php?course=" + course + "&action=" + action;
		callAjax(url,'#course-nav');
	}

	// function callAjax(course){
	// 	$.ajax({
	// 	url:"php/functions.php?course=" + course,
	// 	method: "GET",
 //    	datatype: "text/html"
	// 	})
	// 	// do this when you have retrieved the data
	// 	.done(function(data) { 
	// 		$('#assignments').html('');
	// 		$('#assignments').html(data);
			
	// 	}); //ajax 
	// }
	//src='js/canvas.js'
		//ajax call to php script
	 //getAssignments(courseId);
	 //send back text/html
	 //put in assignments div