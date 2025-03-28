<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db-connect.php';

// 未読通知数を取得
$user_id = $_SESSION['user']['user_id'];
$sql_unread_notifications = "SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt_unread_notifications = $pdo->prepare($sql_unread_notifications);
$stmt_unread_notifications->execute([$user_id]);
$unread_count = $stmt_unread_notifications->fetchColumn();

// 最新の質問を3件取得するクエリ
$sql_latest_questions = "SELECT q_id, q_title FROM question ORDER BY q_create_time DESC LIMIT 3";
$stmt_latest_questions = $pdo->query($sql_latest_questions);
$latest_questions = $stmt_latest_questions->fetchAll(PDO::FETCH_ASSOC);

// タイトルを省略する関数
function shorten_title($title, $max_length = 12) {
    if (mb_strlen($title) > $max_length) {
        return mb_substr($title, 0, $max_length) . '・・・';
    }
    return $title;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>右側サイドバー</title>
    <style>
        .right-sidebar {
            background-color: #0068b7;
            padding: 10px;
            width: 150px;
            border-left: 1px solid #ddd;
            color: white;
            position: fixed;
            right: 0;
            top: 60px; /* ヘッダーの高さに合わせる */
            bottom: 0;
            overflow-y: auto;
        }
        .right-sidebar h3 {
            margin-top: 0;
        }
        .right-sidebar a {
            display: block;
            text-decoration: none;
            color: white;
            margin-bottom: 10px;
        }
        .right-sidebar a:hover {
            text-decoration: underline;
        }
        .notification-count {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 3px 8px;
            font-size: 12px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="right-sidebar">
        <h3>質問箱</h3>
        <a href="indexq.php">質問箱へ</a>
        <a href="enquirybox.php">質問箱使い方</a>
        <h3>最新の質問</h3>
        <?php foreach ($latest_questions as $question): ?>
            <a href="view_question.php?q_id=<?= htmlspecialchars($question['q_id'], ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars(shorten_title($question['q_title']), ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endforeach; ?>
        <h3>マイページ</h3>
        <a href="mypage.php">マイページへ</a>
        <a href="notifications.php">通知へ
            <?php if ($unread_count > 0): ?>
                <span class="notification-count"><?= $unread_count ?></span>
            <?php endif; ?>
        </a>
    </div>
</body>
</html>
