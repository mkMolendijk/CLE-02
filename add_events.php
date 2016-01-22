<?php
require_once "includes/db_connect.php";

// Values received via ajax
$name = $_POST['stuName'];
$location = $_POST['stuLocation'];
$start = $_POST['startTime'];
$end = $_POST['endTime'];
$adminId = $_POST['adminId'];

// Insert the records into the DB
$sql = "INSERT INTO `agenda_entries` (`title`, `start`, `end`, `location`, `admin_id`)
        VALUES ('$name', '$start', '$end', '$location', '$adminId')";
$db->query($sql);
