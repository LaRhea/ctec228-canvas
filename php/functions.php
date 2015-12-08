<?php
	session_start();
	include 'connect.inc.php';
	include 'db_connect.php';
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
	} //end get mainMenu


	function getAvatar(){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/users/self?access_token=" . $key; 

		$data = CallAPI("GET",$url);
		$data = json_decode($data);
		echo "<img class='avatar' src=\"" . $data->avatar_url . "\" alt='User Avatar'>";
	}//end getAvatar


	function getSelf(){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/users/self?access_token=" . $key; 
		//var_dump($url);
		$data = CallAPI("GET",$url);
		$data = json_decode($data);
		echo $data->name ;
	
	}//end getSelf

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
	} //end getGrades

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
		$data = json_decode($data);
		$data = sortOrder($data);
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
						echo "<td class='main'><button class='favorite' onClick=\"callAjax('php/favorites.php?id=" . $data[$i]->id . "&course=" . $data[$i]->course_id . "','#favorites')\"><i class=\"fa fa-star\"></i></button><a target=\"_blank\" href=\"" . $data[$i]->html_url . "\" title=\"Assignment - " . $data[$i]->name . "\">" . $data[$i]->name . "</a></td>";
						if (!empty($data[$i]->due_at)) {
							$date = $data[$i]->due_at;
							$date = strtotime($date);
							$date = date("M j \b\y h:i a", $date);
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
	} //end getFuture

	function getPast($course,$action){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/courses/" . $course . "/assignments?per_page=50&bucket=past&include=submission&access_token=" . $key;
		// var_dump($url);
		$data = CallAPI("GET",$url);
		$data = json_decode($data);
		$data = sortOrder($data);
		if (is_array($data)) {
			//fix date issues 
			echo "<h2>Past " . $action . "</h2>";
			echo "<table class='container'>";
			echo "<tr>";
			echo "<th>Name</th>";
			echo "<th>Due At</th>";
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
						echo "<td class='main'><button class='favorite' onClick=\"callAjax('php/favorites.php?id=" . $data[$i]->id . "&course=" . $data[$i]->course_id . "','#favorites')\"><i class=\"fa fa-star\"></i></button><a target=\"_blank\" href=\"" . $data[$i]->html_url . "\" title=\"Assignment - " . $data[$i]->name . "\">" . $data[$i]->name . "</a></td>";
						// $date = substr($data[$i]->due_at, 0,10);
						if (!empty($data[$i]->due_at)) {
							$date = $data[$i]->due_at;
							$date = strtotime($date);
							$date = date("M j \b\y h:i a", $date);
							echo "<td>" . $date . "</td>";
						} else {
							echo "<td>No Due Date</td>";
						}

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

	function sortOrder($data){
		usort($data, function($a, $b) {
			return strtotime($b->due_at) - strtotime($a->due_at);
		});
		return $data;
	}

	function sortOrderUpcoming($data){
		//sort data
		usort($data, function($a, $b) {
			return strtotime($b->assignment->due_at) - strtotime($a->assignment->due_at);
		});
		return $data;
	}

	function getUpcoming($course){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/courses/" . $course . "/todo?access_token=" . $key;
		// var_dump($url);
		$data = CallAPI("GET",$url);
		$data = json_decode($data);
		$data = sortOrderUpcoming($data);

		echo "<h2>Upcoming This Week</h2>";
		if (count($data) != 0) {
			for ($i=0; $i < count($data); $i++) { 
				for ($j=0; $j < count($data[$i]->assignment); $j++) { 
					echo "<div class='lg-row'><p><a target=\"_blank\" href=\"" . $data[$i]->html_url . "\" title=\"Upcoming Assignment - " . $data[$i]->assignment->name . "\">" . $data[$i]->assignment->name . "</a></p></div>";
					echo "<div class='md-row'>";
					if (!empty($data[$i]->assignment->due_at)) {
						$date = $data[$i]->assignment->due_at;
						$date = strtotime($date);
						$date = date("M j \b\y h:i a", $date);
						echo "<p>" . $date . "</p>";
					} else {
						echo "<p>No Due Date</p>";
					}
					echo "</div>";
				}
			}
		} else {
			echo "<p>Nothing Coming Up This Week</p>";
		}
		

	} //end getUpcoming

	function getAlerts(){
		global $canvas_site;
		global $key;
		$url = $canvas_site . "/users/self/activity_stream?access_token=" . $key;
		// var_dump($url);
		$data = CallAPI("GET",$url);
		$data = json_decode($data);
		if (is_array($data)) {
			for ($i=0; $i < count($data); $i++) { 
				echo "<div class='item-container'>";
				echo "<a target='_blank' href='" . $data[$i]->html_url . "' title='Details - " . $data[$i]->title . "'>" . $data[$i]->title . "</a>";
				
				echo "</div>";
			}//count
		} else {
			echo "<p>No New Alerts or Messages</p>";
		}//is array
	}//end getAlerts


	// function getFavorites(){
	// 	global $dbc;
	// 	$sql = "SELECT * FROM `favorites` WHERE `active`=1";
	// 	$result = @mysqli_query($dbc,$sql);
	// 	if(mysqli_affected_rows($dbc) == 1) {
	// 		echo "<h2>Important Assignments</h2>";
	// 		while($row = mysqli_fetch_array($result)){
	// 			getFavoriteInfo($row['assignment_id'], $row['course_id']);
	// 		}
	// 	} else {
	// 		echo "No Favorites";
	// 	}
	// }


	// function getFavoriteInfo($id,$course){
	// 	global $canvas_site;
	// 	global $key;
	// 	$url = $canvas_site . "/courses/" . $course . "/assignments/$id?access_token=" . $key;
	// 	// var_dump($url);
	// 	$data = CallAPI("GET",$url);
	// 	$data = json_decode($data);
	// 	// $data = sortOrder($data);
	// 	// is_array($data) || 
	// 	if (!empty($data)) {
	// 		// for ($i=0; $i < count($data); $i++) { 
	// 			echo "<div class='item-container'><i class=\"fa fa-star\"></i><a target=\"_blank\" href=\"" . $data->html_url . "\" title=\"Assignment - " . $data->name . "\">" . $data->name . "</a></div>";
	// 		// }
	// 	} else {
	// 		echo "No Assignment Details";
	// 	}
	// }
 ?>