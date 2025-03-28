<?php
require_once 'common.php';
require_once 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $q_id = $_POST['q_id'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user']['user_id'];

    $sql = 'INSERT INTO answers (q_id, user_id, answer_content, answer_create_time) VALUES (:q_id, :user_id, :content, NOW())';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':q_id', $q_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header('Location: view_question.php?q_id=' . $q_id);
        exit;
    } else {
        echo '回答の投稿に失敗しました。';
    }
}
?>
