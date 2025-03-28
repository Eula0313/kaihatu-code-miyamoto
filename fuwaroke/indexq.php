<?php
require_once 'common.php';
require_once 'db-connect.php';

// GETリクエストからpost_idを取得
if (isset($_GET['post_id'])) {
    $_SESSION['post_id'] = (int)$_GET['post_id'];
}

$post_id = isset($_SESSION['post_id']) ? (int)$_SESSION['post_id'] : '';

// データベースクエリを作成します
$sql_post_name = "SELECT post_title FROM post WHERE post_id = ?";

// クエリを準備します
$stmt_post_name = $pdo->prepare($sql_post_name);

// クエリパラメータにpost_idをバインドします
$stmt_post_name->bindParam(1, $post_id, PDO::PARAM_INT);

// クエリを実行します
$stmt_post_name->execute();

// クエリの結果を取得します
$post_row = $stmt_post_name->fetch(PDO::FETCH_ASSOC);

// クエリ結果が存在しない場合、エラーメッセージを表示します
if ($post_row === false) {
    $post_name = '不明'; // post_nameに'不明'を設定します
} else {
    $post_name = $post_row['post_title']; // クエリ結果からpost_titleを取得します
}

// 最新の質問を3件取得するクエリ
$sql_latest_questions = "SELECT q_id, q_title FROM question ORDER BY q_create_time DESC LIMIT 3";
$stmt_latest_questions = $pdo->query($sql_latest_questions);
$latest_questions = $stmt_latest_questions->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="image/favicon.ico">
    <title>質問箱</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .header {
            top: 0;
        }
        .footer {
            bottom: 0;
        }
        .main-content {
            display: flex;
            flex: 1;
            padding-top: 40px; /* ヘッダーの高さに合わせる */
            padding-bottom: 40px; /* フッターの高さに合わせる */
        }
        .sidebar, .right-sidebar {
            width: 20%;
            padding: 20px;
            background-color: #e0e0e0;
            box-sizing: border-box;
            position: fixed;
            top: 60px;
            bottom: 0;
            overflow-y: auto;
        }
        
        .content {
            width: 60%;
            padding: 20px;
            box-sizing: border-box;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin: 75px auto 5px auto; /* ヘッダーとフッターの高さに合わせる */
            min-height: calc(100vh - 170px); /* ヘッダーとフッターの高さを引いた値 */
        }
        .content h1, .content h2, .content h3 {
            color: #333;
            margin-bottom: 20px;
        }
        .content h3 {
            border-bottom: 2px solid #007acc;
            padding-bottom: 5px;
        }
        form {
            margin: 20px 0;
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007acc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #005f99;
        }
        a {
            text-decoration: none;
            color: #007acc;
        }
        a:hover {
            text-decoration: underline;
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
            <h2>質問箱</h2>
            <p><a href="post_question.php">質問投稿</a></p>
            <p><a href="view_questions.php">質問一覧</a></p>
            <h3>最近の質問</h3>
            <?php foreach ($latest_questions as $question): ?>
                <p>
                    <a href="view_question.php?q_id=<?= htmlspecialchars($question['q_id'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($question['q_title'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </p>
            <?php endforeach; ?>
        </div>
        
        <div class="right-sidebar">
            <?php include 'right.php'; ?>
        </div>
    </div>

   
</body>
</html>
