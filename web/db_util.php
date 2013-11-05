<?php
$mysqli = NULL;

function dbInit()
{
	$mysqli = new mysqli("test.com", "root", "pass", "database");
	if($mysqli->connect_errno)
	{
		echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}
}

function create_student($first, $last, $netid, $pass, $major, $start_year, $start_semester)
{
	if(!$mysqli)
	{
		dbInit();
	}

	if (!($stmt = $mysqli->prepare("INSERT INTO `student` (`first_name`, `last_name`, `netid`, `password`, `salt`, `majorid`, `start_year`, `start_semester`, `scheduleid`)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")))
	{
    	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	// change this into select statement
	$majorid = 0;

	// Create new schedule and get id
	$scheduleid = 0;
	
	$salt = openssl_random_pseudo_bytes(4);
	if (!$stmt->bind_param("s", $first, $last, $netid, crypt($pass, $salt), $salt, $majorid, $start_year, $start_semester, $scheduleid)) {
    	echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	if (!$stmt->execute())
	{
	    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
}

function validate_login($netid, $pass)
{
	if(!$mysqli)
	{
		dbInit();
	}

	$res = $mysqli->query("SELECT `password`, `salt` FROM `student` WHERE `netid` = $netid");
	$row = $res->fetch_assoc();

	return $row['password'] == crypt($pass, $row['salt']);
}

?>