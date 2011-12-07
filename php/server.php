<?php
header('Content-type: application/json; charset=utf-8');
include 'info.php';
include 'sms.php';

/*
 * Project:     myChild
 * Description: Update parents on child's progress via SMS
 * Website:     
 * 
 * Author:      Ezra Velazquez
 * Website:     http://ezraezraezra.com
 * Date:        December 2011
 * 
 */

class Server {
	
	var $connection;
	var $db_selected;
	var $info_object;
	
	function Server() {
		// INITIALIZE HERE
		$this->info_object = new info();
	}
	
	function startApp() {
		$this->connection = mysql_connect($this->info_object->hostname, $this->info_object->user, $this->info_object->pwd);
		if(!$this->connection) {
			die("Error ".mysql_errno()." : ".mysql_error());
		}
		
		$this->db_selected = mysql_select_db($this->info_object->database, $this->connection);
		if(!$this->db_selected) {
			die("Error ".mysql_errno()." : ".mysql_error());
		}
	}
	
	function closeApp() {
		mysql_close($this->connection);
	}
	
	function submit_info($data, $conn, $return) {
		$result = mysql_query($data,$conn);
		if(!$result) {
			die("Error ".mysql_errno()." : ".mysql_error());
		}
		else if($return == true) {
			return $result;
		}
	}
	
	function saveTeacherView($id, $absent, $creativity, $disrespectful, $disruptive, $insight, $late, $missing, $participation, $persistence, $teamwork) {
		$request_string = "UPDATE final_performance SET 
							participation = '$participation', teamwork = '$teamwork', creativity = '$creativity', 
							insight = '$insight', persistance = '$persistence', disruptive = '$disruptive', 
							missing_work = '$missing', disrespectful = '$disrespectful', late = '$late', absent = '$absent' 
							WHERE id='$id'";
		$request = $this->submit_info($request_string, $this->connection, true);
		
		$arr = array('status'=>'200');
		$output = json_encode($arr);
		
		return $output;
	}
	
	function populateTeacherView($school, $teacher, $period) {
		$request_string = "SELECT student.first_name, student.last_name, performance.id AS performance_id, performance.participation, performance.creativity, performance.teamwork, performance.persistance, performance.insight, performance.disruptive, performance.missing_work, performance.late, performance.absent, performance.disrespectful 
FROM final_student AS student, final_period AS period, final_period_X_student AS period_X_student, final_performance AS performance, final_student_X_performance AS student_X_performance, final_teacher_X_period AS teacher_X_period, final_school_X_teacher AS school_X_teacher, final_school AS school, final_teacher AS teacher 
WHERE student.id = period_X_student.student_id AND period.id = period_X_student.period_id AND performance.id = student_X_performance.performance_id AND student.id = student_X_performance.student_id AND teacher.id = teacher_X_period.teacher_id AND period.id = teacher_X_period.period_id AND school.id = school_X_teacher.school_id AND teacher.id = school_X_teacher.teacher_id 

AND teacher.name = '$teacher' AND school.name = '$school' AND period.class = '$period'";
		$request = $this->submit_info($request_string, $this->connection, true);
		
		while(($rows[] = mysql_fetch_assoc($request)) || array_pop($rows));
		$counter = 0;
		foreach ($rows as $row):
			$result[$counter] = array('performance_id'=>"{$row['performance_id']}", 'first_name'=>"{$row['first_name']}", 'last_name'=>"{$row['last_name']}", 'participation'=>"{$row['participation']}", 'creativity'=>"{$row['creativity']}", 'teamwork'=>"{$row['teamwork']}", 'persistance'=>"{$row['persistance']}", 'insight'=>"{$row['insight']}", 'disruptive'=>"{$row['disruptive']}", 'missing_work'=>"{$row['missing_work']}", 'late'=>"{$row['late']}", 'absent'=>"{$row['absent']}", 'disrespectful'=>"{$row['disrespectful']}");
			$counter = $counter + 1;
		endforeach;
		
		if($counter != 0) {
			$arr = array("status"=>200, "results"=>$result, "total"=>$counter);
		}
		else {
			$arr = array('status'=>'400');
		}
		$output = json_encode($arr);
		return $output;
	}
	
	function registerParent($number, $id, $participation, $disruptive, $creativity, $late, $teamwork, $missing, $persistence, $absent, $insight, $disrespectful) {
		$request_string = "INSERT INTO final_parent (number, student_id, participation, creativity, teamwork, persistance, insight, disruptive, late, missing_work, absent, disrespectful) VALUES('$number', '$id', '$participation', '$creativity', '$teamwork', '$persistence', '$insight', '$disruptive', '$late', '$missing', '$absent', '$disrespectful')";
		
		$request = $this->submit_info($request_string, $this->connection, true);
		
		$arr = array('status'=>'200');
		$output = json_encode($arr);
		
		$sms_app = new SMS($this->info_object->AccountSid, $this->info_object->AuthToken);
		$sms_app->confirmRegistration($number);
		
		return $output;
	}
	
	function sendReport($number) {
		$request_string = "SELECT * FROM final_parent WHERE number = '$number'";
		$request = $this->submit_info($request_string, $this->connection, true);
		
		while(($rows[] = mysql_fetch_assoc($request)) || array_pop($rows));
		$counter = 0;
		foreach ($rows as $row):
			$result_parent = array('number'=>"{$row['number']}", 'student_id'=>"{$row['student_id']}", 'participation'=>"{$row['participation']}", 'creativity'=>"{$row['creativity']}", 'teamwork'=>"{$row['teamwork']}", 'persistance'=>"{$row['persistance']}", 'insight'=>"{$row['insight']}", 'disruptive'=>"{$row['disruptive']}", 'missing_work'=>"{$row['missing_work']}", 'late'=>"{$row['late']}", 'absent'=>"{$row['absent']}", 'disrespectful'=>"{$row['disrespectful']}");
			$counter = $counter + 1;
		endforeach;
		
		// A match was found
		if($counter > 0) {
			//$counter = $counter - 1;
			$student_id = $result_parent['student_id'];
			
			// All results
			$request_string = "SELECT performance.participation, performance.teamwork, performance.creativity, performance.insight, performance.persistance, performance.disruptive, performance.missing_work, performance.disrespectful, performance.late, performance.absent FROM final_student AS student, final_performance AS performance, final_student_X_performance AS student_X_performance, final_parent AS parent WHERE student_X_performance.student_id = student.id AND student_X_performance.performance_id = performance.id AND parent.student_id = student.id AND student.id = '$student_id' LIMIT 0, 1";
			$request = $this->submit_info($request_string, $this->connection, true);
			
			while(($rows[] = mysql_fetch_assoc($request)) || array_pop($rows));
			//$counter = 0;
			foreach ($rows as $row):
				$result_student = array('participation'=>"{$row['participation']}", 'creativity'=>"{$row['creativity']}", 'teamwork'=>"{$row['teamwork']}", 'persistance'=>"{$row['persistance']}", 'insight'=>"{$row['insight']}", 'disruptive'=>"{$row['disruptive']}", 'missing_work'=>"{$row['missing_work']}", 'late'=>"{$row['late']}", 'absent'=>"{$row['absent']}", 'disrespectful'=>"{$row['disrespectful']}");
				//$counter = $counter + 1;
			endforeach;
			
			//ONLY NEED THE ONES PARENT WANTS FILTER THEM OUT HERE
			$output_string = array();
			
			foreach($result_parent as $key => $value):
				if($key != 'student_id' || $key != 'number') {
					if($value == 1) {
						$output_string[$key] = $result_student[$key];
					}
				}
			endforeach;
			
			
			$arr = array('status'=>'200');
			$output = json_encode($arr);
			$sms_response = "Your child status: ";
			
			foreach($output_string as $key => $value):
				if($value == 1) {
					$sms_response = $sms_response.$key.", ";
				}
			endforeach;
			
			$sms_response = rtrim($sms_response, ", ");
			
		}
		else {
			$arr = array('status'=>'400');
			$output = json_encode($arr);
			$sms_response = "myChild: This phone is not registered with this student";
		}
		
		// RETURN
		
		$sms_app = new SMS($this->info_object->AccountSid, $this->info_object->AuthToken);
		$sms_app->sendReport($number, $sms_response);
		
		//return $output;
		
	}

	function loginTeacher($teacher, $password) {
		$request_string = "SELECT id FROM final_teacher WHERE name = '$teacher' AND password = '$password'";
		$request = $this->submit_info($request_string, $this->connection, true);
		
		while(($rows[] = mysql_fetch_assoc($request)) || array_pop($rows));
		$counter = 0;
		foreach ($rows as $row):
			$teacher_id = "{$row['id']}";
			$counter = $counter + 1;
		endforeach;
		
		if($counter >= 1) {
			$status = '200';
			
			/**
			 * TODO FIX BUG: First result is null
			 */
			
			$request_string_new = "SELECT school.name AS school_name, period.class AS class_period FROM final_school_X_teacher AS school_X_teacher, final_school AS school, final_period AS period, final_teacher_X_period AS teacher_X_period WHERE school_X_teacher.teacher_id = '$teacher_id' AND school_X_teacher.school_id = school.id AND teacher_X_period.teacher_id = school_X_teacher.teacher_id AND teacher_X_period.period_id = period.id";
			$request_new = $this->submit_info($request_string_new, $this->connection, true);
			$counter_inner = 0;
			while(($rows[] = mysql_fetch_assoc($request_new)) || array_pop($rows));
			foreach ($rows as $row):
				$result_teacher[$counter_inner] = array('name'=>"{$row['school_name']}", 'class_period'=>"{$row['class_period']}");
				$counter_inner = $counter_inner + 1;
			endforeach;
			
		}
		else {
			$status = '400';
		}
		
		$arr = array('status'=>$status, 'results'=>$result_teacher);
		$output = json_encode($arr);
		
		return $output;
	}
}


if(isset($_REQUEST['From'])) {
	$from_number = $_REQUEST['From'];
	$function = 'sendReport';
}
else {
	$function = $_GET['function'];
}

$server = new Server();
$server->startApp();


switch($function) {
	case 'populateTeacherView':
		echo $server->populateTeacherView($_GET['school'], $_GET['teacher'], $_GET['period']);
		break;
	case 'saveTeacherView':
		echo $server->saveTeacherView($_GET['performance_id'], $_GET['absent'], $_GET['creativity'], $_GET['disrespectful'], $_GET['disruptive'], $_GET['insight'], $_GET['late'], $_GET['missing'], $_GET['participation'], $_GET['persistence'], $_GET['teamwork']);
		break;
	case 'registerParent':
		echo $server->registerParent($_GET['number'], $_GET['student_id'], $_GET['participation'], $_GET['disruptive'], $_GET['creativity'], $_GET['late'], $_GET['teamwork'], $_GET['missing'], $_GET['persistence'], $_GET['absent'], $_GET['insight'], $_GET['disrespectful']);
		break;
	case 'sendReport':
		echo $server->sendReport($from_number);
		break;
	case 'loginTeacher':
		echo $server->loginTeacher($_GET['teacher'], $_GET['password']);
		break;
}


$server->closeApp();

?>
