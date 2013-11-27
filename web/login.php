<?php
/**
*
* Logs a user in and stores their name in the session so 
* they can keep coming back without having to log in
**/

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
?>

<html>
<head>
	<title>Four Years</title>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <!-- Bootstrap -->
	    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
	    <link href="styles.css" rel="stylesheet">

	    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	    <![endif]-->
</head>
<body>
	<p style='color:red'><?= $errMsg ?></p>
	
	<div class="container">
	<form class="form-signin" action='login.php' method='post'>
        <h2 class="form-signin-heading">Please sign in</h2>
		<input type='text' class="form-control" placeholder="Net ID" name='netid' required><br>
		<input type='password'class="form-control" placeholder="Password" name='pass' required><br>
		<button class="btn btn-lg btn-primary btn-block" type='submit' value='Login'> Sign In</button>
		<button class="btn btn-lg btn-primary btn-block" type='submit' value='Register' formaction='register.php'> Register</button>
	</form>

	</div> <!-- /container -->
	
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script src="https://code.jquery.com/jquery.js"></script>
	    <!-- Include all compiled plugins (below), or include individual files as needed -->
	    <script src="js/bootstrap.min.js"></script>
</body>
</html>