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

<html lang="en">
<head>
	<title>Four Years</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/maincss.css" rel="stylesheet">
    <link href="css/main2.css" rel="stylesheet">
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">


	    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	    <![endif]-->
</head>
<body class="l-landing" style="zoom: 1;"> 
    <div class="page-header">
        <h1>Four Years <small>An intelligent planner</small></h1>
    </div>
    <section data-type="background" data-speed="10" class="parallax-0 pages">
    <div class="container">
        <form class="form-signin" action='login.php' method='post'>
            <h2 class="form-signin-heading" style="text-align: center; margin-top: 100px;">Please sign in</h2>
            <h5 style='font-style:italic; color:red'><?= $errMsg ?></h5>
            <input type='text' class="form-control" placeholder="Net ID" name='netid' required><br>
            <input type='password'class="form-control" placeholder="Password" name='pass' required><br>
            <button class="btn btn-lg btn-primary btn-block" type='submit' value='Login' style="margin-bottom: 10px;"> Sign In</button>
        </form>
        <button class="btn btn-lg btn-primary btn-block" value='Register' onclick="location.href='register.html'"> Register </button>
    </div> <!-- /container -->	
    </section>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script src="https://code.jquery.com/jquery.js"></script>
	    <!-- Include all compiled plugins (below), or include individual files as needed -->
	    <script src="js/bootstrap.min.js"></script>
</body>
</html>