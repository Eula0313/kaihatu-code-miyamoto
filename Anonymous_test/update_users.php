<?php
session_start();
require 'db-connect.php';

$room_id = $_POST['room_id'] ?? '';
$role_id = $_POST['role_id'] ?? '';
$team_id = $_POST['team_id'] ?? '';
$nickname = $_SESSION['nickname'] ?? '';

if (empty($room_id) || empty($role_id) || empty($team_id) || empty($nickname)) {
    echo '必要な情報が不足しています。';
    exit();
}

try {
    $pdo = connectDB();
    $pdo->beginTransaction();

    // チームと役割が既に選択されているかを確認
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM User WHERE room_ID = ? AND team_ID = ? AND role_ID = ?");
    $stmt->execute([$room_id, $team_id, $role_id]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        $stmt = $pdo->prepare("UPDATE User SET role_ID = ?, team_ID = ? WHERE room_ID = ? AND user_name = ?");
        $stmt->execute([$role_id, $team_id, $room_id, $nickname]);
        $_SESSION['role_id'] = $role_id; // 役割IDをセッションに保存
        $_SESSION['team_id'] = $team_id; // チームIDをセッションに保存
        $pdo->commit();
        echo "役割が設定されました";
    } else {
        echo "この役割は既に選択されています";
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    echo 'データベース接続エラー: ' . $e->getMessage();
}
?>
