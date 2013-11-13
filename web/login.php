<?php

include 'db_util.php';
$errMsg = '';
if(isset($_POST['netid']))
{
	$netid = $_POST['netid'];
	if(validate_login($netid, $_POST['pass']))
	{
		session_start();
		$userInfo = select_student('*', 'netid', $netid);
		$_SESSION['netid'] = $netid;
		$_SESSION['first'] = $userInfo['first_name'];
		$_SESSION['last'] = $userInfo['last_name'];
		header('Location: ' . 'index.php');
	}
	else
	{
		$errMsg = 'Incorrect password or netid, please try again';
	}
}
//$first, $last, $netid, $pass, $major, $start_year, $start_semester
//create_student('Johnny','Bravo','jbravo','imsexy','Software Engineering','2010','F');
//var_dump(validate_login('jbravo', 'imsexy'));
?>

<html>
<body>
	<p style='color:red'><?= $errMsg ?></p>
	<form action='login.php' method='post'>
		Net ID: <input type='text' name='netid' required><br>
		Password: <input type='password' name='pass' required><br>
		<input type='submit'>
		<input type='submit' formaction='register.php'>
	</form>
</body>
</html>