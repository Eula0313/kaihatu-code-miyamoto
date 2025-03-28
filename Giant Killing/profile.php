<?php
session_start();
require 'db-connect.php';

if (!isset($_SESSION['user_id'])) {
    echo 'ユーザーがログインしていません。';
    exit;
}

$user_id = $_SESSION['user_id'];

// ユーザー名を取得
$sql_user = "SELECT user_name FROM users WHERE user_id = ?";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

$user_name = $user ? $user['user_name'] : 'Unknown User';

// `users` テーブルから現在のプロフィールアイコンを取得
$sql_icon = "SELECT icon FROM users WHERE user_id = ?";
$stmt_icon = $pdo->prepare($sql_icon);
$stmt_icon->execute([$user_id]);
$icon = $stmt_icon->fetch(PDO::FETCH_ASSOC);

// デフォルトのアイコン画像
$default_icon = 'image/☆１シンプル南.png';

// iconが空またはnullの場合はデフォルト画像を使用
$character_image = !empty($icon['icon']) ? $icon['icon'] : $default_icon;


// 収穫情報を取得
$sql_harvest = "SELECT COUNT(DISTINCT character_id) AS discovered_characters, COUNT(*) AS total_harvest FROM harvest_log WHERE user_id = ?";
$stmt_harvest = $pdo->prepare($sql_harvest);
$stmt_harvest->execute([$user_id]);
$harvest = $stmt_harvest->fetch(PDO::FETCH_ASSOC);

$discovered_characters = $harvest['discovered_characters'] ?? 0;
$total_harvest = $harvest['total_harvest'] ?? 0;

// `zukan` テーブルから重複しないキャラクター情報を取得
$sql_characters = "SELECT DISTINCT character_id, character_image FROM zukan WHERE user_id = ?";
$stmt_characters = $pdo->prepare($sql_characters);
$stmt_characters->execute([$user_id]);
$characters = $stmt_characters->fetchAll(PDO::FETCH_ASSOC);


// 現在のワールドを取得
$sql = "SELECT current_world FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$current_world = $stmt->fetchColumn();

// 現在のワールドに応じた戻る URL を設定
$backUrl = 'top.php'; // デフォルトは top.php
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
    <title>Ningen License Card</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #d6d6d6;
            background-image: url('image/aig-ai221017149-xl_TP_V.png');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card-container {
            background-color: rgba(194, 165, 98, 0.9);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            width: 700px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }

        .card-container:hover {
            transform: translateY(-10px);
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(135deg, #8b5e34, #a6713d);
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            text-decoration: none;
            transition: background-color 0.3s;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .back-button:hover {
            background-color: #a6713d;
        }

        .icon-container {
            position: relative;
            display: inline-block;
        }

        .icon-image {
    border-radius: 50%;
    width: 200px; /* 100px → 200px */
    height: 200px; /* 100px → 200px */
    object-fit: cover;
    transition: transform 0.3s ease-in-out;
}


        .icon-image:hover {
            transform: scale(1.1);
        }

        .info {
            margin: 10px 0;
            font-size: 1.4rem;
            color: #fff;
            letter-spacing: 1px;
            font-weight: bold;
        }

        .harvest-info {
            margin-top: 15px;
            background: linear-gradient(135deg, #8b5e34, #a6713d);
            padding: 10px;
            border-radius: 5px;
            color: #fff;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            font-size: 1.1rem;
        }

        .character-grid {
            display: none;
            grid-template-columns: repeat(7, 1fr); /* 横7列に変更 */
            gap: 10px; /* カードの間隔を少し広げる */
            margin-top: 20px;
            position: absolute; /* 親要素に合わせて表示 */
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(255, 255, 255, 0.9); /* 背景の透明度を調整 */
            border-radius: 10px;
            padding: 10px;
            z-index: 10; /* 他の要素より前面に */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* ボックスに影を追加 */
        }

        .character-grid img {
            width: 60px; /* アイコンサイズを調整 */
            height: 60px; /* アイコンサイズを調整 */
            object-fit: cover; /* アイコンのアスペクト比を保つ */
            border-radius: 5px;
            transition: transform 0.3s ease-in-out;
            cursor: pointer;
        }


        .icon-container:hover .character-grid {
            display: grid;
        }

        

        .character-grid img:hover {
            transform: scale(1.1);
        }
    </style>
    <iframe src="bgm_player.php" style="display:none;" id="bgm-frame"></iframe>
    <script>
        function changeIcon(newIcon) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_icon.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // アイコンを更新
                    document.querySelector('.icon-image').src = newIcon;
                }
            };
            xhr.send('new_icon=' + encodeURIComponent(newIcon));
        }

        // グリッドをマウスオーバーで表示
        document.addEventListener('DOMContentLoaded', () => {
            const iconContainer = document.querySelector('.icon-container');
            const characterGrid = document.querySelector('.character-grid');

            let hideTimeout;

            iconContainer.addEventListener('mouseenter', () => {
                characterGrid.style.display = 'grid';
            });

            iconContainer.addEventListener('mouseleave', () => {
                hideTimeout = setTimeout(() => {
                    characterGrid.style.display = 'none';
                }, 300);
            });

            characterGrid.addEventListener('mouseenter', () => {
                clearTimeout(hideTimeout);
            });

            characterGrid.addEventListener('mouseleave', () => {
                characterGrid.style.display = 'none';
            });
        });
     
    </script>
</head>
<body>

<a href="<?= htmlspecialchars($backUrl) ?>" class="back-button">← 戻る</a>
<form action="logout.php" method="post" style="position: absolute; top: 20px; right: 20px;">
    <button type="submit" style="background: linear-gradient(135deg, #8b5e34, #a6713d); color: #fff; padding: 10px 20px; border-radius: 5px; border: none; cursor: pointer; font-size: 1rem;">
        ログアウト
    </button>
</form>

    <div class="card-container">
        <h2>Ningen License Card</h2>

        <div class="icon-container">
            <img src="<?= htmlspecialchars($character_image) ?>" alt="User Icon" class="icon-image">
            <div class="character-grid">
                <?php foreach ($characters as $character): ?>
                    <img src="<?= htmlspecialchars($character['character_image']) ?>" alt="Character Image" onclick="changeIcon('<?= htmlspecialchars($character['character_image']) ?>')">
                <?php endforeach; ?>
            </div>
        </div>

        <div class="info"><?= htmlspecialchars($user_name) ?></div>

        <div class="harvest-info">
            <div>発見した人間: <?= htmlspecialchars($discovered_characters) ?>人</div>
            <div>収穫総数: <?= htmlspecialchars($total_harvest) ?>人</div>
        </div>
    </div>
    <iframe src="bgm.html" style="display:none;" id="bgm-frame"></iframe>
</body>
</html>
