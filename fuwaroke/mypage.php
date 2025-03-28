<?php
session_start();
require 'header.php';
require 'db-connect.php';

$pdo->query('SET NAMES utf8mb4');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_image'])) {
    $target_dir = "image/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        $stmt = $pdo->prepare("UPDATE user SET icon = ? WHERE user_id = ?");
        $stmt->execute([$target_file, $_SESSION['user']['user_id']]);
        $_SESSION['user']['icon'] = $target_file;
    }
}

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : (isset($_SESSION['user']['user_id']) ? (int)$_SESSION['user']['user_id'] : 0);

if ($user_id == 0) {
    echo '<h2 class="error-message">ユーザーの情報が見つかりませんでした。</h2>';
    exit();
}

$sql = $pdo->prepare('
    SELECT user.*, school_category.school_name
    FROM user
    JOIN school_category ON user.school_id = school_category.school_id
    WHERE user.user_id = ?');
$sql->execute([$user_id]);
$row = $sql->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo '<h2 class="error-message">ユーザーの情報が見つかりませんでした。</h2>';
    exit();
}

$icon = htmlspecialchars($row['icon'] ?? '', ENT_QUOTES, 'UTF-8');
$user_name = htmlspecialchars($row['user_name'] ?? '', ENT_QUOTES, 'UTF-8');
$school_name = htmlspecialchars($row['school_name'] ?? '', ENT_QUOTES, 'UTF-8');
$introduction = htmlspecialchars($row['introduction'] ?? '', ENT_QUOTES, 'UTF-8');
$portfolio = htmlspecialchars($row['portfolio'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="image/favicon.ico">
    <title>マイページ</title>
    <link rel="stylesheet" href="css/mypage.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 14px;
            font-family: 'Montserrat', sans-serif;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            margin: 10px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .toggle-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 14px;
            font-family: 'Montserrat', sans-serif;
            color: #007bff;
            background-color: transparent;
            border: 2px solid #007bff;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .toggle-btn:hover {
            background-color: #007bff;
            color: #fff;
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
    </style>
    <script>
        function togglePosts(section) {
            const morePosts = document.getElementById('more' + section);
            const moreBtn = document.getElementById('moreBtn' + section);
            if (morePosts.style.display === 'none') {
                morePosts.style.display = 'block';
                moreBtn.textContent = '閉じる';
            } else {
                morePosts.style.display = 'none';
                moreBtn.textContent = 'もっと見る';
            }
        }
    </script>
</head>

<body>
    <?php require 'side.php'; ?>
    <?php require 'right.php'; ?>
    <div class="container">
        <div class="content">
            <div class="profile">
                <h1>Profile</h1>
                <div class="photo-left">
                    <form id="profileImageForm" action="mypage.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="profile_image" id="profileImageInput" style="display: none;" onchange="document.getElementById('profileImageForm').submit();">
                        <img class="photo" src="<?php echo $icon; ?>" alt="プロフィール画像" id="profileImage" onclick="document.getElementById('profileImageInput').click();">
                    </form>
                    <br>
                    <h2 class="name"><?php echo $user_name; ?></h2>
                    <div class="desc">
                        <div class="desc-title">学校名</div>
                        <br>
                        <p class="desc-content"><?php echo $school_name; ?></p>
                        <div class="desc-title">自己紹介</div>
                        <br>
                        <p class="desc-content"><?php echo nl2br($introduction); ?></p>
                        <div class="desc-title">ポートフォリオ</div>
                        <br>
                        <p class="desc-content"><?php echo nl2br($portfolio); ?></p>
                    </div>
                    <a href="home.php" class="btn">ホームに戻る</a>
                    <a href="mypage_change.php" class="btn">プロフィールを編集</a>
                    <a href="logout.php" class="btn">ログアウト</a>
                    <div class="social">
                        <a href="https://www.facebook.com/"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
                        <a href="https://twitter.com/"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
                        <a href="https://www.instagram.com/"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                        <a href="https://www.github.com/"><i class="fa fa-github" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>

            <?php

            $post_sql = $pdo->prepare('
            SELECT post.post_id, post.post_title, user.user_name, category.cate_name, post.t_create_time
            FROM post
            JOIN user ON post.user_id = user.user_id
            JOIN category ON post.cate_id = category.cate_id
            WHERE post.user_id = ?');
            $post_sql->execute([$user_id]);
            $posts = $post_sql->fetchAll(PDO::FETCH_ASSOC);

            if ($posts) {
                echo '<div class="posts"><h3>掲示板作成履歴</h3>';
                foreach ($posts as $index => $post) {
                    $displayStyle = $index < 3 ? 'block' : 'none';
                    echo '<div class="post-item" style="display: ' . $displayStyle . ';">';
                    echo '<a href="index.php?post_id=' . htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($post['post_title'], ENT_QUOTES, 'UTF-8') . '</a>';
                    echo '<div class="post-meta">作成者: ' . htmlspecialchars($post['user_name'], ENT_QUOTES, 'UTF-8') . ' | カテゴリー: ' . htmlspecialchars($post['cate_name'], ENT_QUOTES, 'UTF-8') . ' | 作成日: ' . htmlspecialchars($post['t_create_time'], ENT_QUOTES, 'UTF-8') . '</div>';
                    echo '</div>';
                }
                if (count($posts) > 3) {
                    echo '<div id="moreposts" style="display: none;">';
                    foreach ($posts as $index => $post) {
                        if ($index >= 3) {
                            echo '<div class="post-item">';
                            echo '<a href="index.php?post_id=' . htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($post['post_title'], ENT_QUOTES, 'UTF-8') . '</a>';
                            echo '<div class="post-meta">作成者: ' . htmlspecialchars($post['user_name'], ENT_QUOTES, 'UTF-8') . ' | カテゴリー: ' . htmlspecialchars($post['cate_name'], ENT_QUOTES, 'UTF-8') . ' | 作成日: ' . htmlspecialchars($post['t_create_time'], ENT_QUOTES, 'UTF-8') . '</div>';
                            echo '</div>';
                        }
                    }
                    echo '</div>';
                    echo '<button id="moreBtnposts" class="toggle-btn" onclick="togglePosts(\'posts\')">もっと見る</button>';
                }
                echo '</div>';
            } else {
                echo '<br><p class="error-message">掲示板の作成履歴がありません</p>';
            }

            $comment_sql = $pdo->prepare('
            SELECT DISTINCT post.post_id, post.post_title, user.user_name, category.cate_name, post.t_create_time
            FROM t_rireki
            JOIN post ON t_rireki.post_id = post.post_id
            JOIN user ON post.user_id = user.user_id
            JOIN category ON post.cate_id = category.cate_id
            WHERE t_rireki.user_id = ?');
            $comment_sql->execute([$user_id]);
            $commented_posts = $comment_sql->fetchAll(PDO::FETCH_ASSOC);

            if ($commented_posts) {
                echo '<div class="posts"><h3>コメントしたことがある掲示板</h3>';
                foreach ($commented_posts as $index => $post) {
                    $displayStyle = $index < 3 ? 'block' : 'none';
                    echo '<div class="post-item" style="display: ' . $displayStyle . ';">';
                    echo '<a href="index.php?post_id=' . htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($post['post_title'], ENT_QUOTES, 'UTF-8') . '</a>';
                    echo '<div class="post-meta">投稿者: ' . htmlspecialchars($post['user_name'], ENT_QUOTES, 'UTF-8') . ' | カテゴリー: ' . htmlspecialchars($post['cate_name'], ENT_QUOTES, 'UTF-8') . ' | 投稿日: ' . htmlspecialchars($post['t_create_time'], ENT_QUOTES, 'UTF-8') . '</div>';
                    echo '</div>';
                }
                if (count($commented_posts) > 3) {
                    echo '<div id="morecomments" style="display: none;">';
                    foreach ($commented_posts as $index => $post) {
                        if ($index >= 3) {
                            echo '<div class="post-item">';
                            echo '<a href="index.php?post_id=' . htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($post['post_title'], ENT_QUOTES, 'UTF-8') . '</a>';
                            echo '<div class="post-meta">投稿者: ' . htmlspecialchars($post['user_name'], ENT_QUOTES, 'UTF-8') . ' | カテゴリー: ' . htmlspecialchars($post['cate_name'], ENT_QUOTES, 'UTF-8') . ' | 投稿日: ' . htmlspecialchars($post['t_create_time'], ENT_QUOTES, 'UTF-8') . '</div>';
                            echo '</div>';
                        }
                    }
                    echo '</div>';
                    echo '<button id="moreBtncomments" class="toggle-btn" onclick="togglePosts(\'comments\')">もっと見る</button>';
                }
                echo '</div>';
            } else {
                echo '<br><p class="error-message">コメントしたことがある掲示板はありません</p><br>';
            }
            ?>
        </div>
    </div>
</body>

</html>
