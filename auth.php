<?php
include_once('db/connect_db.php');
include('mail/mail.php');

function auth($login, $passwd)
{
    if ($login && $passwd)
    {
        $hashed_passwd = hash('whirlpool', $passwd);
        $pdo = connect_db();
        if (!$pdo) {
            return 'Database connection error';
        }

        $sql_select_login = "SELECT * FROM `users` WHERE `username`=:username AND `password`=:passwd";
        $result_select_login = $pdo->prepare($sql_select_login);
        $result_select_login->execute([
            'username' => $login,
            'passwd' => $hashed_passwd,
        ]);

        $user_arr = $result_select_login->fetchAll();
        if (!count($user_arr)) {
            return 'User with this username and password was not found.';
        }

        $user = $user_arr[0];
        if (!$user['verified']) {
            $send_mail = confirmation_mail($user['username'], $user['mail'], $user['token']);
            if (!$send_mail) {
                return 'you did not log in';
            }
            return '
            <p style="color:red;">Confirm your email</p>
            <p>We have sent an additional letter to your mail with a link to confirm your email.</p>
            <p>Check your email!</p>
            ';
        }
        return $user['id'];
    }
    return 'you did not log in';
}
?>