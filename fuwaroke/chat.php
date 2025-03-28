<?php
session_start();
require_once 'db-connect.php';

$post_id = $_SESSION['post_id'];

$sql = "
    SELECT t.id, t.user_id, t.post_id, t.t_post AS content, t.t_post_time AS post_time, u.user_name, u.icon, t.reply_id
    FROM t_rireki t
    INNER JOIN user u ON t.user_id = u.user_id
    WHERE t.post_id = ?
    ORDER BY post_time ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$post_id]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getParentComment($pdo, $reply_id) {
    $sql = "SELECT t.t_post AS content, u.user_name 
            FROM t_rireki t 
            INNER JOIN user u ON t.user_id = u.user_id 
            WHERE t.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$reply_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

foreach ($result as $row) {
    $user_id = $row['user_id'];
    $user_name = htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8');
    $icon = htmlspecialchars($row['icon'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8');
    $post_time = htmlspecialchars($row['post_time'], ENT_QUOTES, 'UTF-8');
    $reply_id = $row['reply_id'];

    $balloon_class = $user_id == $_SESSION['user']['user_id'] ? 'balloon_r' : 'balloon_l';

    echo "<div class='$balloon_class' id='comment-{$row['id']}'>";
    echo "<div class='user-info'>";
    echo "<a href='view_user.php?user_id=$user_id'><img src='$icon' alt='$user_name'></a>";
    echo "<p>$user_name</p>";
    echo "</div>";
    echo "<div class='says'>";

    if ($reply_id) {
        $parent_comment = getParentComment($pdo, $reply_id);
        if ($parent_comment) {
            $parent_text = htmlspecialchars($parent_comment['content'], ENT_QUOTES, 'UTF-8');
            $parent_user_name = htmlspecialchars($parent_comment['user_name'], ENT_QUOTES, 'UTF-8');
            echo "<blockquote><small>$parent_user_name:</small><br>$parent_text</blockquote>";
        } else {
            echo "<blockquote><small>親コメントが見つかりませんでした。</small></blockquote>";
        }
    }

    echo "<p>$content</p>";
    echo "<div class='comment-footer'>";
    echo "<span class='post-time'>$post_time</span>";
    echo "<button class='reply-button' onclick='toggleReplyForm({$row['id']}, \"$content\")'>リプライ</button>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}
?>
