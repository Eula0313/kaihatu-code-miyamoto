<?php 
session_start();
require 'db-connect.php'; // db-connect.php を正しく利用

// ログインしているか確認
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// zukan テーブルと harvest_log テーブルを結合して収穫状態を確認
$sql = "SELECT zukan.entry_id, zukan.character_id, zukan.character_image, characters.name,
               (SELECT COUNT(*) FROM harvest_log WHERE harvest_log.character_id = zukan.character_id AND harvest_log.user_id = :user_id) AS harvested
        FROM zukan 
        JOIN characters ON zukan.character_id = characters.character_id
        WHERE zukan.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 現在のワールドを取得
$sql_world = "SELECT current_world FROM users WHERE user_id = :user_id";
$stmt_world = $pdo->prepare($sql_world);
$stmt_world->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_world->execute();
$current_world = $stmt_world->fetchColumn();

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
    <title>図鑑</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f0f0;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 20px auto;
            padding: 20px;
            width: 90%;
            max-width: 1200px;
            background-color: #a37934;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #fff;
        }

        .grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .card {
            background-color: white;
            margin: 15px;
            padding: 20px;
            border-radius: 10px;
            width: 250px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            flex-basis: calc(33.333% - 30px);
            box-sizing: border-box;
            position: relative;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 10px;
            background-color: #f4f4f4;
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            font-size: 18px;
            margin: 10px 0;
            color: #333;
        }

        .back-button {
            position: absolute;
            top: 40px;
            left: 60px;
            background: linear-gradient(135deg, #8b5e34, #a6713d);
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            text-decoration: none;
            transition: background-color 0.3s;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .card {
                flex-basis: calc(50% - 30px); /* タブレット用 */
            }
        }

        @media (max-width: 480px) {
            .card {
                flex-basis: calc(100% - 30px); /* モバイル用 */
            }
        }
    </style>
</head>
<body>

<a href="<?= htmlspecialchars($backUrl) ?>" class="back-button">← 戻る</a>

<div class="container">
    <h1>図鑑</h1>
    <div class="grid">
        <?php
        if (!empty($result)) {
            foreach ($result as $row) {
                // 収穫記録がない場合にはhatena.pngを使用
                $characterImage = $row['harvested'] > 0 ? $row['character_image'] : 'image/hatena.png';
                echo '<div class="card">';
                echo '<a href="character_detail.php?entry_id=' . htmlspecialchars($row['entry_id']) . '">';
                echo '<img src="' . htmlspecialchars($characterImage) . '" alt="' . htmlspecialchars($row['character_id']) . '">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo "<p>キャラクターが見つかりません</p>";
        }
        ?>
    </div>
</div>
<iframe src="bgm_player.php" style="display:none;" id="bgm-frame"></iframe>
</body>
</html>
