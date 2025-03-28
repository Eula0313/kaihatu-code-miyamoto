<?php
session_start();
ob_start(); // 出力バッファリングを開始する

require_once 'db-connect.php';

// ログインしているユーザーIDを取得（ログインしていない場合はnull）
$logged_in_user_id = isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : null;

// ログインしていない場合はエラーメッセージを表示して終了
if (!$logged_in_user_id) {
    echo "<p>ログインしていません。</p>";
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>掲示板作成説明</title>
    <link rel="icon" href="image/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        display: flex;
        flex-direction: column;
        height: 100vh;
        
    }

    .main {
        padding: 20px;
    }

    .container {
    
        flex: 1;
       
    }

    .main {
        background: white;
        flex: 1;
        max-width: 650px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        margin: 0 auto;
        margin-bottom: 40px;
        margin-top: 80px;
    }

    h1 {
        margin-top: 0;
    }

    .image-container {
        margin: 20px auto;
        text-align: center;
    }

    .image-container img {
        max-width: 100%;
        height: auto;
        margin-bottom: 10px;
        
    }

    .build-link {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .build-link:hover {
        background-color: #0056b3;
    }
    </style>
</head>

<body>
    <?php require_once 'header.php'; ?>
    <div class="container">
        <?php require_once 'side.php'; ?>
        <div class="main">
            <h1>掲示板作成説明</h1>
            <div class="image-container">
                <img src="image/build1.png" alt="build1">
                <img src="image/build2.png" alt="build2">
            </div>
            <a href="build.php" class="build-link">掲示板作成はこちら</a>
        </div>
        <?php require 'right.php'; ?>
    </div>
</body>

</html>
