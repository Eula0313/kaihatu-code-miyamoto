<?php
session_start();
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "image/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("UPDATE user SET icon = ? WHERE user_id = ?");
            $stmt->execute([$target_file, $_SESSION['user']['user_id']]);
            $_SESSION['user']['icon'] = $target_file;

            echo json_encode(['status' => 'success', 'url' => $target_file]);
            exit();
        }
    }
    echo json_encode(['status' => 'error']);
}
?>
