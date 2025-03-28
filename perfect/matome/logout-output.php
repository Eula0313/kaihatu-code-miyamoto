<?php session_start(); ?>
<?php
if (isset($_SESSION['client'])) {
    unset($_SESSION['client']);
    require 'db-connect.php';
    require 'header.php';
    echo '<h1 class="logout-success">ログアウトしました。</h1>';
    require 'menu.php';
} else {
    echo '<h1 style="text-align: center; font-size: 25px;">すでにログアウトしています。</h1><br>';
    echo '<a href="login-input.php"><h2 style="text-align: center; font-size: 25px;">ログイン</h2></a>';
}
?>
<?php require 'footer.php'; ?>


<style>
    h1.logout-success {
        margin: 0 auto;
        text-align: center;
    }

</style>
