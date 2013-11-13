<html lang="us">
<head>
	<meta charset="utf-8">
	<title>Porous</title>
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
			}
			else if (cla != "selected" && cla != "complete")
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
		
		//}
		
	    $("#starter").trigger("click");
 
	    
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
				Name: First Last
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
              	<ul>
				
				</ul>
              	</div>

              </dd>
		  <dt>Spring 2011</dt>
              <dd>
                <div id="sem2" class="semesterBlock">
              	<ul>
				
				</ul>
              	</div>
              </dd>
		  <dt>Fall 2011</dt>
		  	<dd>
              <div id="sem3" class="semesterBlock">
              	<ul>
				
				</ul>
              	</div>
		     </dd>
          <dt>Spring 2012</dt>
		  	<dd>
              <div id="sem4" class="semesterBlock">
              	<ul>
				
				</ul>
              	</div>
		     </dd>
		  <dt>Fall 2012</dt>
		  	<dd>
              <div id="sem5" class="semesterBlock">
              	<ul>
				
				</ul>
              	</div>
		     </dd>
		  <dt>Spring 2013</dt>
		  	<dd>
               <div id="sem6" class="semesterBlock">
              	<ul>
				
				</ul>
              	</div>
		     </dd>
		  <dt>Fall 2013</dt>
		  	<dd>
              <div id="sem7" class="semesterBlock">
              	<ul>
				
				</ul>
              	</div>
		     </dd>
		   <dt>Spring 2014</dt>
		  	<dd>
               <div id="sem8" class="semesterBlock">
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
