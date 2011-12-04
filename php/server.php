<?php
header('Content-type: application/json; charset=utf-8');
include 'info.php';
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
		
	}
}

$function = $_GET['function'];

$server = new Server();
$server->startApp();
//methodToExecute($function);


//function methodToExecute($function) {
	switch($function) {
		case 'populateTeacherView':
			echo $server->populateTeacherView($_GET['school'], $_GET['teacher'], $_GET['period']);
			break;
		case 'saveTeacherView':
			echo $server->saveTeacherView($_GET['performance_id'], $_GET['absent'], $_GET['creativity'], $_GET['disrespectful'], $_GET['disruptive'], $_GET['insight'], $_GET['late'], $_GET['missing'], $_GET['participation'], $_GET['persistence'], $_GET['teamwork']);
			break;
		case 'registerParent':
			echo $server->
		// $arr = array('status'=>'400');
// 		
		// $output = json_encode($arr);
		// echo $output;
			
			break;
	}
//}
$server->closeApp();

?>
