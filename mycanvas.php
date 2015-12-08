<?php 
	require 'php/sessions.php';
	require 'php/functions.php';
	include 'php/favorites.php';

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
		<h1>Canvas Dashboard</h1>
		
	</header>
	<div class="user">
		<h2>Welcome <?php getSelf(); ?></h2>
		<?php
			getAvatar();
		 ?>
	</div>
	<div class="content">
		
		<section class='cols grades'>
			<h2>Current Course Grades</h2>
			<?php getGrades(); ?>
		</section>
		<section class="alerts">
			<h2>Messages &amp; Alerts</h2>
			<?php getAlerts(); ?>
		</section>
		
		<section id="favorites"><?php getFavorites(); ?></section>


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