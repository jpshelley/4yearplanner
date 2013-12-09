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
    $stmt_courses = $database->prepare("SELECT course_name FROM course");
    $stmt_courses->execute();
    $all_classes_results = array();
    for($i = 0; $i < 30; $i++){
        $all_classes_results[$i] = $stmt_courses->fetchAll();
    }

}

function addClass($semester, $classes)
{
	$select_sem = $database->prepare("SELECT semesterid FROM semester WHERE netid = ? AND order = ?");
	$params = array($netid, $semester);
	$select_sem->execute($params);
	$semesterid = $select_sem->fetch();
	$select_class = $database->prepare("SELECT courseid FROM course WHERE course_name = ?");
	$insert_class = $database->prepare("INSERT INTO semester_schedule (courseid, semesterid) VALUES (?, ?)");
	foreach($classes as $class)
	{
		$params = array($class);
		$select_class->execute($params);
		$courseid = $stmt_class->fetch();
		
		$params = array($courseid, $semesterid);
		$insert_class->execute($params);
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
               $("#sidebar_inner").append("<a class='menu'>" + courses[i][0] + "</a>");
           }
        });
        
		$(function() {
		  $( "#accordion" ).accordion();
		});
		
		$(function() {
		  $( "#tabs" ).tabs();
		});
		
		
		var lastScrollTop = 0;
		var itemIndex = 0;		
		$(window).scroll(function(event){
		   var st = $(this).scrollTop();
		   if ((st > lastScrollTop) && (itemIndex > 1)){
		       itemIndex = itemIndex - 1;
		   } else {
		      itemIndex = itemIndex + 1;
		   }
		   itemIndex = parseInt(itemIndex, 10);   
		   $("#accordion").accordion('option','active', (itemIndex));
		   lastScrollTop = st;
		});
		
		
	$(function() {
	
		var semcourses = <?php echo json_encode($sem_courses); ?>;
		// 1 loop for each of 8 semesters, with each loop appending courses from the corresponding course array from php
		for(var j = 0; j < 8; j++)
		{
			var courses = semcourses[j];
			for (var i = 0; i < courses.length; i++) {
				var course = courses[i];
				var id = j + 1;
				if (course.credits == 0)
				{
					$("#sem" + id + " ul").append("<li class=\"elective\"><span>" + course[0] + "</span><span class=\"creds\">" + course[1] + "</span></li>");
				}
				else
					$("#sem" + id + " ul").append("<li><span>" + course[0] + "</span><span class=\"creds\">" + course[1] + "</span></li>");
			}
		}
		
		
		
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
	    
	    // clicking image of inactive column just opens column, doesn't go to link   
	    $("#wrap").delegate("a.image","click", function(e) { 
	        
	        if ( !$(this).parent().hasClass("curCol") ) {         
	            e.preventDefault(); 
	            $(this).next().find('dt:first').click(); 
	        } 
	        
	    });
	    var $activeelec;
		// Bind click to class boxes, with a special case for electives to pop in the elective chooser script
		$("#wrap").delegate(".semesterBlock li", "click", function() {
			var cla = $(this).attr("class");
			if (cla == "elective") {
				$activeelec = $(this);			
				var position = $(this).offset();
				var left = position.left + 107;
				$("#electivedialog").css({
					top: position.top+"px",
					left: left+"px"
				}).animate({opacity:1,left: "+=30px"}, 100);
				$("#electivedialog li").bind("click", function() {
					var ctext = $(this).text().split("::");
					$activeelec.children().eq(0).text(ctext[0]);
					$activeelec.children().eq(1).text("Credits: " + ctext[1].charAt(1));
					$("#electivedialog").animate({opacity:0, left: "-=30px"}, 50);
					setTimeout(function() {
						$("#electivedialog").css({
							top: "0px",
							left: "0px"
						});
					}, 100);
					$("#electivedialog li").unbind();
				});
				setTimeout(function() {
					$("#electivedialog").bind("mouseenter", function() {
						$("#electivedialog").bind("mouseleave", function() {
							$("#electivedialog").animate({opacity:0, left: "-=30px"}, 50);
							setTimeout(function() {
								$("#electivedialog").css({
									top: "0px",
									left: "0px"
								});
							}, 100);
							$("#electivedialog").unbind();
							$("#electivedialog li").unbind();
						});
					});
				}, 100);
			}
			else if (cla != "selected" && cla != "complete" && cla != "isCompleted")
				$(this).addClass("selected");
			else if (cla == "selected")
			{
				$(this).removeClass("selected");
			}
			return false;
		});
		
	    // clicking on titles does stuff
	    $("#wrap").delegate("dt", "click", function() {
	        
	        // cache this, as always, is good form
	        $el = $(this);
	        $("#electivedialog").animate({opacity:0, left: "-=30px"}, 50);
			setTimeout(function() {
				$("#electivedialog").css({
					top: "0px",
					left: "0px"
				});
			}, 100);
	        // if this is already the active cell, don't do anything
	        if (!$el.hasClass("current")) {
	        
	            $parentWrap = $el.parent().parent();
	            $otherWraps = $(".info-col").not($parentWrap);
	            
	            // remove current cell from selection of all cells
	            $allTitles = $("dt").not(this);
	            
	            // close all info cells
	            $allCells.slideUp();
	            
	            // return all titles (except current one) to normal size
	            $allTitles.animate({
	                fontSize: "14px",
	                paddingTop: 5,
	                paddingRight: 5,
	                paddingBottom: 5,
	                paddingLeft: 5
	            });
	            
	            // animate current title to larger size            
	            $el.animate({
	                "font-size": "20px",
	                paddingTop: 10,
	                paddingRight: 5,
	                paddingBottom: 0,
	                paddingLeft: 10
	            }).next().slideDown();
	            
	            // make the current column the large size
	            $parentWrap.animate({
	                width: 900
	            }).addClass("curCol");
	            
	            // make other columns the small size
	            $otherWraps.animate({
	                width: 140
	            }).removeClass("curCol");
	            
	            // make sure the correct column is current
	            $allTitles.removeClass("current");
	            $el.addClass("current");  
	        
	        }
	        
	    });
		$("#electivedialog").load("electivechooser.php");
		//for (var i=0; i<5; i++;) {
		//}
		
	    $("#starter").trigger("click");

		//Checks semester completed checkbox.  Sends AJAX request to update database
	    $(".isCompleted div").click(function(){
			var isChecked = $(this).find('#completed_id').prop('checked');
			if(isChecked){
				$(this).find('#completed_id').prop('checked', false);
				$.post("complete_semester.php",
						  {
						  		//TODO - get semester ID for input.  Take from id field in semesterBlock
						  		semester_id:"",
							  	is_complete:"false"
						  },
						  function(data,status){
						  });
			}
			else{
				$(this).find('#completed_id').prop('checked', true);
				$.post("complete_semester.php",
						  {
						  		semester_id:"",
							  	is_complete:"true"
						  },
						  function(data,status){
						  });
			}
			
			
		});

		
		
		$(".addClass").click(function(){
			semesterToAddTo = $(this).parent().parent();
			$("#classbar").css("visibility", "visible");
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

			$(".courseListRow").each(function(i){
				
				var course = $(this).children(":first");
				var className = course.text().trim();

				if(course.parent().css("border-color") != 'rgb(255, 231, 191)'){
					semesterToAddTo.find(".classes").append("<div class=\"course\"><p><b>" + className + "</b></p><p>3 cr.</p></div>");
				}
			});

			$(".courseListRow").css("border", "#FFE7BF solid 1px");
			$("#classbar").css("visibility", "hidden");
			$("#allSemesters").animate({width:"1170px"},{duration: 400, queue: false });
			
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
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">User <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="#">Profile</a></li>
          <li><a href="#">Social</a></li>
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
						<p class="semesterHeaderTitle" id="semester1">Spring 2010</p>
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
		</div>
		<div id = "classbar" class="container">
			<div class="row">
				<div class="col-md-12">
					<h4>Course List</h4>
				</div>
			</div>
			<div class="container" style="overflow-y:scroll;">
				<div class="courseListRow row">
					<div class="col-md-12">
						CS 229
					</div>
				</div>
				<div class="courseListRow row">
					<div class="col-md-12">
						CS 309
					</div>
				</div>
				<div class="courseListRow row">
					<div class="col-md-12">
						SE 329
					</div>
				</div>
				<div class="courseListRow row">
					<div class="col-md-12">
						CS 228
					</div>
				</div>
			</div>
			<div class="container">
				<button id="courseListOk" type="button">
					OK
				</button>
			</div>
		</div>
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
            
            $( document ).ready(function() {
                ('#')
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