<?php
require_once 'common.php';
require_once 'db-connect.php';

// フォームが送信された場合
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $user_id = $_SESSION['user']['user_id'];

    // カテゴリオプションを処理
    if ($_POST['category_option'] === 'new') {
        $new_cate_name = $_POST['new_cate_name'];

        // 新しいカテゴリ名が既に存在するかチェック
        $sql = 'SELECT cate_id FROM category WHERE cate_name = :cate_name';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cate_name', $new_cate_name, PDO::PARAM_STR);
        $stmt->execute();
        $existing_category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_category) {
            // 既存のカテゴリが見つかった場合、そのIDを使用
            $cate_id = $existing_category['cate_id'];
        } else {
            // 既存のカテゴリが見つからない場合、新しいカテゴリを追加
            $sql = 'INSERT INTO category (cate_name) VALUES (:cate_name)';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':cate_name', $new_cate_name, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $cate_id = $pdo->lastInsertId();
            } else {
                echo 'カテゴリの作成に失敗しました。';
                exit;
            }
        }
    } else {
        $cate_id = $_POST['existing_cate_id'];
    }

    // 質問をデータベースに挿入
    $sql = 'INSERT INTO question (q_title, user_id, q_create_time, cate_id) VALUES (:title, :user_id, NOW(), :cate_id)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':cate_id', $cate_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $q_id = $pdo->lastInsertId();
        header('Location: view_question.php?q_id=' . $q_id);
        exit;
    } else {
        echo '質問の投稿に失敗しました。';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="image/favicon.ico">
    <title>質問投稿</title>
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

    .sidebar,
    .right-sidebar {
        width: 20%;
        padding: 20px;
        background-color: #e0e0e0;
        box-sizing: border-box;
        position: fixed;
        top: 60px;
        /* ヘッダーの高さに合わせる */
        bottom: 0;
        overflow-y: auto;
        /* 縦方向のスクロールを有効にする */
    }

    .content {
        width: 60%;
        padding: 20px;
        box-sizing: border-box;
        /* Ensures padding doesn't add to width */
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-top: 90px;
        /* ヘッダーの高さ */
        margin-bottom: 50px;
        margin-left: 280px;
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
    <script>
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

        document.addEventListener("DOMContentLoaded", function() {
            fetchRightSidebar();

            setInterval(function() {
                fetchRightSidebar();
            }, 3000);
        });
    </script>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="main-content">
        <div class="sidebar">
            <?php include 'side.php'; ?>
        </div>

        <div class="content">
            <h2>質問投稿</h2>
            <form method="POST">
                質問タイトル: <input type="text" name="title" required>
                <br>
                <input type="radio" id="use_existing_category" name="category_option" value="existing" checked
                    onclick="toggleCategoryInput()">
                <label for="use_existing_category">既存のカテゴリを選択</label><br>
                <select id="existing_category" name="existing_cate_id">
                    <?php
                    // データベースに接続して既存のカテゴリを取得
                    $sql = 'SELECT cate_id, cate_name FROM category';
                    foreach ($pdo->query($sql) as $row) {
                        echo '<option value="' . $row['cate_id'] . '">' . $row['cate_name'] . '</option>';
                    }
                    ?>
                </select>
                <br>
                <input type="radio" id="is_new_category" name="category_option" value="new"
                    onclick="toggleCategoryInput()">
                <label for="is_new_category">新しいカテゴリを入力</label><br>
                <input type="text" id="new_category" name="new_cate_name" disabled><br>
                <button type="submit">投稿</button>
            </form>
        </div>

        <div class="right-sidebar">
            <?php include 'right.php'; ?>
        </div>
    </div>
</body>

</html>
