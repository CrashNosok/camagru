<?php 
    if (!isset($_SESSION['loggued_on_user'])) {
?>
<div class="login_form">
    <div class="succsess_box">
        <p>This page is available for registered users only!</p>
        <p>Please <a href="/camagru/register.php">register</a> or <a href="/camagru/login.php">login</a></p>
    </div>
</div>
<?php
exit;
}
?>