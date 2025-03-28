<?php
session_start();
require 'header.php';
require 'db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_image'])) {
    $target_dir = "image/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        $stmt = $pdo->prepare("UPDATE user SET icon = ? WHERE user_id = ?");
        $stmt->execute([$target_file, $_SESSION['user']['user_id']]);
        $_SESSION['user']['icon'] = $target_file;
    }
}

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

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
    <link rel="icon" href="image/favicon.ico">
    <meta charset="UTF-8">
    <title>ユーザーページ</title>
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
            margin: 40px;
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

        .desc {
            width: 500px;
        }

        .desc-content {
            word-break: break-word;
        }
    </style>
    <script>
        function togglePosts(section) {
            var moreText = document.getElementById("more" + section);
            var moreButton = document.getElementById("moreBtn" + section);

            if (moreText.style.display === "none") {
                moreText.style.display = "block";
                moreButton.textContent = "閉じる";
            } else {
                moreText.style.display = "none";
                moreButton.textContent = "もっと見る";
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
                    <form id="profileImageForm" action="view.php" method="post" enctype="multipart/form-data">

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
                    <div class="social">
                        <a href="https://www.facebook.com/"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
                        <a href="https://twitter.com/"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
                        <a href="https://www.instagram.com/"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                        <a href="https://www.github.com/"><i class="fa fa-github" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>


        </div>
    </div>
</body>

</html>
