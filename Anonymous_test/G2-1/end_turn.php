<?php
session_start();
require '../db-connect.php';

$room_id = $_POST['room_id'] ?? '';

if (empty($room_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Room ID is missing']);
    exit();
}

try {
    $pdo = connectDB();
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT current_team, current_role FROM GameState WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $current_state = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$current_state) {
        throw new Exception('Game state not found');
    }

    $current_team = $current_state['current_team'];
    $current_role = $current_state['current_role'];

    if ($current_role == 1) {
        $next_role = 2;
        $next_team = $current_team;
    } else {
        $next_role = 1;
        $next_team = ($current_team == 1) ? 2 : 1;
    }

    // GameStateテーブルを更新
    $stmt = $pdo->prepare("UPDATE GameState SET current_team = ?, current_role = ? WHERE room_ID = ?");
    $stmt->execute([$next_team, $next_role, $room_id]);

    // Room テーブルの last_update を更新
    $stmt = $pdo->prepare("UPDATE Room SET last_update = CURRENT_TIMESTAMP WHERE room_ID = ?");
    $stmt->execute([$room_id]);

    $pdo->commit();
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
?>
