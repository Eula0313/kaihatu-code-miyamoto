<?php
require '../db-connect.php';

$room_id = $_GET['room_id'] ?? '';

if (empty($room_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Room ID is missing']);
    exit();
}

try {
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT current_team, current_role FROM GameState WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $game_state = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$game_state) {
        throw new Exception('Game state not found');
    }

    echo json_encode(['status' => 'success', 'game_state' => $game_state]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
?>
