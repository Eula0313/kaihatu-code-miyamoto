<?php
require_once '../db-connect.php';

if (isset($_GET['board_id'])) {
    $board_id = $_GET['board_id'];

    // トランザクションを開始
    $pdo->beginTransaction();

    try {
        // 掲示板に関連するメッセージとそのリプライを削除
        $sql_delete_replies = "
            DELETE t1
            FROM t_rireki t1
            WHERE t1.post_id = ? AND t1.reply_id IS NOT NULL";
        $stmt_delete_replies = $pdo->prepare($sql_delete_replies);
        $stmt_delete_replies->execute([$board_id]);

        $sql_delete_messages = "
            DELETE t1
            FROM t_rireki t1
            WHERE t1.post_id = ? AND t1.reply_id IS NULL";
        $stmt_delete_messages = $pdo->prepare($sql_delete_messages);
        $stmt_delete_messages->execute([$board_id]);

        // 掲示板の削除
        $sql_delete_board = "DELETE FROM post WHERE post_id = ?";
        $stmt_delete_board = $pdo->prepare($sql_delete_board);
        $stmt_delete_board->execute([$board_id]);

        // トランザクションをコミット
        $pdo->commit();

        echo '<script>alert("掲示板が削除されました。"); window.location.href = "board-show.php";</script>';
    } catch (PDOException $e) {
        // トランザクションをロールバック
        $pdo->rollBack();
        echo '<script>alert("削除に失敗しました: ' . $e->getMessage() . '"); window.location.href = "board-show.php";</script>';
    }
} else {
    echo '<script>alert("掲示板IDが指定されていません。"); window.location.href = "board-show.php";</script>';
}
