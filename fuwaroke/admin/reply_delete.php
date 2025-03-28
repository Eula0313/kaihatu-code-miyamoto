<?php
require '../db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reply_id = $_POST['reply_id'];

    try {
        // リプライの削除
        $sql_delete_reply = "DELETE FROM replies WHERE reply_id = ?";
        $stmt_delete_reply = $pdo->prepare($sql_delete_reply);
        $stmt_delete_reply->execute([$reply_id]);

        echo "success";
    } catch (Exception $e) {
        echo "エラーが発生しました: " . $e->getMessage();
    }
}
