<?php
require 'db-connect.php';

$room_id = $_POST['room_id'] ?? '';

if (empty($room_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Room ID is missing']);
    exit();
}

try {
    $pdo = connectDB();
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT card_ID, card_name FROM Card ORDER BY RAND() LIMIT 25");
    $stmt->execute();
    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $colors = array_merge(
        array_fill(0, 9, 'red'),
        array_fill(0, 8, 'blue'),
        array_fill(0, 1, 'black'),
        array_fill(0, 7, 'white')
    );
    shuffle($colors);

    $board_id = 1;
    foreach ($cards as $index => $card) {
        $stmt = $pdo->prepare("INSERT INTO Board (room_ID, board_ID, state_ID, card_name, color) VALUES (?, ?, 2, ?, ?)");
        $stmt->execute([$room_id, $board_id, $card['card_name'], $colors[$index]]);
        $board_id++;
    }

    $stmt = $pdo->prepare("UPDATE Room SET status = 'started' WHERE room_ID = ?");
    $stmt->execute([$room_id]);

    $stmt = $pdo->prepare("INSERT INTO GameState (room_ID, current_team, current_role, hint_text, hint_count) VALUES (?, 1, 1, '', 0)");
    $stmt->execute([$room_id]);

    // Logテーブルの初期化
    $stmt = $pdo->prepare("DELETE FROM Log WHERE room_ID = ?");
    $stmt->execute([$room_id]);

    $pdo->commit();

    echo json_encode(['status' => 'success', 'redirect' => "../G2-1/G2-1.php?room_id=$room_id"]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
?>
