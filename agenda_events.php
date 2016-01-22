<?php
require_once "includes/db_connect.php";
session_start();

// Admin id from SESSION array
$adminId = $_SESSION['id'];

// Query that retrieves events
$sql = "SELECT * FROM `agenda_entries` WHERE `admin_id` = '$adminId'";
$result = $db->query($sql);

// List of events
$events = array();

//while ($row = mysqli_fetch_assoc($result)) {
//    $data[] = $row;
//}
while ($row = mysqli_fetch_assoc($result)) {
    $data = array();
    $data['id'] = $row['id'];
    $data['title'] = $row['title'];
    $data['location'] = $row['location'];
    $data['start'] = $row['start'];
    $data['end'] = $row['end'];
    $data['admin_id'] = $row['admin_id'];
    array_push($events, $data);
}

//var_dump($data);
// sending the encoded result to success page
echo json_encode($events);

