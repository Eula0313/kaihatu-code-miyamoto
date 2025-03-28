<?php
session_start();
require 'db-connect.php'; // データベース接続用

// 勝敗の結果を取得
$result = isset($_GET['result']) ? $_GET['result'] : '';
$user_id = $_SESSION['user_id'] ?? null; // セッションでログイン中のユーザーIDを取得

// 報酬の設定（勝利：3000、敗北：1000）
$reward = 0;
if ($result === 'win') {
    $reward = 100;
} elseif ($result === 'lose') {
    $reward = 5;
}

// moneyを更新する処理
if ($user_id !== null && $reward > 0) {
    try {
        // 現在のmoneyを取得
        $stmt = $pdo->prepare("SELECT money FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $current_money = $stmt->fetchColumn();

        if ($current_money !== false) {
            // moneyを更新
            $new_money = $current_money + $reward;
            $stmt = $pdo->prepare("UPDATE users SET money = ? WHERE user_id = ?");
            $stmt->execute([$new_money, $user_id]);
        }
    } catch (PDOException $e) {
        echo "エラーが発生しました: " . $e->getMessage();
        exit;
    }
}

// 現在のワールドを取得
$current_world = 'default_world'; // デフォルト値
if ($user_id) {
    try {
        $stmt = $pdo->prepare("SELECT current_world FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $current_world = $stmt->fetchColumn() ?: 'default_world';
    } catch (PDOException $e) {
        echo "エラーが発生しました: " . $e->getMessage();
        exit;
    }
}

// 戻り先URLを設定
$back_link = 'top.php'; // デフォルトはトップページ
if ($current_world === 'SD3E') {
    $back_link = 'SD3E_top.php';
} elseif ($current_world === 'disney') {
    $back_link = 'disney_top.php';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>リザルト画面</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #2c3e50;
            color: #ecf0f1;
        }
        .result-container {
            text-align: center;
            background: #34495e;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .result-container h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .result-container p {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }
        .result-container button {
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            background-color: #e74c3c;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .result-container button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="result-container">
        <h1>
            <?php if ($result === 'win'): ?>
                勝利！
            <?php elseif ($result === 'lose'): ?>
                敗北…
            <?php else: ?>
                結果不明
            <?php endif; ?>
        </h1>
        <p>
            <?php if ($result === 'win' || $result === 'lose'): ?>
                報酬: <?= $reward ?>c 獲得！<br>
                現在の所持金: <?= isset($new_money) ? $new_money : 'エラー' ?>c
            <?php else: ?>
                結果が取得できませんでした。
            <?php endif; ?>
        </p>
        <button onclick="window.location.href='start.php'">ヒューマンバトルへ</button>
        <button id="home-button">ホームに戻る</button>
    </div>
    <iframe src="btlbgm_player.php" style="display:none;" id="bgm-frame"></iframe>
    <script>
        // PHPから戻り先リンクをJavaScriptに渡す
        const backLink = <?php echo json_encode($back_link); ?>;

        // ホームボタンのクリックイベント
        document.getElementById('home-button').onclick = function() {
            window.location.href = backLink; // 現在のワールドにリダイレクト
        };
    </script>
</body>
</html>
