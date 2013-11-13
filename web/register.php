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

<html>
<body>
	<p style='color:red'><?= $errMsg ?></p>

	<form action='register.php' method='post'>
		First Name: <input type='text' name='first'><br>
		Last Name:  <input type='text' name='last'><br>
		Net ID:     <input type='text' name='netid' required><br>
		Password:   <input type='password' name='pass' required><br>
		Major: <input list='majors' name='major'><br>
		Year:  <input type='text' name='year'><br>
		Start Semester: <input list='sem' name='start_sem'><br>
		<input type='submit'>
	</form>
	<datalist id='majors'>
		<?php
			foreach(get_majors() as $major)
			{
				$major_name = $major['major_name'];
				echo "<option value='$major_name'>";
			}
		?>
	</datalist>
	<datalist id='sem'>
		<option value='F'>
		<option value='S'>
	</datalist>
</body>
</html>