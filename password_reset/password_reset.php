<?php
session_start();
include_once('../db/connect_db.php');
include('../mail/mail.php');

function passwd_reset()
{
    if ($_POST['submit'] && $_POST['submit'] == 'Reset' &&
            $_POST['login'] && $_POST['email'])
    {
        $login = $_POST['login'];
        $mail = $_POST['email'];

        $pdo = connect_db();
        if (!$pdo) {
            echo 'Database connection error';
            return;
        }

        if (!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $mail)) {
            echo "Invalid email";
            return;
        }

        $sql_select_user = "SELECT * FROM `users` WHERE `username`=:username AND `mail`=:mail";
        $result_select_user = $pdo->prepare($sql_select_user);
        $result_select_user->execute([
            'username' => $login,
            'mail' => $mail,
        ]);
        $user_arr = $result_select_user->fetchAll();
        $user = $user_arr[0];
        if (!count($user_arr)) {
            echo 'The entered data is incorrect';
            return;
        }

        $code = rand(100000, 999999);
        $id = $user['id'];
        $sql = "UPDATE `users` SET `code` = :code WHERE `id` = :id;";
        $result = $pdo->prepare($sql);
        $result->execute([
            'code' => $code,
            'id' => $id,
        ]);

        $send_mail = reset_password($user['username'], $user['mail'], $code);
        if ($send_mail) {
            echo "
            <script>
            location.href = './password_reset.php?form=code&i=$id';
            </script>
            ";
        } else {
            echo 'ERROR';
            exit;
        }
    }
    else
        echo 'ERROR';
}

function code_confirm() {
    if ($_POST['submit'] && $_POST['submit'] == 'Confirm' 
            && $_POST['code'] && $_GET['form'] == 'code' && $_GET['i']
            && $_POST['passwd'] && $_POST['passwd2']) {
        $id = $_GET['i'];
        $code = $_POST['code'];
        $passwd_1 = $_POST['passwd'];
        $passwd_2 = $_POST['passwd2'];

        $pdo = connect_db();
        if (!$pdo) {
            echo 'Database connection error';
            return;
        }

        $sql_select_user = "SELECT * FROM `users` WHERE `id`=:id";
        $result_select_user = $pdo->prepare($sql_select_user);
        $result_select_user->execute([
            'id' => $id,
        ]);
        $user_arr = $result_select_user->fetchAll();
        $user = $user_arr[0];
        if (!isset($user_arr)) {
            echo 'ERROR';
            return;
        }
        if ($user['code'] == $code && $passwd_1 == $passwd_2) {
            $sql_update_passwd = "UPDATE `users` SET `password` = :passwd WHERE `id` = :id;";
            $result_update_passwd = $pdo->prepare($sql_update_passwd);
            $result_update_passwd->execute([
                'passwd' => hash('whirlpool', $passwd_1),
                'id' => $id,
            ]);
            echo '<p id="succsess" style="color: green;font-size: 30px;">Succsess.</p> <a style="font-size: 30px;" href="../login.php">Log in</a>';
        } else {
            echo '<p style="color: #707070;">ERROR.</p> <p style="color: red;">The code sent to your email has been changed.</p> <a href="./password_reset.php">Try again</a>';
        }
        $sql_update_user = "UPDATE `users` SET `code` = :code WHERE `id` = :id;";
        $result_update_user = $pdo->prepare($sql_update_user);
        $result_update_user->execute([
            'code' => rand(100000, 999999),
            'id' => $id,
        ]);
    } else {
        echo 'ERROR';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password reset</title>
    <link rel="stylesheet" href="../static/css/login.css">
    <link rel="stylesheet" href="../static/css/footer.css">
    <link rel="stylesheet" href="../static/css/password_reset.css">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet"> -->
</head>
<body>

<div class="container">
    <div class="right">
        <div class="login_form">
            <div class="form">
                
                <?php
                if (isset($_GET['form']) && $_GET['form'] == 'code') {
                ?>

                <form id="code_form" class="box" action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST">
                    <p class="blue-color">
                        <?php
                            include('../already_login.php');
                            if ($_POST) {
                                code_confirm();
                            }   
                        ?>
                    </p>
                    <h1>Camagru</h1>
                    <div id="for_del">
                        <p class="reset">Password reset</p>
                        <p class="text">Enter the code that was sent to your email</p>
                        <div class="log">
                            <input id="code" type="text" name="code" placeholder="code">
                            <input id="pas_1" type="password" name="passwd" placeholder="Password">
                            <input id="pas_2" type="password" name="passwd2" placeholder="Repeat Password">
                            <input id="submit_code" class="norm_btn" type="submit" name="submit" value="Confirm">
                        </div>
                        <p class="forgot_passwd">
                            <p class="gray_color">Remember your password? <a href="../login.php">Login</a></p>
                        </p>
                    </div>
                </form>

                <?php
                } else {
                ?>

                <form id="user_form" class="box" action="password_reset.php" method="POST">
                    <p class="blue-color">
                        <?php
                            include('../already_login.php');
                            if ($_POST) {
                                passwd_reset();
                            }
                        ?>
                    </p>
                    <h1>Camagru</h1>
                    <p class="reset">Password reset</p>
                    <input id="email" type="text" name="email" placeholder="email">
                    <input id="login" type="text" name="login" placeholder="Username">
                    <input id="submit" class="norm_btn" type="submit" name="submit" value="Reset">
                    <p class="forgot_passwd">
                        <p class="gray_color">Remember your password? <a href="../login.php">Login</a></p>
                    </p>
                </form>

                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
    
</body>
<?php
include('../footer.php');
?>
<script src="../static/js/reset.js"></script>
</html>
