<?php
session_start();
include_once('./db/connect_db.php');

$response = array('error' => false);
$json = json_decode(file_get_contents('php://input'));

if (!isset($json->id) || !isset($_SESSION['id'])) {
    $response['error'] = 'ERROR';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$user_id = $_SESSION['id'];
$photo_id = $json->id;

$pdo = connect_db();
if (!$pdo) {
    $response['error'] = 'Database connection error';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// проверить принадлежность фото юзеру
$sql_check = "SELECT * FROM `photoes` WHERE `id` = :photo_id;";
$sql_check_res = $pdo->prepare($sql_check);
$sql_check_res->execute([
    'photo_id' => $photo_id,
]);
$photoes = $sql_check_res->fetchAll();
if (!count($photoes)) {
    $response['error'] = 'Photo does not exist';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}
$photo = $photoes[0];
if ($photo['user_id'] != $user_id) {
    $response['error'] = 'Permission denied';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "DELETE FROM `comments` WHERE `photo_id` = :photo_id;
        DELETE FROM `likes` WHERE `photo_id` = :photo_id;
        DELETE FROM `photoes` WHERE `id` = :photo_id;";
$result = $pdo->prepare($sql);
$result->execute([
    'photo_id' => $photo_id,
]);

echo json_encode($response, JSON_UNESCAPED_UNICODE);

?>