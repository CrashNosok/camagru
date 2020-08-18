<?php
include_once('../db/connect_db.php');

function confirm() {
    if (isset($_GET['token']) && isset($_GET['log'])) {
        $token = $_GET['token'];
        $login = $_GET['log'];
    
        $pdo = connect_db();
        if (!$pdo) {
            echo 'Database connection error';
            return;
        }
    
        $sql_update_user = "UPDATE `users` SET `verified` = 1 WHERE `token` = :token;";
        $result_update_user = $pdo->prepare($sql_update_user);
        $result_update_user->execute([
            'token' => $token,
        ]);
        if ($result_update_user->rowCount()) {
            echo '<p style="color:green;">SUCCSESS.</p> <p>You can <a href="../login.php">login</a></p>';
        } else {
            echo '<p style="color:red;">ERROR.</p> <p>Something went wrong!</p>';
        }
    } else {
        echo '<p style="color:red;">ERROR.</p> <p>Wrong parameters!</p>';
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Successful registration</title>
    <link rel="stylesheet" href="../static/css/login.css">
    <link rel="stylesheet" href="../static/css/footer.css">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet"> -->
</head>
<body>
    <div class="login_form">
        <div class="succsess_box">
            <?php confirm(); ?>
        </div>
    </div>

<?php
include('../footer.php');
?>
</body>
</html>