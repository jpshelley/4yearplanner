<?php
include 'db_util.php';
session_start();
if(!isset($_SESSION['netid']))
{
	 header('Location: ' . 'login.php');
}
$name = $_SESSION['last'] . ', ' . $_SESSION['first'];
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
    
    // Get Courses for Class Listing
    $stmt_courses = $database->prepare("SELECT course_name, url FROM course");
    $stmt_courses->execute();
    $all_classes_results = array();
    for($i = 0; $i < 30; $i++){
        $all_classes_results[$i] = $stmt_courses->fetchAll();
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
	   <script>	

	   var semesterToAddTo;
       
        $(function() {
           var courseArr = <?php echo json_encode($all_classes_results); ?>;
           var courses = courseArr[0];
           for(var i = 0; i < courses.length; i++){
               $("#sidebar_inner").append("<a class='menu' href='#'  title=" + courses[i][1]+ ">" + courses[i][0] + "</a>");
			   $("#courseList").append("<div class=\"courseListRow row\"><div class=\"col-md-12\">" + courses[i][0] + "</div></div>");
           }
        });
		
	$(function() {
	
		var semcourses = <?php echo json_encode($sem_courses); ?>;
		// 1 loop for each of 8 semesters, with each loop appending courses from the corresponding course array from php
		$("#allSemesters > .semester.row").each(function(j) {
			var courses = semcourses[j];
			for (var i = 0; i < courses.length; i++) {
				var course = courses[i];
				var id = j + 1;
				if (course.credits == 0)
				{
					$(this).find(".classes").append("<div class=\"course appendedCourse elective\"><div class=\"courseClose\">X</div><p><b>" + course[0] + "</b></p><p>" + course[1] + " cr.</p></div>");
				}
				else
					$(this).find(".classes").append("<div class=\"course appendedCourse\"><div class=\"courseClose\">X</div><p><b>" + course[0] + "</b></p><p>" + course[1] + " cr.</p></div>");
			}
		});
		
		
		
	    // Set up variables
	    var $el, $parentWrap, $otherWrap, 
	        $allTitles = $("dt").css({
	            padding: 5, // setting the padding here prevents a weird situation, where it would start animating at 0 padding instead of 5
	            "cursor": "pointer" // make it seem clickable
	        }),
	        $allCells = $("dd").css({
	            position: "relative",
	            top: -1,
	            left: 0,
	            display: "none" // info cells are just kicked off the page with CSS (for accessibility)
	        });		
		
		$(".addClass").click(function(){
			semesterToAddTo = $(this).parent().parent();
			$("#classbar").css("visibility", "visible");
			$("#courseListOk").css("visibility", "visible");
			$("#allSemesters").animate({width:"950px"},{duration: 400, queue: false });
		});
		
		$(".courseListRow").click(function(){
			var color = $(this).css("border-color");
			if($(this).css("border-color") == 'rgb(255, 231, 191)'){
				$(this).css("border", "green solid 1px");
			}
			else{
				$(this).css("border", "#FFE7BF solid 1px");
			}

		});
		
		$("#courseListOk").click(function(){

			var courseArr = [];
			$(".courseListRow").each(function(i){
				
				var course = $(this).children(":first");
				var className = course.text().trim();

				if(course.parent().css("border-color") != 'rgb(255, 231, 191)'){
					courseArr.push(className);
					semesterToAddTo.find(".classes").append("<div class=\"course appendedCourse\"><div class=\"courseClose\">X</div><p><b>" + className + "</b></p><p>3 cr.</p></div>");
				}
			});

			$(".courseListRow").css("border", "#FFE7BF solid 1px");
			$("#classbar").css("visibility", "hidden");
			$("#courseListOk").css("visibility", "hidden");
			$("#allSemesters").animate({width:"1170px"},{duration: 400, queue: false });

			$(".courseClose").click(function(){
				$(this).parent().remove();
			});

			$.get('addClass.php', {
				semester: semesterToAddTo.children(':first').children(':first').text(), 
				classes: courseArr
			});

			//Haytham
			$(".appendedCourse").click( function(){
				var coursename = $('p:first', this).text();
				var netid = "<?php echo $_SESSION['netid']; ?>";
			
				if($(this).hasClass("courseComplete")) {
					$(this).removeClass("courseComplete");
					//$.post('complete_course.php', {course_name: "Com S 229", net_id: "jbravo", complete: "false"});
					$.post('complete_course.php', {course_name: coursename, net_id: netid, complete: "false"});
				} else {
					$(this).addClass("courseComplete");
					//$.post('complete_course.php', {course_name: "Com S 229", net_id: "jbravo", complete: "true"});
					$.post('complete_course.php', {course_name: coursename, net_id: netid, complete: "true"});
				}
			});
						
		});

		$(".courseClose").click(function(){
			$(this).parent().remove();
		});
		
		//Haytham
		$(".course").click( function(){
			var coursename = $('p:first', this).text();
			var netid = "<?php echo $_SESSION['netid']; ?>";
			
			if($(this).hasClass("courseComplete")) {
				$(this).removeClass("courseComplete");
				//$.post('complete_course.php', {course_name: "Com S 229", net_id: "jbravo", complete: "false"});
				$.post('complete_course.php', {course_name: coursename, net_id: netid, complete: "false"});
			} else {
				$(this).addClass("courseComplete");
				//$.post('complete_course.php', {course_name: "Com S 229", net_id: "jbravo", complete: "true"});
				$.post('complete_course.php', {course_name: coursename, net_id: netid, complete: "true"});
			}
		});
	    
	});
        
	</script>    
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
    <a class="navbar-brand" href="#">4Y</a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
      <li id="sidebar_toggle"><a href="#">Classes</a></li>
      <li><a href="analytics.html">Analytics</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="http://www.iastate.edu">Iowa State</a></li>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['netid']; ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="profile.php">Profile</a></li>
          <li><a href="social.php">Social</a></li>
          <li class="divider"></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>
    
<div id="sidebar">
    <div id="sidebar_inner">
      <div id="sidebar_border"></div>
        <h2>Classes</h2>
    </div><!-- #sidebar_inner -->
</div><!-- #sidebar -->
    

<div id="content">

 <div id="overlay" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Course</h4>
      </div>
      <div class="modal-body">
              <iframe id="course-overlay" class="well well-sm" style="height: 500px; background-color: #822433;" src=""></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h3 id="progress-title">Your Current Progress</h3>
    <h5 id="progress-title"> You're almost there!</h5>
    <div class="progress progress-striped">
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
            <span class="sr-only">80% Complete (success)</span>
        </div>
    </div>
</div>
    
<div class = "schedule">
	<span>
		<div id="allSemesters" class="container">
			<div class = "semester row">
					<div class="semesterHeader col-md-1">
						<p class="semesterHeaderTitle" id="semester1">Fall 2010</p>
					</div>
					<div class="classes col-md-10">
					</div>
					<div class="col-xs-1" style="width: 33px;">
						<button type="button" class="addClass addIcon btn btn-default btn-sm">
							<span class="glyphicon glyphicon-plus"></span>
						</button>
					</div>
			</div>
			<div class = "semester row">
					<div class="semesterHeader col-md-1">
						<p class="semesterHeaderTitle" id="semester1">Spring 2011</p>
					</div>
					<div class="classes col-md-10">
					</div>
					<div class="col-xs-1" style="width: 33px;">
						<button type="button" class="addClass addIcon btn btn-default btn-sm">
							<span class="glyphicon glyphicon-plus"></span>
						</button>
					</div>
			</div>
			<div class = "semester row">
					<div class="semesterHeader col-md-1">
						<p class="semesterHeaderTitle" id="semester1">Fall 2011</p>
					</div>
					<div class="classes col-md-10">
					</div>
					<div class="col-xs-1" style="width: 33px;">
						<button type="button" class="addClass addIcon btn btn-default btn-sm">
							<span class="glyphicon glyphicon-plus"></span>
						</button>
					</div>
			</div>
			<div class = "semester row">
					<div class="semesterHeader col-md-1">
						<p class="semesterHeaderTitle" id="semester1">Spring 2012</p>
					</div>
					<div class="classes col-md-10">
					</div>
					<div class="col-xs-1" style="width: 33px;">
						<button type="button" class="addClass addIcon btn btn-default btn-sm">
							<span class="glyphicon glyphicon-plus"></span>
						</button>
					</div>
			</div>
			<div class = "semester row">
					<div class="semesterHeader col-md-1">
						<p class="semesterHeaderTitle" id="semester1">Fall 2012</p>
					</div>
					<div class="classes col-md-10">
					</div>
					<div class="col-xs-1" style="width: 33px;">
						<button type="button" class="addClass addIcon btn btn-default btn-sm">
							<span class="glyphicon glyphicon-plus"></span>
						</button>
					</div>
			</div>
			<div class = "semester row">
					<div class="semesterHeader col-md-1">
						<p class="semesterHeaderTitle" id="semester1">Spring 2013</p>
					</div>
					<div class="classes col-md-10">
					</div>
					<div class="col-xs-1" style="width: 33px;">
						<button type="button" class="addClass addIcon btn btn-default btn-sm">
							<span class="glyphicon glyphicon-plus"></span>
						</button>
					</div>
			</div>
			<div class = "semester row">
					<div class="semesterHeader col-md-1">
						<p class="semesterHeaderTitle" id="semester1">Fall 2013</p>
					</div>
					<div class="classes col-md-10">
					</div>
					<div class="col-xs-1" style="width: 33px;">
						<button type="button" class="addClass addIcon btn btn-default btn-sm">
							<span class="glyphicon glyphicon-plus"></span>
						</button>
					</div>
			</div>
			<div class = "semester row">
					<div class="semesterHeader col-md-1">
						<p class="semesterHeaderTitle" id="semester1">Spring 2014</p>
					</div>
					<div class="classes col-md-10">
					</div>
					<div class="col-xs-1" style="width: 33px;">
						<button type="button" class="addClass addIcon btn btn-default btn-sm">
							<span class="glyphicon glyphicon-plus"></span>
						</button>
					</div>
			</div>
		</div>
		<div id = "classbar" class="container" style="overflow-y:scroll;">
			<div class="row">
				<div class="col-md-2">
					<h4>Course List</h4>
				</div>
			</div>
			<div id="courseList" class="container" style="width:150px;">
				
			</div>

		</div>
            <button id="courseListOk" type="button" class="btn btn-default">
					OK
		    </button>

	</span>
</div>

<footer class="panel footer">
    <div class="panel-footer">
       <h2>Addidas</h2>
        <div id="navcontainer">
            <ul>
                <li><a href="http://twitter.com/jpshells">John</a></li>
                <li><a href="#">Haitham</a></li>
                <li><a href="#">Ian</a></li>
                <li><a href="#">Trevor</a></li>
            </ul>
        </div>
    </div>
</footer>
</div>
        <script>
        /*
        var xhr;
        if (window.XMLHttpRequest) xhr = new XMLHttpRequest();      // all browsers except IE
        else xhr = new ActiveXObject("Microsoft.XMLHTTP");      // for IE
         
        xhr.open('GET', 'classes.xml', false);
        xhr.onreadystatechange = function () {
            if (xhr.readyState===4 && xhr.status===200) {           
                var items = xhr.responseXML.getElementsByTagName('title');
                var output = '<ul>';
                for (var i=0; i<items.length; i++) 
                    output += '<li>' + "test" + items[i].firstChild.nodeValue + '</li>';
                output += '</ul>';
         
                var div = document.getElementById('year1');
                div.innerHTML = output;
            }
        }
        xhr.send();
        */
        </script>
	    <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script>
            
            // popover demo
            $(function(){
                $('.menu').click(function(){
                    var title = $(this).attr( "title" );
                    $('#overlay').modal('show');
                    $('#course-overlay').attr('src', title);
                });
            });

            
            $('#sidebar_toggle').click(function(){
                   var content_position = $('#content').offset();
                      if(content_position.left > 0){
                             $('#content').not('#tag_button.relative').stop(true, true).removeClass('sidebar_open', 400, 'linear', function(){
                    $('#sidebar_toggle').removeClass('open');
                    $('#sidebar_toggle').removeClass('active');

                             });
                      } else {

                             var open_width = $('#menubar').outerWidth();
                             $('#content').not('#tag_button.relative').stop(true, true).addClass('sidebar_open', 400, 'linear', function(){
                                    $('#sidebar_toggle').addClass('open');
                                    $('#sidebar_toggle').addClass('active');
                                    $('#class_toggle').removeClass('active');
                             });
                      }
               });
            
            
        </script>
	    <script src="dist/js/bootstrap.min.js"></script>
        <script src="js/chart/Chart.min.js"></script>
    </body>
</html>