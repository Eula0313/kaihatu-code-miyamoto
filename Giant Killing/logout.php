<?php
// logout.php

// セッションを破棄
session_start();
session_unset();
session_destroy();

// クッキーを削除
setcookie('user_id', '', time() - 3600, '/');

// menu.php へリダイレクト
header('Location: menu.php');
exit;
?>

