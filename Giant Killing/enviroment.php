<?php
ob_start();  // 出力バッファリングを開始
require 'db-connect.php';
session_start();

// ログインユーザー情報取得
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo 'ログインが必要です。';
    exit;
}

// ユーザー情報の取得
$sql = "SELECT money, life_support_purchased, life_support_active, growth_speed FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$totalMoney = $user['money'] ?? 0;
$lifeSupportPurchased = $user['life_support_purchased'] ?? 0; // 購入状態
$lifeSupportActive = $user['life_support_active'] ?? 0;       // ON/OFF状態
$growthSpeed = $user['growth_speed'] ?? 5;

// セッションに保存
$_SESSION['total_money'] = $totalMoney;
$_SESSION['life_support_purchased'] = $lifeSupportPurchased;
$_SESSION['life_support_active'] = $lifeSupportActive;
$_SESSION['growth_speed'] = $growthSpeed;

// アイテム情報を取得
$sql = "SELECT item_id, item_name, price, effect, item_image, level FROM items WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// エラーメッセージをアイテムごとに保持
$errorMessages = [];

// ON/OFF切り替え処理
if (isset($_POST['toggle_life_support'])) {
    $newStatus = $_POST['newStatus'] ?? 0; // POSTデータから新しいステータスを取得
    $sql = "UPDATE users SET life_support_active = :newStatus WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['life_support_active'] = $newStatus; // セッションを更新
        echo json_encode(['status' => 'success', 'newStatus' => $newStatus]);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit;
}

// 購入処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];

    // 選択したアイテム情報を取得
    $sql = "SELECT price, effect, level, item_name FROM items WHERE item_id = :item_id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item && $totalMoney >= $item['price'] && $item['level'] == 0) {
        // 所持金を更新
        $newMoney = $totalMoney - $item['price'];
        $sql = "UPDATE users SET money = :newMoney WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newMoney', $newMoney, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // アイテムのレベルを1（購入済み）に更新
        $sql = "UPDATE items SET level = 1 WHERE item_id = :item_id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($item['item_name'] === 'レア薬') {
            $sql = "UPDATE users SET rare_drug_purchased = 1 WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
            // SQL実行とエラー確認
            if ($stmt->execute()) {
                $_SESSION['rare_drug_purchased'] = true; // セッションにも反映
                error_log("Rare drug purchase updated in DB for user_id: {$user_id}");
            } else {
                error_log("Failed to update rare drug purchase for user_id: {$user_id}");
                error_log(print_r($stmt->errorInfo(), true)); // エラー情報をログ出力
            }
        }
        

        // 生命維持装置購入処理
        if ($item['item_name'] === '生命維持装置') {
            $sql = "UPDATE users SET life_support_purchased = 1, life_support_active = 0 WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['life_support_purchased'] = true;
            $_SESSION['life_support_active'] = false;
            $lifeSupportPurchased = true;
            $lifeSupportActive = false;
        }

        // 栽培速度UP薬の購入処理
        if ($item['item_name'] === '栽培速度UP薬') {
            $sql = "UPDATE users SET growth_speed = 2.5 WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            // セッションに新しい栽培速度を保存
            $_SESSION['growth_speed'] = 2.5;

            echo '<script>alert("栽培速度UP薬を購入しました！栽培速度が2.5秒に変更されました。");</script>';
        }

        // 特定アイテム購入時にワールドアンロック
        if ($item['item_name'] === 'SD3Eワールド' || $item['item_name'] === 'ディズニーワールド') {
            $world_type = ($item['item_name'] === 'SD3Eワールド') ? 'SD3E' : 'disney';
            
            // ユーザーがすでにこのワールドを持っているか確認
            $check_world_sql = "SELECT COUNT(*) FROM world WHERE user_id = :user_id AND world_type = :world_type";
            $check_stmt = $pdo->prepare($check_world_sql);
            $check_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $check_stmt->bindParam(':world_type', $world_type);
            $check_stmt->execute();
            $world_exists = $check_stmt->fetchColumn();

            // ワールドが未登録の場合のみ追加
            if (!$world_exists) {
                $world_sql = "INSERT INTO world (user_id, world_type) VALUES (:user_id, :world_type)";
                $world_stmt = $pdo->prepare($world_sql);
                $world_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $world_stmt->bindParam(':world_type', $world_type);
                $world_stmt->execute();
            }
        }

        // セッションを更新
        $_SESSION['total_money'] = $newMoney;
        header('Location: enviroment.php');
        exit;
    } else {
        $errorMessages[$item_id] = $item['level'] > 0 ? "このアイテムはすでに購入済みです。" : "所持金が不足しています。";
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

ob_end_flush();  // バッファリングを終了
?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>栽培環境</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #8B4513; /* 背景色を茶色に */
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .wallet-container {
                position: absolute;
                top: 20px;
                right: 20px;
                background-color: #ffcf33; /* 背景色を黄色に設定 */
                padding: 15px 25px;
                border-radius: 25px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                font-size: 1.2rem;
                font-weight: bold;
                color: #333;
                display: flex;
                align-items: center; /* テキストとアイコンを垂直方向に中央揃え */
                z-index: 10;
            }
 
                .wallet-container img {
                    width: 24px;
                    height: 24px;
                    margin-right: 8px;
                    vertical-align: middle; /* アイコンをテキストと中央揃え */
                }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 50px;
            padding: 40px;
            margin-top: 80px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .upgrade-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            border: 2px solid #b08a3c;
            border-radius: 10px;
            background-color: #fff9e0;
            width: 160px;
            text-align: center;
            transition: transform 0.3s;
        }

        .upgrade-item:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        .upgrade-item img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }

        .upgrade-item h3 {
            font-size: 1.2rem;
            color: #333;
            margin: 5px 0;
        }

        .upgrade-item p {
            margin: 5px 0;
            font-size: 0.9rem;
            color: #666;
        }

        .upgrade-button {
            margin-top: 10px;
            padding: 8px 15px;
            font-size: 0.9rem;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .upgrade-button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            font-size: 0.8rem;
            margin-top: 5px;
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
    </style>
</head>
<a href="<?= htmlspecialchars($backUrl) ?>" class="back-button">← 戻る</a>

    <div class="wallet-container">
        <img src="image/coin_kinoko.png" alt="Coin Icon"> <!-- アイコンを所持金の横に表示 -->
        <?php echo htmlspecialchars($_SESSION['total_money']); ?>c
    </div>

    <div class="container">
    <?php foreach ($items as $item): ?>
    <div class="upgrade-item">
        <img src="<?= htmlspecialchars($item['item_image']) ?>" alt="<?= htmlspecialchars($item['item_name']) ?>">
        <h3><?= htmlspecialchars($item['item_name']) ?></h3>
        <p>効果: <?= htmlspecialchars($item['effect']) ?></p>
        <p>価格: <?= htmlspecialchars($item['price']) ?>c</p>
        
        <?php if ($item['level'] > 0): ?>
            <p>このアイテムは購入済みです。</p>
            
            <!-- 生命維持装置の場合のみON/OFF切り替えボタンを表示 -->
            <?php if ($item['item_name'] === '生命維持装置' && $item['level'] > 0 && $lifeSupportPurchased): ?>
            <form id="lifeSupportToggleForm" method="POST">
                <input type="hidden" name="toggle_life_support" value="1">
                <input type="hidden" name="newStatus" id="lifeSupportNewStatus" value="<?= $lifeSupportActive ? 0 : 1 ?>">
                <button type="button" onclick="toggleLifeSupport()">
                    生命維持装置を<?= $lifeSupportActive ? 'OFF' : 'ON' ?>にする
                </button>
            </form>
        <?php endif; ?>


        <?php else: ?>
            <form method="POST">
                <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                <button class="upgrade-button">購入</button>
            </form>
            <?php if (isset($errorMessages[$item['item_id']])): ?>
                <p class="error-message"><?= $errorMessages[$item['item_id']] ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

</div>
<iframe src="bgm_player.php" style="display:none;" id="bgm-frame"></iframe>
<script>
function toggleLifeSupport() {
    const form = document.getElementById("lifeSupportToggleForm");
    const newStatus = document.getElementById("lifeSupportNewStatus").value;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "enviroment.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                alert(`生命維持装置を${newStatus == 1 ? 'ON' : 'OFF'}にしました。`);
                location.reload(); // 状態を反映するためページをリロード
            } else {
                console.error("切り替えに失敗しました。");
            }
        } else {
            console.error("通信エラーが発生しました。");
        }
    };
    xhr.send(`toggle_life_support=1&newStatus=${newStatus}`);
}


</script>
<iframe src="bgm.html" style="display:none;" id="bgm-frame"></iframe>
</body>
</html>