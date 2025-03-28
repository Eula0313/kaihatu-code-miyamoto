<?php
require_once '../db-connect.php';

$post_id = $_GET['board_id'];

$sql = "
    SELECT t.id, t.user_id, t.post_id, t.t_post AS content, t.t_post_time AS post_time, u.user_name, u.icon, t.reply_id
    FROM t_rireki t
    INNER JOIN user u ON t.user_id = u.user_id
    WHERE t.post_id = ?
    ORDER BY t.t_post_time ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$post_id]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getParentComment($pdo, $reply_id)
{
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
    $icon = htmlspecialchars("../" . $row['icon'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8');
    $post_time = htmlspecialchars($row['post_time'], ENT_QUOTES, 'UTF-8');
    $reply_id = $row['reply_id'];

    echo "<div id='comment-{$row['id']}' class='user-info-main'>";
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
    echo "<button class='delete-button' onclick='deleteMessage({$row['id']}, " . ($reply_id ? "true" : "false") . ")'>削除</button>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}
?>
<script>
    function confirmDeleteBoard() {
        if (confirm('本当にこの掲示板を削除しますか？')) {
            window.location.href = 'board_delete.php?board_id=<?php echo $post_id; ?>';
        }
    }

    function deleteMessage(postId, isReply) {
        if (confirm('本当にこのメッセージを削除しますか？')) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'message_delete.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    if (xhr.responseText == 'success') {
                        var messageElement = document.getElementById('comment-' + postId);
                        if (messageElement) {
                            messageElement.parentNode.removeChild(messageElement);
                        }
                        alert('メッセージが削除されました。');
                    } else {
                        alert('メッセージの削除に失敗しました。');
                    }
                }
            };
            xhr.send('message_id=' + postId + '&is_reply=' + isReply);
        }
    }
</script>
