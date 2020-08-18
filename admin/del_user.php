<?php
session_start();
include_once('../db/connect_db.php');
$json = json_decode(file_get_contents('php://input'));

if (!isset($json->del_user)) {
    $response['error'] = 'ERROR';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$user_id = $_SESSION['id'];
$del_user = $json->del_user;

$pdo = connect_db();
if (!$pdo) {
    $response['error'] = 'Database connection error';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql_check = "SELECT * FROM `users` WHERE `id` = :userid;";
$sql_check_res = $pdo->prepare($sql_check);
$sql_check_res->execute([
    'userid' => $user_id,
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

// получение всех его фото:
$select_photoes = "SELECT * FROM `photoes` WHERE `user_id` = :del_user;";
$res = $pdo->prepare($select_photoes);
$res->execute([
    'del_user' => $del_user,
]);
$photoes = $res->fetchAll();
foreach ($photoes as $photo) {
    // удаление его фоток
    $sql = "DELETE FROM `comments` WHERE `photo_id` = :photo_id;
        DELETE FROM `likes` WHERE `photo_id` = :photo_id;
        DELETE FROM `photoes` WHERE `id` = :photo_id;";
    $result = $pdo->prepare($sql);
    $result->execute([
    'photo_id' => $photo['id'],
    ]);
}
//удаление юзера
$sql_del_user = "DELETE FROM `users` WHERE `users`.`id` = :userid";
$res = $pdo->prepare($sql_del_user);
$res->execute([
    'userid' => $del_user,
])

?>