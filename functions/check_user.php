<?php

function check_user($login, $mail) {
    $pdo = connect_db();
    if (!$pdo) {
        return 'Database connection error';
    }
    // проверка существует ли пользьзователь с таким именем или почтой:
    $sql_select_login = "SELECT * FROM `users` WHERE `username`=:username OR `mail`=:mail;";
    $result_select_login = $pdo->prepare($sql_select_login);
    $result_select_login->execute([
        'username' => $login,
        'mail' => $mail,
    ]);
    $arr = $result_select_login->fetchAll();
    if (count($arr)) {
        foreach ($arr as $user) {
            if ($user['mail'] == $mail) {
                return 'A user with this mail is already registered';
            }
            if ($user['username'] == $login) {
                return 'A user with this login is already registered';
            }
        }
    }
    return true;
}

function check_current_user($login, $mail, $id) {
    $pdo = connect_db();
    if (!$pdo) {
        return 'Database connection error';
    }
    // проверка существует ли пользьзователь с таким именем или почтой:
    $sql_select_login = "SELECT * FROM `users` WHERE `id` <> :id AND (`username`=:username OR `mail`=:mail);";
    $result_select_login = $pdo->prepare($sql_select_login);
    $result_select_login->execute([
        'id' => $id,
        'username' => $login,
        'mail' => $mail,
    ]);
    $arr = $result_select_login->fetchAll();
    if (count($arr)) {
        foreach ($arr as $user) {
            if ($user['mail'] == $mail) {
                return 'A user with this mail is already registered';
            }
            if ($user['username'] == $login) {
                return 'A user with this login is already registered';
            }
        }
    }
    return true;
}
?>