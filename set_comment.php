<?php
session_start();
include_once('./db/connect_db.php');

$response = array('error' => false);
$json = json_decode(file_get_contents('php://input'));

if (!isset($json->id) || !isset($json->text) || !isset($_SESSION['id'])) {
    $response['error'] = 'ERROR';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$user_id = $_SESSION['id'];
$photo_id = $json->id;
$comment_text = $json->text;

$pdo = connect_db();
if (!$pdo) {
    $response['error'] = 'Database connection error';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}    

$sql = "INSERT INTO `comments` (`photo_id`, `from_id`, `pubdate`, `text`) VALUES (:photo_id, :from_id, NOW(), :comment_text);";
$result = $pdo->prepare($sql);
$result->execute([
    'photo_id' => $photo_id,
    'from_id' => $user_id,
    'comment_text' => $comment_text,
]);

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;

?>