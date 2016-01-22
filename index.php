<?php
require_once "includes/db_connect.php";
//require_once "includes/validators.php";

session_start();

// Var for error messages
$errors = "";

if (isset($_POST['submit'])) {

    $loginEmail = mysqli_real_escape_string($db, $_POST['email']);
    $loginPass = password_hash(mysqli_real_escape_string($db, $_POST['pass']), PASSWORD_DEFAULT);
    $query = "SELECT * FROM `users` WHERE `email` = '$loginEmail'";
    $result = mysqli_query($db, $query);
    $data = mysqli_fetch_assoc($result);
    $userRole = $data['role'];
    $hashPass = $data['password'];

    //check if email is correct
    if ($loginEmail == $data['email']) {
        //validate password
        if (password_verify($_POST['pass'], $hashPass) == TRUE) {
            //set session variables
            $_SESSION['id'] = $data['id'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['role'] = $data['role'];
            //check role from user and redirect
            if ($userRole === "admin") {
                header("location: admin_home.php");
                exit;
            } else {
                header("location: user_home.php");
                exit;
            }
        } else {
            $errors = 'Wachtwoord is onjuist';
        }
    } else {
        $errors = 'Email komt niet overeen';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digtale leskaart</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.js"></script>
</head>
<body>
<div class="container index">
    <div class="col-sm-6 col-md-4 col-md-offset-4 vertical-center">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Log in digitale leskaart</h3>
            </div>

            <div class="panel-body">
                <?
                if ($errors) {
                    echo '<div class="statusmsg" style="display: block;">' . $errors . '</div>';
                }
                ?>
                <form role="form" name="loginForm" method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
                    <fieldset>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" id="login-email" placeholder="Email adres:">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="pass" id="login-pass" placeholder="Wachtwoord:">
                        </div>
                        <button type="submit" name="submit" class="btn btn-lg btn-success btn-block">Log in</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
