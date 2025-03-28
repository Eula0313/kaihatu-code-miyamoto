<?php
// データベース接続
require 'db-connect.php';
 
// ログインしているユーザーIDを取得（セッションから）
session_start();
$user_id = $_SESSION['user_id'] ?? null;
 
// ログインしているか確認
if (!$user_id) {
    echo "ログインしてください。";
    exit;
}
 
// キャラクター一覧を取得
try {
    $stmt = $pdo->prepare("
        SELECT c.character_id, c.name, c.rarity, c.attack_type, c.character_image, c.point, c.HP, c.speed, h.harvest_time
        FROM characters AS c
        JOIN harvest_log AS h ON c.character_id = h.character_id
        WHERE h.user_id = :user_id
        ORDER BY h.harvest_time DESC
        LIMIT 30
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $characters = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "データ取得エラー: " . $e->getMessage();
    exit;
}
 
 
// パーティー配列をセッションで管理（character_id と harvest_time を使用）
if (!isset($_SESSION['party'])) {
    $_SESSION['party'] = [1 => null, 2 => null, 3 => null];
}
$party = &$_SESSION['party'];
 
// キャラクターをパーティーに追加（重複する character_id と harvest_time の組み合わせを許可しない）
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_party'])) {
    $character_id = $_POST['character_id'];
    $harvest_time = $_POST['harvest_time']; // 収穫時間も一緒に送信
 
    // 同じ character_id と harvest_time の組み合わせがない場合のみ追加
    $is_duplicate = false;
    foreach ($party as $existing_character) {
        if ($existing_character && $existing_character['character_id'] == $character_id && $existing_character['harvest_time'] == $harvest_time) {
            $is_duplicate = true;
            break;
        }
    }
 
    if (!$is_duplicate) {
        foreach ($party as $position => $existing_character) {
            if ($existing_character === null) {
                $party[$position] = ['character_id' => $character_id, 'harvest_time' => $harvest_time];
                break;
            }
        }
    }
}
 
// キャラクターをパーティーから取り消し
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_party'])) {
    $character_id = $_POST['character_id'];
    $harvest_time = $_POST['harvest_time'];
 
    foreach ($party as $position => $existing_character) {
        if ($existing_character && $existing_character['character_id'] == $character_id && $existing_character['harvest_time'] == $harvest_time) {
            $party[$position] = null;
            break;
        }
    }
}
 
// パーティーを保存
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_party'])) {
    try {
        $pdo->beginTransaction();
       
        // ユーザーの既存のパーティーデータを削除
        $stmt = $pdo->prepare("DELETE FROM party WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
       
        // 新しいパーティーデータを挿入
        foreach ($party as $position => $character) {
            if ($character !== null) {
                $stmt = $pdo->prepare("INSERT INTO party (user_id, character_id, position)
                                       VALUES (:user_id, :character_id, :position)");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':character_id', $character['character_id'], PDO::PARAM_INT);
                $stmt->bindParam(':position', $position, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
 
        $pdo->commit();
        echo "パーティーが保存されました。";
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "保存エラー: " . $e->getMessage();
    }
}
// キャラクターをリセット
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_party'])) {
    $_SESSION['party'] = [1 => null, 2 => null, 3 => null]; // パーティーをリセット
}

 
 
// キャラクター詳細の表示
$selected_character = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['select_character'])) {
    $selected_character_id = $_POST['character_id'];
    $selected_harvest_time = $_POST['harvest_time'];
    foreach ($characters as $character) {
        if ($character['character_id'] == $selected_character_id && $character['harvest_time'] == $selected_harvest_time) {
            $selected_character = $character;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編成画面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #34495e, #2c3e50);
            margin: 0;
            padding: 20px;
            color: #ecf0f1;
        }
 
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #f1c40f;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }
 
        .party-slot-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }
 
        .slot {
            border: 2px dashed #f1c40f;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: #2c3e50;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
 
        .slot img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }
 
        .slot:hover {
            background: #3b556c;
        }
 
        .controls {
            text-align: center;
            margin-bottom: 30px;
        }
 
        .controls button {
            background: linear-gradient(to right, #16a085, #1abc9c);
            color: #fff;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }
 
        .controls button:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
 
        .container {
            display: flex;
            justify-content: space-between;
        }
 
        .character-list {
            flex: 3;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            padding: 20px;
            background: #34495e;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
 
        .character {
            border: 2px solid #f1c40f;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            background: #2c3e50;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
 
        .character img {
            max-width: 100%;
            max-height: 100px;
            object-fit: contain;
            margin-bottom: 10px;
        }
 
        .character:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }
 
        .character p {
            margin: 5px 0;
            font-size: 14px;
            color: #f1c40f;
        }
 
        .character button {
            background: linear-gradient(to right, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }
 
        .character button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
 
        .details {
            flex: 1;
            margin-left: 20px;
            padding: 20px;
            background: #2c3e50;
            border: 2px solid #f1c40f;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
 
        .details h2 {
            font-size: 18px;
            color: #f1c40f;
            margin-bottom: 15px;
        }
 
        .details img {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
            margin-bottom: 10px;
            border-radius: 10px;
        }
 
        .details p {
            margin: 5px 0;
            font-size: 14px;
            color: #ecf0f1;
        }
    </style>
</head>
<body>
    <h1>編成画面</h1>
    <iframe src="btlbgm_player.php" style="display:none;" id="bgm-frame"></iframe>

    <!-- パーティースロット -->
    <div class="party-slot-container">
        <?php foreach ($party as $position => $character): ?>
            <div class="slot" onclick="selectCharacter(<?= htmlspecialchars(json_encode($character), ENT_QUOTES, 'UTF-8') ?>)">
                <?= $character ? '<img src="' . htmlspecialchars($characters[array_search($character['character_id'], array_column($characters, 'character_id'))]['character_image'], ENT_QUOTES, 'UTF-8') . '" alt="キャラ">' : $position . '体目' ?>
            </div>
        <?php endforeach; ?>
    </div>
 
    <!-- 保存ボタンとリセットボタン -->
    <form method="POST" class="controls">
        <button type="submit" name="save_party">保存</button>
        <button type="submit" name="reset_party">リセット</button> <!-- リセットボタン -->
        <button type="button" onclick="window.location.href='start.php'">戻る</button>
    </form>

    <!-- キャラクターリストと詳細表示 -->
    <div class="container">
        <div class="character-list">
            

            <?php foreach ($characters as $character): ?>
                <form class="character" method="POST">
                    <input type="hidden" name="character_id" value="<?= htmlspecialchars($character['character_id'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="harvest_time" value="<?= htmlspecialchars($character['harvest_time'], ENT_QUOTES, 'UTF-8') ?>">
                    <img src="<?= htmlspecialchars($character['character_image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($character['name'], ENT_QUOTES, 'UTF-8') ?>">
                    <p><?= htmlspecialchars($character['name'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php
                    $is_in_party = false;
                    foreach ($party as $party_character) {
                        if ($party_character && $party_character['character_id'] == $character['character_id'] && $party_character['harvest_time'] == $character['harvest_time']) {
                            $is_in_party = true;
                            break;
                        }
                    }
                    ?>
                    <?php if ($is_in_party): ?>
                        <button type="submit" name="remove_from_party">取り消し</button>
                    <?php else: ?>
                        <button type="submit" name="add_to_party">編成</button>
                    <?php endif; ?>
                    <button type="submit" name="select_character">詳細表示</button>
                </form>
            <?php endforeach; ?>
        </div>

        <!-- キャラクター詳細 -->
        <div class="details">
            <h2>選択したキャラクターの詳細</h2>
            <?php if ($selected_character): ?>
                <img src="<?= htmlspecialchars($selected_character['character_image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($selected_character['name'], ENT_QUOTES, 'UTF-8') ?>">
                <p>名前: <?= htmlspecialchars($selected_character['name'], ENT_QUOTES, 'UTF-8') ?></p>
                <p>レア度: <?= htmlspecialchars($selected_character['rarity'], ENT_QUOTES, 'UTF-8') ?></p>
                <p>タイプ: <?= htmlspecialchars($selected_character['attack_type'], ENT_QUOTES, 'UTF-8') ?></p>
                <p>体力: <?= htmlspecialchars($selected_character['HP'], ENT_QUOTES, 'UTF-8') ?></p>
                <p>素早さ: <?= htmlspecialchars($selected_character['speed'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php else: ?>
                <p>キャラクターを選択してください。</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // フォームの送信を非同期にする
        document.querySelectorAll('.character form').forEach(form => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault(); // ページリロード防止
 
                // フォームデータを取得
                const formData = new FormData(form);
               
                // 非同期リクエストを送信
                const response = await fetch('', {
                    method: 'POST',
                    body: formData
                });
 
                // サーバーからのレスポンスをHTMLに反映
                const result = await response.text();
                document.getElementById('details').innerHTML = result;
            });
        });
    </script>
</body>

</html>
 