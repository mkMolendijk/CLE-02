<?php
require_once "includes/db_connect.php";
sleep(1);

print_r($_POST);
$colName = mysqli_real_escape_string($db, $_POST['name']);
$colVal = mysqli_real_escape_string($db, $_POST['value']);
$userId = $_POST['pk'];

$sql = "UPDATE `students` SET `$colName` = '$colVal' WHERE `user_id` = '$userId'";
if ($db->query($sql) == TRUE ){
    echo "success";
} else {
    echo "failed";
}
