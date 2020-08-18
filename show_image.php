<?php
    session_start();
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
    <link rel="stylesheet" href="static/css/footer.css">
    <link rel="stylesheet" href="static/css/show_photo.css">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Camagru</title>
</head>
<?php
    include_once('db/connect_db.php');

    $pdo = connect_db();
    if (!$pdo) {
        echo 'Database connection error';
        return;
    }

    if (!isset($_GET['i'])) {
        echo 'error';
        exit;
    }
    if (!is_numeric($_GET['i'])) {
        echo 'error';
        exit;
    }

    $sql = "SELECT * FROM `photoes` WHERE `id` = :photo_id";
    $res = $pdo->prepare($sql);
    $res->execute([
        'photo_id' => $_GET['i'],
    ]);
    $photo_arr = $res->fetchAll();
    if (!count($photo_arr)) {
        echo 'error';
        exit;
    }
    $photo = $photo_arr[0]['photo'];

    $sql_select_login = "SELECT * FROM `users` WHERE `username`=:username AND `password`=:passwd";
    $result_select_login = $pdo->prepare($sql_select_login);
    $result_select_login->execute([
        'username' => $login,
        'passwd' => $hashed_passwd,
    ]);

    $user_arr = $result_select_login->fetchAll();
?>
<body>
    <section class="show_first">
        <h1><a href="./gallery/gallery.php">Camagru</a></h1>
    </section>
    <section class="show_content wrapper">
        <div class="show_center">
            <?php echo '<img src="data:image/jpeg;base64,'.$photo.'" />'; ?>
        </div>
    </section>
</body>

<?php
    include('footer.php');
?>
