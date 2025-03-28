<?php
require '../db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message_id = $_POST['message_id'];
    $is_reply = $_POST['is_reply'] === 'true';

    try {
        // トランザクションを開始
        $pdo->beginTransaction();

        if ($is_reply) {
            // リプライの削除
            $sql_delete_reply = "DELETE FROM t_rireki WHERE id = ?";
            $stmt_delete_reply = $pdo->prepare($sql_delete_reply);
            $stmt_delete_reply->execute([$message_id]);
        } else {
            // メッセージに関連するリプライの削除
            $sql_delete_replies = "DELETE FROM t_rireki WHERE reply_id = ?";
            $stmt_delete_replies = $pdo->prepare($sql_delete_replies);
            $stmt_delete_replies->execute([$message_id]);

            // メッセージの削除
            $sql_delete_message = "DELETE FROM t_rireki WHERE id = ?";
            $stmt_delete_message = $pdo->prepare($sql_delete_message);
            $stmt_delete_message->execute([$message_id]);
        }

        // トランザクションをコミット
        $pdo->commit();

        echo "success";
    } catch (Exception $e) {
        // トランザクションをロールバック
        $pdo->rollBack();
        echo "エラーが発生しました: " . $e->getMessage();
    }
}
