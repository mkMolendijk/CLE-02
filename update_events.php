<?php
require_once "includes/db_connect.php";

// Values received via ajax
$id = $_POST['id'];
$name = $_POST['stuName'];
$location = $_POST['stuLocation'];
$start = $_POST['startTime'];
$end = $_POST['endTime'];
var_dump($_POST);
// update the records
$sql = "UPDATE `agenda_entries` SET `title`= '$name', `start`= '$start', `end`= '$end', `location`= '$location', `id`= ''";

//$db->query($sql);
