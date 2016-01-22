<?php
require_once "includes/db_connect.php";

$eventId = $_POST['eventId'];

$sql = "DELETE from `agenda_entries` WHERE id= '$eventId'";
$db->query($sql);
