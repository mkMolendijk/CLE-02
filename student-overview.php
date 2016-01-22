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

// Fetch GET id
$stuId = $_GET['id'];

// Var for error messages
$errors = "";

//Query uitvoeren voor data student en reports tabellen
$sql = "SELECT * FROM `students`
LEFT JOIN `report_entries`
ON students.user_id = report_entries.user_id
WHERE students.user_id = '" . $stuId . "'";
$result = mysqli_query($db, $sql);

$userData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $userData[] = $row;
}
//Query voor de report insert

if (isset($_POST['submit'])) {
    $repDate = mysqli_real_escape_string($db, $_POST['date']);
    $repTime = mysqli_real_escape_string($db, $_POST['time']);
    $repUserId = mysqli_real_escape_string($db, $_POST['userId']);
    $repActivity = htmlentities(mysqli_real_escape_string($db, $_POST['activities']));
    $repSql = "INSERT INTO `report_entries`(`date`, `time`, `activities`, `user_id`, `admin_id`) VALUES ('$repDate','$repTime','$repActivity','$repUserId','$adminId')";

    if ($db->query($repSql) === TRUE) {
        //success and reload page
        header("location: student-overview.php?id=" . $repUserId);
        exit;
    } else {
        $errors = "Er is iets fout gegaan, probeer het opnieuw.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My students</title>
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
        <!-- Student data table -->
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="panel panel-default">
                    <!-- Default panel contents -->
                    <div class="panel-heading">
                        <strong><?= $userData[0]['fname'] . " " . $userData[0]['lname']; ?></strong>
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
                                    <a href="#" id="package" data-name="package" data-type="select" data-pk="<?= $stuId; ?>" data-title="Lespakket">

                                        <?= $userData[0]['package']; ?>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <!-- Add report collapse form -->
                <div class="collapse" id="addReport">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Verslag toevoegen</h3>
                        </div>
                        <div class="panel-body">
                            <?
                            if ($errors) {
                                echo '<div class="statusmsg" style="display: block;">' . $errors . '</div>';
                            }
                            ?>
                            <form class="form" role="form" action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
                                <div class="form-group form-inline">
                                    <div class="form-group">
                                        <input type="date" class="form-control" title="rep-date" name="date" id="rep-date" required/>
                                    </div>
                                    <div class="form-group">
                                        <input type="time" class="form-control" title="rep-time" name="time" id="rep-time" required/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" title="userId" name="userId" value="<?= $stuId; ?>">
                                    <textarea class="form-control" name="activities" id="rep-activities" placeholder="Behandelde onderdelen" rows="5" style="resize:none;" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" name="submit">Verslag toevoegen</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End collapse form -->
                <!-- Report data table -->
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Omschrijving rijlessen</strong></div>
                    <div class="table-responsive">
                        <table id="reportInfo" class="table">
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
                                <th class="text-right">

                                    <a data-toggle="collapse" href="#addReport" aria-expanded="false" aria-controls="collapseExample" style="font-weight:normal;">
                                        Les toevoegen
                                    </a>

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
                                        <a href="#" id="activity_<?= $value['id']; ?>" class="activity_editable" data-name="activities" data-type="text" data-pk="<?= $value['id']; ?>" data-title="Activities">
                                            <?= $value['activities']; ?>
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <a class="glyphicon glyphicon-pencil editReport" href="#" data-id="<?= $value['id']; ?>"></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <form class="form" role="form" action="delete_student.php" method="POST" onsubmit="return confirm('Weet u zeker dat u dit account wilt verwijderen?');">
                    <input type="hidden" title="stuId" name="stuId" value="<?= $stuId; ?>">
                    <button type="submit" class="btn btn-danger pull-right" style="margin-bottom:15px;">
                        Verwijder account
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="lib/xeditable/js/bootstrap-editable.js"></script>
<script>
    $(document).ready(function () {
        document.getElementById('rep-date').valueAsDate = new Date();

        $.fn.editable.defaults.mode = 'inline';
        $.fn.editable.defaults.disabled = 'true';

        //User info edit
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
        var sourceSel = [
            {value: '10 Lessen + CBR praktijkexamen', text: '10 Lessen + CBR praktijkexamen'},
            {value: '15 Lessen + CBR praktijkexamen', text: '15 Lessen + CBR praktijkexamen'},
            {value: '20 Lessen + CBR praktijkexamen', text: '20 Lessen + CBR praktijkexamen'},
            {value: '25 Lessen + CBR praktijkexamen', text: '25 Lessen + CBR praktijkexamen'},
            {value: '30 Lessen + CBR praktijkexamen', text: '30 Lessen + CBR praktijkexamen'},
            {value: '35 Lessen + CBR praktijkexamen', text: '35 Lessen + CBR praktijkexamen'},
            {value: '40 Lessen + CBR praktijkexamen', text: '40 Lessen + CBR praktijkexamen'},
            {value: '45 Lessen + CBR praktijkexamen', text: '45 Lessen + CBR praktijkexamen'}
        ];
        $("#package").editable('option', 'source', sourceSel);

        //Report summary edit
        $(".editReport").click(function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $("#activity_" + id).editable('toggleDisabled');
        });
        $(".activity_editable").editable({
            url: 'update_report.php'
        });
    });
</script>
</html>
