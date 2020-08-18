<?php
session_start();
include_once('../db/connect_db.php');
include('../functions/check_user.php');
include('../functions/check_password.php');
include('../mail/mail.php');

if (!isset($_SESSION['id'])) {
    echo 'ERROR1';
    exit;
}

if (isset($_POST['submit']) && $_POST['submit'] === 'Save' && isset($_POST['login'])
        && isset($_POST['email']) && isset($_POST['passwd_1']) && isset($_POST['passwd_2'])) {
    global $pdo, $user;

    $id = $_SESSION['id'];
    $username = $_POST['login'];
    $mail = $_POST['email'];
    $passwd_1 = $_POST['passwd_1'];
    $passwd_2 = $_POST['passwd_2'];
    $notifications = 1;
    if ($_POST['notifications'] != 'on') {
    $notifications = 0;
    }

    // проверка для обновления логина, емэйла
    $res = check_current_user($username, $mail, $id);
    if ($res !== true) {
    echo $res;
    return;
    }
    if (!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $mail)) {
    echo "Invalid email";
    return;
    }
    $token = $user['token'];
    $verified = 1;
    if ($mail != $user['mail']) {
    $token = hash('md5', $mail);
    $send_mail = change_mail($username, $mail, $token);
    if (!$send_mail) {
        exit;
    }
    $verified = 0;
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
    if (check_password($passwd) == false) {
        echo 'Too weak password';
        return;
    }
    $passwd = hash('whirlpool', $passwd_1);
    }

    // проверка на то, нужно ли менять пароль
    $sql_update_user = "UPDATE `users` SET `username` = :username, `mail` = :mail, `password` = :passwd, `token` = :token, `verified` = :verified, `allow_mail` = :allow WHERE `id` = :id;";
    $result_update_user = $pdo->prepare($sql_update_user);
    $result_update_user->execute([
    'username' => $username,
    'mail' => $mail,
    'passwd' => $passwd,
    'token' => $token,
    'verified' => $verified,
    'allow' => $notifications,
    'id' => $id,
    ]);
} else {
    echo 'ERROR2';
    exit;
}
?>