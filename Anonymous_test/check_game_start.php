<?php
require 'db-connect.php';

$room_id = $_GET['room_id'] ?? '';

if (empty($room_id)) {
    echo 'not started';
    exit();
}

try {
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT status FROM Room WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $status = $stmt->fetchColumn();

    if ($status === 'started') {
        echo 'started';
    } else {
        echo 'not started';
    }
} catch (PDOException $e) {
    echo 'error: ' . $e->getMessage();
}
?>
