<?php
    session_start();

    include_once('../db/connect_db.php');
    $pdo = connect_db();
    if (!$pdo) {
        echo 'Database connection error';
        return;
    }

    $start_photo = 1;
    if (isset($_GET['p'])) {
        if (!is_numeric($start_photo)) {
            echo 'Error';
            exit;
        }
        $nu = (int)$_GET['p'];
        if ($nu > 1) {
            $start_photo = $nu;
        }
    }
    $end_photo = $start_photo + 6;
    $miss = ($start_photo - 1) * 6;
    $sql_select_photo = "SELECT * FROM `photoes` INNER JOIN `users` ON photoes.user_id = users.id ORDER BY photoes.pubdate DESC LIMIT $miss, 6;";
    $result_select_photo = $pdo->prepare($sql_select_photo);
    $result_select_photo->execute();
    $photo_arr = $result_select_photo->fetchAll();
    $the_next = true;
    if (count($photo_arr) < 6) {
        $the_next = false;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:url" content="http://localhost/camagru/gallery/gallery.php">
    <meta property="og:title" content="image">
    <meta property="og:description" content="Look at this">
    <meta property="og:image" content="../static/img/cat.png">
    <link rel="stylesheet" href="../static/css/base_style.css">
    <link rel="stylesheet" href="../static/css/menu.css">
    <link rel="stylesheet" href="../static/css/modal_photo.css">
    <link rel="stylesheet" href="../static/css/footer.css">
    <link rel="stylesheet" href="../static/css/gallery.css">
    <link rel="stylesheet" href="../static/css/modal_photo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet"> -->
    <title>Gallery</title>
</head>
<body>
<?php 
include('../menu.php');
?>
<section class="first_section">
    <div class="wrapper_gallery">
        <h1>Gallery</h1>
        <div class="gallery">
            <ul class="g_list">

            <?php
                foreach ($photo_arr as $photo) {
                    $sql_count_likes = "SELECT * FROM `likes` WHERE `photo_id` = :photo_id;";
                    $result_count_likes = $pdo->prepare($sql_count_likes);
                    $result_count_likes->execute([
                        'photo_id' => $photo[0],
                    ]);
                    $count_like = $result_count_likes->rowCount();
                    $likes = $result_count_likes->fetchAll();
                    $turn_like = false;
                    if (isset($_SESSION['id'])) {
                        foreach ($likes as $like) {
                            if ($like['from_id'] == $_SESSION['id']) {
                                $turn_like = true;
                            }
                        }
                    }

                    $sql_count_comments = "SELECT COUNT(*) AS 'count' FROM `comments` WHERE `photo_id` = :photo_id;";
                    $result_count_comments = $pdo->prepare($sql_count_comments);
                    $result_count_comments->execute([
                        'photo_id' => $photo[0],
                    ]);
                    $count_comments_arr = $result_count_comments->fetchAll()[0];
                    $count_comments = $count_comments_arr['count'];
                ?>
                    <li id="<?php echo $photo[0] ?>" class="g_item">
                    <div class="item">
                        <div class="user_name">
                            <img src="https://img.icons8.com/material-rounded/24/000000/user-male-circle.png"/>
                            <p class="name">
                            <?php
                            $username = str_replace('<', '&lt;', $photo['username']);
                            $username = str_replace('>', '&gt;', $username);
                            echo $username;
                            ?></p>
                        </div>
                        <div class="item_img popup_link">
                            <?php echo '<img src="data:image/jpeg;base64,'.$photo['photo'].'" />'; ?>
                        </div>
                        <div class="item_info">
                            <div class="h-link">
                                <p>
                                    <span id="like_<?php echo $photo[0] ?>" class="heart <?php if ($turn_like) { echo 'like_on'; } ?>">&#10084;</span>
                                    <span id="puplic_<?php echo $photo[0] ?>" class="heart_number"><?php echo $count_like; ?></span>
                                    <span class="comment">&#9998;</span>
                                    <span id="preview_comment_<?php echo $photo[0] ?>" class="comment_number"><?php echo $count_comments; ?></span>
                                    <span class="photo_pubdate_info">
                                        <?php echo $photo['pubdate']; ?>
                                    </span>
                                </p>
                            </div>
                                <div id="comment_photo_<?php echo $photo[0] ?>" class="add_comment">
                                    <input class="comment_text" type="text" name="comment_text" placeholder="Add a comment...">
                                    <span class="comment_sub">Publish</span>
                                </div>
                            
                        </div>
                    </div>
                    </li>
            <?php
                }
            ?>

            </ul>
        </div>
    </div>
</section>
<section>
<div class="page_nu">
    <div class="page_number_content">
        <?php
        if ($start_photo > 1) {
        ?>
            <a href="./gallery.php?p=<?php echo $start_photo - 1; ?>"><-</a>
        <?php
        }
        ?>
        <p><?php echo $start_photo; ?></p>
        <?php 
        if ($the_next) {
        ?>
            <a href="./gallery.php?p=<?php echo $start_photo + 1; ?>">-></a>
        <?php
        }
        ?>
    </div>
</div>
</section>
<?php 
include('../footer.php');
?>

<div id="popup" class="popup">
    <div class="popup_body wrapper">
        <div class="popup_content">
            <a href="#menu_id" id="close_x" class="popup_close close_popup">X</a>
            <div class="pop_wrapper">
                <div class="pop_container">
                    <div class="pop_left">
                        <img id="pop_img" src="../static/img/cat.png" alt="">
                    </div>
                    <div class="pop_right">
                        <div class="pop_name">
                            <p id="usersphoto">Name</p>
                            <p id="location" class="location"></p>
                        </div>
                        <div class="pop_comments">
                            <ul id="pop_comments">

                            </ul>
                        </div>
                        <div class="pop_likes">
                            <span id="pop_heart">&#10084;</span>
                            <span id="pop_heart_number">0</span>
                            <span id="photo_pubdate">
                                0000-00-00 00:00:00
                            </span>
                        </div>
                        <div class="pop_share">
                            <a id="twitter_link" href="javascript:void(window.open('https://twitter.com/share?url=[URL]&text=Photo-from-camagru', 'Share twitter','width=600,height=460,menubar=no,location=no,status=no'));" class="fa fa-twitter"></a>
                            <a id="vk_link" href="javascript:void(window.open('http://vk.com/share.php?url=[URL]&title=Camagru&description=Photo-from-camagru', 'Share VK','width=600,height=460,menubar=no,location=no,status=no'));" class="fa fa-vk"></a>
                            <a id="odnoklassniki_link" href="javascript:void(window.open('http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=[URL]&st.comments=camagru', 'Share odnoklassniki','width=600,height=460,menubar=no,location=no,status=no'));" class="fa fa-odnoklassniki"></a>
                            <a id="facebook" href="https://www.facebook.com/sharer/sharer.php" target="_blank" class="fa fa-facebook"></a>
                        </div>
                        <div class="pop_add_comment">
                            <input id="pop_comment_text" type="text" name="comment_text" placeholder="Add a comment...">
                            <span id="pop_add_comment" class="comment_submit">Publish</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="../static/js/menu_script.js"></script>
<?php
if (isset($_SESSION['id'])){
?>
<script src="../static/js/gallery_like.js"></script>
<?php
}
?>

<script src="../static/js/modal_photo.js"></script>
<script src="../static/js/get_user.js"></script>
<script src="../static/js/send_mail.js"></script>

<?php
if (isset($_SESSION['id'])){
?>
<script src="../static/js/set_like.js"></script>
<script src="../static/js/gallery_popup_like.js"></script>
<script src="../static/js/send_mail.js"></script>
<script src="../static/js/comment.js"></script>
<script src="../static/js/comment_gallery.js"></script>
<?php
}
?>
<?php

if (!isset($_SESSION['id'])){
?>
<script src="../static/js/forbid_gallery.js"></script>
<?php
}
?>
</html>