<?php
if (isset($_SESSION['loggued_on_user']) && $_SESSION['loggued_on_user']) {
    echo '<p>You are already logged in <a href="logout.php">log out</a></p>
    <a class="my-link2" href="index.php">To the website</a>';
    exit;
}
?>