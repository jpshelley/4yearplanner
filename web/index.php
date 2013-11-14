<?php

session_start();
if(!isset($_SESSION['netid']))
{
	 header('Location: ' . 'login.php');
}
$name = $_SESSION['last'] . ', ' . $_SESSION['first'];

function dbInit_SQLite()
{

	//$database = sqlite_open("project3.sqlite.db") or die("Failed to make/connect to database. ");
	$database = new PDO('sqlite:project3.sqlite.db');
	$database->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	return $database;
}
if (isset($_SESSION['netid']))
{
$database = dbInit_SQLite();
// change this into select statement
//$major = $majorid;
// Create new schedule and get id
$stmt_sem_1 = $database->prepare("SELECT semesterid, completed FROM semester WHERE netid = ? AND order = 0");
$stmt_sem_2 = $database->prepare("SELECT semesterid, completed FROM semester WHERE netid = ? AND order = 1");
$stmt_sem_3 = $database->prepare("SELECT semesterid, completed FROM semester WHERE netid = ? AND order = 2");
$stmt_sem_4 = $database->prepare("SELECT semesterid, completed FROM semester WHERE netid = ? AND order = 3");
$stmt_sem_5 = $database->prepare("SELECT semesterid, completed FROM semester WHERE netid = ? AND order = 4");
$stmt_sem_6 = $database->prepare("SELECT semesterid, completed FROM semester WHERE netid = ? AND order = 5");
$stmt_sem_7 = $database->prepare("SELECT semesterid, completed FROM semester WHERE netid = ? AND order = 6");
$stmt_sem_8 = $database->prepare("SELECT semesterid, completed FROM semester WHERE netid = ? AND order = 7");
$params = array('jbravo'); 

$stmt_sem_1->execute($params);
$stmt_sem_2->execute($params);
$stmt_sem_3->execute($params);
$stmt_sem_4->execute($params);
$stmt_sem_5->execute($params);
$stmt_sem_6->execute($params);
$stmt_sem_7->execute($params);
$stmt_sem_8->execute($params);

$stmt_sem_1_res = $stmt_sem_1->fetchAll();
$stmt_sem_2_res = $stmt_sem_2->fetchAll();
$stmt_sem_3_res = $stmt_sem_3->fetchAll();
$stmt_sem_4_res = $stmt_sem_4->fetchAll();
$stmt_sem_5_res = $stmt_sem_5->fetchAll();
$stmt_sem_6_res = $stmt_sem_6->fetchAll();
$stmt_sem_7_res = $stmt_sem_7->fetchAll();
$stmt_sem_8_res = $stmt_sem_8->fetchAll();

$stmt_sem_1 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[0][0]); 
$stmt_sem_1->execute($params);
$stmt_sem_2 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[0][1]);
$stmt_sem_2->execute($params);
$stmt_sem_3 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[0][2]);
$stmt_sem_3->execute($params);
$stmt_sem_4 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[0][3]);
$stmt_sem_4->execute($params);
$stmt_sem_5 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[0][4]);
$stmt_sem_5->execute($params);
$stmt_sem_6 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[0][5]);
$stmt_sem_6->execute($params);
$stmt_sem_7 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[0][6]);
$stmt_sem_7->execute($params);
$stmt_sem_8 = $database->prepare("SELECT courseid FROM semester_schedule WHERE semesterid = ?");
$params = array($stmt_sem_1_res[0][7]);
$stmt_sem_8->execute($params);

$sem_1_courses = $stmt_sem_1->fetchAll(PDO::FETCH_COLUMN, 0);
$sem_2_courses = $stmt_sem_2->fetchAll(PDO::FETCH_COLUMN, 0);
$sem_3_courses = $stmt_sem_3->fetchAll(PDO::FETCH_COLUMN, 0);
$sem_4_courses = $stmt_sem_4->fetchAll(PDO::FETCH_COLUMN, 0);
$sem_5_courses = $stmt_sem_5->fetchAll(PDO::FETCH_COLUMN, 0);
$sem_6_courses = $stmt_sem_6->fetchAll(PDO::FETCH_COLUMN, 0);
$sem_7_courses = $stmt_sem_7->fetchAll(PDO::FETCH_COLUMN, 0);
$sem_8_courses = $stmt_sem_8->fetchAll(PDO::FETCH_COLUMN, 0);


$sem_1_c = array_map(create_function('$value', 'return (int)$value;'),$sem_1_courses);
$sem_2_c = array_map(create_function('$value', 'return (int)$value;'),$sem_2_courses);
$sem_3_c = array_map(create_function('$value', 'return (int)$value;'),$sem_3_courses);
$sem_4_c = array_map(create_function('$value', 'return (int)$value;'),$sem_4_courses);
$sem_5_c = array_map(create_function('$value', 'return (int)$value;'),$sem_5_courses);
$sem_6_c = array_map(create_function('$value', 'return (int)$value;'),$sem_6_courses);
$sem_7_c = array_map(create_function('$value', 'return (int)$value;'),$sem_7_courses);
$sem_8_c = array_map(create_function('$value', 'return (int)$value;'),$sem_8_courses);

$sem_1_place_holders = implode(',', array_fill(0, count($sem_1_c), '?'));
$sem_2_place_holders = implode(',', array_fill(0, count($sem_2_c), '?'));
$sem_3_place_holders = implode(',', array_fill(0, count($sem_3_c), '?'));
$sem_4_place_holders = implode(',', array_fill(0, count($sem_4_c), '?'));
$sem_5_place_holders = implode(',', array_fill(0, count($sem_5_c), '?'));
$sem_6_place_holders = implode(',', array_fill(0, count($sem_6_c), '?'));
$sem_7_place_holders = implode(',', array_fill(0, count($sem_7_c), '?'));
$sem_8_place_holders = implode(',', array_fill(0, count($sem_8_c), '?'));

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
		
		alert(sem1courses[0][0]);
	
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
		$('#sem1 ul').append("<li><span>COMS 141</span><span class=\"creds\">Credits: 4</span></li>");
		$('#sem1 ul').append("<li class=\"complete\"><span>COMS 341</span><span class=\"creds\">Credits: 4</span></li>");
		$('#sem1 ul').append("<li><span>COMS 441</span><span class=\"creds\">Credits: 4</span></li>");
		$('#sem2 ul').append("<li class=\"elective\"><span>Supp Elective</span><span class=\"creds\">Credits: 3-4</span></li>");
		$('#sem1 ul').append("<li class=\"isCompleted\"><div>Completed: <input type=\"checkbox\" id=\"completed_id\" value=\"completed_value\" /></div></li>");
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
						  		semester_id:""
							  	is_complete:"false"
						  },
						  function(data,status){
						  });
			}
			else{
				$(this).find('#completed_id').prop('checked', true);
				$.post("complete_semester.php",
						  {
						  		semester_id:""
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
<div id="electivedialog" style="opacity:0"></div>
<div id="wrap">
    <div class="info-col">
    
    	<h2>User</h2>
    	
    	<dl>
    		<dt id="starter">User Info</dt>
    			<dd>
				Name: <?= $name ?>
				<br>
				Add Classes here.	   
    	  		</dd>
			<dt id="major">Major Info</dt>
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


</body>
     
    
</html>
