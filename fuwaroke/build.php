<?php
session_start();
ob_start(); // 出力バッファリングを開始する

require_once 'db-connect.php';

// ログインしているユーザーIDを取得（ログインしていない場合はnull）
$logged_in_user_id = isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : null;

// ログインしていない場合はエラーメッセージを表示して終了
if (!$logged_in_user_id) {
    echo "<p>ログインしていません。</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="image/favicon.ico">
    <title>掲示板作成</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .main-content {
        display: flex;
        flex: 1;
        margin-top: 20px;
        /* Add margin to avoid overlap */
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

    .content {
        width: 60%;
        padding: 20px;
        box-sizing: border-box;
        /* Ensures padding doesn't add to width */
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-top: 70px;
        /* ヘッダーの高さ */
        margin-bottom: 30px;
        margin-left: 261px;
        /* サイドバーの幅 */
        margin-right: 200px;
        /* サイドバーの幅 */
    }

    form {
        margin: 20px 0;
    }

    input[type="text"],
    textarea,
    select {
        width: 90%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        background-color: #0068b7;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }
    </style>
    <script>
    function toggleCategoryInput() {
        var existingCategory = document.getElementById('existing_category');
        var newCategory = document.getElementById('new_category');
        var isNewCategory = document.getElementById('is_new_category').checked;

        existingCategory.disabled = isNewCategory;
        newCategory.disabled = !isNewCategory;
    }

    function fetchRightSidebar() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'right.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.querySelector('.right-sidebar').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}



fetchRightSidebar();

setInterval(function() {
    var currentComment = saveCommentInput();
    fetchMessages();
    restoreCommentInput(currentComment);
    fetchRightSidebar();
}, 3000);

    </script>
</head>

<body>
    <?php require_once 'header.php'; ?>

    <div class="main-content">
        <div class="sidebar">
            <?php require_once 'side.php'; ?>
        </div>

        <div class="content">
            <h2>掲示板作成</h2>
            <form action="" method="POST">
                <label for="post_name">タイトル</label>
                <input type="text" id="post_name" name="post_name" required>
                <br>
                <label>カテゴリー</label>
                <div>
                    <input type="radio" id="existing_category_option" name="cate_option" value="existing" checked
                        onclick="toggleCategoryInput()">
                    <label for="existing_category_option">既存のカテゴリを選択</label>
                </div>
                <select id="existing_category" name="existing_cate_id">
                    <?php
                    // 既存のカテゴリを取得してオプションを生成
                    $stmt = $pdo->query("SELECT cate_id, cate_name FROM category");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . $row['cate_id'] . '">' . $row['cate_name'] . '</option>';
                    }
                    ?>
                </select>

                <div>
                    <input type="radio" id="is_new_category" name="cate_option" value="new"
                        onclick="toggleCategoryInput()">
                    <label for="is_new_category">新しいカテゴリを入力</label>
                </div>
                <input type="text" id="new_category" name="new_cate_name" disabled><br>

                <label for="t_post">コメント</label>
                <br>
                <textarea id="t_post" name="t_post" maxlength="1000" required></textarea>

                <button type="submit">作成</button>
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                try {
                    // フォームからのデータを取得
                    $post_name = $_POST['post_name'];
                    $t_post = $_POST['t_post'];
                    $cate_option = $_POST['cate_option'];

                    // 新しいカテゴリを処理
                    if ($cate_option === 'new') {
                        $new_cate_name = $_POST['new_cate_name'];

                        // 新しいカテゴリ名が既に存在するかチェック
                        $stmt = $pdo->prepare("SELECT cate_id FROM category WHERE cate_name = :cate_name");
                        $stmt->execute(['cate_name' => $new_cate_name]);
                        $existing_category = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($existing_category) {
                            // 既存のカテゴリが見つかった場合、そのIDを使用
                            $cate_id = $existing_category['cate_id'];
                        } else {
                            // 既存のカテゴリが見つからない場合、新しいカテゴリを追加
                            $stmt = $pdo->prepare("INSERT INTO category (cate_name) VALUES (:cate_name)");
                            $stmt->execute(['cate_name' => $new_cate_name]);
                            $cate_id = $pdo->lastInsertId();
                        }
                    } else {
                        $cate_id = $_POST['existing_cate_id'];
                    }

                    // 掲示板を作成
                    $stmt = $pdo->prepare("INSERT INTO post (post_title, cate_id, user_id, t_create_time) VALUES (:post_title, :cate_id, :user_id, NOW())");
                    $stmt->execute(['post_title' => $post_name, 'cate_id' => $cate_id, 'user_id' => $logged_in_user_id]);
                    $post_id = $pdo->lastInsertId();

                    // 一言を t_rireki に保存
                    $stmt = $pdo->prepare("INSERT INTO t_rireki (t_post, post_id, user_id) VALUES (:t_post, :post_id, :user_id)");
                    $stmt->execute(['t_post' => $t_post, 'post_id' => $post_id, 'user_id' => $logged_in_user_id]);

                    // 掲示板作成後に作成した掲示板のページにリダイレクト
                    header("Location: index.php?post_id=$post_id");
                    ob_end_flush(); // バッファリングを終了して出力
                    exit(); // スクリプトの実行を終了
                } catch (PDOException $e) {
                    echo "<p>データベースに接続できません: " . $e->getMessage() . "</p>";
                    error_log($e->getMessage()); // エラーメッセージをログに出力
                }
            }
            ?>
        </div>

        <div class="right-sidebar">
            <?php require 'right.php'; ?>
        </div>
    </div>
</body>

</html>
