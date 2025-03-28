<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// デバッグメッセージを表示
//echo "<p>side.php内のpost_id: " . (isset($_SESSION['post_id']) ? htmlspecialchars($_SESSION['post_id'], ENT_QUOTES, 'UTF-8') : '未設定') . "</p>";
?>
<!-- サイドバーのHTMLコード -->
<a href="main.php?post_id=1">全体チャット</a>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name=”viewport” content=”width=device-width, initial-scale=1.0″>
    <title>サイドバー</title>
    <style>
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    .sidebar {
        background-color: #0068b7;
        padding: 10px;
        width: 150px;
        border-right: 1px solid #ddd;
        color: white;
        position: fixed;
        top: 60px;
        /* ヘッダーの高さに合わせる */
        bottom: 0;
        overflow-y: auto;
        /* 縦方向のスクロールを有効にする */
        /* スクロールバーを非表示にするスタイル */
        scrollbar-width: none;
        /* Firefox */
        -ms-overflow-style: none;
        /* Internet Explorer 10+ */
    }

    .sidebar::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Opera */
    }

    .sidebar h3 {
        margin-top: 0;
    }

    .sidebar a {
        display: block;
        text-decoration: none;
        color: white;
        margin-bottom: 10px;
    }

    .sidebar a:hover {
        text-decoration: underline;
    }

    .category,
    .thread {
        margin-bottom: 20px;
    }

    .category-list,
    .thread-list {
        margin-left: 10px;
    }

    .show-more {
        cursor: pointer;
        color: #ffcc00;
        display: block;
        margin: 5px 0;
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var categories = document.querySelectorAll('.category-list a');
        var threads = document.querySelectorAll('.thread-list a');

        function toggleVisibility(items, button) {
            for (var i = 6; i < items.length; i++) {
                items[i].style.display = 'none';
            }
            button.addEventListener('click', function() {
                for (var i = 6; i < items.length; i++) {
                    items[i].style.display = (items[i].style.display === 'none') ? 'block' : 'none';
                }
                button.textContent = button.textContent === 'もっと見る' ? '折りたたむ' : 'もっと見る';
            });
        }

        if (categories.length > 6) {
            var categoryButton = document.createElement('span');
            categoryButton.textContent = 'もっと見る';
            categoryButton.className = 'show-more';
            var categoryList = document.querySelector('.category-list');
            categoryList.appendChild(categoryButton);
            toggleVisibility(categories, categoryButton);
        }

        if (threads.length > 6) {
            var threadButton = document.createElement('span');
            threadButton.textContent = 'もっと見る';
            threadButton.className = 'show-more';
            var threadList = document.querySelector('.thread-list');
            threadList.appendChild(threadButton);
            toggleVisibility(threads, threadButton);
        }

        // スレッドリンクをクリックしたときにpost_idを送信する
        threads.forEach(function(thread) {
            thread.addEventListener('click', function(event) {
                event.preventDefault();
                var postId = this.getAttribute('data-post-id');
                window.location.href = 'index.php?post_id=' + postId;
            });
        });
    });
    </script>
</head>


<body>
    <div class="sidebar">
        <h3><a href="home.php?post_id=1">全体チャット</a></h3>
        <div class="thread">
            <h3><a href="allpost.php">スレッド</a></h3>
            <div class="thread-list">
                <?php
                require_once 'db-connect.php';

                $thread_sql = "SELECT * FROM post";
                $thread_stmt = $pdo->query($thread_sql);
                if ($thread_stmt === FALSE) {
                    echo "Error: " . $pdo->$error;
                } else {
                    while ($thread_row = $thread_stmt->fetch(PDO::FETCH_ASSOC)) {
                        $post_id = htmlspecialchars($thread_row['post_id']);
                        $post_title = htmlspecialchars($thread_row['post_title']);
                        echo "<a href='post.php?post_id={$post_id}' data-post-id='{$post_id}'>{$post_title}</a>";
                    }
                }
                ?>
            </div>
        </div>

        <div class="category">
            <h3><a href="Allcategory.php">カテゴリ</a></h3>
            <div class="category-list">
                <?php
                require_once 'db-connect.php';

                $sql = "SELECT * FROM category";
                $result = $pdo->query($sql);
                if ($result === FALSE) {
                    echo "Error: " . $pdo->$error;
                } else {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $cate_id = htmlspecialchars($row['cate_id']);
                        $cate_name = htmlspecialchars($row['cate_name']);
                        echo "<a href='category.php?cate_id={$cate_id}'>{$cate_name}</a>";
                    }
                }
                ?>
            </div>
        </div>




        <h3>掲示板作成</h3>
        <a href="build.php">作成</a>
        <a href="buildkai.php">使い方</a>
        <h3></h3>
    </div>
</body>

</html>
