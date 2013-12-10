<?php
addClass($_POST['semester'], $_POST['classes']);

function addClass($semester, $classes)
{
	$select_sem = $database->prepare("SELECT semesterid FROM semester WHERE netid = ? AND order = ?");
	$params = array($netid, $semester);
	$select_sem->execute($params);
	$semesterid = $select_sem->fetch();
	$select_class = $database->prepare("SELECT courseid FROM course WHERE course_name = ?");
	$insert_class = $database->prepare("INSERT INTO semester_schedule (courseid, semesterid) VALUES (?, ?)");
	foreach($classes as $class)
	{
		$params = array($class);
		$select_class->execute($params);
		$courseid = $stmt_class->fetch();
		
		$params = array($courseid, $semesterid);
		$insert_class->execute($params);
	}
}
?>