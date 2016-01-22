<?php
$host = "localhost";
$user = "root";
$pass = "";
$database = "dlk_db";

// Create connection
$db = mysqli_connect($host, $user, $pass, $database)
or die ("Connection Failed" . mysqli_connect_error());

// Check if connection happened
//echo "Connected successfully";
