<?php
function is_admin() {
    $user_id = $_SESSION['id'];
    $pdo = connect_db();
    if (!$pdo) {
        return false;
    }

    $sql_check = "SELECT * FROM `users` WHERE `id` = :userid;";
    $sql_check_res = $pdo->prepare($sql_check);
    $sql_check_res->execute([
        'userid' => $user_id,
    ]);
    $users = $sql_check_res->fetchAll();
    if (!count($users)) {
        return false;
    }
    $user = $users[0];
    if (!$user['is_admin']) {
        return false;
    }
    return true;
}
?>

<div class="m-menu-wrapper">
    <header id="menu_id" class="m-header lock_padding">
        <div class="m-container">
            <div class="m-header-body">
                <a href="/camagru/gallery/gallery.php" class="m-header-logo">
                    <p>Camagru</p>
                </a>
                <div class="m-header-burger">
                    <span></span>
                </div>
                <nav class="m-header-menu">
                    <ul class="m-header-list">
                        <li>
                            <a href="/camagru/gallery/gallery.php" class="m-header-link gen_menu">Feed</a>
                        </li>
                        <li>
                            <a href="/camagru/index.php" class="m-header-link gen_menu">Camera</a>
                        </li>
                        <li>
                            <a href="/camagru/user/settings.php" class="m-header-link gen_menu">Profile settings</a>
                        </li>
                        <?php
                        if (is_admin()) {
                        ?>
                        <li>
                            <a href="/camagru/admin/admin.php" class="m-header-link gen_menu">admin</a>
                        </li>
                        <?php
                        }
                        ?>
                        <li>
                            <?php
                            if (isset($_SESSION['id'])){
                            ?>
                            <a href="/camagru/logout.php" class="m-header-link m-button">log out</a>
                            <?php
                            } else {
                            ?>
                            <a href="/camagru/login.php" class="m-header-link m-button">log in</a>
                            <?php
                            }
                            ?>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    </div>