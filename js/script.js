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
		// $('.favorite').each(function(){
		// 	$(document).on('click', '.assignments', function(){
		// 		url = "php/functions.php?action=assignments";
		// 		location = '#course-content';
		// 		callAjax(url,location);
		// 	}); //end assignments
		// });
		

		//if assignments is clicked, send action to function
		$(document).on('click', '.assignments', function(){
			url = "php/functions.php?action=assignments";
			location = '#course-content';
			callAjax(url,location);
		}); //end assignments

		//if quizzes is clicked, send action to function
		$(document).on('click', '.quizzes', function(){
			url = "php/functions.php?action=quizzes";
			location = '#course-content';
			callAjax(url,location);
		}); //end 

		//if discussions is clicked, send action to function
		$(document).on('click', '.discussions', function(){
			url = "php/functions.php?action=discussions";
			location = '#course-content';
			callAjax(url,location);
		}); //end 

		//if home/upcoming is clicked, send action to function
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
			console.log('callAjax');
		}); //ajax 
	}

	function callSess(course,action){
		url = "php/functions.php?course=" + course + "&action=" + action;
		callAjax(url,'#course-nav');
	}

	function callAjaxFav(url,location){
		// $(location).fadeOut(800);
		$.ajax({
		url: url,
		method: "GET",
    	datatype: "text/html"
		})
		// do this when you have retrieved the data
		.done(function(data) {
			$(location).append(data);
			console.log('favorites');
		}); //ajax 
	}