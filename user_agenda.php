<?php
require_once "includes/db_connect.php";
session_start();

require_once "includes/validators.php";
//ifLoggedIn();
ifNotLoggedIn();

$userId = $_SESSION['id'];
$userEmail = $_SESSION['email'];
$userRole = $_SESSION['role'];

//get additional information of the user
$query = "SELECT * FROM `students` WHERE `user_id` = '$userId'" or die(mysqli_error($db));
$result = mysqli_query($db, $query);
$data = mysqli_fetch_assoc($result);

$_SESSION['id'] = $data['admin_id'];
$adminId = $_SESSION['id'];
$_SESSION['firstName'] = $data['fname'];
$_SESSION['lastName'] = $data['lname'];
$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agenda instructeur</title>
    <link rel='stylesheet' href='lib/fullcalendar/fullcalendar.css'/>
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
                        <li role="presentation"><a href="user_home.php">Mijn overzicht</a></li>
                        <li role="presentation" class="active"><a href="user_agenda.php">Planning Instructeur</a></li>
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
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src='lib/fullcalendar/lib/moment.min.js'></script>
<script src='lib/fullcalendar/fullcalendar.js'></script>
<script src='lib/fullcalendar/lang/nl.js'></script>
<script src="js/bootstrap.js"></script>
<script>
    $(document).ready(function () {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var calendar = $('#calendar').fullCalendar({
            editable: false,
            defaultView: 'agendaWeek',
            allDaySlot: false,
            selectOverlap: false,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },

            events: "agenda_events.php",

            selectable: false,
            selectHelper: false,
//            select: function (start, end, allDay) {
//                //declare variables from form for processing
//                var start = moment(start).format("YYYY-MM-DD HH:mm");
//                var end = moment(end).format("YYYY-MM-DD HH:mm");
//                var mywhen = start + ' - ' + end;
//                var stuName = $('#calendarModal #stuName').val(stuName);
//                var stuLocation = $('#calendarModal #stuLocation').val(stuLocation);
//                var adminId = $('#calendarModal #adminId').val(adminId);
//
//                $('#calendarModal #startTime').val(start);
//                $('#calendarModal #endTime').val(end);
//                $('#calendarModal #when').text(mywhen);
//                $('#calendarModal').modal();
//
//                calendar.fullCalendar('renderEvent',
//                    {
//                        title: title,
//                        start: start,
//                        end: end
//                    },
//                    true // make the event "stick"
//                );
//
//                calendar.fullCalendar('unselect');
//            },

        });
    });

</script>
</html>
