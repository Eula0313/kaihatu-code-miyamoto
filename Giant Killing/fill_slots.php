<?php
require 'db-connect.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'ログインが必要です']);
    exit;
}

try {
    // レア薬の購入状況を確認
    $sql = "SELECT rare_drug_purchased FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $rareDrugPurchased = $user['rare_drug_purchased'] ?? 0;

    // 条件に応じてキャラクターを取得
    if ($rareDrugPurchased) {
        $sql = "SELECT name, rarity, character_image, point FROM characters WHERE rarity BETWEEN 1 AND 5 ORDER BY RAND() LIMIT 28";
    } else {
        $sql = "SELECT name, rarity, character_image, point FROM characters WHERE rarity IN (1, 2) ORDER BY RAND() LIMIT 28";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $characters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 必要に応じてキャラクターを補充
    $totalCharacters = count($characters);
    if ($totalCharacters < 28) {
        while (count($characters) < 28) {
            $characters[] = $characters[array_rand($characters)];
        }
    }

    echo json_encode(['status' => 'success', 'characters' => $characters]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
