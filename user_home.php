<?php
require_once "includes/db_connect.php";
session_start();

require_once "includes/validators.php";
ifNotLoggedIn();

$userId = $_SESSION['id'];
$userEmail = $_SESSION['email'];
$adminRole = $_SESSION['role'];

//Query uitvoeren voor data student en reports tabellen
$sql = "SELECT *
FROM `report_entries`
INNER JOIN `students`
ON report_entries.user_id = students.user_id
WHERE report_entries.user_id = '" . $userId . "'";
$result = mysqli_query($db, $sql);

$userData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $userData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mijn overzicht</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="lib/xeditable/css/bootstrap-editable.css"
</head>
<body>
<div id="wrapper">
    <div class="row navbar navbar-default">
        <div id="main-menu">
            <div class="container">
                <div class="col-xs-6 col-sm-6 col-md-4">
                    <h2>
                        <?= $userData[0]['fname'] . ' ' . $userData[0]['lname']; ?>
                    </h2>
                </div>
                <div class="col-xs-6 col-md-4 col-lg-4 col-md-offset-4">
                    <ul id="menu-items" class="nav nav-pills pull-right hidden-xs hidden-sm hidden-md visible-lg">
                        <li role="presentation" class="active"><a href="user_home.php">Mijn overzicht</a></li>
                        <li role="presentation"><a href="user_agenda.php">Planning Instructeur</a></li>
                        <li role="presentation"><a href="php/logout.php">Log uit</a></li>
                    </ul>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 col-md-offset-4">
                    <div id="menu-items" class="btn-group hidden-lg visible-xs visible-sm visible-md pull-right">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Menu <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="user_home.php">Mijn overzicht</a></li>
                            <li><a href="user_agenda.php">Planning Instructeur</a></li>
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
                <div class="panel panel-default">
                    <!-- Default panel contents -->
                    <div class="panel-heading">
                        <strong>Mijn gegevens</strong>
                        <a class="pull-right" href="#" id="editInfo">Gegevens wijzigen</a>
                    </div>
                    <div class="table-responsive">
                        <table id="stuInfo" class="table">
                            <tr>
                                <td>
                                    Email adres:
                                </td>
                                <td>
                                    <a href="#" id="email" data-name="email" data-type="text" data-pk="<?= $stuId; ?>" data-title="Email adres">
                                        <?= $userData[0]['email']; ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Telefoon nummer:
                                </td>
                                <td>
                                    <a href="#" id="phone" data-name="phone" data-type="tel" data-pk="<?= $stuId; ?>" data-title="Telefoon nummer">
                                        <?= $userData[0]['phone']; ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Adres:
                                </td>
                                <td>
                                    <a href="#" id="address" data-name="address" data-type="text" data-pk="<?= $stuId; ?>" data-title="Adres">
                                        <?= $userData[0]['address']; ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Postcode:
                                </td>
                                <td>
                                    <a href="#" id="zip" data-name="zip" data-type="text" data-pk="<?= $stuId; ?>" data-title="Postcode">
                                        <?= $userData[0]['zip']; ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Woonplaats:
                                </td>
                                <td>
                                    <a href="#" id="city" data-name="city" data-type="text" data-pk="<?= $stuId; ?>" data-title="Woonplaats">
                                        <?= $userData[0]['city']; ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Lespakket:
                                </td>
                                <td>
                                    <?= $userData[0]['package']; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Omschrijving rijlessen</strong></div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>
                                    Les:
                                </th>
                                <th>
                                    Datum:
                                </th>
                                <th>
                                    Behandelde onderdelen:
                                </th>
                            </tr>
                            </thead>
                            <?php
                            $i = 0;
                            foreach ($userData as $key => $value) {
                                $i++;
                                ?>

                                <tr>
                                    <td>
                                        <?= $i; ?>
                                    </td>
                                    <td>
                                        <?= $value['date']; ?>
                                    </td>
                                    <td>
                                        <?= $value['activities']; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="lib/xeditable/js/bootstrap-editable.js"></script>
<script>
    $(document).ready(function () {
        $.fn.editable.defaults.mode = 'inline';
        $.fn.editable.defaults.disabled = 'true';
        $('#editInfo').click(function () {
            $('#stuInfo .editable').editable('toggleDisabled');
        });
        $('#stuInfo a').editable({
            url: 'update_student.php'
        });
        $('#email').editable();
        $('#phone').editable();
        $('#address').editable();
        $('#city').editable();
        x
    });
</script>
</html>
