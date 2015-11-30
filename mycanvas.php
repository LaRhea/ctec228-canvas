<?php 
	require 'php/sessions.php';
	require 'php/functions.php';
	

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SB No. 5</title>
	<link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700|Roboto:400,300,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

</head>
<body>
	<header>
	<h1>Welcome <?php getSelf(); ?></h1>
	<?php
		getAvatar();
		
		
	 ?>
	</header>
	<div class="content">
		<section class='cols grades'>
			<h2>Current Course Grades</h2>
			<?php getGrades(); ?>
			<div class="row"><div class="full-row"><h3>CTEC 228  F15  2538 - API &amp; ADV INTEGRATION</h3></div><div class="xsm-row circle dark-green"><p class="lg-num">B</p></div><div class="clear"></div></div>	
			<div class="row"><div class="full-row"><h3>CTEC 228  F15  2538 - API &amp; ADV INTEGRATION</h3></div><div class="xsm-row circle orange"><p class="lg-num">D</p></div><div class="clear"></div></div>
			<div class="row"><div class="full-row"><h3>CTEC 228  F15  2538 - API &amp; ADV INTEGRATION</h3></div><div class="xsm-row circle green"><p class="lg-num">A</p></div><div class="clear"></div></div>
			<div class="row"><div class="full-row"><h3>CTEC 228  F15  2538 - API &amp; ADV INTEGRATION</h3></div><div class="xsm-row circle red"><p class="lg-num">F</p></div><div class="clear"></div></div>	
			<div class="row"><div class="full-row"><h3>CTEC 228  F15  2538 - API &amp; ADV INTEGRATION</h3></div><div class="xsm-row circle yellow"><p class="lg-num">C</p></div><div class="clear"></div></div>
			<div class="row"><div class="full-row"><h3>CTEC 228  F15  2538 - API &amp; ADV INTEGRATION</h3></div><div class="xsm-row circle green"><p class="lg-num">A</p></div><div class="clear"></div></div>				
		</section>

		 <section>
		 	<h2>Get Course Assignemnts, Quizzes, Discussions</h2>
			 <select name="courses" id="courses">
			 	<!-- <option value='test'>Test</option> -->
			 	<?php getCourses(); ?>
			 </select>
			 
			 <div id="course-nav"></div>
			 <div id="course-content"></div>
		</section>
	</div>
	<script >
	$(document).ready(function(){
		var url ='';
		var course ='';
		var location ='';
		$('#courses').change(function(){
			course = $(this).val();
			console.log(course);
			if (course != '--') {
				callSess(course,'course-sess');
			} else {
				$('#course-nav').html('Please Select Which Course You Want');
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
	</script>
</body>
</html>