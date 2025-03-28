<?php
require_once 'common.php';
require_once 'db-connect.php';

$sql = 'SELECT q.q_id, q.q_title, u.user_name, q.q_create_time, c.cate_name 
        FROM question q
        JOIN user u ON q.user_id = u.user_id
        JOIN category c ON q.cate_id = c.cate_id
        ORDER BY q.q_create_time DESC';
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="image/favicon.ico">
    <title>質問一覧</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
       
        .main-content {
            display: flex;
            flex: 1;
            padding-top: 60px; /* Ensure content starts below the header */
        }
        .sidebar, .right-sidebar {
            width: 20%;
            padding: 20px;
            background-color: #e0e0e0;
            box-sizing: border-box;
            position: fixed;
            top: 60px; /* ヘッダーの高さに合わせる */
            bottom: 0;
            overflow-y: auto; /* 縦方向のスクロールを有効にする */
        }
        
        .content {
            width: 60%;
            padding: 20px;
            box-sizing: border-box;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-left: 20%;
            margin-right: 20%;
        }
        .container {
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
        }
        .question {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .question-title {
            font-size: 1.2em;
            margin: 0;
        }
        .question-meta {
            font-size: 0.9em;
            color: #777;
        }
    </style>
    <script>
        function fetchRightSidebar() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'right.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.querySelector('.right-sidebar').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        document.addEventListener("DOMContentLoaded", function() {
            fetchRightSidebar();

            setInterval(function() {
                fetchRightSidebar();
            }, 3000);
        });
    </script>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="main-content">
        <div class="sidebar">
            <?php include 'side.php'; ?>
        </div>
        <div class="content">
            <div class="container">
                <h2>質問一覧</h2>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="question">
                        <p class="question-title"><a href="view_question.php?q_id=<?= htmlspecialchars($row['q_id']) ?>"><?= htmlspecialchars($row['q_title']) ?></a></p>
                        <p class="question-meta">投稿者: <?= htmlspecialchars($row['user_name']) ?> | カテゴリー: <?= htmlspecialchars($row['cate_name']) ?> | 投稿日: <?= htmlspecialchars($row['q_create_time']) ?></p>
                    </div>
                <?php endwhile; ?>
                <?php if (isset($_SESSION['user']['user_id'])): ?>
                    <p><a href="post_question.php">質問を投稿する</a></p>
                <?php else: ?>
                    <p>質問を投稿するには<a href="login.php">ログイン</a>してください。</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="right-sidebar">
            <?php include 'right.php'; ?>
        </div>
    </div>
</body>
</html>

