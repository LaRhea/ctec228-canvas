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
			if ($action == 'home') {
				getUpcoming($_SESSION['course']);

			} elseif($action != 'course-sess') {
				getFuture($_SESSION['course'], $_GET['action']);
				getPast($_SESSION['course'],$_GET['action']);
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
		echo "<nav><button class='home'><i class=\"fa fa-clock-o\"></i><br>Upcoming</button><button class=\"assignments\"><i class=\"fa fa-files-o\"></i><br>Assignments</button><button class=\"discussions\"><i class=\"fa fa-comments-o\"></i><br>Discussions</button><button class=\"quizzes\"><i class=\"fa fa-pencil-square-o\"></i><br>Quizzes</button></nav>";
	} 


	function getAvatar(){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/users/self?access_token=" . $key; 

		$data = CallAPI("GET",$url);
		$data = json_decode($data);
		echo "<img class='avatar' src=\"" . $data->avatar_url . "\" alt='User Avatar'>";
	}


	function getSelf(){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/users/self?access_token=" . $key; 
		//var_dump($url);
		$data = CallAPI("GET",$url);
		$data = json_decode($data);
		echo $data->name ;
	
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

					if (strpos($grade, 'A') !== false){
						$class = 'green';
					} elseif(strpos($grade, 'B')!== false){
						$class = 'dark-green';
					} elseif(strpos($grade, 'C')!== false){
						$class = 'yellow';
					}elseif(strpos($grade, 'D')!== false){
						$class = 'orange';
					}elseif(strpos($grade, 'F')!== false){
						$class = 'red';
					} else {
						$class = 'gray';
					}
					echo "<div class='xsm-row circle " . $class . "'><p class='lg-num'>" . $grade .  "</p></div>";

				}
				echo "<div class='clear'></div>";
				echo "</div>";
			} //end if course not accessible anymore
		} //end loop
	}

	function getCourses(){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/courses?access_token=" . $key; 
		// var_dump($url);
		$data = CallAPI("GET",$url);
		$data = json_decode($data);
		if (is_array($data)) {
			echo "<option value=\"--\">-- Select A Course --</option>";
			for ($i=0; $i < count($data); $i++) {
				//check to make sure it is a current class
				if (isset($data[$i]->name)) {
					echo "<option value=\"" . $data[$i]->id  . "\">" . $data[$i]->name . "</option>";
				}
			} 
		} else {
			echo "<p>You Have No Courses :(</p>";
		}
		
	} //end getCourses


	function getFuture($course,$action){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/courses/" . $course . "/assignments?per_page=50&bucket=future&access_token=" . $key;
		var_dump($url);
		$data = CallAPI("GET",$url);
		// echo $data;
		$data = json_decode($data);
		if (is_array($data)) {
			echo "<h2>Upcoming " . $action . "</h2>";
			echo "<table class='container'>";
			echo "<tr>";
			echo "<th>Name</th>";
			echo "<th>Date</th>";
			echo "</tr>";

			for ($i=0; $i < count($data) ; $i++) {
				echo "<tr>";
				$sub_type = $data[$i]->submission_types;
				// for ($j=0; $j <count($sub_type); $j++) { 
					//check which section is to be displayed
					if ($action == 'quizzes') {
						$sub_query = (in_array("online_quiz", $sub_type));
					} elseif($action == 'discussions') {
						$sub_query = (in_array("discussion_topic", $sub_type));
					} elseif($action == 'assignments'){
						$sub_query = (!in_array("online_quiz", $sub_type) && !in_array("discussion_topic", $sub_type));
					}
					//go through submission matching query types
					//display name in link with due date
					if ($sub_query) {
						echo "<td><a target=\"_blank\" href=\"" . $data[$i]->html_url . "\" title=\"Assignment - " . $data[$i]->name . "\">" . $data[$i]->name . "</a></td>";
						if (!empty($data[$i]->due_at)) {
							$date = substr($data[$i]->due_at, 0,10);
							echo "<td>" . $date . "</td>";
						} else {
							echo "<td> No Due Date </td>";
						}
					} //end sub_query
				// }
				echo "</tr>";		
			} //end count data
			echo "</table>";
		} else {
			echo "<p>Nothing Coming Up!</p>";
		}
	}


	function getDiscussions($course){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/courses/" . $course . "/discussion_topics?per_page=50&access_token=" . $key;
		//var_dump($url);
		$data = CallAPI("GET",$url);
		// echo $data;
		$data = json_decode($data);
		if (is_array($data)) {
			echo "<h2>Discussion Topics</h2>";
			for ($i=0; $i < count($data) ; $i++) {
				echo "<p><a target=\"_blank\" href=\"" . $data[$i]->html_url . "\" title=\"Discussion - " . $data[$i]->title . "\">" .$data[$i]->title."</a></p>";
			} //end count data
		} else {
			echo "<p>Nothing here to see!</p>";
		}

	}

	function getPast($course,$action){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/courses/" . $course . "/assignments?per_page=50&bucket=past&include=submission&access_token=" . $key;

		// $url = $canvas_site . "/courses/" . $course . "/assignments?per_page=50&access_token=" . $key;
		var_dump($url);
		$data = CallAPI("GET",$url);
		// echo $data;
		$data = json_decode($data);
		if (is_array($data)) {
			//fix date issues 
			echo "<h2>Past " . $action . "</h2>";
			echo "<table class='container'>";
			echo "<tr>";
			echo "<th>Name</th>";
			echo "<th>Date</th>";
			echo "<th>Score</th>";
			echo "</tr>";
			for ($i=0; $i < count($data) ; $i++) {
				echo "<tr>";
				$sub_type = $data[$i]->submission_types;
				if ($action == 'quizzes') {
					$sub_query = (in_array("online_quiz", $sub_type));
				} elseif($action == 'discussions') {
					$sub_query = (in_array("discussion_topic", $sub_type));
				} elseif($action == 'assignments'){
					$sub_query = (!in_array("online_quiz", $sub_type) && !in_array("discussion_topic", $sub_type));
				}
				// for ($j=0; $j < count($sub_type); $j++) { 
					// if ($sub_type[$j] != "online_quiz" && $sub_type[$j] != "discussion_topic") {
					if ($sub_query) {
						echo "<td><a target=\"_blank\" href=\"" . $data[$i]->html_url . "\" title=\"Assignment - " . $data[$i]->name . "\">" . $data[$i]->name . "</a></td>";
						echo "<td>" . substr($data[$i]->due_at, 0,10) . "</td>";
						if (isset($data[$i]->submission)) {
							echo "<td>" . $data[$i]->submission->score . "/" . $data[$i]->points_possible . "</td>";	

						} else {
							echo "<td>Not Graded</td>";
						}
					} //if not quiz or discussion

				// }//sub_type count
				echo "</tr>";
			} //end count data
			echo "</table>";
			//getPastAssignments($data);
		} else {
			echo "<p>Nothing here to see!</p>";
		}
	} //end getAssignments


	function getQuizzes($course){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/courses/" . $course . "/quizzes?per_page=50&access_token=" . $key;
		//var_dump($url);
		$data = CallAPI("GET",$url);
		// echo $data;
		$data = json_decode($data);
		echo "<h2>Quizzes</h2>";
		if (is_array($data)) {
			for ($i=0; $i < count($data) ; $i++) {
				echo "<p><a target=\"_blank\" href=\"" . $data[$i]->html_url . "\" title=\"Quiz - " . $data[$i]->title . "\">" . $data[$i]->title . "</p>";
			} //end count data
		} else {
			echo "<p>Nothing here to see!</p>";
		}

	} //end getQuizzes

	function getUpcoming($course){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/courses/" . $course . "/todo?access_token=" . $key;
		//var_dump($url);
		$data = CallAPI("GET",$url);
		$data = json_decode($data);
		echo "<h2>Upcoming This Week</h2>";
		if (count($data) != 0) {
			for ($i=0; $i < count($data); $i++) { 
				for ($j=0; $j < count($data[$i]->assignment); $j++) { 
					echo "<p><a target=\"_blank\" href=\"" . $data[$i]->html_url . "\" title=\"Upcoming Assignment - " . $data[$i]->assignment->name . "\">" . $data[$i]->assignment->name . "</p>";
				}
			}
		} else {
			echo "<p>Nothing Coming Up This Week</p>";
		}
		

	}

 ?>