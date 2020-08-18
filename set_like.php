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
$turn = $json->turn;

$pdo = connect_db();
if (!$pdo) {
    $response['error'] = 'Database connection error';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql_check_like = "SELECT * FROM `likes` WHERE `photo_id` = :photo_id AND `from_id` = :from_id;";
$check_like_res = $pdo->prepare($sql_check_like);
$check_like_res->execute([
    'photo_id' => $photo_id,
    'from_id' => $user_id,
]);
$likes = $check_like_res->fetchAll();
$turn = 0;
if (!count($likes)) {
    $turn = 1;
}

if ($turn) {
    $sql = "INSERT INTO `likes` (`photo_id`, `from_id`) VALUES (:photo_id, :from_id);";
    $result = $pdo->prepare($sql);
    $result->execute([
        'photo_id' => $photo_id,
        'from_id' => $user_id,
    ]);
} else {
    $sql = "DELETE FROM `likes` WHERE `photo_id` = :photo_id AND `from_id` = :from_id;";
    $result = $pdo->prepare($sql);
    $result->execute([
        'photo_id' => $photo_id,
        'from_id' => $user_id,
    ]);
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);

?>