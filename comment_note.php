<?php
session_start();
include_once('./db/connect_db.php');
include('./mail/mail.php');

$json = json_decode(file_get_contents('php://input'));
$response = array('error' => false);
if (!isset($json->text) || !isset($json->to_user) || !isset($_SESSION['id'])) {
    $response['error'] = 'ERROR';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}
$text = $json->text;
$to_user = $json->to_user;
$from = $_SESSION['loggued_on_user'];
$pdo = connect_db();
if (!$pdo) {
    $response['error'] = 'Database connection error';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql_select_user = "SELECT * FROM `users` WHERE  `username`=:username;";
$result_select_user = $pdo->prepare($sql_select_user);
$result_select_user->execute([
    'username' => $to_user,
]);

// увеедомление об отметки
$users_arr = array();
preg_match_all('/@\S+/', $text, $users_arr);
if (count($users_arr)) {
    foreach ($users_arr[0] as $user_name) {
        $user_name = str_replace('@', '', $user_name);
        $sql = "SELECT * FROM `users` WHERE `username` = :username;";
        $sql_res = $pdo->prepare($sql);
        $sql_res->execute([
            'username' => $user_name,
        ]);
        $users_arr = $sql_res->fetchAll();
        if (count($users_arr)) {
            $user = $users_arr[0];
            mentioned_mail($user['username'], $user['mail'], $text, $from);
        }
    }
}

$user = $result_select_user->fetchAll()[0];
if ($user['allow_mail']) {
    $send_mail = comment_mail($to_user, $user['mail'], $text, $from);
    if (!$send_mail) {
        exit;
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
?>