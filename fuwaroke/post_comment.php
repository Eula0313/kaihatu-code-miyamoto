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

try {
    $pdo->beginTransaction();

    // commentsテーブルに追加
    $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->execute();

    // t_rirekiテーブルに追加
    $sql = "INSERT INTO t_rireki (post_id, user_id, t_post) VALUES (:post_id, :user_id, :content)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->execute();

    $pdo->commit();
    header("Location: index.php");
    exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    echo 'エラー: ' . $e->getMessage();
}
?>
