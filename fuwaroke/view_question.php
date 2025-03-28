<?php
require_once 'common.php';
require_once 'db-connect.php';

if (!isset($_GET['q_id'])) {
    echo '質問IDが指定されていません。';
    exit;
}

$q_id = $_GET['q_id'];

$sql = 'SELECT q.q_title, q.q_create_time, u.user_name 
        FROM question q
        JOIN user u ON q.user_id = u.user_id
        WHERE q.q_id = :q_id';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':q_id', $q_id, PDO::PARAM_INT);
$stmt->execute();
$question = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = 'SELECT a.answer_content, a.answer_create_time, u.user_name 
        FROM answers a
        JOIN user u ON a.user_id = u.user_id
        WHERE a.q_id = :q_id';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':q_id', $q_id, PDO::PARAM_INT);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="image/favicon.ico">
    <title>質問詳細</title>
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
        .header, .footer {
            background-color: #0068b7;
            color: white;
            text-align: center;
            padding: 10px 0;
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
            margin-left: 22%;
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
        .question, .answer {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .question-title {
            font-size: 1.2em;
            margin: 0;
        }
        .question-meta, .answer-meta {
            font-size: 0.9em;
            color: #777;
        }
        textarea {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #0068b7;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
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
                <h2>質問詳細</h2>
                <?php if ($question): ?>
                    <div class="question">
                        <p class="question-title"><?= htmlspecialchars($question['q_title']) ?></p>
                        <p class="question-meta">投稿者: <?= htmlspecialchars($question['user_name']) ?> | 投稿日: <?= htmlspecialchars($question['q_create_time']) ?></p>
                    </div>
                    <h3>回答一覧</h3>
                    <?php while ($answer = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="answer">
                            <p class="answer-meta">回答者: <?= htmlspecialchars($answer['user_name']) ?> | 投稿日: <?= htmlspecialchars($answer['answer_create_time']) ?></p>
                            <p><?= htmlspecialchars($answer['answer_content']) ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>質問が見つかりませんでした。</p>
                <?php endif; ?>

                <?php if (isset($_SESSION['user']['user_id'])): ?>
                    <h3>回答を投稿する</h3>
                    <form action="post_answer.php" method="POST">
                        <input type="hidden" name="q_id" value="<?= htmlspecialchars($q_id) ?>">
                        <textarea name="content" rows="5" required></textarea>
                        <button type="submit">回答を投稿</button>
                    </form>
                <?php else: ?>
                    <p>回答を投稿するには<a href="login.php">ログイン</a>してください。</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="right-sidebar">
            <?php include 'right.php'; ?>
        </div>
    </div>
    
</body>
</html>
