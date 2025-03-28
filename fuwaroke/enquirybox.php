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
    <title>質問箱の使用方法</title>
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
        margin-top: 80px;
        margin-bottom: 40px;
        
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

    .enquiry-link {
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
            <h1>質問箱の使い方</h1>
            <h2>＜質問投稿の使い方＞</h2>
            <div class="image-container">
                <img src="image/enquiry1.jpg" alt="enquiry1">
                <img src="image/enquiry2.jpg" alt="enquiry2">
                <img src="image/enquiry3.jpg" alt="enquiry3">
            </div>
            <h2>＜質問一覧の使い方＞<h2>
                <div class="image-container">
                    <img src="image/enquiry4.jpg" alt="enquiry4">
                    <img src="image/enquiry5.jpg" alt="enquiry5">
                    <img src="image/enquiry6.jpg" alt="enquiry6">
                    <img src="image/enquiry7.jpg" alt="enquiry7">
                </div>
            <a href="indexq.php" class="enquiry-link">質問箱はこちら</a>
        </div>
        <?php require 'right.php'; ?>
    </div>
</body>

</html>
