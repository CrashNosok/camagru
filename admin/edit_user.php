<?php
session_start();
include_once('../db/connect_db.php');
include('../functions/check_user.php');
include('../functions/check_password.php');

function update_user_info() {
    if (isset($_POST['submit']) && $_POST['submit'] === 'Save' && isset($_POST['login'])
            && isset($_POST['email']) && isset($_POST['passwd_1']) && isset($_POST['passwd_2'])) {
        global $pdo;

        $user = get_user();
        $id = $_GET['i'];
        $username = $_POST['login'];
        $mail = $_POST['email'];
        $passwd_1 = $_POST['passwd_1'];
        $passwd_2 = $_POST['passwd_2'];
        $notifications = 1;
        if ($_POST['notifications'] != 'on') {
            $notifications = 0;
        }
        $is_admin = 0;
        if ($_POST['is_admin'] === 'on') {
            $is_admin = 1;
        }
        $verified = 0;
        if ($_POST['verified'] === 'on') {
            $verified = 1;
        }

        if (strlen($username) > 100) {
            echo 'To long username';
            return false;
        }
        if (strlen($mail) > 100) {
            echo 'To long mail';
            return false;
        }
        // проверка для обновления логина, емэйла
        $res = check_current_user($username, $mail, $id);
        if ($res !== true) {
            echo $res;
            return 'error3';
        }
        if (!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $mail)) {
            echo "Invalid email";
            return;
        }
        $token = $user['token'];
        if ($mail != $user['mail']) {
            $token = hash('md5', $mail);
        }

        // все проверки пароля только если нужно менять пароль (если поля пустые, ничего не делать)
        $passwd = $user['password'];
        if ($passwd_1 != '' && $passwd_2 != '') {
            // проверка пароля
            if ($passwd_1 !== $passwd_2) {
                echo "Password mismatch";
                return;
            }
            if (strlen($passwd_1) < 8) {
                echo "Too short password";
                return;
            }
            if (check_password($passwd_1) == false) {
                echo 'Too weak password';
                return;
            }
            $passwd = hash('whirlpool', $passwd_1);
        }

        $sql_update_user = "UPDATE `users` SET `username` = :username, `mail` = :mail, `password` = :passwd, `token` = :token, `verified` = :verified, `allow_mail` = :allow, `is_admin` = :is_admin WHERE `id` = :id;";
        $result_update_user = $pdo->prepare($sql_update_user);
        $result_update_user->execute([
            'username' => $username,
            'mail' => $mail,
            'passwd' => $passwd,
            'token' => $token,
            'verified' => $verified,
            'allow' => $notifications,
            'is_admin' => $is_admin,
            'id' => $id,
        ]);
    }
}

function get_user() {
    global $pdo;

    $sql_select_login = "SELECT * FROM `users` WHERE `id`=:id";
    $result_select_login = $pdo->prepare($sql_select_login);
    $result_select_login->execute([
        'id' => $_GET['i'],
    ]);
    $users_arr = $result_select_login->fetchAll();
    $user = $users_arr[0];
    if (!count($users_arr)) {
        echo 'ERROR2';
        return;
    }
    return $user;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/login.css">
    <link rel="stylesheet" href="../static/css/menu.css">
    <link rel="stylesheet" href="../static/css/admin.css">
    <link rel="stylesheet" href="../static/css/footer.css">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet"> -->
    <title>Settings</title>
</head>
<body>

<?php
include('../is_activate.php');
$pdo = connect_db();
if (!$pdo) {
    echo 'Database connection error';
    return;
}
$sql_check = "SELECT * FROM `users` WHERE `id` = :userid;";
$sql_check_res = $pdo->prepare($sql_check);
$sql_check_res->execute([
    'userid' => $_SESSION['id'],
]);
$users = $sql_check_res->fetchAll();
if (!count($users)) {
    $response['error'] = 'User does not exist';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}
$user = $users[0];
if (!$user['is_admin']) {
    $response['error'] = 'Permission denied';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}
?>

<?php
include('../menu.php');
?>
<div class="wrapper">
    <div class="content">
        <h1>Settings</h1>
        <?php
            if ($_POST) {
                ?><p class="user_error">
                <?php 
                    update_user_info();
                }
                $user = get_user();
                ?> 
                </p>
        <hr>
        <form action="./edit_user.php?i=<?php echo $user['id']; ?>" method="POST">
            <div class="paragraph">
                <h2>Details</h2>
                <p>Username:</p>
                <input type="text" name="login" value="<?php echo $user['username'] ?>">
                <p>Email:</p>
                <input type="text" name="email" value="<?php echo $user['mail'] ?>">
                <input type="submit" name="submit" value="Save">
            </div>
            <hr>
            <div class="paragraph">
                <h2>Update password:</h2>
                <p>New password:</p>
                <input type="password" name="passwd_1">
                <p>Confirm new password:</p>
                <input type="password" name="passwd_2">
                <input type="submit" name="submit" value="Save">
            </div>
            <hr>
            <div class="paragraph">
                <h2>Notifications:</h2>
                <div class="notifications">
                    <input type="checkbox" name="notifications" <?php if ($user['allow_mail']) {echo 'checked';} ?>>
                    <p>Allow notifications:</p>
                </div>
                <input type="submit" name="submit" value="Save">
            </div>
            <hr>

            <div class="paragraph">
                <h2>Vetified:</h2>
                <div class="notifications">
                    <input type="checkbox" name="verified" <?php if ($user['verified']) {echo 'checked';} ?>>
                    <p>Vetified account:</p>
                </div>
                <input type="submit" name="submit" value="Save">
            </div>
            <hr>


            <div class="paragraph">
                <h2>Admin:</h2>
                <div class="notifications">
                    <input type="checkbox" name="is_admin" <?php if ($user['is_admin']) {echo 'checked';} ?>>
                    <p>Is admin:</p>
                </div>
                <input type="submit" name="submit" value="Save">
            </div>
            <hr>

            

        </form>
    </div>
</div>
<?php
include('../footer.php');
?>

</body>
<script src="../static/js/menu_script.js"></script>
</html>
