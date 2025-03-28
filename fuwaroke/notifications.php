<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'db-connect.php';

$user_id = $_SESSION['user']['user_id'];

// 未読通知を取得
$sql_notifications = "
    SELECT n.*, u.user_name AS sender_name
    FROM notifications n
    JOIN user u ON n.sender_id = u.user_id
    WHERE n.user_id = ? AND n.is_read = 0
    ORDER BY n.created_at DESC
";
$stmt_notifications = $pdo->prepare($sql_notifications);
$stmt_notifications->execute([$user_id]);
$notifications = $stmt_notifications->fetchAll(PDO::FETCH_ASSOC);

// 未読通知を既読に更新
$sql_update_notifications = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0";
$stmt_update_notifications = $pdo->prepare($sql_update_notifications);
$stmt_update_notifications->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="image/favicon.ico">
    <title>通知</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .content {
            display: flex;
            flex-grow: 1;
            margin-top: 60px;
            overflow: hidden;
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
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            margin-left: 200px;
            margin-right: 200px;
            padding: 20px;
            box-sizing: border-box;
        }
        .notification-list {
            margin-top: 20px;
        }
        .notification {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .notification strong {
            font-weight: bold;
        }
        .notification-time {
            color: gray;
            font-size: 12px;
        }
        .no-notifications {
            text-align: center;
            color: gray;
            padding-right: 10%;
        }
    </style>
</head>
<body>
    <?php require 'header.php'; ?>
    <div class="content">
        <?php require 'side.php'; ?>
        <div class="main-content">
            <h2>通知</h2>
            <?php if (count($notifications) > 0): ?>
                <div class="notification-list">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notification">
                            <span>
                                <strong><?php echo htmlspecialchars($notification['sender_name'] ?? '不明', ENT_QUOTES, 'UTF-8'); ?></strong> さんが
                                <a href="view_comment.php?comment_id=<?php echo $notification['comment_id']; ?>&notification_id=<?php echo $notification['id']; ?>">
                                    コメントしました。<?php echo htmlspecialchars($notification['message'], ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </span>
                            <span class="notification-time"><?php echo htmlspecialchars($notification['created_at'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-notifications">新しい通知はありません。</p>
            <?php endif; ?>
        </div>
        <?php require 'right.php'; ?>
    </div>
</body>
</html>
