<?php
require 'db-connect.php';
session_start();
 
// ユーザーIDの取得
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo 'ログインが必要です。';
    exit;
}
 
// キャラクター情報を取得
try {
    $sql = "SELECT name, rarity, character_image, point FROM characters WHERE rarity IN (1, 2)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $characters = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'データ取得エラー: ' . htmlspecialchars($e->getMessage());
    exit;
}
 
// データベースからユーザーの所持金と生命維持装置の状態を取得し、セッションに保存
try {
    $sql = "SELECT money, rare_drug_purchased, life_support_purchased, life_support_active, growth_speed FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
 
    $totalMoney = $user['money'] ?? 0;
    $_SESSION['total_money'] = $totalMoney;
    $rareDrugPurchased = $user['rare_drug_purchased'] ?? false;
    $isLifeSupportPurchased = $user['life_support_purchased'] ?? false;
    $isLifeSupportActive = $user['life_support_active'] ?? false;
    $growthSpeed = $user['growth_speed'] ?? 5;
 
} catch (PDOException $e) {
    echo '所持金データの取得エラー: ' . htmlspecialchars($e->getMessage());
    exit;
}
 
// レア薬購入後、レア度1～5までのキャラクターを成長対象にする
if ($rareDrugPurchased) {
    try {
        $sql = "SELECT name, rarity, character_image, point FROM characters";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $characters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'データ取得エラー: ' . htmlspecialchars($e->getMessage());
        exit;
    }
}
 
// 収穫時の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $characterName = $_POST['name'] ?? '';
    $characterPoint = (int)($_POST['point'] ?? 0);
 
    try {
        // 現在の所持金を再取得
        $sql = "SELECT money FROM users WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $currentMoney = $user['money'] ?? 0;
 
        // 新しい所持金を計算
        $newMoney = $currentMoney + $characterPoint;
 
        // 収穫ログを保存
        $sql = "INSERT INTO harvest_log (user_id, character_id)
                VALUES (:user_id, (SELECT character_id FROM characters WHERE name = :name LIMIT 1))";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $characterName, PDO::PARAM_STR);
        $stmt->execute();
 
        // 新しい所持金をデータベースで更新
        $sql = "UPDATE users SET money = :new_money WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':new_money', $newMoney, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
 
        // セッションにも新しい所持金を保存
        $_SESSION['total_money'] = $newMoney;
        echo $newMoney;
    } catch (PDOException $e) {
        echo 'エラー: ' . htmlspecialchars($e->getMessage());
    }
    exit;

    // 加湿器のレア度の範囲を取得
$rarityRange = [];
foreach ($tools as $tool) {
    if ($tool['tool_name'] === '加湿器') {
        // 加湿器の場合、レベルに応じてレア度の範囲を設定
        $level = $tool['level'];
        if ($level == 1) {
            $rarityRange = [1, 2]; // レベル1の場合、レア度は1と2
        } elseif ($level == 2) {
            $rarityRange = [1, 2, 3]; // レベル2の場合、レア度は1から3
        } elseif ($level == 3) {
            $rarityRange = [1, 2, 3, 4]; // レベル3の場合、レア度は1から4
        } elseif ($level == 4) {
            $rarityRange = [1, 2, 3, 4, 5]; // レベル4の場合、レア度は1から5
        }
    }
}
 
 
// ここで$rarityRangeには、加湿器のレベルに応じたレア度の範囲が格納されます
// 例えば、レベル2なら[1, 2, 3]が設定されます
 
if (isset($_SESSION['updated_tool_effect'])) {
    echo "現在の道具の効果: " . $_SESSION['updated_tool_effect'];
    // 効果を表示後、セッションから削除することもできます
    unset($_SESSION['updated_tool_effect']);
}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>なめこ栽培キッド</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            text-align: center;
            background-image: url('image/gensou.webp');
            background-size: cover; /* 全画面に拡大 */
            background-position: center;
            overflow: hidden;
        }
 
        #nameko-container {
            margin: 90px 0;
            margin-left: 5px;
            position: relative;
        }
 
        .nameko {
            font-size: 50px;
            display: inline-block;
            margin: 5px;
            cursor: pointer;
            position: absolute;

        }
 
 
        .log {
            width: 100%;
            height: 100vh; /* 画面の高さに合わせる */
            position: relative;
            margin: 0 auto;
        }
 
 
        .pointbox{
            padding: 0.5em 1em;
            background: -moz-linear-gradient(#ffb03c, #ff708d);
            background: -webkit-linear-gradient(#ffb03c, #ff708d);
            background: linear-gradient(to right, #ffb03c, #ff708d);
            color: #FFF;
            position: absolute;
            right: 20px;
            }
 
                    /* worldbox-image自体の位置調整 */
            .worldbox-image {
                position: absolute;
                left: 20px;
                top: 80px;
                z-index: 10;
            }
 
            /* worldbox-image内のimg要素を円形にする */
            .worldbox-image img {
                width: 100px;
                height: 100px;
                border-radius: 50%;         /* 円形にするために50%のボーダー半径を指定 */
                border: 2px solid #fff;     /* 白い枠線を追加（必要に応じて色や太さを変更） */
                box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5); /* 影を追加（お好みで） */
            }
 
            /* pointbox-image（右側の画像群）の位置調整は既存のままで問題ありません */
            .pointbox-image {
                position: absolute;
                right: 20px;
                top: 0px;
                z-index: 10;
                margin-left: auto;
                margin-right: 30px;
                top: 80px;
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
 
            .taiyou1-image {
                position: absolute;
                left: 300px;
                top: 0px;
                z-index: 10;
            }
 
       
            .takibi-image {
                position: absolute;
                left: 200px;
                bottom: 20px;
                z-index: 10;
            }
 
            .spring-image{
                position: absolute;
                right: 200px;
                bottom: 20px;
                z-index: 10;
            }
 
        #main-button {
            position: absolute;
            left: 20px;
            bottom: 20px;
            width: 120px;
            height: 120px;
            background-color: white;
            border: 2px solid black;
            border-radius: 50%;
            background-image: url('image/tin.png'); /* 1の画像 */
            background-size: cover;
            cursor: pointer;
        }
        .popup {
            position: absolute;
            bottom: 120px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 2px solid red;
            display: none;
            cursor: pointer;
            background-size: cover;
        }
        #popup2 {
            left: 10px;
            bottom: 160px;
            background-image: url('image/dan.png'); /* 2の画像 */
        }
        #popup3 {
            left: 125px;
            bottom: 130px;
            background-image: url('image/kana.png'); /* 3の画像 */
        }
        #popup4 {
            left: 170px;
            bottom: 20px;
            background-image: url('image/pro.png'); /* 4の画像 */
        }
 
                /* point-iconを右側中央に配置 */
        .point-icon {
            position: absolute;
            right: 20px;       /* 画面右から20pxの位置に配置 */
            top: 90%;          /* 画面の中央付近 */
            transform: translateY(-50%); /* 上下中央揃え */
            z-index: 10;
        }
 
    </style>
    <?php include 'header.php'; ?>
</head>
<body>
<!-- <iframe src="bgm_player.php" style="display:none;" id="bgm-frame"></iframe> -->
    <!-- 合計ポイント表示 -->
    <!-- 所持金表示 -->
<div class="wallet-container">
    <img src="image/coin_kinoko.png" alt="Coin Icon"> <!-- アイコンを所持金の横に表示 -->
    <span id="money-display"><?php echo htmlspecialchars($totalMoney); ?>c</span>
</div>
 
 
    <!-- 各種リンク、メインボタン、ポップアップボタン -->
    <div class="worldbox-image">
    <a href="world.php"><img src="image/world.webp" alt="世界" ></a>
    </div>
 
    <div class="taiyou-image">
    <!-- <img src="image/taiyou1" alt="灯"  width="100"; height="100";> -->
    </div>
 
    <div class="pointbox-image">
        <a href="Miyakoku.php"><img src="image/koukoku.webp" alt="広告" width="100" height="100"></a>
        <a href="zukan.php"><img src="image/zukan.webp" alt="図鑑" width="100" height="100"></a>
        <a href="setting.php"><img src="image/setei.webp" alt="設定" width="100" height="100"></a>
    </div>
 
    <!-- なめこコンテナ -->
    <div id="nameko-container">
        <div class="log"></div>
    </div>
 
    <!-- <div class="spring-image">
    <img src="image/spring1.webp" alt="灯" width="100" height="100">
    </div>
 
    <div class="takibi-image">
    <img src="image/takibi1.webp" alt="灯" width="100" height="100">
    </div>
  -->
    <div id="container">
        <div id="main-button"></div>
        <div id="popup2" class="popup" onclick="navigateTo('start.php')"></div>
        <div id="popup3" class="popup" onclick="navigateTo('enviroment.php')"></div>
        <div id="popup4" class="popup" onclick="navigateTo('profile.php')"></div>
    </div>
 
        <!-- 右側中央に配置するpointアイコン -->
    <div class="point-icon">
        <a href="saibai_item.php"><img src="image/point.webp" alt="c" width="100" height="100"></a>
    </div>
 
 
    <script>
    let isVisible = false;
    document.getElementById('main-button').addEventListener('click', function() {
        isVisible = !isVisible;
        togglePopups(isVisible);
    });
 
    function togglePopups(show) {
        const popups = document.querySelectorAll('.popup');
        popups.forEach(popup => popup.style.display = show ? 'block' : 'none');
    }
 
    function navigateTo(page) {
        window.location.href = page;
    }
 
    // PHPから変数をJavaScriptに渡す
    const rareDrugPurchased = <?php echo json_encode($rareDrugPurchased); ?>;
    const userId = <?php echo json_encode($user_id); ?>;  // 現在のユーザーID
 
    // キャラクター情報
    const characters = <?php echo json_encode($characters, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    let namekos = [];
    const maxNamekos = 28;
    const growthTime = <?php echo json_encode($growthSpeed * 1000); ?>; // PHPから反映
    let growthInterval;
 
    // ローカルストレージから保存されたキャラクターを復元（ユーザーごとに管理）
    function loadNamekos() {
        const savedNamekos = localStorage.getItem(`namekos_${userId}`);
        if (savedNamekos) {
            namekos = JSON.parse(savedNamekos);
        }
    }
 
    // キャラクター生成関数
    function getRandomCharacter() {
        const filteredCharacters = characters.filter(character => {
            return rareDrugPurchased ? character.rarity >= 1 && character.rarity <= 5 : character.rarity <= 2;
        });
 
        const totalRarity = filteredCharacters.reduce((sum, char) => sum + (6 - char.rarity), 0);
        const randomValue = Math.random() * totalRarity;
        let cumulativeRarity = 0;
 
        for (const character of filteredCharacters) {
            cumulativeRarity += (6 - character.rarity);
            if (randomValue < cumulativeRarity) {
                return character;
            }
        }
        return filteredCharacters[0];
    }
 
    // なめこを成長させる関数
    function growNameko() {
        if (namekos.length < maxNamekos) {
            namekos.push(getRandomCharacter());
            displayNamekos();
            saveNamekos();  // ローカルストレージに保存
        }
    }
 
    // なめこの成長を一度だけ開始
    function startGrowth() {
        if (!growthInterval) {
            growthInterval = setInterval(growNameko, growthTime);
        }
    }
 
    // ページ読み込み時に成長開始とデータ復元
    window.addEventListener('load', function() {
        loadNamekos();
        displayNamekos();  // 保存されたデータを表示
        startGrowth();
    });
 
    // キャラクター情報をローカルストレージに保存（ユーザーごとに保存）
    function saveNamekos() {
        localStorage.setItem(`namekos_${userId}`, JSON.stringify(namekos));
    }
 
    // なめこを表示する関数
    function displayNamekos() {
        const namekoContainer = document.getElementById('nameko-container');
        namekoContainer.innerHTML = '<div class="log"></div>';
        const containerWidth = namekoContainer.offsetWidth;
        const logHeight = window.innerHeight * 0.8;
        const totalColumns = 14;
        const offsetY = 100;
 
        namekos.forEach((nameko, index) => {
            const namekoElement = document.createElement('span');
            const imgElement = document.createElement('img');
            imgElement.src = nameko.character_image;
            imgElement.alt = nameko.name;
            imgElement.title = `${nameko.name} - ${nameko.rarity}`;
            imgElement.style.width = '80px';
            imgElement.style.height = '80px';
            namekoElement.appendChild(imgElement);
            namekoElement.addEventListener('click', () => harvestNameko(index));
 
            const gap = 0.5; // 隙間のサイズ
            const xPosition = (index % totalColumns) * (containerWidth / totalColumns) + gap * (index % totalColumns);
            const yPosition = logHeight - (100 + gap) * Math.floor(index / totalColumns) - offsetY;

            namekoElement.style.left = `${xPosition}px`;
            namekoElement.style.bottom = `${yPosition}px`;
            namekoElement.style.position = 'absolute';
            namekoContainer.appendChild(namekoElement);
        });
    }
 
    // なめこを収穫する関数
    function harvestNameko(index) {
        const nameko = namekos[index];
        namekos.splice(index, 1);
        displayNamekos();
        saveNamekos();  // 収穫後に保存
 
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('money-display').textContent = `${xhr.responseText}c`;
            } else {
                console.error('エラー: 収穫ログ送信に失敗');
            }
        };
        xhr.send(`name=${encodeURIComponent(nameko.name)}&point=${nameko.point}`);
    }
 
            // PHPから生命維持装置の購入状況を渡す
            const isLifeSupportPurchased = <?php echo json_encode($isLifeSupportPurchased); ?>;
            const isLifeSupportActive = <?php echo json_encode($isLifeSupportActive); ?>;
 
            function startDecay() {
    // 生命維持装置が購入済みでONの場合は消滅処理を停止
    if (isLifeSupportPurchased && isLifeSupportActive) {
        console.log("生命維持装置がONです。消滅処理を停止します。");
        return;
    }
 
    console.log("生命維持装置がOFFです。消滅処理を開始します。");
 
    setInterval(() => {
        if (namekos.length > 0) {
            namekos.shift(); // 配列の先頭を削除
            displayNamekos(); // 表示を更新
        }
    }, 40000); // 40秒ごと
}
    // ローカルストレージから新しいキャラクターをロード
    function loadNewCharacters() {
        const savedCharacters = localStorage.getItem('new_characters');
        if (savedCharacters) {
            const newCharacters = JSON.parse(savedCharacters);
            namekos.push(...newCharacters); // 新しいキャラクターを追加
            if (namekos.length > maxNamekos) {
                namekos = namekos.slice(0, maxNamekos); // 最大数を超えないように調整
            }
            displayNamekos(); // 画面を更新
            saveNamekos();    // 状態を保存
            localStorage.removeItem('new_characters'); // 使用後削除
        }
    }
 
    // ページ読み込み時に新しいキャラクターを確認
    window.addEventListener('load', () => {
        loadNamekos(); // 既存のキャラクターを読み込み
        loadNewCharacters(); // 広告で取得した新しいキャラクターを読み込み
    });
 
 
                // ページ読み込み時に消滅開始
         window.addEventListener('load', startDecay);
 
</script>
</body>
</html>