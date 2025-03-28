<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['user_id'];
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : null;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    if (empty($comment)) {
        echo "コメントを入力してください。";
        exit;
    }

    if (empty($post_id)) {
        echo "投稿IDが指定されていません。";
        exit;
    }

    // 投稿が存在するか確認
    $sql_check_post = "SELECT user_id FROM post WHERE post_id = ?";
    $stmt_check_post = $pdo->prepare($sql_check_post);
    $stmt_check_post->bindParam(1, $post_id, PDO::PARAM_INT);
    $stmt_check_post->execute();
    $post_owner_id = $stmt_check_post->fetchColumn();

    if (!$post_owner_id) {
        echo "無効な投稿IDです。";
        exit;
    }

    // コメントを`t_rireki`テーブルに追加
    $sql = "INSERT INTO t_rireki (user_id, post_id, t_post, t_post_time) VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $post_id, PDO::PARAM_INT);
    $stmt->bindParam(3, $comment, PDO::PARAM_STR);

    try {
        $stmt->execute();

        // コメントしたユーザーの名前を取得
        $sql_user_name = "SELECT user_name FROM user WHERE user_id = ?";
        $stmt_user_name = $pdo->prepare($sql_user_name);
        $stmt_user_name->execute([$user_id]);
        $sender_name = $stmt_user_name->fetchColumn();

        // 通知メッセージを作成
        $notification_message = "{$sender_name} さんがコメントしました: {$comment}";

        // 新しい通知を追加
        $sql_notification = "INSERT INTO notifications (user_id, sender_id, message, post_id, created_at, is_read) VALUES (?, ?, ?, ?, NOW(), 0)";
        $stmt_notification = $pdo->prepare($sql_notification);
        $stmt_notification->execute([$post_owner_id, $user_id, $notification_message, $post_id]);

        echo "<script>
            if (Notification.permission === 'granted') {
                new Notification('新しい通知', {
                    body: '{$notification_message}',
                    icon: 'icon.png'
                });
            } else {
                Notification.requestPermission().then(function(permission) {
                    if (permission === 'granted') {
                        new Notification('新しい通知', {
                            body: '{$notification_message}',
                            icon: 'icon.png'
                        });
                    }
                });
            }
            window.location.href = 'index.php';
        </script>";
        exit;
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
        exit;
    }
}
?>
