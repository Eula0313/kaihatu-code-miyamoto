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
    $stmt = $pdo->prepare("
        SELECT 
            t.team_name,  -- チーム名を取得
            l.hint, 
            l.sheet 
        FROM 
            Log l 
            JOIN User u ON l.user_ID = u.user_ID 
            JOIN Team t ON u.team_ID = t.team_ID 
        WHERE 
            l.room_ID = ? 
        ORDER BY 
            l.log_ID DESC
    ");
    $stmt->execute([$room_id]);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'logs' => $logs]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
?>
