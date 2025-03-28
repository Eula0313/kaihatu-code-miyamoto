<?php
session_start();
require_once 'db-connect.php';

if (!isset($_GET['comment_id']) || !isset($_GET['notification_id'])) {
    header("Location: index.php");
    exit;
}

$comment_id = (int)$_GET['comment_id'];
$notification_id = (int)$_GET['notification_id'];

// 通知を既読に更新
$sql_update_notification = "UPDATE notifications SET is_read = 1 WHERE id = ?";
$stmt_update_notification = $pdo->prepare($sql_update_notification);
$stmt_update_notification->execute([$notification_id]);

// コメントが属するポストIDを取得
$sql_comment = "SELECT post_id FROM t_rireki WHERE id = ?";
$stmt_comment = $pdo->prepare($sql_comment);
$stmt_comment->execute([$comment_id]);
$comment = $stmt_comment->fetch(PDO::FETCH_ASSOC);

if (!$comment) {
    header("Location: index.php");
    exit;
}

$post_id = $comment['post_id'];

// 掲示板ページにリダイレクトして、コメントIDをハッシュとして渡す
header("Location: index.php?post_id=$post_id#comment-$comment_id");
exit;
?>
