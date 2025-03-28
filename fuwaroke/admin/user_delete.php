<?php
session_start();
require '../db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user_id = $_POST['user_id'];

  try {
    // トランザクションを開始
    $pdo->beginTransaction();

    // answers テーブルから question に関連するレコードを削除
    $sql_delete_answers_by_question = "DELETE FROM answers WHERE q_id IN (SELECT q_id FROM question WHERE user_id = ?)";
    $stmt_delete_answers_by_question = $pdo->prepare($sql_delete_answers_by_question);
    $stmt_delete_answers_by_question->execute([$user_id]);

    // question テーブルからユーザーに関連するレコードを削除
    $sql_delete_questions = "DELETE FROM question WHERE user_id = ?";
    $stmt_delete_questions = $pdo->prepare($sql_delete_questions);
    $stmt_delete_questions->execute([$user_id]);

    // answers テーブルからユーザーに関連するレコードを削除
    $sql_delete_answers = "DELETE FROM answers WHERE user_id = ?";
    $stmt_delete_answers = $pdo->prepare($sql_delete_answers);
    $stmt_delete_answers->execute([$user_id]);

    // ユーザーが作成した投稿を削除
    $sql_delete_posts = "DELETE FROM post WHERE user_id = ?";
    $stmt_delete_posts = $pdo->prepare($sql_delete_posts);
    $stmt_delete_posts->execute([$user_id]);

    // notifications テーブルからユーザーに関連するレコードを削除
    $sql_delete_notifications = "DELETE FROM notifications WHERE user_id = ? OR sender_id = ?";
    $stmt_delete_notifications = $pdo->prepare($sql_delete_notifications);
    $stmt_delete_notifications->execute([$user_id, $user_id]);

    // ユーザー情報を削除するSQLクエリ
    $sql_delete_user = "DELETE FROM user WHERE user_id = ?";
    $stmt_delete_user = $pdo->prepare($sql_delete_user);
    $stmt_delete_user->execute([$user_id]);

    // トランザクションをコミット
    $pdo->commit();

    // セッション情報をクリア
    session_destroy();

    echo '<script>
            alert("ユーザーが削除されました。");
            window.location.href = "customer-show.php";
        </script>';
    exit();
  } catch (Exception $e) {
    // トランザクションをロールバック
    $pdo->rollBack();
    echo '<script>
            alert("削除に失敗しました: ' . $e->getMessage() . '");
            window.location.href = "customer-show.php";
        </script>';
    exit();
  }
}
