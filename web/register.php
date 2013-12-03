<?php

include 'db_util.php';
$errMsg = '';
if(isset($_POST['netid']))
{
	$netid = $_POST['netid'];
	if(!exists($netid))
	{
		$errMsg = 'That netid is already in use';
	}
	else
	{
		create_student($_POST['first'],$_POST['last'],$_POST['netid'],$_POST['pass'],$_POST['major'],$_POST['year'],$_POST['start_sem']);
		session_start();
		$_SESSION = $_POST;
		header('Location: index.php');
	}
}
?>