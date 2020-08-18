<?php
    session_start();

    include_once('../db/connect_db.php');
    $pdo = connect_db();
    if (!$pdo) {
        echo 'Database connection error';
        return;
    }
    $user_id = $_SESSION['id'];
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
        echo 'Permission denied';
        exit;
    }
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/base_style.css">
    <link rel="stylesheet" href="../static/css/login.css">
    <link rel="stylesheet" href="../static/css/menu.css">
    <link rel="stylesheet" href="../static/css/footer.css">
    <link rel="stylesheet" href="../static/css/admin.css">
    <link rel="stylesheet" href="../static/css/real_admin.css">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet"> -->
    <title>Admin</title>
</head>

<?php
include('../is_activate.php');
?>
<body>
<?php
include('../menu.php');
?>
<main>
<section class="first-section wrapper">
    <h1>Admin</h1>
</section>
<section>
    <div class="fields">
        <a href="./admin.php?f=user">Users</a>
        <a class="photoes_link" href="./admin.php?f=photo">Photoes</a>
    </div>
</section>
<section class="admin_content wrapper">
<?php
if ($_GET['f'] == 'user') {
    $sql_select_user = "SELECT * FROM `users` WHERE `id`<>:id;";
    $result_select_user = $pdo->prepare($sql_select_user);
    $result_select_user->execute([
        'id' => $_SESSION['id'],
    ]);
    $users = $result_select_user->fetchAll();
    ?>
    <div class="users">
    <ul class="users_list">
    <?php
    foreach ($users as $user) {
    ?>
        <li>
            <?php 
            $username = str_replace('<', '&lt;', $user['username']);
            $username = str_replace('>', '&gt;', $username);
            ?>
            <div class="admin_user_info">
                <p class="user_id"><?php echo $user['id']; ?></p>
                <p class="username"><?php echo $username; ?></p>
                <a class="edit" href="./edit_user.php?i=<?php echo $user['id']; ?>">edit</a>
                <p class="del">del</a>
            </div>
        </li>
    <?php
    }
    ?>
    </ul>
    </div>
    <?php
}
if ($_GET['f'] == 'photo') {
    $sql_select_photoes = "SELECT * FROM `photoes` INNER JOIN `users` ON photoes.user_id = users.id ORDER BY `photoes`.`pubdate` DESC;";
    $result_select_photo = $pdo->prepare($sql_select_photoes);
    $result_select_photo->execute([
        'id' => $_SESSION['id'],
    ]);
    $photoes = $result_select_photo->fetchAll();
    ?>
    <ul class="photo_list">
    <?php
    foreach ($photoes as $photo) {
    ?>
    <li>
        <div class="admin_photo">
            <p class="del_photo">del photo</p>
            <div class="for_img">
                <?php echo '<img id="'.$photo[0] .'" src="data:image/jpeg;base64,'.$photo['photo'].'" />'; ?>
            </div>
            
            <h2 class="header">Comments:</h2>
            <ul class="comments">
            <?php
                $sql = "SELECT * FROM `comments` INNER JOIN `users` ON comments.from_id = users.id WHERE `photo_id` = :photo_id;";
                $res = $pdo->prepare($sql);
                $res->execute([
                    'photo_id' => $photo[0],
                ]);
                $comments = $res->fetchAll();
                foreach ($comments as $comment) {
                ?>
                    <li class="comment">
                        <div class="for_flex">
                            <?php
                                $username_comment = str_replace('<', '&lt;', $comment['username']);
                                $username_comment = str_replace('>', '&gt;', $username_comment);

                                $comment_text = str_replace('<', '&lt;', $comment['text']);
                                $comment_text = str_replace('>', '&gt;', $comment_text);
                            ?>
                            <p class="from"><?php echo $username_comment; ?></p>
                            <p class="comment_text"><?php echo $comment_text; ?></p>
                            <p class="comment_pubdate"><?php echo $comment['pubdate']; ?></p>
                            <p id="comment_<?php echo $comment[0]; ?>" class="del_comment">del</p>
                        </div>
                    </li>
                <?php
                }
            ?>  
            </ul>
            
            <h2 class="header">Likes:</h2>
            <ul class="comments">
            <?php
                $sql = "SELECT * FROM `likes` INNER JOIN `users` ON likes.from_id = users.id WHERE `photo_id` = :photo_id;";
                $res = $pdo->prepare($sql);
                $res->execute([
                    'photo_id' => $photo[0],
                ]);
                $likes = $res->fetchAll();
                foreach ($likes as $like) {
                    $username = str_replace('<', '&lt;', $like['username']);
                    $username = str_replace('>', '&gt;', $username);
                ?>
                <li class="comment">
                    <div class="for_flex like_flex">
                        <p class="like_from"><?php echo $username; ?></p>
                        <p id="like_<?php echo $like[0]; ?>" class="del_like">del</p>
                    </div>
                </li>
                <?php
                }
            ?>
            </ul>
        </div>
    </li>
    <hr>
    <?php
    }
    ?>
    </ul>
    <?php
}
?>
</section>
</main>
<?php 
include('../footer.php');
?>
<script src="../static/js/admin/del_user.js"></script>
<script src="../static/js/admin/del_photo.js"></script>
<script src="../static/js/admin/del_comment.js"></script>
<script src="../static/js/admin/del_like.js"></script>
<script src="../static/js/menu_script.js"></script>
</body>
