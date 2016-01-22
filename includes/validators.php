<?php

function ifLoggedIn()
{
    $sessionRole = $_SESSION['role'];

    if (isset($_SESSION['role'])) {
        if ($sessionRole == "admin") {
            header("location: admin_home.php");
            exit;
        } else {
            header("location: user_home.php");
            exit;
        }
    }
}

function ifNotLoggedIn() {

    if (!isset($_SESSION['id'])) {
        header("location: index.php");
        exit;
    }
}
