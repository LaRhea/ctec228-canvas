<?php 
	require 'php/functions.php';
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SB No. 5</title>
	<link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700|Raleway:400,300,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

</head>
<body>
	<nav>
		
			<?php mainMenu(); ?><button class="assignments"><i class="fa fa-files-o"></i></button><button class="discussions"><i class="fa fa-comments-o"></i></button><button class="quizzes"><i class="fa fa-pencil-square-o"></i></button>
			 <!-- <button class="all">ALL</button> -->
		
	</nav>
	<header>
	<?php
		getSelf();
		getAvatar();
	 ?>
	</header>
	<section class='cols grades'>
		<h2>Current Course Grades</h2>
		<?php getGrades(); ?>
	</section>

	 <section>
		 <select name="courses" id="courses">
		 	<!-- <option value='test'>Test</option> -->
		 	<?php getCourses(); ?>
		 </select>
		 <div id="assignments"></div>
	</section>

	<script >
	$(document).ready(function(){
		$('#courses').change(function(){
			var course = $(this).val();
			console.log(course);
			if (course != '--') {
				callAjax(course);
			} else {
				$('#assignments').html('Please Select Which Course You Want');
			}
		});


		
		
	}); //end ready

	function callAjax(course){
		$.ajax({
		url:"php/functions.php?course=" + course,
		method: "GET",
    	datatype: "text/html"
		})
		// do this when you have retrieved the data
		.done(function(data) { 
			$('#assignments').html('');
			$('#assignments').html(data);
			
		}); //ajax 
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
	</script>
</body>
</html>