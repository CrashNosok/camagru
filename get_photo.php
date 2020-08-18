<?php
session_start();
include_once('./db/connect_db.php');

$response = array('error' => false);
$json = json_decode(file_get_contents('php://input'));

if (!isset($json->id)) {
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

// получение фото
$sql_select_photo = "SELECT * FROM `photoes` WHERE `id`=:photo_id;";
$result_select_photo = $pdo->prepare($sql_select_photo);
$result_select_photo->execute([
    'photo_id' => $photo_id,
]);

$photo_arr = $result_select_photo->fetchAll();
if (count($photo_arr) !== 1) {
    $response['error'] = 'Photo finding error';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$photo = $photo_arr[0];
// вернуть на место
$response['photo'] = $photo['photo'];
// $response['photo'] = 'kek';
$response['pubdate'] = $photo['pubdate'];
$response['location'] = $photo['geolocation'];

// получение юзера
$sql_select_user = "SELECT * FROM `users` WHERE `id`=:usersid;";
$result_select_user = $pdo->prepare($sql_select_user);
$result_select_user->execute([
    'usersid' => $photo['user_id'],
]);

$user = $result_select_user->fetchAll()[0];
$username = str_replace('<', '&lt;', $user['username']);
$username = str_replace('>', '&gt;', $username);
$response['usersphoto'] = $username;

// получение кол-ва лайков
$sql_count_likes = "SELECT * FROM `likes` WHERE `photo_id` = :photo_id;";
$result_count_likes = $pdo->prepare($sql_count_likes);
$result_count_likes->execute([
    'photo_id' => $photo_id,
]);
$response['count_likes'] = $result_count_likes->rowCount();
$likes = $result_count_likes->fetchAll();
$response['turn_like'] = false;
foreach ($likes as $like) {
    if ($like['from_id'] == $user_id) {
        $response['turn_like'] = true;
    }
}

// получение комментариев
$sql_comments = "SELECT * FROM `comments`INNER JOIN `users` ON comments.from_id = users.id WHERE `photo_id` = :photo_id ORDER BY comments.pubdate DESC;";
$result_sql_comments = $pdo->prepare($sql_comments);
$result_sql_comments->execute([
    'photo_id' => $photo_id,
]);

$comments = array();
$i = 0;
foreach ($result_sql_comments as $row) {
    $fixed_text = str_replace('<', '&lt;', $row['text']);
    $fixed_text = str_replace('>', '&gt;', $fixed_text);
    $username = str_replace('<', '&lt;', $row['username']);
    $username = str_replace('>', '&gt;', $username);
    $comments[$i] = array(
        'username' => $username,
        'pubdate' => $row['pubdate'],
        'text' => $fixed_text,
    );
    $i++;
}
$response['comments'] = $comments;

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>