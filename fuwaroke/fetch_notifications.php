<?php
session_start();
require 'db-connect.php';

$user_id = isset($_SESSION['user']['user_id']) ? (int)$_SESSION['user']['user_id'] : 0;

if ($user_id == 0) {
    echo json_encode([]);
    exit();
}

$sql = $pdo->prepare('SELECT message FROM notifications WHERE user_id = ? AND is_read = 0');
$sql->execute([$user_id]);
$notifications = $sql->fetchAll(PDO::FETCH_ASSOC);

// 未読通知を既読に更新
$update = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0');
$update->execute([$user_id]);

echo json_encode($notifications);
?>
