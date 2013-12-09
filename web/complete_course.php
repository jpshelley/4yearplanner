<?php
/**
* This is a little page that will complete a course
* when a class box is clicked and fires an Ajax request
**/
include 'db_util.php';

if(isset($_POST['course_name']) && isset($_POST['net_id']) && isset($_POST['complete']))
{
	$database = dbInit_SQLite();
	if($_POST['complete'] == "true")
		$stmt_complete = $database->prepare("UPDATE semester_schedule SET completed = 1 WHERE courseid IN (SELECT courseid FROM course WHERE course_name = ?) and semesterid IN (SELECT semesterid FROM semester WHERE netid = ?)");
	else
		$stmt_complete = $database->prepare("UPDATE semester_schedule SET completed = 0 WHERE courseid IN (SELECT courseid FROM course WHERE course_name = ?) and semesterid IN (SELECT semesterid FROM semester WHERE netid = ?)");
	
	$params = array($_POST['course_name'],$_POST['net_id']);
	$stmt_complete->execute($params);
}

?>