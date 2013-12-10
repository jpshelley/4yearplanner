<?php
include 'db_util.php';
$database = dbInit_SQLite();
session_start();
addClass($_GET['semester'], $_GET['classes'], $_SESSION['netid'], $database);

function addClass($semester, $classes, $netid, $database)
{
	// Create the order variable
	$select_student = $database->prepare("SELECT start_semester, start_year FROM student WHERE netid = ?");
	$select_student->execute(array($netid));
	$start_sem = $select_student->fetch();

	$semester = split(' ', $semester);
	$order = ($semester[1] - $start_sem['start_year']) * 2;
	$semester[0] = substr(trim(strtolower($semester[0])),0,1);
	$start_sem['start_semester'] = substr(trim(strtolower($start_sem['start_semester'])),0,1);
	var_dump(strcmp($semester[0], $start_sem['start_semester']), $order);
	$test = strcmp($semester[0], $start_sem['start_semester']);
	switch($test > 0)
	{
		case false:
			$order += $test == 0 ? 0 : 1;
			break;
		case true:
			$order--;
			break;
	}
	mvar_dump($order);
	// switch($semester[0])
	// {
	// 	case 'f':
	// 		if(strcmp($start_sem['start_semester'], $semester[0]) == 0)
	// 		{
	// 			$order += $order;
	// 		}
	// 		else
	// 		{
	// 			$order += $order + 1;
	// 		}
	// 		break;
	// 	case 's':
	// 		if(strcmp($start_sem['start_semester'], $semester[0]) == 0)
	// 		{
	// 			$order += $order + 1;
	// 		}
	// 		else
	// 		{
	// 			$order += $order == 1 ? 0 : $order;
	// 		}
	// 		break;
	// }
	// mvar_dump($order);

	// Get semesterid
	$select_sem = $database->prepare("SELECT semesterid FROM semester WHERE netid = ? AND `order` = ?");
	$params = array($netid, $order);
	$select_sem->execute($params);
	$semesterid = $select_sem->fetch();
	$semesterid = $semesterid[0];

	// Get the specific class to insert
	$select_class = $database->prepare("SELECT courseid FROM course WHERE course_name = ?");
	$insert_class = $database->prepare("INSERT INTO semester_schedule (courseid, semesterid) VALUES (?, ?)");
	if(gettype($classes) != 'array')
		$classes = array($classes);
	foreach($classes as $class)
	{
		$params = array($class);
		$select_class->execute($params);
		$courseid = $select_class->fetch();
		$courseid = $courseid[0];

		$params = array($courseid, $semesterid);
		$insert_class->execute($params);
	}
}
?>