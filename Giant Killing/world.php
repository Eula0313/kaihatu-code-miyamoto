<?php
require 'db-connect.php';
session_start();

// ログインユーザー情報取得
$user_id = $_SESSION['user_id'];

// ワールドの選択処理
if (isset($_GET['world'])) {
    $selected_world = $_GET['world'];

    // 現在のワールドを更新
    $sql = "UPDATE users SET current_world = :selected_world WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':selected_world', $selected_world, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // ワールドに応じたページにリダイレクト
    if ($selected_world === 'SD3E') {
        header('Location: SD3E_top.php');
        exit;
    } elseif ($selected_world === 'disney') {
        header('Location: disney_top.php');
        exit;
    } elseif ($selected_world === 'default') {
        header('Location: top.php');
        exit;
    }
}

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

// ユーザーが開放したワールドを取得
$sql = "SELECT world_type FROM world WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$unlocked_worlds = $stmt->fetchAll(PDO::FETCH_COLUMN);

// ワールド情報を設定
$worlds = [
    'default' => ['name' => 'デフォルトワールド', 'image' => 'image/harry.png'],
    'SD3E' => ['name' => 'SD3E ワールド', 'image' => 'image/SD3E.png'],
    'disney' => ['name' => 'ディズニー ワールド', 'image' => 'image/ディズニー.png']
];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ワールド選択</title>
    <style>
       body {
    font-family: 'Press Start 2P', sans-serif; /* レトロゲーム風フォント */
    background: url('image/warp.png') no-repeat center center fixed; /* ゲーム風背景画像 */
    background-size: cover;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #fff;
    height: 100vh; /* 画面全体をカバー */
}

h1 {
    font-size: 3rem;
    margin-bottom: 40px;
    color: #ffcc00; /* ゴールド風の文字色 */
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7), 0 0 20px #ffcc00, 0 0 30px #ffcc00; /* 光るテキスト */
}

.container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* 自動調整のグリッド */
    gap: 20px;
    padding: 20px;
    max-width: 900px;
    border: 3px solid #444;
    border-radius: 15px;
    background: rgba(0, 0, 0, 0.7); /* 半透明の黒背景 */
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.8); /* ボックスシャドウ */
}

.world-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    background: linear-gradient(145deg, #3a3a3a, #1a1a1a); /* 立体的な背景色 */
    border-radius: 15px;
    padding: 20px;
    border: 2px solid #555;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.6);
    text-decoration: none;
    color: #fff;
}

.world-option img {
    width: 80px;
    height: 80px;
    margin-bottom: 10px;
    border: 3px solid #444;
    border-radius: 50%;
    transition: transform 0.3s ease, border-color 0.3s ease;
}

.world-option:hover {
    transform: translateY(-10px) scale(1.1);
    box-shadow: 0 10px 20px rgba(255, 255, 255, 0.8);
    background: linear-gradient(145deg, #555, #333);
}

.world-option:hover img {
    transform: rotate(360deg); /* 回転アニメーション */
    border-color: #ffcc00; /* ゴールドの縁 */
}

.world-option.current-world {
    background: linear-gradient(145deg, #ffcc00, #ffaa00);
    box-shadow: 0 0 20px #ffcc00, 0 0 40px #ffaa00;
}

.disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.back-button {
    position: absolute;
    top: 20px;
    left: 20px;
    background: linear-gradient(135deg, #8b5e34, #a6713d);
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.back-button:hover {
    background-color: #e64a19;
    transform: translateY(-5px);
}

    </style>
</head>
<body>

    <button class="back-button" onclick="window.location.href='<?= htmlspecialchars($backUrl) ?>'">← 戻る</button>

    <h1>ワールド選択</h1>

    <div class="container">
        <?php foreach ($worlds as $type => $world): ?>
            <?php if (in_array($type, $unlocked_worlds) || $type === 'default'): ?>
                <a href="world.php?world=<?= htmlspecialchars($type) ?>" class="world-option <?= $type === $current_world ? 'current-world' : '' ?>">
                    <span><?= htmlspecialchars($world['name']) ?></span>
                    <img src="<?= htmlspecialchars($world['image']) ?>" alt="<?= htmlspecialchars($world['name']) ?>">
                </a>
            <?php else: ?>
                <div class="world-option disabled">
                    <span><?= htmlspecialchars($world['name']) ?></span>
                    <img src="<?= htmlspecialchars($world['image']) ?>" alt="<?= htmlspecialchars($world['name']) ?>">
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <iframe src="bgm_player.php" style="display:none;" id="bgm-frame"></iframe>
</body>
</html>
