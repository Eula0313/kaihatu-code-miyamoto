<?php
session_start();

// 管理者であることを確認
if (!isset($_SESSION['user_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者画面</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <?php require "admin_header.php"; ?>
    <div class="content">
        <?php require "admin_side.php"; ?>
        <div class="flex-content">
            <h1 class="content-title">管理者<wbr>メニューです。</h1>
        </div>
    </div>
</body>

</html>

<!-- <h1>管理者メニュー</h1>
    <button onclick="location.href='customer.php'" name="customer-info">顧客情報</button>
    <button onclick="location.href='board.php'" name="board-info">掲示板リスト</button> -->