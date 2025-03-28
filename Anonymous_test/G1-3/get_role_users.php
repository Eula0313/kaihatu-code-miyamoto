<?php
require '../db-connect.php';

$room_id = $_GET['room_id'] ?? '';

if (empty($room_id)) {
    echo json_encode([]);
    exit();
}

try {
    $pdo = connectDB();
    // 役割ごとのユーザー情報を取得
    $stmt = $pdo->prepare("SELECT user_name, team_ID, role_ID FROM User WHERE room_ID = ? AND role_ID IS NOT NULL");
    $stmt->execute([$room_id]);
    $roleUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($roleUsers);
} catch (PDOException $e) {
    echo json_encode(['error' => 'データベース接続エラー: ' . $e->getMessage()]);
}
?>
