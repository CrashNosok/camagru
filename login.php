<?php
include('auth.php');
session_start();
function login()
{
    if ($_POST['submit'] && $_POST['submit'] == 'Login' &&
        $_POST['login'] && $_POST['passwd'])
    {
        $id = auth($_POST['login'], $_POST['passwd']);
        if (is_numeric($id) != 1) {
            $_SESSION['loggued_on_user'] = '';
            $_SESSION['id'] = '';
            echo $id;
            return;
        }
        $_SESSION['loggued_on_user'] = $_POST['login'];
        $_SESSION['id'] = $id;
        echo '
        <script>
        location.href = "./index.php";
        </script>
        ';
    }
    else
    {
        $_SESSION['loggued_on_user'] = '';
        echo 'you did not log in';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="static/css/login.css">
    <link rel="stylesheet" href="static/css/footer.css">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet"> -->
</head>
<body>

<div class="container">
    <div class="left">
        <img src="static/img/cat.png" alt="" class="cat_img">
    </div>
    <div class="right">
        <div class="login_form">
            <div class="form">
                <form class="box" action="login.php" method="POST">
                    <p class="blue-color">
                        <?php
                            include('already_login.php');
                            if ($_POST) {
                                login();
                            }
                                
                        ?>
                    </p>
                    <h1><a class="main_link" href="/camagru/gallery/gallery.php">Camagru</a></h1>
                    <input id="login" type="text" name="login" placeholder="Username">
                    <input id="password" type="password" name="passwd" placeholder="Password">
                    <input id="submit" class="active_btn" type="submit" name="submit" value="Login">

                    <p class="forgot_passwd">
                        <a href="password_reset/password_reset.php">Forgot your password?</a>
                    </p>
                </form>
            </div>
        </div>
        <div class="register">
            <p>Don't have an account?</p>
            <a href="./register.php">Register now</a>
        </div>
    </div>
</div>
    
</body>
<?php
include('footer.php');
?>
<script src="static/js/login.js"></script>
</html>