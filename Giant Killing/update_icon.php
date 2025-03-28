<?php
session_start();
require 'db-connect.php';

if (!isset($_SESSION['user_id'])) {
    echo 'ユーザーがログインしていません。';
    exit;
}

$user_id = $_SESSION['user_id'];
$new_icon = $_POST['new_icon'] ?? '';

if ($new_icon) {
    // 新しいアイコンをデータベースに保存
    $sql_update_icon = "UPDATE users SET icon = ? WHERE user_id = ?";
    $stmt = $pdo->prepare($sql_update_icon);
    $stmt->execute([$new_icon, $user_id]);

    echo 'アイコンが更新されました。';
} else {
    echo 'アイコンの画像が指定されていません。';
}
?>
