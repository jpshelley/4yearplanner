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

/* 
Although this is not pretty, it works. 8 seperate queries are built and run for each of 8 semesters (this would pose a problem for more than 8)
  */
$stmt_sem_1 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[0][0]); 
$stmt_sem_1->execute($params);
$stmt_sem_2 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[1][0]);
$stmt_sem_2->execute($params);
$stmt_sem_3 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[2][0]);
$stmt_sem_3->execute($params);
$stmt_sem_4 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[3][0]);
$stmt_sem_4->execute($params);
$stmt_sem_5 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[4][0]);
$stmt_sem_5->execute($params);
$stmt_sem_6 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[5][0]);
$stmt_sem_6->execute($params);
$stmt_sem_7 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[6][0]);
$stmt_sem_7->execute($params);
$stmt_sem_8 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[7][0]);
$stmt_sem_8->execute($params);

$sem_1_courses = $stmt_sem_1->fetchAll(PDO::FETCH_COLUMN, 0);
$sem_2_courses = $stmt_sem_2->fetchAll(PDO::FETCH_COLUMN, 0);
$sem_3_courses = $stmt_sem_3->fetchAll(PDO::FETCH_COLUMN, 0);
$sem_4_courses = $stmt_sem_4->fetchAll(PDO::FETCH_COLUMN, 0);
$sem_5_courses = $stmt_sem_5->fetchAll(PDO::FETCH_COLUMN, 0);
$sem_6_courses = $stmt_sem_6->fetchAll(PDO::FETCH_COLUMN, 0);
$sem_7_courses = $stmt_sem_7->fetchAll(PDO::FETCH_COLUMN, 0);
$sem_8_courses = $stmt_sem_8->fetchAll(PDO::FETCH_COLUMN, 0);

// Setting up array of courseid's for the courses in each semester, so we can grab the name and credits values
$sem_1_c = array_map(create_function('$value', 'return (int)$value;'),$sem_1_courses);
$sem_2_c = array_map(create_function('$value', 'return (int)$value;'),$sem_2_courses);
$sem_3_c = array_map(create_function('$value', 'return (int)$value;'),$sem_3_courses);
$sem_4_c = array_map(create_function('$value', 'return (int)$value;'),$sem_4_courses);
$sem_5_c = array_map(create_function('$value', 'return (int)$value;'),$sem_5_courses);
$sem_6_c = array_map(create_function('$value', 'return (int)$value;'),$sem_6_courses);
$sem_7_c = array_map(create_function('$value', 'return (int)$value;'),$sem_7_courses);
$sem_8_c = array_map(create_function('$value', 'return (int)$value;'),$sem_8_courses);

// Imploding arrays to fill with ? for queries
$sem_1_place_holders = implode(',', array_fill(0, count($sem_1_c), '?'));
$sem_2_place_holders = implode(',', array_fill(0, count($sem_2_c), '?'));
$sem_3_place_holders = implode(',', array_fill(0, count($sem_3_c), '?'));
$sem_4_place_holders = implode(',', array_fill(0, count($sem_4_c), '?'));
$sem_5_place_holders = implode(',', array_fill(0, count($sem_5_c), '?'));
$sem_6_place_holders = implode(',', array_fill(0, count($sem_6_c), '?'));
$sem_7_place_holders = implode(',', array_fill(0, count($sem_7_c), '?'));
$sem_8_place_holders = implode(',', array_fill(0, count($sem_8_c), '?'));

// Final select statements for course table
$final_sem_1 = $database->prepare("SELECT course_name, credits FROM course WHERE courseid IN ($sem_1_place_holders)");
$final_sem_2 = $database->prepare("SELECT course_name, credits FROM course WHERE courseid IN ($sem_2_place_holders)");
$final_sem_3 = $database->prepare("SELECT course_name, credits FROM course WHERE courseid IN ($sem_3_place_holders)");
$final_sem_4 = $database->prepare("SELECT course_name, credits FROM course WHERE courseid IN ($sem_4_place_holders)");
$final_sem_5 = $database->prepare("SELECT course_name, credits FROM course WHERE courseid IN ($sem_5_place_holders)");
$final_sem_6 = $database->prepare("SELECT course_name, credits FROM course WHERE courseid IN ($sem_6_place_holders)");
$final_sem_7 = $database->prepare("SELECT course_name, credits FROM course WHERE courseid IN ($sem_7_place_holders)");
$final_sem_8 = $database->prepare("SELECT course_name, credits FROM course WHERE courseid IN ($sem_8_place_holders)");

$final_sem_1->execute($sem_1_c);
$final_sem_2->execute($sem_2_c);
$final_sem_3->execute($sem_3_c);
$final_sem_4->execute($sem_4_c);
$final_sem_5->execute($sem_5_c);
$final_sem_6->execute($sem_6_c);
$final_sem_7->execute($sem_7_c);
$final_sem_8->execute($sem_8_c);

// Final course lists as arrays for each semester 
$sem_1_courses = $final_sem_1->fetchAll();
$sem_2_courses = $final_sem_2->fetchAll();
$sem_3_courses = $final_sem_3->fetchAll();
$sem_4_courses = $final_sem_4->fetchAll();
$sem_5_courses = $final_sem_5->fetchAll();
$sem_6_courses = $final_sem_6->fetchAll();
$sem_7_courses = $final_sem_7->fetchAll();
$sem_8_courses = $final_sem_8->fetchAll();



}

?>
<html lang="us">
    <head>
	   <meta charset="utf-8">
	   <title>4 Year Planner</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <!-- Bootstrap -->
        <link href="dist/css/bootstrap.min.css" rel="stylesheet">
	   <link href="style.css" rel="stylesheet">
	   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	   <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	   <script>	
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
	
		var sem1courses = <?php echo json_encode($sem_1_courses); ?>;
		var sem2courses = <?php echo json_encode($sem_2_courses); ?>;
		var sem3courses = <?php echo json_encode($sem_3_courses); ?>;
		var sem4courses = <?php echo json_encode($sem_4_courses); ?>;
		var sem5courses = <?php echo json_encode($sem_5_courses); ?>;
		var sem6courses = <?php echo json_encode($sem_6_courses); ?>;
		var sem7courses = <?php echo json_encode($sem_7_courses); ?>;
		var sem8courses = <?php echo json_encode($sem_8_courses); ?>;
		// 1 loop for each of 8 semesters, with each loop appending courses from the corresponding course array from php
		for (var i = 0; i < sem1courses.length; i++) {
			if (sem1courses[i][1] == 0)
			{
				$("#sem1 ul").append("<li class=\"elective\"><span>" + sem1courses[i][0] + "</span><span class=\"creds\">" + sem1courses[i][1] + "</span></li>");
			}
			else
				$("#sem1 ul").append("<li><span>" + sem1courses[i][0] + "</span><span class=\"creds\">" + sem1courses[i][1] + "</span></li>");
		}
		for (var i = 0; i < sem2courses.length; i++) {
			if (sem2courses[i][1] == 0)
			{
				$("#sem2 ul").append("<li class=\"elective\"><span>" + sem2courses[i][0] + "</span><span class=\"creds\">" + sem2courses[i][1] + "</span></li>");
			}
			else
				$("#sem2 ul").append("<li><span>" + sem2courses[i][0] + "</span><span class=\"creds\">" + sem2courses[i][1] + "</span></li>");
		}
		for (var i = 0; i < sem3courses.length; i++) {
			if (sem3courses[i][1] == 0)
			{
				$("#sem3 ul").append("<li class=\"elective\"><span>" + sem3courses[i][0] + "</span><span class=\"creds\">" + sem3courses[i][1] + "</span></li>");
			}
			else
				$("#sem3 ul").append("<li><span>" + sem3courses[i][0] + "</span><span class=\"creds\">" + sem3courses[i][1] + "</span></li>");
		}
		for (var i = 0; i < sem4courses.length; i++) {
			if (sem4courses[i][1] == 0)
			{
				$("#sem4 ul").append("<li class=\"elective\"><span>" + sem4courses[i][0] + "</span><span class=\"creds\">" + sem4courses[i][1] + "</span></li>");
			}
			else
				$("#sem4 ul").append("<li><span>" + sem4courses[i][0] + "</span><span class=\"creds\">" + sem4courses[i][1] + "</span></li>");
		}
		for (var i = 0; i < sem5courses.length; i++) {
			if (sem5courses[i][1] == 0)
			{
				$("#sem5 ul").append("<li class=\"elective\"><span>" + sem5courses[i][0] + "</span><span class=\"creds\">" + sem5courses[i][1] + "</span></li>");
			}
			else
				$("#sem5 ul").append("<li><span>" + sem5courses[i][0] + "</span><span class=\"creds\">" + sem5courses[i][1] + "</span></li>");
		}
		for (var i = 0; i < sem6courses.length; i++) {
			if (sem6courses[i][1] == 0)
			{
				$("#sem6 ul").append("<li class=\"elective\"><span>" + sem6courses[i][0] + "</span><span class=\"creds\">" + sem6courses[i][1] + "</span></li>");
			}
			else
				$("#sem6 ul").append("<li><span>" + sem6courses[i][0] + "</span><span class=\"creds\">" + sem6courses[i][1] + "</span></li>");
		}
		for (var i = 0; i < sem7courses.length; i++) {
			if (sem7courses[i][1] == 0)
			{
				$("#sem7 ul").append("<li class=\"elective\"><span>" + sem7courses[i][0] + "</span><span class=\"creds\">" + sem7courses[i][1] + "</span></li>");
			}
			else
				$("#sem7 ul").append("<li><span>" + sem7courses[i][0] + "</span><span class=\"creds\">" + sem7courses[i][1] + "</span></li>");
		}
		for (var i = 0; i < sem8courses.length; i++) {
			if (sem8courses[i][1] == 0)
			{
				$("#sem8 ul").append("<li class=\"elective\"><span>" + sem8courses[i][0] + "</span><span class=\"creds\">" + sem8courses[i][1] + "</span></li>");
			}
			else
				$("#sem8 ul").append("<li><span>" + sem8courses[i][0] + "</span><span class=\"creds\">" + sem8courses[i][1] + "</span></li>");
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
      <li class="active"><a href="#">User</a></li>
      <li><a href="#">Classes</a></li>
      <li><a href="analytics.html">Analytics</a></li>
    </ul>
    <form class="navbar-form navbar-right" autocomplete="on" role="search">
      <div class="form-group">
        <input type="text" class="form-control"  placeholder="Search">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>
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
    
<div>
    <div id="electivedialog" style="opacity:0"></div>
        <div id="wrap">
        <div class="info-col">
    	       <h2>User</h2>
    	       <dl>
            <dt id="starter">Student Information</dt>
    			<dd>
				Name: <?= $name ?>
				<br>
				Add Classes here.	   
    	  		</dd>
			<dt id="major">Degree Information</dt>
    			<dd>
				Major: 
				<a href="http://www.se.iastate.edu/academics/resources/">Software Engineering</a>
    	  		</dd>
				
			<dt id="completed">Completed Courses</dt>
    			<dd>
				Required Classes
				<br><br>
				Technical Electives
				<br><br>
				Supplemental Electives
    	  		</dd>
    	 </dl>
        </div>
        <div class="info-col">
           <h2>Classes</h2>
           <dl>
        <dt>Fall 2010</dt>
            <dd>
                <div id="sem1" class="semesterBlock">
				<div style="display: none"></div>
              	<ul>
				</ul>
              	</div>
              </dd>
        <dt>Spring 2011</dt>
            <dd>
                <div id="sem2" class="semesterBlock">
				<div style="display: none"></div>
              	<ul>	
				</ul>
              	</div>
              </dd>
        <dt>Fall 2011</dt>
            <dd>
                <div id="sem3" class="semesterBlock">
                <div style="display: none"></div>
              	<ul>
				</ul>
              	</div>
		     </dd>
        <dt>Spring 2012</dt>
            <dd>
                <div id="sem4" class="semesterBlock">
                <div style="display: none"></div>
              	<ul>
				</ul>
              	</div>
		      </dd>
        <dt>Fall 2012</dt>
		  	<dd>
              <div id="sem5" class="semesterBlock">
			  <div style="display: none"></div>
              	<ul>	
				</ul>
              	</div>
		     </dd>
        <dt>Spring 2013</dt>
		  	<dd>
                <div id="sem6" class="semesterBlock">
                <div style="display: none"></div>
                <ul>
				</ul>
              	</div>
		     </dd>
        <dt>Fall 2013</dt>
            <dd>
                <div id="sem7" class="semesterBlock">
                <div style="display: none"></div>
              	<ul>
				</ul>
              	</div>
		     </dd>
        <dt>Spring 2014</dt>
            <dd>
                <div id="sem8" class="semesterBlock">
                <div style="display: none"></div>
              	<ul>
				</ul>
              	</div>
		     </dd>
		</dl>
	</div>
    </div>
					
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
    
	    <script src="dist/js/bootstrap.min.js"></script>
        <script src="js/chart/Chart.min.js"></script>
    </body>
</html>