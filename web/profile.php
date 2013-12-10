<?php
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
          <li class="active"><a href="profile.php">Profile</a></li>
          <li><a href="#">Social</a></li>
          <li class="divider"></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>    

    <div class="jumbotron">
    <h1>Hello, <?php echo $_SESSION['netid']; ?>!</h1>
    <p>Here you will find all of your personal information, for safe keeping.</p>
    <p><a id="close-jumbo"class="btn btn-primary btn-lg" role="button">Got It!</a></p>
    </div>
    
    <div class="user-info">
        <header class="profile-header js-profile-header">
            <div class="l-container">
            <a class="avatar avatar-gargantuan js-profile-pic"
               href="profile.php">
            <img class="avatar-current-user avatar-image" src=""/>
            </a>
            <div class="profile-header-name">
                <h1><?php echo $name; ?></h1>

                <div class="profile-header-name">
                    <h2>Iowa State University - Software Engineering</h2>
                </div>
                <div class="profile-header-name-social">
                    <a href="https://www.facebook.com/john.shelley.p">
                        <i class="icon-facebook-sign"></i>
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </header>
        
        
<div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
            Classes In Session
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in">
      <div class="panel-body">
          <a class="menu"> Sample Class</a>
          <a class="menu"> Sample Class 2</a>
          <a class="menu"> Sample Class 3</a>
          <a class="menu"> Sample Class 4</a>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
          Classes Planned
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body">
          <a class="menu"> Sample Class</a>
          <a class="menu"> Sample Class 2</a>
          <a class="menu"> Sample Class 3</a>
          <a class="menu"> Sample Class 4</a>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
          Classes Completed
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body">
          <a class="menu"> Sample Class</a>
          <a class="menu"> Sample Class 2</a>
          <a class="menu"> Sample Class 3</a>
          <a class="menu"> Sample Class 4</a>
      </div>
    </div>
  </div>
</div>
</div>
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