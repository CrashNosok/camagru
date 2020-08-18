<?php
    session_start();
    include_once('./db/connect_db.php');
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:url" content="http://localhost/camagru/gallery/gallery.php">
    <meta property="og:title" content="image">
    <meta property="og:description" content="Look at this">
    <meta property="og:image" content="../static/img/cat.png">
    <link rel="stylesheet" href="static/css/base_style.css">
    <link rel="stylesheet" href="static/css/login.css">
    <link rel="stylesheet" href="static/css/menu.css">
    <link rel="stylesheet" href="static/css/video.css">
    <link rel="stylesheet" href="static/css/modal_photo.css">
    <link rel="stylesheet" href="static/css/footer.css">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Camagru</title>
</head>

<body>
<?php
include('./is_activate.php');
?>
<?php
include('./menu.php');
?>

<main>
    <section id="first-section" class="wrapper">
        <h1 class="welcome">Create Photo</h1>
    </section>

    <section>
    
    <div class="main-action">
            <div class="ma-left">
            <div class="booth">
                <video id="video" autoplay="autoplay"></video>
                <canvas id="canvas"></canvas>
                <div class="photo_buttons">
                    <a href="#" id="del_photo" class="booth-clear-button button_dnw">Clear</a>
                    <a href="#" id="capture" class="booth-capture-button button_dnw">Take a photo</a>
                    <a href="#" id="download_photo" class="booth-download-button button_dnw">Download</a>
                </div>
                <img src="static/img/error.png" alt="" id="tmp_photo">
            </div>
            <div class="slider">
                <div class="snake">
                    <label for="snake_width">width</label>
                    <input type="range" id="snake_width" min="0" max="600" value="200" step="1" oninput="update_width(value)">
                    <output for="snake_width" id="snake_width_volume">200</output>
                 
                    <label for="snake_height">height</label> 
                    <input type="range" id="snake_height" min="0" max="600" value="200" step="1" oninput="update_height(value)">
                    <output for="snake_height" id="snake_height_volume">200</output>
                </div>
                <div id="slide">
                    <img src="static/img/mask5.png" alt="" class="slide_single">
                    <img src="static/img/mask6.png" alt="" class="slide_single">
                    <img src="static/img/mask7.png" alt="" class="slide_single">
                    <img src="static/img/mask.png" alt="" class="slide_single">
                    <!-- <img src="static/img/mask1.png" alt="" class="slide_single"> -->
                    <img src="static/img/mask2.png" alt="" class="slide_single">
                    <!-- <img src="static/img/mask3.png" alt="" class="slide_single"> -->
                    <img src="static/img/mask4.png" alt="" class="slide_single">
                    <!-- <img src="static/img/mask8.png" alt="" class="slide_single"> -->
                </div>
                <div>
                    <img id="arrow" src="static/img/arrow.png" alt="">
                    <img id="arrow2" src="static/img/arrow2.png" alt="">
                </div> 
                <div class="upload_form">
                    <form action="upload_photo.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="upload" id="file" accept="image/*" onchange="previewImage();">
                        <label for="file">
                            choose a photo
                        </label>
                    </form>
                </div>
            </div>

        </div>

        <div class="ma-right">
            <h2 class="header_photo">Your photos</h2>
            <ul id="cards" class="cards">

                <?php
                    $pdo = connect_db();
                    if (!$pdo) {
                        echo 'Database connection error';
                        return;
                    }

                    $sql_select_photoes = "SELECT * FROM `photoes` WHERE `user_id`=:id_user ORDER BY `pubdate` DESC;";
                    $result_select_photoes = $pdo->prepare($sql_select_photoes);
                    $result_select_photoes->execute([
                        'id_user' => $_SESSION['id'],
                    ]);
                    foreach ($result_select_photoes as $photo) {
                        $image = $photo['photo'];
                        $id = $photo['id'];

                        $sql_count_likes = "SELECT COUNT(*) as 'count_likes' FROM `likes` WHERE `photo_id`=:photo_id;";
                        $result_count_likes = $pdo->prepare($sql_count_likes);
                        $result_count_likes->execute([
                            'photo_id' => $id,
                        ]);
                        $count_likes_arr = $result_count_likes->fetchAll()[0];
                        $count_likes = $count_likes_arr['count_likes'];

                        $sql_count_comments = "SELECT COUNT(*) as 'count_comments' FROM `comments` WHERE `photo_id`=:photo_id;";
                        $result_count_comments = $pdo->prepare($sql_count_comments);
                        $result_count_comments->execute([
                            'photo_id' => $id,
                        ]);
                        $count_comments_arr = $result_count_comments->fetchAll()[0];
                        $count_comments = $count_comments_arr['count_comments'];
                ?>
                        <li id="<?php echo $id; ?>" class="h-list popup_link">
                        <div class="descr">
                            <div class="h-link">
                                <p>
                                    <span class="heart">&#10084;</span>
                                    <span <?php echo 'id="preview_'.$id.'"' ?> class="heart_number"><?php echo $count_likes ?></span>
                                    <span class="comment">&#9998;</span>
                                    <span <?php echo 'id="preview_comment_'.$id.'"' ?>><?php echo $count_comments ?></span>
                                </p>
                            </div>
                        </div>
                    <?php
                        echo '<img class="u-img" src="data:image/jpeg;base64,'.$image.'" />';
                    ?>
                    </li>
                <?php   
                    }
                ?>
            </ul>
        </div>
    </div>
    </section>
</main>

<?php
    include('footer.php');
?>

<div id="popup" class="popup">
    <div class="popup_body wrapper">
        <div class="popup_content">
            <a href="#menu_id" id="close_x" class="popup_close close_popup">X</a>
            <div class="pop_wrapper">
                <div class="pop_container">
                    <div class="pop_left">
                        <img id="pop_img" src="./static/img/cat.png" alt="">
                    </div>
                    <div class="pop_right">

                        <div class="popup_menu">
                            <input id="popup_check_menu" type="checkbox">
                            <label for="popup_check_menu">...</label>
                            <nav class="popup_main_menu">
                                <p id="menu_del_photo">Delete photo</p>
                            </nav>
                        </div>

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
<script src="static/js/get_position.js"></script>
<script src="static/js/primary.js"></script>
<script src="static/js/masks.js"></script>
<script src="static/js/cam.js"></script>
<script src="static/js/menu_script.js"></script>
<script src="static/js/change_size.js"></script>
<script src="static/js/slider.js"></script>
<script src="static/js/download_photo.js"></script>
<script src="static/js/modal_photo.js"></script>
<script src="static/js/get_user.js"></script>
<script src="static/js/set_like.js"></script>
<script src="static/js/like.js"></script>
<script src="static/js/comment.js"></script>
<script src="static/js/send_mail.js"></script>
<script src="static/js/del_photo.js"></script>
</html>