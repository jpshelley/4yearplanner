<?php
function dbInit_mySQL()
{
	$mysqli = new mysqli("test.com", "root", "pass", "database");
	if($mysqli->connect_errno)
	{
		echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}
}

function dbInit_SQLite()
{
	//$database = sqlite_open("project3.sqlite.db") or die("Failed to make/connect to database. ");
	$database = new PDO('sqlite:project3.sqlite.db');
	return $database;
}

function create_student($first, $last, $netid, $pass, $major, $start_year, $start_semester)
{
	$database = dbInit_SQLite();



	// change this into select statement
	$majorid = 0;

	// Create new schedule and get id
	$stmt = $database->prepare("INSERT INTO `semester` (`completed`, `order`, `netid`) VALUES(0, ?, ?)");
	for($i = 0; $i < 8; $i++)
	{
		$stmt->execute(array($i, $netid));
	}

	$stmt = $database->prepare("INSERT INTO `student` (`first_name`, `last_name`, `netid`, `password`, `salt`, `majorid`, `start_year`, `start_semester`)
								VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
	$salt = openssl_random_pseudo_bytes(4);
	$params = array($first, $last, $netid, crypt($pass, $salt), $salt, $majorid, $start_year, $start_semester);
	$stmt->execute($params);
}

function validate_login($netid, $pass)
{
	$database = dbInit_SQLite();

	$res = $database->prepare("SELECT `password`, `salt` FROM `student` WHERE `netid` = ?");
	$res->execute(array($netid));
	foreach ($res as $row) {
		return $row['password'] == crypt($pass, $row['salt']);
	}
}

function select_student($columns, $whereCol, $value)
{
	$database = dbInit_SQLite();

	$res = $database->prepare("SELECT $columns FROM `student` WHERE $whereCol = ?");
	$res->execute(array($value));
	foreach ($res as $row) {
		return $row;
	}
}

function exists($netid)
{
	$database = dbInit_SQLite();

	$res = $database->prepare("SELECT COUNT(netid) FROM `student` WHERE netid = ?");
	$res->execute(array($netid));
	if(!$res->fetch(PDO::FETCH_ASSOC))
	{
		return false;
	}
	return true;
}

function get_majors()
{
	$database = dbInit_SQLite();

	$res = $database->query("SELECT major_name FROM `major`");
	return $res;
}

?>