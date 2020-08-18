<?php
session_start();
include_once('../db/connect_db.php');

$response = array('error' => false);
$json = json_decode(file_get_contents('php://input'));

if (!isset($json->id) || !isset($_SESSION['id'])) {
    $response['error'] = 'ERROR';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$user_id = $_SESSION['id'];
$comment_id = $json->id;

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

$sql = "DELETE FROM `comments` WHERE `id` = :comment_id;";
$result = $pdo->prepare($sql);
$result->execute([
    'comment_id' => $comment_id,
]);

echo json_encode($response, JSON_UNESCAPED_UNICODE);