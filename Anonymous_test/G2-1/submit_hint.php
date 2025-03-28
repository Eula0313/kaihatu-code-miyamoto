<?php
session_start();
require '../db-connect.php';

$room_id = $_POST['room_id'] ?? '';
$hint = $_POST['hint'] ?? '';
$hint_count = $_POST['hint_count'] ?? '';
$team_id = $_SESSION['team_id'] ?? '';

if (empty($room_id) || empty($hint) || empty($hint_count) || empty($team_id)) {
    echo json_encode(['status' => 'error', 'message' => '必要な情報が不足しています']);
    exit();
}

try {
    $pdo = connectDB();
    $pdo->beginTransaction();

    // ヒントと枚数を更新
    $stmt = $pdo->prepare("UPDATE GameState SET hint_text = ?, hint_count = ?, current_role = 2 WHERE room_ID = ?");
    $stmt->execute([$hint, $hint_count, $room_id]);

    // Room テーブルの last_update を更新
    $stmt = $pdo->prepare("UPDATE Room SET last_update = CURRENT_TIMESTAMP WHERE room_ID = ?");
    $stmt->execute([$room_id]);

    // Logテーブルに記録
    $stmt = $pdo->prepare("INSERT INTO Log (room_ID, user_ID, hint, sheet) VALUES (?, ?, ?, ?)");
    $stmt->execute([$room_id, $_SESSION['user_id'], $hint, $hint_count]);

    $pdo->commit();
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
?>
