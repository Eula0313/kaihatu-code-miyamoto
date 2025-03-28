<?php
// db-connect.php を読み込む
require_once 'db-connect.php';

// 現在のワールドを取得するクエリ
$sql = "SELECT current_world FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$current_world = $stmt->fetchColumn();

// 現在のワールドに応じた URL を設定
$backUrl = 'login.php'; // デフォルトは top.php
if ($current_world === 'SD3E') {
    $backUrl = 'SD3E_top.php';
} elseif ($current_world === 'disney') {
    $backUrl = 'disney_top.php';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ヒューマン・ハーベスト</title>
    <style>
        /* ページ全体のスタイル */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* 背景画像の設定とアニメーション */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 110%;
            height: 110%;
            background-image: url('image/forest_background.jpg');
            background-size: cover;
            background-position: center;
            animation: moveBackground 30s linear infinite;
            transform: scale(1.1);
            z-index: -1;
        }

        @keyframes moveBackground {
            0% { transform: scale(1.1) translateX(0); }
            50% { transform: scale(1.1) translateX(-5%); }
            100% { transform: scale(1.1) translateX(0); }
        }

        /* コンテナのスタイル */
        .container {
            position: relative;
            z-index: 1;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 40px;
            border-radius: 10px;
            max-width: 400px;
        }

        /* タイトルのスタイル */
        h1 {
            font-size: 3rem;
            color: #d4af37;
            margin-bottom: 20px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8);
        }

        /* ボタンスタイル */
        .button {
            display: block;
            margin: 15px auto;
            padding: 12px 30px;
            font-size: 1.2rem;
            color: #fff;
            background-color: #5c3a1e;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        .button:hover {
            background-color: #8b4513;
            transform: scale(1.1);
        }
    </style>
    <?php include 'header.php'; ?>
</head>
<body>
<iframe src="bgm_player.php" style="display:none;" id="bgm-frame"></iframe>
    <div class="background"></div>

    <div class="container">
        <h1>Human Harvest</h1>
        <!-- Strat ボタンで指定したワールドに遷移 -->
        <a href="<?= htmlspecialchars($backUrl) ?>" class="button">スタート</a>
        <a href="Newregistration.php" class="button">新規登録</a>
    </div>
</body>
</html>
