<?php
session_start();
require 'db-connect.php';

$stmt = $pdo->prepare('SELECT cate_id, cate_name FROM category');
$stmt->execute();
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="image/favicon.ico">
    <title>カテゴリ一覧</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: #f8f8f8;
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
        }
        .main {
            display: flex;
            margin-top: 60px; /* ヘッダーの高さを確保 */
            flex: 1;
            width: 100%;
        }
        .sidebar {
            width: 200px;
            background-color: #f0f0f0;
            padding: 10px;
            border-right: 1px solid #ddd;
            box-sizing: border-box;
            height: calc(100vh - 60px); /* ヘッダーの高さを引いた高さ */
            position: fixed;
            top: 60px; /* ヘッダーの高さ */
            left: 0;
        }
        .content {
            flex: 1;
            padding: 10px;
            margin-left: 220px; /* サイドバーの幅を確保 */
            margin-right: 220px; /* 右サイドバーの幅を確保 */
            box-sizing: border-box;
        }
        .right {
            background-color: #f0f0f0;
            padding: 10px;
            border-left: 1px solid #ddd;
            box-sizing: border-box;
            height: calc(100vh - 60px); /* ヘッダーの高さを引いた高さ */
            position: fixed;
            top: 60px; /* ヘッダーの高さ */
            right: 0;
        }
        .post-list {
            list-style-type: none; /* 箇条書きをなくす */
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px; /* タイトルの間隔 */
        }
        .post-item {
            background-color: #f0f0f0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: calc(25% - 10px); /* 横に4つ表示するための幅（100% / 4 = 25%） */
            box-sizing: border-box;
            text-align: center;
            white-space: nowrap; /* 改行させない */
            overflow: hidden; /* オーバーフローを隠す */
            text-overflow: ellipsis; /* オーバーフロー時に省略記号を表示 */
        }
        .post-item a {
            text-decoration: none;
            color: #333;
            display: block;
            width: 100%;
            text-overflow: ellipsis; /* オーバーフロー時に省略記号を表示 */
            white-space: nowrap; /* 改行させない */
            overflow: hidden; /* オーバーフローを隠す */
        }
    </style>
</head>
<body>
    <div class="header">
        <?php require_once 'header.php'; ?>
    </div>
    <div class="main">
        <div class="sidebar">
            <?php require_once 'side.php'; ?>
        </div>
        
        <div class="content">
            <h1>カテゴリ一覧</h1>
            <?php
            if (empty($categories)) {
                echo "<p>カテゴリがありません。</p>";
            } else {
                echo "<ul class=\"post-list\">";
                foreach ($categories as $category) {
                    $cate_id = $category['cate_id'];
                    $cate_name = htmlspecialchars($category['cate_name'], ENT_QUOTES, 'UTF-8');
                    // タイトルが7文字を超える場合は省略する
                    if (mb_strlen($cate_name) > 7) {
                        $cate_name = mb_substr($cate_name, 0, 7) . '...';
                    }
                    echo "<li class=\"post-item\"><a href=\"index.php?cate_id={$cate_id}\">{$cate_name}</a></li>";
                }
                echo "</ul>";
            }
            ?>
        </div>

        <div class="right">
            <?php require 'right.php'; ?>
        </div>
    </div>
</body>
</html>
