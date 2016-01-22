<?php
require_once "includes/db_connect.php";
session_start();

$errors = "";
if (isset($_POST['submit'])) {
    if (isset($_POST['email'])) {
        $adminEmail = mysqli_real_escape_string($db, $_POST['email']);
        $adminRole = mysqli_real_escape_string($db, $_POST['role']);
        $adminPass = password_hash(mysqli_real_escape_string($db, $_POST['pass']), PASSWORD_DEFAULT);
        $adminFName = mysqli_real_escape_string($db, $_POST['fname']);
        $adminLName = mysqli_real_escape_string($db, $_POST['lname']);
        $adminPhone = mysqli_real_escape_string($db, $_POST['phone']);

        $sqlOne = "INSERT INTO `users` VALUES (NULL, '$adminEmail', '$adminPass', '$adminRole')";

        if ($db->query($sqlOne) == TRUE) {
            $newAdminId = mysqli_insert_id($db);
            $sqlTwo = "INSERT INTO `admins`(`admin_id`, `fname`, `lname`, `phone`)
                  VALUES ('$newAdminId', '$adminFName', '$adminLName', '$adminPhone')";
            if ($db->query($sqlTwo) == TRUE) {
                header("location: admin_home.php");
                exit;
            } else {
                $errors = "Het account kon niet toegevoegd worden, probeer het later opnieuw.";
            }
        } else {
            $errors = "Het account kon niet toegevoegd worden, probeer het later opnieuw.";
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registratie admin</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.js"></script>
</head>
<body>
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-sm-6 col-md-4 col-md-offset-4 vertical-center">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Registratie rij instructeur</h3>
                    </div>
                    <form role="form" action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
                        <div class="panel-body">
                            <?
                            if ($errors) {
                                echo '<div class="statusmsg" style="display: block;">' . $errors . '</div>';
                            }
                            ?>
                            <fieldset class="form-group">
                                <input type="email" class="form-control" name="email" id="adminEmail" placeholder="Email adres:" required/>
                                <input type="hidden" name="role" id="adminRole" value="admin">
                            </fieldset>
                            <fieldset class="form-group">
                                <input type="password" class="form-control" name="pass" id="adminPass" placeholder="Password:" required/>
                            </fieldset>
                            <fieldset class="form-group">
                                <input type="text" class="form-control" name="fname" id="adminFname" placeholder="Voornaam:" required/>
                            </fieldset>
                            <fieldset class="form-group">
                                <input type="text" class="form-control" name="lname" id="adminLname" placeholder="Achternaam:" required/>
                            </fieldset>
                            <fieldset class="form-group">
                                <input type="tel" pattern="{10}" class="form-control" name="phone" id="adminPhone" placeholder="Telefoon nummer:" required/>
                            </fieldset>
                            <button type="submit" class="btn btn-success btn-block" name="submit">Maak account aan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
