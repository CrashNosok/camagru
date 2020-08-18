<?php
session_start();
include_once('db/connect_db.php');
include('mail/mail.php');
include('functions/check_user.php');
include('functions/check_password.php');
function register()
{
    if ($_POST['submit'] && $_POST['submit'] == 'Register' &&
            $_POST['login'] && $_POST['passwd'] && $_POST['passwd2'] && $_POST['mail'])
    {
        $login = $_POST['login'];
        $passwd = $_POST['passwd'];
        $passwd2 = $_POST['passwd2'];
        $mail = $_POST['mail'];
        $token = hash('md5', $mail);

        if (strlen($login) > 100) {
            echo 'Too long username';
            return;
        }
        if (strlen($mail) > 100) {
            echo 'Too long email';
            return;
        }
        if ($passwd != $passwd2) {
            echo 'Password mismatch';
            return;
        }
        if (check_password($passwd) == false) {
            echo 'Too weak password';
            return;
        }
        if (!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $mail)) {
            echo "Invalid email";
            return;
        }

        $pdo = connect_db();
        if (!$pdo) {
            echo 'Database connection error';
            return;
        }

        $res = check_user($login, $mail);
        if ($res !== true) {
            echo $res;
            return;
        }

        $sql = "INSERT INTO `users` (`username`, `password`, `mail`, `token`, `code`) VALUES (:username, :passwd, :mail, :token, :code);";
        $result = $pdo->prepare($sql);
        $result->execute([
            'username' => $login,
            'passwd' => hash('whirlpool', $passwd),
            'mail' => $mail,
            'token' => $token,
            'code' => rand(100000, 999999),
        ]);

        $send_mail = confirmation_mail($login, $mail, $token);
        if ($send_mail) {
            echo '
            <script>
            location.href = "./successful_registration.php";
            </script>
            ';
        }
    }
    else
        echo 'ERROR';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="static/css/login.css">
    <link rel="stylesheet" href="static/css/footer.css">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet"> -->
</head>
<body>
<div class="login_form register_form">
    <form class="box" action="register.php" method="POST">
        <p class="register_error">
        <?php
            include('already_login.php');
            if ($_POST)
                register();
        ?>        
        </p>
        <h1><a class="main_link" href="/camagru/gallery/gallery.php">Camagru</a></h1>
        <p class="p_reg">Register to watch photos of your friends.</p>
        <input id="mail" type="text" name="mail" placeholder="Mail">
        <input id="login" type="text" name="login" placeholder="Username">
        <input id="pas_1" type="password" name="passwd" placeholder="Password">
        <input id="pas_2" type="password" name="passwd2" placeholder="Repeat Password">
        <input id="submit" type="submit" name="submit" value="Register">

    </form>
</div>
<div class="entrance">
    <p>Have an account?</p>
    <a href="./login.php">Login</a>
</div>
<?php
include('footer.php');
?>
<script src="static/js/register.js"></script>
</body>
</html>