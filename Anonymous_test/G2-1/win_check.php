<?php
require '../db-connect.php';

$room_id = $_GET['room_id'] ?? '';

if (empty($room_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Room ID is missing']);
    exit();
}

try {
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT status, winner_team FROM GameState WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $game_state = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$game_state) {
        throw new Exception('Game state not found');
    }

    if ($game_state['status'] == 'win' && $game_state['winner_team'] != 0) {
        $winning_team_name = ($game_state['winner_team'] == 1) ? '赤' : '青';
        echo json_encode(['status' => 'win', 'message' => $winning_team_name . 'チームの勝ち！']);
    } else {
        echo json_encode(['status' => 'continue']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
?>
