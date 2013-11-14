<?php
/**
* This is a little page that will complete a semester
* when a checkbox is checked and fires an Ajax request
**/
include 'db_util.php';

if(isset($_POST['semester_id']))
{
	$database = dbInit_SQLite();
	$res = $database->prepare('UPDATE semester SET completed = ? WHERE semester_id = ?');
	$res->execute($_POST['is_complete'], $_POST['semester_id']);
}

?>