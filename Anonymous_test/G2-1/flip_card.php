<?php
session_start();
require '../db-connect.php';

$card_id = $_POST['card_id'] ?? '';
$room_id = $_POST['room_id'] ?? '';

if (empty($card_id) || empty($room_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Card ID or Room ID is missing']);
    exit();
}

try {
    $pdo = connectDB();
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT state_ID FROM Board WHERE room_ID = ? AND board_ID = ?");
    $stmt->execute([$room_id, $card_id]);
    $card_state = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($card_state['state_ID'] == 1) {
        echo json_encode(['status' => 'error', 'message' => 'このカードはすでにめくられています。']);
        exit();
    }

    // カードの状態を更新
    $stmt = $pdo->prepare("UPDATE Board SET state_ID = 1 WHERE room_ID = ? AND board_ID = ?");
    $stmt->execute([$room_id, $card_id]);

    // デバッグログ
    error_log("Card with ID $card_id flipped in room $room_id");

    // Room テーブルの last_update を更新
    $stmt = $pdo->prepare("UPDATE Room SET last_update = CURRENT_TIMESTAMP WHERE room_ID = ?");
    $stmt->execute([$room_id]);

    $stmt = $pdo->prepare("SELECT color FROM Board WHERE room_ID = ? AND board_ID = ?");
    $stmt->execute([$room_id, $card_id]);
    $card = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$card) {
        throw new Exception('Card not found');
    }

    $card_color = $card['color'];

    // 勝利条件のチェック
    $stmt = $pdo->prepare("SELECT color, COUNT(*) as count FROM Board WHERE room_ID = ? AND state_ID = 2 GROUP BY color");
    $stmt->execute([$room_id]);
    $color_counts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $counts = [
        'red' => 0,
        'blue' => 0,
        'black' => 0,
        'white' => 0
    ];

    foreach ($color_counts as $count) {
        $counts[$count['color']] = $count['count'];
    }

    $stmt = $pdo->prepare("SELECT current_team FROM GameState WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $current_state = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_team = $current_state['current_team'];

    if ($counts['red'] == 0) {
        // 赤チームの勝利
        $stmt = $pdo->prepare("UPDATE GameState SET status = 'win', winner_team = 1 WHERE room_ID = ?");
        $stmt->execute([$room_id]);
        $pdo->commit();
        echo json_encode(['status' => 'win', 'message' => '赤チームの勝ち！']);
        exit();
    } elseif ($counts['blue'] == 0) {
        // 青チームの勝利
        $stmt = $pdo->prepare("UPDATE GameState SET status = 'win', winner_team = 2 WHERE room_ID = ?");
        $stmt->execute([$room_id]);
        $pdo->commit();
        echo json_encode(['status' => 'win', 'message' => '青チームの勝ち！']);
        exit();
    } elseif ($card_color == 'black') {
        // 黒カードを引いた場合
        $winning_team = ($current_team == 1) ? 2 : 1;
        $stmt = $pdo->prepare("UPDATE GameState SET status = 'win', winner_team = ? WHERE room_ID = ?");
        $stmt->execute([$winning_team, $room_id]);
        $pdo->commit();
        echo json_encode(['status' => 'win', 'message' => ($winning_team == 1 ? '赤' : '青') . 'チームの勝ち！']);
        exit();
    }

    // 次のターンの設定
    $stmt = $pdo->prepare("SELECT current_team, current_role, hint_count FROM GameState WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $current_state = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$current_state) {
        throw new Exception('Game state not found');
    }

    $current_team = $current_state['current_team'];
    $current_role = $current_state['current_role'];
    $hint_count = $current_state['hint_count'];

    if (($current_team == 1 && $card_color != 'red') || ($current_team == 2 && $card_color != 'blue')) {
        $next_role = 1;
        $next_team = ($current_team == 1) ? 2 : 1;
    } else {
        $hint_count--;
        if ($hint_count < 0) {  // ここを修正して、-1まで許容する
            $next_role = 1;
            $next_team = ($current_team == 1) ? 2 : 1;
        } else {
            $next_role = 2;
            $next_team = $current_team;
        }
    }

    // GameStateテーブルを更新
    $stmt = $pdo->prepare("UPDATE GameState SET current_team = ?, current_role = ?, hint_count = ? WHERE room_ID = ?");
    $stmt->execute([$next_team, $next_role, $hint_count, $room_id]);

    $pdo->commit();
    echo json_encode(['status' => 'success', 'reload' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
?>
