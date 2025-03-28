<?php
session_start();

if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db-connect.php';

if (isset($_GET['post_id'])) {
    $_SESSION['post_id'] = (int)$_GET['post_id'];
}

$post_id = isset($_SESSION['post_id']) ? (int)$_SESSION['post_id'] : '';

$sql_post_name = "SELECT post_title FROM post WHERE post_id = ?";
$stmt_post_name = $pdo->prepare($sql_post_name);
$stmt_post_name->execute([$post_id]);
$post_row = $stmt_post_name->fetch(PDO::FETCH_ASSOC);
$post_name = $post_row ? $post_row['post_title'] : '不明';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>掲示板</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="image/favicon.ico">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .content {
            display: flex;
            flex-grow: 1;
            margin-top: 60px;
            overflow: hidden;
        }
        .sidebar, .right-sidebar {
            width: 20%;
            padding: 20px;
            background-color: #e0e0e0;
            box-sizing: border-box;
            position: fixed;
            top: 60px;
            bottom: 0;
            overflow-y: auto;
        }
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            margin-left: 200px;
            margin-right: 200px;
        }
        .chat-container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            padding: 10px;
            overflow-y: auto;
            background-color: #fff;
            margin-top: 90px;
            margin-bottom: 80px;
            border-radius: 4px;
            height: calc(100vh - 200px);
            box-sizing: border-box;
        }
        .chat-input {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            border-top: 1px solid #ddd;
            background-color: #ffffff;
            position: fixed;
            bottom: 10px;
            left: 210px;
            right: 210px;
            box-sizing: border-box;
            border-radius: 30px;
            flex-direction: column;
        }
        .chat-input textarea {
            flex-grow: 1;
            padding: 10px;
            border: none;
            border-radius: 20px;
            resize: none;
            box-sizing: border-box;
            min-height: 40px;
            margin-right: 10px;
            background-color: #fff;
            color: #333;
            outline: none;
            font-size: 16px;
            width: calc(100% - 20px);
        }
        .chat-input button {
            padding: 10px 20px;
            border: none;
            background-color: #0068b7;
            color: white;
            border-radius: 20px;
            cursor: pointer;
            box-sizing: border-box;
            white-space: nowrap;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            margin-top: 10px;
        }
        .chat-input button:hover {
            background-color: #005fa3;
        }
        .chat-input button:active {
            background-color: #00508a;
        }
        .board-title {
            z-index: 1;
            position: fixed;
            top: 60px;
            left: 200px;
            right: 200px;
            background-color: #fff;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            box-sizing: border-box;
        }
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #ddd;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .popup input, .popup textarea {
            display: block;
            width: 100%;
            margin: 10px 0;
        }
        .popup button {
            display: block;
            margin-top: 10px;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .balloon_l, .balloon_r {
            display: flex;
            align-items: flex-start;
            margin: 10px 0;
        }
        .balloon_r {
            justify-content: flex-end;
        }
        .icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 10px;
        }
        .says {
            max-width: 700px;
            display: flex;
            flex-wrap: wrap;
            position: relative;
            padding: 17px 13px 15px 18px;
            border-radius: 12px;
            background: #f5fbff;
            box-sizing: border-box;
            margin: 0;
            line-height: 1.5;
        }
        .says p {
            margin: 8px 0 0;
        }
        .says p:first-child {
            margin-top: 0;
        }
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        .user-info img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .user-info p {
            margin: 0;
        }
        .post-time {
            color: gray;
            font-size: 12px;
            margin: 0;
            display: inline;
        }
        .comment-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
            width: 100%;
        }
        .reply-button {
            font-size: 12px;
            background: none;
            border: none;
            color: #0068b7;
            cursor: pointer;
            padding: 0;
            background-color: #f5fbff; /* コメントの背景色と同じに */
            border-radius: 5px;
            display: inline;
            margin-left: 10px;
        }
        .reply-button:hover {
            text-decoration: underline;
        }
        /* 追加: リプライ用のスタイル */
        .reply-form {
            margin-top: 10px;
        }
        .reply {
            background: #d4f7d4;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .reply .user-info {
            display: flex;
            align-items: center;
        }
        .reply .user-info img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .reply .says {
            margin-left: 40px;
        }
        .reply-indicator {
            display: none;
            padding: 10px 20px;
            background-color: #f1f1f1;
            border-radius: 20px;
            width: calc(100% - 40px);
            position: relative;
        }
        .reply-indicator p {
            margin: 0;
            padding-right: 20px;
        }
        .reply-indicator .cancel-reply {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: red;
        }
        blockquote {
            background: #fff; /* 背景色を白に設定 */
            border-left: 10px solid #b0c4de;
            margin: 0;
            padding: 10px;
            width: 100%;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var chatContainer = document.querySelector('.chat-container');
            var previousHeight = chatContainer.scrollHeight;
            var commentInput = document.querySelector('.chat-input textarea');
            var replyIndicator = document.querySelector('.reply-indicator');
            var isFocused = false;
            var replyToCommentId = null;

            commentInput.addEventListener('focus', function() {
                isFocused = true;
            });

            commentInput.addEventListener('blur', function() {
                isFocused = false;
            });

            function fetchMessages(initialLoad = false) {
                if (!isFocused) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', 'chat.php', true);
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            var newContent = xhr.responseText;
                            if (chatContainer.innerHTML !== newContent) {
                                chatContainer.innerHTML = newContent;
                                if (initialLoad || chatContainer.scrollTop + chatContainer.clientHeight >= previousHeight) {
                                    chatContainer.scrollTop = chatContainer.scrollHeight;
                                }
                            }
                            previousHeight = chatContainer.scrollHeight;
                        }
                    };
                    xhr.send();
                }
            }

            function saveCommentInput() {
                return commentInput.value;
            }

            function restoreCommentInput(value) {
                commentInput.value = value;
            }

            fetchMessages(true);

            setInterval(function() {
                var currentComment = saveCommentInput();
                fetchMessages();
                restoreCommentInput(currentComment);
            }, 3000);

            window.toggleReplyForm = function(replyId, content, parentTable) {
    document.querySelector('input[name="parent_comment_id"]').value = replyId;
    document.querySelector('input[name="parent_table"]').value = parentTable;
    document.querySelector('.reply-indicator').style.display = 'block';
    document.querySelector('.reply-indicator p').textContent = content;
};



            window.cancelReply = function() {
                replyToCommentId = null;
                replyIndicator.style.display = 'none';
                replyIndicator.querySelector('p').textContent = '';
                document.querySelector('input[name="parent_comment_id"]').value = '';
                document.querySelector('input[name="parent_table"]').value = 't_rireki';
            };

            var url = new URL(window.location.href);
            var hash = url.hash;
            if (hash && document.querySelector(hash)) {
                setTimeout(function() {
                    document.querySelector(hash).scrollIntoView({ behavior: 'smooth' });
                }, 500);
            }
        });
    </script>
</head>
<body>
    <?php require 'header.php'; ?>
    <div class="content">
        <?php require 'side.php'; ?>
        <div class="main-content">
            <div class="board-title">
                <h2><?php echo htmlspecialchars($post_name, ENT_QUOTES, 'UTF-8'); ?>掲示板</h2>
            </div>
            <div id="overlay" class="overlay" onclick="hidePopup()"></div>
            <div class="chat-container">
                <?php
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
            </div>
            <div class="chat-input">
                <div class="reply-indicator">
                    <p></p>
                    <span class="cancel-reply" onclick="cancelReply()">×</span>
                </div>
                <form action="reply.php" method="POST" style="display: flex; width: 100%;">
                    <input type="hidden" id="post_id" name="post_id" value="<?php echo htmlspecialchars($post_id, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="parent_comment_id" value="">
                    <input type="hidden" name="parent_table" value="t_rireki">
                    <textarea name="comment" placeholder="コメントを入力してください" style="flex-grow: 1; margin-right: 10px;"></textarea>
                    <button type="submit">投稿</button>
                </form>
            </div>
        </div>
        <div class="right-sidebar" id="right-sidebar">
            <?php include 'right.php'; ?>
        </div>
    </div>
    <script>
        function fetchRightSidebar() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'right.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('right-sidebar').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        document.addEventListener("DOMContentLoaded", function() {
            fetchRightSidebar();

            setInterval(function() {
                fetchRightSidebar();
            }, 3000);
        });
    </script>
</body>
</html>
