<?php
require_once "includes/db_connect.php";
require_once "includes/validators.php";
session_start();

//ifLoggedIn();
ifNotLoggedIn();

$adminId = $_SESSION['id'];
$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];
$adminEmail = $_SESSION['email'];
$adminRole = $_SESSION['role'];

// Var for error messages
$errors = "";

// Query for students list
$userQuery = "SELECT `user_id`, `fname`, `lname` FROM `students` WHERE `admin_id` = '$adminId'";
$userData = mysqli_query($db, $userQuery);

// Form handling for submitting and creating a new student account
if (isset($_POST['submit'])) {
    if (isset($_POST['email'])) {
        $stuEmail = mysqli_real_escape_string($db, $_POST['email']);
        $stuRole = mysqli_real_escape_string($db, $_POST['role']);
        $stuPass = password_hash(mysqli_real_escape_string($db, $_POST['pass']), PASSWORD_DEFAULT);
        $stuFName = mysqli_real_escape_string($db, $_POST['fname']);
        $stuLName = mysqli_real_escape_string($db, $_POST['lname']);
        $stuAddress = mysqli_real_escape_string($db, $_POST['address']);
        $stuZip = mysqli_real_escape_string($db, $_POST['zip']);
        $stuCity = mysqli_real_escape_string($db, $_POST['city']);
        $stuPhone = mysqli_real_escape_string($db, $_POST['phone']);
        $stuPackage = mysqli_real_escape_string($db, $_POST['package']);

        $sqlOne = "INSERT INTO `users` VALUES (NULL, '$stuEmail', '$stuPass', '$stuRole')";

        if ($db->query($sqlOne) == TRUE) {
            $newUserId = mysqli_insert_id($db);
            $sqlTwo = "INSERT INTO `students`(`user_id`, `fname`, `lname`, `city`, `address`, `zip`, `email`, `phone`, `package`, `admin_id`)
                  VALUES ('$newUserId', '$stuFName', '$stuLName', '$stuCity', '$stuAddress', '$stuZip', '$stuEmail', '$stuPhone', '$stuPackage', '$adminId')";
            if ($db->query($sqlTwo) == TRUE) {
                header("location: {$_SERVER['PHP_SELF']}");
                exit;
            }
        } else {
            $errors = "De leerling kon niet toegevoegd worden, probeer het later opnieuw.";
        }
    } else {
        $errors = "Incorrect email adres, probeer het opnieuw.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leerlingen</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
<div id="wrapper">
    <div class="row navbar navbar-default">
        <div id="main-menu">
            <div class="container">
                <div class="col-xs-6 col-sm-6 col-md-4">
                    <h2>
                        <?= $firstName . ' ' . $lastName; ?>
                    </h2>
                </div>
                <div class="col-xs-6 col-md-4 col-lg-4 col-md-offset-4">
                    <ul id="menu-items" class="nav nav-pills pull-right hidden-xs hidden-sm hidden-md visible-lg">
                        <li role="presentation"><a href="admin_home.php">Planning</a></li>
                        <li role="presentation" class="active"><a href="my_students.php">Leerlingen</a></li>
                        <li role="presentation"><a href="php/logout.php">Log uit</a></li>
                    </ul>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 col-md-offset-4">
                    <div id="menu-items" class="btn-group hidden-lg visible-xs visible-sm visible-md pull-right">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Menu <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="admin_home.php">Planning</a></li>
                            <li><a href="my_students.php">Leerlingen</a></li>
                            <li><a href="php/logout.php">Log uit</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="row">
                    <div class="col-lg-9">
                        <h2>Leerlingen</h2>
                    </div>
                    <!-- Collapse form for adding a student -->
                    <div class="col-lg-3">
                        <button class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#addStudent" aria-expanded="false" aria-controls="collapseExample">
                            <span>Leerling toevoegen</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <!-- Collapse form -->
                <div class="collapse" id="addStudent">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Leerling toevoegen</h3>
                        </div>
                        <form role="form" action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
                            <div class="panel-body">
                                <?php
                                if ($errors) {
                                    echo '<div class="statusmsg">' . $errors . '</div>';
                                }
                                ?>
                                <fieldset class="form-group">
                                    <input type="email" class="form-control" name="email" id="stu-email" placeholder="Email adres:" required/>
                                    <input type="hidden" name="role" id="stu-role" value="user">
                                </fieldset>
                                <fieldset class="form-group">
                                    <input type="password" class="form-control" name="pass" id="stu-pass" placeholder="Password:" required/>
                                </fieldset>
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" name="fname" id="stu-fname" placeholder="Voornaam:" required/>
                                </fieldset>
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" name="lname" id="stu-lname" placeholder="Achternaam:" required/>
                                </fieldset>
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" name="address" id="stu-address" placeholder="Adres:" required/>
                                </fieldset>
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" maxlength="7" name="zip" id="stu-zip" placeholder="Postcode:"/>
                                </fieldset>
                                <fieldset class="form-group">
                                    <input type="text" class="form-control" name="city" id="stu-city" placeholder="Plaats:" required/>
                                </fieldset>
                                <fieldset class="form-group">
                                    <input type="tel" pattern="{10}" class="form-control" name="phone" id="stu-phone" placeholder="Telefoon nummer:" required/>
                                </fieldset>
                                <fieldset class="form-group">
                                    <select class="form-control" name="package" title="package" id="stu-package">
                                        <option selected disabled>Kies pakket:</option>
                                        <option>10 Lessen + CBR praktijkexamen</option>
                                        <option>15 Lessen + CBR praktijkexamen</option>
                                        <option>20 Lessen + CBR praktijkexamen</option>
                                        <option>25 Lessen + CBR praktijkexamen</option>
                                        <option>30 Lessen + CBR praktijkexamen</option>
                                        <option>35 Lessen + CBR praktijkexamen</option>
                                        <option>40 Lessen + CBR praktijkexamen</option>
                                        <option>45 Lessen + CBR praktijkexamen</option>
                                    </select>
                                </fieldset>
                                <button type="submit" class="btn btn-primary" name="submit">Leerling toevoegen</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End Collapse form -->
            </div>
        </div>

        <!-- Students list -->
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <?php foreach ($userData as $key => $value) { ?>
                            <tr>
                                <td>
                                    <a href="student-overview.php?id=<?= $value['user_id'] ?>">
                                        <?= $value['fname'] . " " . $value['lname']; ?>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
        <!-- End student list -->
    </div>
</div>
</body>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="js/bootstrap.js"></script>
</html>
