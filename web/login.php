<?php

include 'db_util.php';
//$first, $last, $netid, $pass, $major, $start_year, $start_semester
create_student('Johnny','Bravo','jbravo','imsexy','Software Engineering','2010','F');
var_dump(validate_login('jbravo', 'imsexy'));
?>