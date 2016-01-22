<?php
require_once "includes/db_connect.php";
session_start();

require_once "includes/validators.php";
//ifLoggedIn();
ifNotLoggedIn();

$adminId = $_SESSION['id'];
$adminEmail = $_SESSION['email'];
$adminRole = $_SESSION['role'];

//get additional information of the user
$query = "SELECT * FROM `admins` WHERE `admin_id` = '$adminId'" or die(mysqli_error($db));
$result = mysqli_query($db, $query);
$data = mysqli_fetch_assoc($result);

$_SESSION['firstName'] = $data['fname'];
$_SESSION['lastName'] = $data['lname'];
$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $firstName; ?>'s agenda</title>
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
                        <li role="presentation" class="active"><a href="admin_home.php">Planning</a></li>
                        <li role="presentation"><a href="my_students.php">Leerlingen</a></li>
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
                <div id="calendar"></div>
                <!-- Modal for adding events -->
                <div id="calendarModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                                <h4 id="modalTitle" class="modal-title">Afspraak maken</h4>
                            </div>
                            <div id="modalBody" class="modal-body">
                                <form id="createAppointmentForm">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="stuName" id="stuName" placeholder="Naam leerling:">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="stuLocation" id="stuLocation" placeholder="Locatie:">
                                    </div>
                                    <input type="hidden" id="startTime"/>
                                    <input type="hidden" id="endTime"/>
                                    <input type="hidden" id="adminId" value="<?= $adminId; ?>"/>
                                    <div class="control-group">
                                        <label class="control-label" for="when">Datum:</label>
                                        <div class="controls controls-row" id="when" style="margin-top:5px;">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="submitButton">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End modal -->
                <!-- Modal for events details and editing -->
                <div id="fullCalModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                                <h4 id="modalTitle" class="modal-title">Afspraak van <span id="stuName"></span></h4>
                            </div>
                            <div id="modalBody" class="modal-body">
                                <form id="eventDetailsForm">
                                    <p>
                                        <strong>Locatie: </strong>
                                        <span id="stuLocation"></span>
                                    </p>
                                    <p>
                                        <strong>Datum: </strong>
                                        <span id="when"></span>
                                    </p>
                                    <input type="hidden" id="eventId"/>
                                    <input type="hidden" id="startTime"/>
                                    <input type="hidden" id="endTime"/>

                                </form>
                            </div>
                            <div class="modal-footer">
<!--                                <button type="button" class="btn btn-primary">Edit</button>-->
                                <button type="submit" class="btn btn-danger" id="deleteEvent">Annuleer afspraak</button>
                            </div>
                        </div>
                    </div>6
                </div>
                <!-- End modal -->
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

        //Full calendar
        var calendar = $('#calendar').fullCalendar({
            events: "agenda_events.php",
            editable: true,
            defaultView: 'agendaWeek',
            height: 500,
            handleWindowResize: true,
            allDaySlot: false,
            selectOverlap: false,
            weekends: false,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            selectable: true,
            selectHelper: true,
            select: function (start, end) {
                //declare variables from form for processing
                var start = moment(start).format("YYYY-MM-DD HH:mm");
                var end = moment(end).format("YYYY-MM-DD HH:mm");
                var mywhen = start + ' - ' + end;
                $('#calendarModal #startTime').val(start);
                $('#calendarModal #endTime').val(end);
                $('#calendarModal #when').text(mywhen);
                $('#calendarModal').modal();

                calendar.fullCalendar('renderEvent',
                    {
                        id: "id",
                        title: "name",
                        location: "location",
                        start: "start",
                        end: "end"

                    },
                    true // make the event "stick"
                );
            },
            eventClick: function (event, jsEvent, view) {
                var start = event.start.format("YYYY-MM-DD HH:mm");
                var end = event.end.format("YYYY-MM-DD HH:mm");
                var mywhen = start + ' - ' + end;
                var stuLocation = location;
                $("#fullCalModal #eventId").val(event.id);
                $('#fullCalModal #stuName').html(event.title);
                $('#fullCalModal #stuLocation').html(event.location);
                $('#fullCalModal #startTime').val(start);
                $('#fullCalModal #endTime').val(end);
                $('#fullCalModal #when').text(mywhen);
                $('#fullCalModal').modal();
            }
        });

        //Submit action in modal window
        $('#submitButton').on('click', function (e) {
            // We don't want this to act as a link so cancel the link action
            e.preventDefault();

            doSubmit();
        });

        //Function to post to a php file which sends it to a database
        function doSubmit() {
            $("#calendarModal").modal('hide');
            var stuName = $('#stuName').val();
            var stuLocation = $('#stuLocation').val();
            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();
            var adminId = $('#adminId').val();
            $.ajax({
                url: 'add_events.php',
                data: '&stuName=' + stuName + '&stuLocation=' + stuLocation + '&startTime=' + startTime + '&endTime=' + endTime + '&adminId=' + adminId,
                type: "POST",
                success: function (json) {
                    alert('Afspraak gemaakt.');
                    location.reload();
                }
            });
        }

        //Submit delete action in modal window
        $('#deleteEvent').on('click', function (e) {
            // We don't want this to act as a link so cancel the link action
            e.preventDefault();
            deleteEvent();
        });

        function deleteEvent() {
            $("#fullCalModal").modal('hide');
            var resourceId = event.id;
            var eventId = $('#eventId').val();
            $.ajax({
                url: 'delete_event.php',
                data: '&eventId=' + eventId,
                type: 'POST',
                success: function (json) {
                    alert('Afspraak verwijderd.');
                    location.reload();
                }
            });
        }
    });
</script>
</html>
