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
	<script src='js/script.js'></script>

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
			<!-- <div class="row"><div class="full-row"><h3>CTEC 228  F15  2538 - API &amp; ADV INTEGRATION</h3></div><div class="xsm-row dark-green"><p class="lg-num">B</p></div><div class="clear"></div></div>	
			<div class="row"><div class="full-row"><h3>CTEC 228  F15  2538 - API &amp; ADV INTEGRATION</h3></div><div class="xsm-row orange"><p class="lg-num">D</p></div><div class="clear"></div></div>
			<div class="row"><div class="full-row"><h3>CTEC 228  F15  2538 - API &amp; ADV INTEGRATION</h3></div><div class="xsm-row green"><p class="lg-num">A</p></div><div class="clear"></div></div>
			<div class="row"><div class="full-row"><h3>CTEC 228  F15  2538 - API &amp; ADV INTEGRATION</h3></div><div class="xsm-row red"><p class="lg-num">F</p></div><div class="clear"></div></div>	
			<div class="row"><div class="full-row"><h3>CTEC 228  F15  2538 - API &amp; ADV INTEGRATION</h3></div><div class="xsm-row yellow"><p class="lg-num">C</p></div><div class="clear"></div></div>
			<div class="row"><div class="full-row"><h3>CTEC 228  F15  2538 - API &amp; ADV INTEGRATION</h3></div><div class="xsm-row green"><p class="lg-num">A</p></div><div class="clear"></div></div>				
		 --></section>

		 <section class='course-info'>
		 	<h2>Get Course Assignemnts, Quizzes, Discussions</h2>
			 <select name="courses" id="courses">
			 	<!-- <option value='test'>Test</option> -->
			 	<?php getCourses(); ?>
			 </select>
			 
			 <div id="course-nav"></div>
			 <div id="course-content">
			 	<!-- <div class="course-item"><?php //getUpcoming($_SESSION['course']) ?></div>
			 	<div class="course-item"><?php //getAssignments($_SESSION['course']) ?></div>
			 	<div class="course-item"><?php //getDiscussions($_SESSION['course']) ?></div>
			 	<div class="course-item"><?php //getQuizzes($_SESSION['course']) ?></div> -->
			 </div>
		</section>
	</div>
</body>
</html>