<?php

session_start();
var_dump($_SESSION);
$_SESSION = array();
var_dump($_SESSION);

/*if (ini_get("session.use_cookies")) {
	echo 'test';
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000,
		$params["path"], $params["domain"],
		$params["secure"], $params["httponly"]
	);
}*/
header("Location: login.php");
echo session_destroy();
?>
