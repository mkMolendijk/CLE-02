<?php
require_once "includes/db_connect.php";
session_start();
//var_dump($_POST);

// Store post values in variables
$stuId = $_POST['stuId'];

// Query to delete account from database
$sql = "DELETE from `users` WHERE id = '$stuId'";
//check if delete was successful
if ($db->query($sql) == TRUE) {
    // Redirect to overview page
    $msg = "Leerling is verwijderd uit het systeem";
    header("location:my_students.php");
    exit;
} else {
    // Return error
    $errors = "Er was een probleem met het verwijderen van de leerling.";
}
