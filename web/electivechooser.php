<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<!-- We use this page as a self-contained script to grab a list of electives from the database to choose from
The current progress is that all 3 categories are returened, but the supplemental category is always displayed
The next step would be to check what kind of elective was clicked on, so the correct list is displayed -->
<?php
function dbInit_SQLite()
{

	//$database = sqlite_open("project3.sqlite.db") or die("Failed to make/connect to database. ");
	$database = new PDO('sqlite:project3.sqlite.db');
	$database->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	return $database;
}

function get_sup_electives($majorid)
{
	$database = dbInit_SQLite();
	// change this into select statement
	$major = $majorid;
	// get sup electives
	$stmt_sup = $database->prepare("SELECT courseid FROM supplemental_elective WHERE majorid = ?");
	$params = array($major); 
	$stmt_sup->execute($params);
	$sup_result = $stmt_sup->fetchAll(PDO::FETCH_COLUMN, 0);
	$sup_result = array_map(create_function('$value', 'return (int)$value;'),$sup_result);
	$sup_place_holders = implode(',', array_fill(0, count($sup_result), '?'));
	$final_stmt_sup = $database->prepare("SELECT course_name, credits FROM course WHERE courseid IN ($sup_place_holders)");
	$final_stmt_sup->execute($sup_result);
	$sup_classes = $final_stmt_sup->fetchAll();
	return $sup_classes;
}
function get_tech_electives($majorid)
{
	$database = dbInit_SQLite();
	// change this into select statement
	$major = $majorid;
	// get tech electives
	$stmt_tech = $database->prepare("SELECT courseid FROM tech_elective WHERE majorid = ?");
	$params = array($major); 
	$stmt_tech->execute($params);	
	$tech_result = $stmt_tech->fetchAll(PDO::FETCH_COLUMN, 0);
	$tech_result = array_map(create_function('$value', 'return (int)$value;'),$tech_result);
	$tech_place_holders = implode(',', array_fill(0, count($tech_result), '?'));
	$final_stmt_tech = $database->prepare("SELECT course_name, credits FROM course WHERE courseid IN ($tech_place_holders)");
	$final_stmt_tech->execute($tech_result);
	$tech_classes = $final_stmt_tech->fetchAll();
	return $tech_classes;
}
function get_gen_electives($majorid)
{
	$database = dbInit_SQLite();
	// change this into select statement
	$major = $majorid;
	// get general electives
	$stmt_gen = $database->prepare("SELECT courseid FROM general_elective WHERE majorid = ?");
	$params = array($major); 
	$stmt_gen->execute($params);
	$gen_result = $stmt_gen->fetchAll(PDO::FETCH_COLUMN, 0);
	$gen_result = array_map(create_function('$value', 'return (int)$value;'),$gen_result);
	$gen_place_holders = implode(',', array_fill(0, count($gen_result), '?'));
	$final_stmt_gen = $database->prepare("SELECT course_name, credits FROM course WHERE courseid IN ($gen_place_holders)");
	$final_stmt_gen->execute($gen_result);
	$gen_classes = $final_stmt_gen->fetchAll();
	return $gen_classes;
}
$sup_classes = get_sup_electives(1);
$tech_classes = get_tech_electives(1);
$gen_classes = get_gen_electives(1);

?>

<script>
$(document).ready(function() {
	var supclass = <?php echo json_encode($sup_classes); ?>;
	var techclass = <?php echo json_encode($tech_classes); ?>;
	var genclass = <?php echo json_encode($gen_classes); ?>;
	for (var i = 0; i < supclass.length; i++) {
		$("#suplist").append("<li>" + supclass[i][0] + " :: " + supclass[i][1] + " credits</li>");
	}
	for (var i = 0; i < techclass.length; i++) {
		$("#techlist").append("<li>" + techclass[i][0] + " :: " + techclass[i][1] + " credits</li>");
	}
	for (var i = 0; i < genclass.length; i++) {
		$("#genlist").append("<li>" + genclass[i][0] + " :: " + genclass[i][1] + " credits</li>");
	}
	
});

</script>
</head>
<div class="arrow-left"></div>
<ul id="suplist" class="eleclist" >

</ul>
<!-- <ul id="techlist" class="eleclist" >

</ul>
<ul id="genlist" class="eleclist" >

</ul> -->
</html>