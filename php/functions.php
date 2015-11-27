<?php 
	include 'connect.inc.php';

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
		echo "<button class='home'><i class=\"fa fa-clock-o\"></i></button>";
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
		echo "<h1>" . $data->name . "</h1>";
	
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

	}

	function getAssignments($course){
		
	}

	function getQuizzes($course){
		global $canvas_site;
		global$key;
		$url = $canvas_site . "/courses/" . $course . "/quizzes?access_token=" . $key;
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

	} //end getAssignments

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
				echo "<h3>" . $data[$i]->name . "</h3>";
				//count all the grades in enrollments
				for ($j=0; $j < count($data[$i]->enrollments); $j++) { 
					echo "<p class='lg-num'>" . $data[$i]->enrollments[$j]->computed_current_score . " " . $data[$i]->enrollments[$j]->computed_current_grade .  "</p>";
				}
				echo "</div>";
			} //end if course not accessible anymore
		} //end loop
	
		//:1188432/enrollments
		// users/3705576/enrollments
		// user id from self 3705576
		//https://clarkcollege.instructure.com/api/v1/users/3705576/enrollments?access_token=9~zmQ7X66pT7Y3MUbIPWlZOGuubND238cgliWJlkixeKKj9BkCJ664hCOAgf7BXIVw
	}


	if (isset($_GET['course'])) {
		$course = $_GET['course'];
		getQuizzes($course);
	}
 ?>