<?php
require '../db-connect.php';

$room_id = $_GET['room_id'] ?? '';

if (empty($room_id)) {
    echo json_encode(['allRolesSelected' => false, 'roles' => []]);
    exit();
}

try {
    $pdo = connectDB();
    $stmt = $pdo->prepare("SELECT team_ID, role_ID FROM User WHERE room_ID = ?");
    $stmt->execute([$room_id]);
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 全員が役割を選択したかどうかをチェック
    $allRolesSelected = count(array_filter($roles, fn($u) => $u['team_ID'] !== null && $u['role_ID'] !== null)) === 4;

    echo json_encode(['allRolesSelected' => $allRolesSelected, 'roles' => $roles]);
} catch (PDOException $e) {
    echo json_encode(['allRolesSelected' => false, 'roles' => [], 'error' => $e->getMessage()]);
}
?>
