<?php
require 'db-connect.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'] ?? null;

if (!$user_id) {
    http_response_code(400);
    echo json_encode(['error' => 'ユーザーIDが指定されていません']);
    exit;
}

$current_date = date('Y-m-d');

try {
    $sql = "SELECT views FROM ad_views WHERE user_id = :user_id AND date = :current_date";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':current_date', $current_date, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $sql = "UPDATE ad_views SET views = views + 1 WHERE user_id = :user_id AND date = :current_date";
    } else {
        $sql = "INSERT INTO ad_views (user_id, date, views) VALUES (:user_id, :current_date, 1)";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':current_date', $current_date, PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'データベースエラー: ' . $e->getMessage()]);
}
