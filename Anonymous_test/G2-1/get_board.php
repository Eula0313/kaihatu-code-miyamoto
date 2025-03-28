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
    $stmt = $pdo->prepare("SELECT * FROM Board WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $board = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 色の枚数カウント
    $stmt = $pdo->prepare("SELECT color, COUNT(*) as count FROM Board WHERE room_ID = ? AND state_ID = 2 GROUP BY color");
    $stmt->execute([$room_id]);
    $color_counts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $counts = [
        'red' => 9,
        'blue' => 8,
        'black' => 1,
        'white' => 7
    ];

    foreach ($color_counts as $count) {
        $counts[$count['color']] = $count['count'];
    }

    $red_count = $counts['red'];
    $blue_count = $counts['blue'];

    $is_astronaut = ($_SESSION['role_id'] == 2);

    $board_html = '';
    $cards_per_row = 5;
    $total_cards = count($board);

    function getRandomImage($color) {
        $images = [
            'red' => ['red.webp'],
            'blue' => ['blue.webp'],
            'black' => ['black.webp'],
            'white' => ['white.webp']
        ];
        $imageList = $images[$color] ?? ['default.webp'];
        return $imageList[array_rand($imageList)];
    }

    for ($i = 0; $i < $total_cards; $i += $cards_per_row) {
        $board_html .= '<div class="row">';
        for ($j = 0; $j < $cards_per_row; $j++) {
            if ($i + $j < $total_cards) {
                $card = $board[$i + $j];
                if ($card['state_ID'] == 1) {
                    $background_image = getRandomImage($card['color']);
                    $board_html .= '<div class="card" data-card-id="' . $card['board_ID'] . '" style="background-image: url(../img/' . $background_image . ');"></div>';
                } else {
                    $background_color = $is_astronaut ? 'gray' : $card['color'];
                    $board_html .= '<div class="card" data-card-id="' . $card['board_ID'] . '" style="background-color: ' . $background_color . ';">' . $card['card_name'] . '</div>';
                }
            }
        }
        $board_html .= '</div>';
    }

    echo json_encode([
        'status' => 'success',
        'board' => $board_html,
        'red_count' => $red_count,
        'blue_count' => $blue_count
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'データベースエラー: ' . $e->getMessage()]);
}
?>
