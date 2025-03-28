<?php
echo '<style>
body {
   font-family: Arial, sans-serif;
   margin: 0;
   padding: 0;
   height: 100%;
   display: flex;
   flex-direction: column;
}
.main-content {
   flex-grow: 1;
   display: flex;
   flex-direction: column;
   align-items: center;
   padding: 20px;
   margin-left: 100px;
}
.chat-container {
   flex-grow: 1;
   width: 100%;
   display: flex;
   flex-direction: column;
   margin-top: 20px;
}
.post-history-scroll {
   flex-grow: 1;
   width: 100%;
   overflow-y: auto;
   padding: 10px;
}
.chat-input {
   display: flex;
   padding: 10px;
   border-top: 1px solid #ddd;
   background-color: #fff;
}
.chat-input textarea {
   flex-grow: 1;
   padding: 10px;
   border: 1px solid #ddd;
   border-radius: 4px;
   resize: none;
}
.chat-input button {
   padding: 10px 20px;
   margin-left: 10px;
   border: none;
   background-color: #0068b7;
   color: white;
   border-radius: 4px;
   cursor: pointer;
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
   margin: 5px 0;
   line-height: 1.5;
}
.says p {
   margin: 8px 0 0;
}
.says p:first-child {
   margin-top: 0;
}
.user-info-main {
   margin: 30px 0;
}
.user-info {
   display: flex;
   align-items: center;
   margin: 10px 0;
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
}
.delete-button {
   margin-top: 20px;
   padding: 10px 20px;
   border: none;
   background-color: #ff4c4c;
   color: white;
   border-radius: 4px;
   cursor: pointer;
}
.message-delete-button {
   margin: 10px;
   padding: 10px 20px;
   border: none;
   background-color: #ff4c4c;
   color: white;
   border-radius: 4px;
   cursor: pointer;
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
</style>';

$post_id = $_GET['board_id'];

require_once '../db-connect.php';
require_once 'admin_header.php';
require_once 'admin_side.php';

$sql_post_name = "SELECT post_title FROM post WHERE post_id = ?";
$stmt_post_name = $pdo->prepare($sql_post_name);
$stmt_post_name->bindParam(1, $post_id, PDO::PARAM_INT);
$stmt_post_name->execute();
$post_row = $stmt_post_name->fetch(PDO::FETCH_ASSOC);
$post_name = $post_row ? $post_row['post_title'] : '不明';
?>
<div class="content">
   <div class="sidebar">
      <?php require "admin_side.php"; ?>
   </div>
   <div class="main-content">
      <h1><?php echo htmlspecialchars($post_name, ENT_QUOTES, 'UTF-8'); ?></h1>
      <button class="delete-button" onclick="confirmDeleteBoard()">掲示板を削除</button>
      <div class="chat-container">
         <div class="post-history-scroll">
            <?php require 'admin_chat.php' ?>
         </div>
      </div>
   </div>
</div>
<script>
   let target = document.getElementById('content');
   target.scrollIntoView(false);
</script>
