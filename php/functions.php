<?php
	session_start();
	include 'connect.inc.php';
	// include 'sessions.php';

		if (isset($_GET['course'])) {
			$course = $_GET['course'];
			// echo "test";
			if (isset($_GET['action'])) {
				$action = $_GET['action'];
				if ($action == 'course-sess') {
					$_SESSION['course'] = $course;
					//if a course is selected, show the menu options
			 		mainMenu();
				}

			}
		} 

	if (isset($_SESSION['course'])) {
		if (isset($_GET['action'])) {
			$action = $_GET['action'];
			if ($action == 'assignments') {
				getAssignments($_SESSION['course']);
			} elseif ($action == 'quizzes') {
				getQuizzes($_SESSION['course']);
			} elseif($action == 'discussions'){
				getDiscussions($_SESSION['course']);
			} elseif ($action == 'home') {
				getUpcoming($_SESSION['course']);
			}
		}
	}
	

function CallAPI($method, $url, $data = false){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // new line below for SSL
    // this is not the best solution to this
    // but it works for now
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
} // end CallAPI


	function mainMenu(){
		echo "<nav><button class='home'><i class=\"fa fa-clock-o\"></i></button><button class=\"assignments\"><i class=\"fa fa-files-o\"></i></button><button class=\"discussions\"><i class=\"fa fa-comments-o\"></i></button><button class=\"quizzes\"><i class=\"fa fa-pencil-square-o\"></i></button></nav>";
	} 


	function getAvatar(){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/users/self?access_token=" . $key; 

		$data = CallAPI("GET",$url);
		$data = json_decode($data);
		echo "<img src='" . $data->avatar_url . "' alt='User Avatar'/ width='50px' height='50px'>";
	}


	function getSelf(){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/users/self?access_token=" . $key; 

		$data = CallAPI("GET",$url);
		$data = json_decode($data);
		echo $data->name ;
	
	}

	function getCourses(){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/courses?access_token=" . $key; 
		// var_dump($url);
		$data = CallAPI("GET",$url);
		$data = json_decode($data);
		
		echo "<option value=\"--\">-- Select A Course --</option>";
		for ($i=0; $i < count($data); $i++) {
			//check to make sure it is a current class
			if (isset($data[$i]->name)) {
				echo "<option value=\"" . $data[$i]->id  . "\">" . $data[$i]->name . "</option>";
			}
		} 
		
		
	} //end getCourses

	function getDiscussions($course){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/courses/" . $course . "/discussion_topics?per_page=50&access_token=" . $key;
		var_dump($url);
		$data = CallAPI("GET",$url);
		// echo $data;
		$data = json_decode($data);
		for ($i=0; $i < count($data) ; $i++) {
			echo "<h3>".$data[$i]->title."</h3>";
		} //end count data

	}

	function getAssignments($course){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/courses/" . $course . "/assignments?per_page=50&access_token=" . $key;
		var_dump($url);
		$data = CallAPI("GET",$url);
		// echo $data;
		$data = json_decode($data);

		//fix date issues 
		echo "<h2>Upcoming Assignments</h2>";
		for ($i=0; $i < count($data) ; $i++) {
			
			if ($data[$i]->has_submitted_submissions == FALSE) {
				echo "<p>" . $data[$i]->name . "</p>";
			}
		} //end count data
		echo "<h2>Past Assignments</h2>";
		for ($i=0; $i < count($data) ; $i++) {
			if ($data[$i]->has_submitted_submissions == TRUE) {
				echo "<p>" . $data[$i]->name . "</p>";
			} 
		} //end count data
		//getPastAssignments($data);
	} //end getAssignments

	function getPastAssignments($data){
		echo "<h2>Past Assignments</h2>";
		for ($i=0; $i < count($data) ; $i++) {
			if (($data[$i]->has_submitted_submissions == TRUE) && ($data[$i]->lock_at < new DateTime())) {
				echo "<p>" . $data[$i]->name . "</p>";
			} 
		} //end count data

	} //end getAssignments

	function getQuizzes($course){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/courses/" . $course . "/quizzes?per_page=50&access_token=" . $key;
		var_dump($url);
		$data = CallAPI("GET",$url);
		// echo $data;
		$data = json_decode($data);
		for ($i=0; $i < count($data) ; $i++) {
			echo "<h2>" . $data[$i]->title . "</h2>";
			//count all the submission types in array
			// for ($j=0; $j < count($data[$i]->submission_types); $j++) { 
			// 	//if type includes online_quiz, then print the name 
			// 	if ($data[$i]->submission_types[$j] == 'online_quiz') {
			// 		echo "<h2>" . $data[$i]->name . "</h2>";
			// 		if ($data[$i]->has_submitted_submissions == TRUE) {
			// 			echo "<p>Submitted</p>";
			// 		} else {
			// 			echo "<p>Not Submitted</p>";
			// 		}
			// 	}
			// } //end count submission types
		} //end count data

	} //end getQuizzes

	function getupcoming($course){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/courses/" . $course . "/todo?access_token=" . $key;
		var_dump($url);
		$data = CallAPI("GET",$url);
		$data = json_decode($data);
		if (count($data) != 0) {
			for ($i=0; $i < count($data); $i++) { 
				echo "<p>Test</p>";
			}
		} else {
			echo "<p>Nothing Coming Up This Week</p>";
		}
		

	}


	function getGrades() {
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/courses?include=total_scores&access_token=" . $key;
		// var_dump($url);
		$data = CallAPI("GET",$url);
		$data = json_decode($data);

		
		for ($i=0; $i < count($data); $i++) { 
			if (!isset($data[$i]->access_restricted_by_date)) {
		
				echo "<div class='row'>";
				echo "<div class='full-row'><h3>" . $data[$i]->name . "</h3></div>";
				//count all the grades in enrollments
				for ($j=0; $j < count($data[$i]->enrollments); $j++) { 
					// $data[$i]->enrollments[$j]->computed_current_score . " " .
					$grade = $data[$i]->enrollments[$j]->computed_current_grade;
					if ($grade == 'A') {
						$class = 'green';
					} elseif($grade == 'B'){
						$class = 'lightgreen';
					} elseif($grade == 'C'){
						$class = 'yellow';
					}elseif($grade == 'D'){
						$class = 'orange';
					}elseif($grade == 'F'){
						$class = 'red';
					} else {
						$class = '';
					}
					echo "<div class='xsm-row circle " . $class . "'><p class='lg-num'>" . $grade .  "</p></div>";

				}
				echo "<div class='clear'></div>";
				echo "</div>";
			} //end if course not accessible anymore
		} //end loop
	
		//:1188432/enrollments
		// users/3705576/enrollments
		// user id from self 3705576
		//https://clarkcollege.instructure.com/api/v1/users/3705576/enrollments?access_token=9~zmQ7X66pT7Y3MUbIPWlZOGuubND238cgliWJlkixeKKj9BkCJ664hCOAgf7BXIVw
	}


	// if (isset($_GET['course'])) {
	// 	$course = $_GET['course'];
	// 	getQuizzes($course);
	// }

 ?>