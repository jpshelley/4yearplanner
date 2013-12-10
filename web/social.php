<?php

require_once 'sdk/facebook.php';

$config = array('appId' => '484732448310575', 'secret' => 'fe148d4c3a79b03c3dade57d9206b3f9');
$facebook = new Facebook($config);

include 'db_util.php';
session_start();
if(!isset($_SESSION['netid']))
{
         header('Location: ' . 'login.php');
}
$name = $_SESSION['first'].' '.$_SESSION['last'];
$netid = $_SESSION['netid'];
if (isset($_SESSION['netid']))
{
        $database = dbInit_SQLite();

        $stmt_sem_1 = $database->prepare("SELECT semesterid, completed FROM semester WHERE netid = ?");
        $params1 = array($netid); 

        $stmt_sem_1->execute($params1);

        $stmt_sem_1_res = $stmt_sem_1->fetchAll();

        $stmt_sem = $database->prepare("SELECT course_name, credits FROM course WHERE courseid IN (SELECT courseid FROM semester_schedule WHERE semesterid = ?)");
        $sem_courses = array();
        for($i = 0; $i < 8; $i++)
        {
                $params = array($stmt_sem_1_res[$i][0]);
                $stmt_sem->execute($params);
                $sem_courses[$i] = $stmt_sem->fetchAll();
        }
}
?> 
<html lang="us">
    <head>
           <meta charset="utf-8">
           <title>4 Year Planner</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- Bootstrap -->
            <link href="dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="css/style.css" rel="stylesheet">
           <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
           <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    </head>
<body>
    
<nav class="navbar navbar-default" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="index.php">4Y</a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
      <li><a href="analytics.html">Analytics</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="http://www.iastate.edu">Iowa State</a></li>
      <li class="dropdown active">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['netid']; ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li ><a href="profile.php">Profile</a></li>
          <li class="active"><a href="#">Social</a></li>
          <li class="divider"></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>    

    <div class="jumbotron">
    <h1>Hello, <?php echo $netid; ?>!</h1>
    <p>Allowing us to access your social networks will help us to better understand the classes that you and your friends are enrolled in. And will allow you to know when you have friends in particular classes.</p>
    <p><a id="close-jumbo"class="btn btn-primary btn-lg" role="button">Got It!</a></p>
    </div>
    
    
                    <div id="fb-root"></div>
                <!--
                Below we include the Login Button social plugin. This button uses the JavaScript SDK to
                present a graphical Login button that triggers the FB.login() function when clicked.

                Learn more about options for the login button plugin:
                /docs/reference/plugins/login/ -->

                <h1 class="maintitle"><?php echo $name; ?>!</h1>
                <p class="login">
                        Log in here!
                </p>
                <a href="#" onclick="login();"><img src="img/facebook.png"/></a>
                <!-- <fb:login-button show-faces="true" width="200" max-rows="1" onlogin="submitLoginForm();" size="xlarge"></fb:login-button> -->
                <script>
                        window.fbAsyncInit = function() {
                                FB.init({
                                        appId : '249696895179837', // App ID
                                        oauth : true,
                                        channelUrl : '//local.cyspell.com/channel.html', // Channel File
                                        status : true, // check login status
                                        cookie : true, // enable cookies to allow the server to access the session
                                        xfbml : true // parse XFBML
                                });

                                // Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
                                // for any authentication related change, such as login, logout or session refresh. This means that
                                // whenever someone who was previously logged out tries to log in again, the correct case below
                                // will be handled.
                                FB.Event.subscribe('auth.authResponseChange', function(response) {
                                        // Here we specify what we do with the response anytime this event occurs.
                                        if (response.status === 'connected') {
                                                // The response object is returned with a status field that lets the app know the current
                                                // login status of the person. In this case, we're handling the situation where they
                                                // have logged in to the app.
                                                testAPI();
                                        } else if (response.status === 'not_authorized') {
                                                // In this case, the person is logged into Facebook, but not into the app, so we call
                                                // FB.login() to prompt them to do so.
                                                // In real-life usage, you wouldn't want to immediately prompt someone to login
                                                // like this, for two reasons:
                                                // (1) JavaScript created popup windows are blocked by most browsers unless they
                                                // result from direct interaction from people using the app (such as a mouse click)
                                                // (2) it is a bad experience to be continually prompted to login upon page load.
                                                FB.login();
                                        } else {
                                                // In this case, the person is not logged into Facebook, so we call the login()
                                                // function to prompt them to do so. Note that at this stage there is no indication
                                                // of whether they are logged into the app. If they aren't then they'll see the Login
                                                // dialog right after they log in to Facebook.
                                                // The same caveats as above apply to the FB.login() call here.
                                                FB.login();
                                        }
                                });
                        }; ( function() {
                                        var e = document.createElement('script');
                                        e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
                                        e.async = true;
                                        document.getElementById('fb-root').appendChild(e);
                                }());
                        // Load the SDK asynchronously
                        ( function(d) {
                                        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
                                        if (d.getElementById(id)) {
                                                return;
                                        }
                                        js = d.createElement('script');
                                        js.id = id;
                                        js.async = true;
                                        js.src = "//connect.facebook.net/en_US/all.js";
                                        ref.parentNode.insertBefore(js, ref);
                                }(document));

                        // Here we run a very simple test of the Graph API after login is successful.
                        // This testAPI() function is only called in those cases.
                        function testAPI() {
                                console.log('Welcome!  Fetching your information.... ');
                                FB.api('/me', function(response) {
                                        console.log('Good to see you, ' + response.name + '.');
                                });
                        }

                        function login() {
                                FB.login(function(response) {

                                        if (response.authResponse) {
                                                console.log('Welcome!  Fetching your information.... ');
                                                //console.log(response); // dump complete info
                                                access_token = response.authResponse.accessToken;
                                                //get access token
                                                user_id = response.authResponse.userID;
                                                //get FB UID

                                                FB.api('/me', function(response) {
                                                        user_email = response.email;
                                                        //get user email
                                                        // you can store this data into your database
                                                });
                                                console.log('LOGGED IN!, ' + response.name + '.');
                                                window.location.href = "main.php";

                                        } else {
                                                //user hit cancel button
                                                console.log('User cancelled login or did not fully authorize.');

                                        }
                                }, {
                                        scope : 'publish_stream,email'
                                });
                        }


                </script>

    
    
    
        <script src="dist/js/bootstrap.min.js"></script>
        <script src="js/chart/Chart.min.js"></script>
        <script>
    
        $(function(){
                $('#close-jumbo').click(function(){
                    $('.jumbotron').addClass('hidden');
                });
            });
        </script>
</body>
</html>