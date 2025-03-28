<?php
session_start();
require '../db-connect.php';

$room_id = $_GET['room_id'] ?? '';

if (empty($room_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Room ID is missing']);
    exit();
}

try {
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT last_update FROM Room WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        throw new Exception('Room not found');
    }

    $last_update = $result['last_update'];

    echo json_encode(['status' => 'success', 'last_update' => $last_update]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
?>
