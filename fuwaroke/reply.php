<?php
session_start();
require_once 'db-connect.php';

if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['user_id'];
$post_id = $_POST['post_id'];
$content = $_POST['comment'];
$parent_comment_id = isset($_POST['parent_comment_id']) ? $_POST['parent_comment_id'] : null;

try {
    $pdo->beginTransaction();

    // t_rirekiテーブルに追加
    $sql = "INSERT INTO t_rireki (post_id, user_id, t_post, reply_id) VALUES (:post_id, :user_id, :content, :parent_comment_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->bindParam(':parent_comment_id', $parent_comment_id, PDO::PARAM_INT);
    $stmt->execute();

    // 通知を生成
    if ($parent_comment_id) {
        $sql = "SELECT user_id FROM t_rireki WHERE id = :parent_comment_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':parent_comment_id', $parent_comment_id, PDO::PARAM_INT);
        $stmt->execute();
        $parent_user_id = $stmt->fetchColumn();

        if ($parent_user_id && $parent_user_id != $user_id) {
            $message = "あなたのコメントにリプライがありました: $content";
            $sql = "INSERT INTO notifications (user_id, sender_id, message, comment_id) VALUES (:user_id, :sender_id, :message, :comment_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $parent_user_id, PDO::PARAM_INT);
            $stmt->bindParam(':sender_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':message', $message, PDO::PARAM_STR);
            $stmt->bindParam(':comment_id', $parent_comment_id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    $pdo->commit();
    header("Location: index.php");
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    echo 'エラー: ' . $e->getMessage();
}
?>
