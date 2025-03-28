<?php
// db-connect.php の読み込みとセッションの開始
require 'db-connect.php';
session_start();

// ログインしているユーザーのIDを取得
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php'); // 未ログインの場合はログインページへリダイレクト
    exit;
}

// 今日の日付を取得
$current_date = date('Y-m-d');

// ユーザーの当日の広告視聴回数を取得
$sql = "SELECT views FROM ad_views WHERE user_id = :user_id AND date = :current_date";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':current_date', $current_date, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$views_today = $result['views'] ?? 0; // 視聴回数がなければ0
$ad_watch_limit = 10; // 1日の視聴上限回数
$remaining_views = $ad_watch_limit - $views_today; // 残り視聴可能回数

// 広告が表示可能かを判定
$canWatchAd = $views_today < $ad_watch_limit;

// 現在のワールドを取得
$sql = "SELECT current_world FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$current_world = $stmt->fetchColumn();

// 戻り先URLを設定
$back_link = 'top.php'; // デフォルトはトップページ
if ($current_world === 'SD3E') {
    $back_link = 'SD3E_top.php';
} elseif ($current_world === 'disney') {
    $back_link = 'disney_top.php';
}

// バナー広告消去権の状態を確認
$sql = "SELECT level FROM items WHERE user_id = :user_id AND item_name = '広告消去権'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// 広告が表示可能かを判定
$hasAdRemoval = $item && $item['level'] > 0; // 「レベルが1以上の場合は購入済み」とする
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>広告視聴</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('image/kusokora.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }
        #ad-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.7); /* 半透明の背景色 */
            border: 2px solid black;
            text-align: center;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px; /* 丸みを追加 */
        }
        #ad-popup p {
            margin-bottom: 20px;
        }
        .popup-button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px; /* ボタンを丸みを帯びた形に */
        }
        .popup-button.yes {
            background-color: lightgreen;
            border: 1px solid green;
        }
        .popup-button.no {
            background-color: lightcoral;
            border: 1px solid red;
        }
    </style>
</head>
<body>

<?php if ($hasAdRemoval): ?>
    <!-- 広告消去権がある場合 -->
    <div id="ad-popup">
        <p>これ以上広告を再生できません。</p>
        <button onclick="closeAdPopup()">閉じる</button>
    </div>
<?php elseif (!$canWatchAd): ?>
    <!-- 視聴回数制限に達した場合 -->
    <div id="ad-popup">
        <p>本日の広告視聴回数の上限に達しました。</p>
        <p>また明日お試しください。</p>
        <button onclick="closeAdPopup()">閉じる</button>
    </div>
<?php else: ?>
    <!-- 広告表示 -->
    <div id="ad-popup">
        <p>広告を閲覧すると人間が生えてきます。</p>
        <p>今日の視聴可能回数: <?= $remaining_views ?> 回</p>
        <p>※一日に１０回まで視聴できます</p>
        <p>視聴しますか？</p>
        <button class="popup-button yes" onclick="redirectToAd()">はい</button>
        <button class="popup-button no" onclick="closeAdPopup()">いいえ</button>
    </div>
<?php endif; ?>
<iframe src="bgm_player.php" style="display:none;" id="bgm-frame"></iframe>
<script>
    function redirectToAd() {
        // 広告視聴処理の記録（Ajaxなどでサーバーに送信してもOK）
        fetch('record_ad_view.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ user_id: <?php echo $user_id; ?> })
        }).then(() => {
            window.location.href = 'MiyamotoOp.php'; // 広告ページに移動
        });
    }

    function closeAdPopup() {
        window.location.href = '<?php echo htmlspecialchars($back_link); ?>'; // 現在のワールドに応じて戻る
    }
</script>

</body>
</html>
