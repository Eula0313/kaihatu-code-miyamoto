<?php
// データベース接続
require 'db-connect.php';

session_start();

// **戦闘データのリセット**（毎回実行）
unset($_SESSION['battle_data']);

// 敵のキャラクター生成
$random_rarity = rand(1, 5); // ランダムなレアリティ

// 敵チームのキャラクターを取得
$enemy_sql = "
    SELECT c.character_id, c.name, c.character_image, c.HP, c.speed, c.attack_type, c.rarity
    FROM characters c
    WHERE c.rarity = :rarity
    ORDER BY RAND()
    LIMIT 3
";
$enemy_stmt = $pdo->prepare($enemy_sql);
$enemy_stmt->execute(['rarity' => $random_rarity]);
$enemy_characters = $enemy_stmt->fetchAll(PDO::FETCH_ASSOC);

// プレイヤーチームのキャラクターを取得
$self_sql = "
    SELECT c.character_id, c.name, c.character_image, c.HP AS HP_max, c.speed, c.attack_type, c.rarity
    FROM party p
    JOIN characters c ON p.character_id = c.character_id
    WHERE p.user_id = :user_id
    ORDER BY p.position ASC
";

$self_stmt = $pdo->prepare($self_sql);
$self_stmt->execute(['user_id' => $_SESSION['user_id'] ?? 1]);
$self_characters = $self_stmt->fetchAll(PDO::FETCH_ASSOC);

// データチェック
if (empty($self_characters)) {
    die("プレイヤーキャラクターが見つかりません。編成画面でキャラクターを選択してください。");
}

if (empty($enemy_characters)) {
    die("敵キャラクターが見つかりません。データベースを確認してください。");
}

// プレイヤーキャラクターにスキルを割り当て
foreach ($self_characters as &$character) {
    $skill_query = $pdo->prepare("SELECT * FROM skills WHERE type = :type");
    $skill_query->execute(['type' => $character['attack_type']]);
    $character['skills'] = $skill_query->fetchAll(PDO::FETCH_ASSOC);
}

// 敵キャラクターにスキルを割り当て
foreach ($enemy_characters as &$character) {
    $skill_query = $pdo->prepare("SELECT * FROM skills WHERE type = :type");
    $skill_query->execute(['type' => $character['attack_type']]);
    $character['skills'] = $skill_query->fetchAll(PDO::FETCH_ASSOC);
}

// プレイヤーチームと敵チームの初期HP設定
foreach ($self_characters as &$character) {
    $character['HP'] = $character['HP_max'];
}
foreach ($enemy_characters as &$character) {
    $character['HP_max'] = $character['HP']; // 敵のHP_maxを初期化
}

// 戦闘データの初期化
$_SESSION['battle_data'] = [
    'player_team' => $self_characters,
    'enemy_team' => $enemy_characters,
    'player_front' => 0,
    'enemy_front' => 0,
    'turn_order' => array_merge($self_characters, $enemy_characters),
    'current_turn' => 0,
    'logs' => [], // バトルログを初期化
];

// 行動順をスピードでソート
usort($_SESSION['battle_data']['turn_order'], function ($a, $b) {
    return $b['speed'] - $a['speed'];
});

$user_name = '自分のチーム'; // デフォルト値
if (isset($_SESSION['user_id'])) {
    $user_sql = "SELECT user_name FROM users WHERE user_id = :user_id";
    $user_stmt = $pdo->prepare($user_sql);
    $user_stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user_result = $user_stmt->fetch(PDO::FETCH_ASSOC);
    if ($user_result) {
        $user_name = htmlspecialchars($user_result['user_name']);
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>敵情報画面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #2c3e50;
            color: #ecf0f1;
            margin: 0;
            padding: 0;
        }

        h1 {
            margin: 2px 0;
            text-align: center;
            color: #f1c40f;
        }

        .battle-container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            gap: 20px;
        }

        .team {
            background: #34495e;
            border-radius: 10px;
            padding: 15px;
            width: 45%;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);
            overflow-y: auto;
        }

        .team h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: #f1c40f;
            text-align: center;
        }

        .character-box {
            display: flex;
            align-items: flex-start;
            background: #2c3e50;
            border-radius: 10px;
            padding: 10px;
            margin: 10px 0;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .character-box img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            margin-right: 15px;
            border: 2px solid #ecf0f1;
        }

        .character-box:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        .character-stats {
            text-align: left;
            flex: 1;
        }

        .character-stats .top-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .character-stats .bottom-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .character-stats p {
            margin: 5px 0;
            font-size: 14px;
        }

        .skill-list {
            margin-top: 10px;
            background: #2c3e50;
            border-radius: 5px;
            padding: 10px;
            display: none;
        }

        .button-container {
            text-align: center;
            margin: 20px 0;
        }

        .button {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 5px 150px;
            font-size: 1em;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background: #c0392b;
            transform: scale(1.05);
        }
    </style>
    <script>
        function toggleSkills(skillId) {
            const skillList = document.getElementById(skillId);
            skillList.style.display = skillList.style.display === 'none' || skillList.style.display === '' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <h1>戦闘前画面</h1>
<div class="battle-container">
    <!-- プレイヤーチーム -->
    <div class="team">
        <h3><?= "{$user_name}のチーム" ?></h3>
        <?php foreach ($self_characters as $index => $character): ?>
            <div class="character-box">
                <img src="<?= htmlspecialchars($character['character_image']) ?>" alt="<?= htmlspecialchars($character['name']) ?>">
                <div class="character-stats">
                    <div class="top-row">
                        <p><strong><?= htmlspecialchars($character['name']) ?></strong></p>
                        <p>レア度: <?= htmlspecialchars($character['rarity']) ?></p>
                        <p>タイプ: <?= htmlspecialchars($character['attack_type']) ?></p>
                    </div>
                    <div class="bottom-row">
                        <p>HP: <?= htmlspecialchars($character['HP']) ?></p>
                        <p>Speed: <?= htmlspecialchars($character['speed']) ?></p>
                    </div>
                    <button class="button" onclick="toggleSkills('player-skills-<?= $index ?>')">特技を見る</button>
                    <div id="player-skills-<?= $index ?>" class="skill-list">
                        <h4>特技一覧</h4>
                        <ul>
                            <?php foreach ($character['skills'] as $skill): ?>
                                <li><?= htmlspecialchars($skill['name']) ?>: <?= htmlspecialchars($skill['effect']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- 敵チーム -->
    <div class="team">
        <h3><?= "レア度☆{$random_rarity}の敵チーム" ?></h3>
        <?php foreach ($enemy_characters as $index => $character): ?>
            <div class="character-box">
                <img src="<?= htmlspecialchars($character['character_image']) ?>" alt="☆<?= htmlspecialchars($character['rarity']) ?>の敵">
                <div class="character-stats">
                    <div class="top-row">
                        <p><strong><?= htmlspecialchars($character['name']) ?></strong></p>
                        <p>レア度: <?= htmlspecialchars($character['rarity']) ?></p>
                        <p>タイプ: <?= htmlspecialchars($character['attack_type']) ?></p>
                    </div>
                    <div class="bottom-row">
                        <p>HP: <?= htmlspecialchars($character['HP']) ?></p>
                        <p>Speed: <?= htmlspecialchars($character['speed']) ?></p>
                    </div>
                    <button class="button" onclick="toggleSkills('enemy-skills-<?= $index ?>')">特技を見る</button>
                    <div id="enemy-skills-<?= $index ?>" class="skill-list">
                        <h4>特技一覧</h4>
                        <ul>
                            <?php foreach ($character['skills'] as $skill): ?>
                                <li><?= htmlspecialchars($skill['name']) ?>: <?= htmlspecialchars($skill['effect']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="button-container">
    <form method="POST" action="battle.php">
        <button class="button" type="submit">ゲーム開始</button>
    </form>
    <form method="GET" action="start.php">
        <button class="button" type="button" onclick="window.location.href='start.php'">タイトルへ</button>
    </form>
</div>

<iframe src="btlbgm_player.php" style="display:none;" id="bgm-frame"></iframe>
</body>
</html>
