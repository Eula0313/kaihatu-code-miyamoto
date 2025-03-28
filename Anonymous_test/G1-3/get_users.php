<?php
require '../db-connect.php';

$room_id = $_GET['room_id'] ?? '';

try {
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT user_name, team_ID, role_ID FROM User WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $teamNames = [1 => '赤チーム', 2 => '青チーム'];
    $roleNames = [1 => 'オペレーター', 2 => 'アストロノーツ'];

    foreach ($users as $user) {
        if ($user['team_ID'] !== null && $user['role_ID'] !== null) {
            echo "<p>{$teamNames[$user['team_ID']]} - {$roleNames[$user['role_ID']]} - {$user['user_name']}</p>";
        } else {
            echo "<p>{$user['user_name']}</p>";
        }
    }
} catch (PDOException $e) {
    echo 'エラー: ' . $e->getMessage();
}
?>
