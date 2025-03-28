<?php
require 'db-connect.php';

// キャラクター情報を取得するSQL文
try {
    $sql = "SELECT name, rarity, character_image FROM characters";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $characters = $stmt->fetchAll(PDO::FETCH_ASSOC); // キャラクター情報を取得
} catch (PDOException $e) {
    echo 'データ取得エラー: ' . htmlspecialchars($e->getMessage());
    exit;
}

// なめこ収穫時の処理（POSTリクエストによる処理）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 収穫されたなめこのデータを受け取る
    $characterName = $_POST['name'] ?? '';
    $userId = 1; // ユーザーIDは動的に取得する必要があります（例: ログイン機能から）

    // データベースに収穫ログを保存する
    try {
        $sql = "INSERT INTO harvest_log (user_id, character_id) 
                VALUES (:user_id, (SELECT character_id FROM characters WHERE name = :name LIMIT 1))";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $characterName, PDO::PARAM_STR);
        $stmt->execute();
        echo '収穫ログが正常に保存されました。';
    } catch (PDOException $e) {
        echo '収穫ログ保存エラー: ' . htmlspecialchars($e->getMessage());
    }
    exit;
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
        }

        #nameko-container {
            margin: 20px 0;
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
            height: 460px;
            background-image: url('image/ki.png');
            background-size: cover;
            position: relative;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📷</h1>
        <div id="nameko-container">
            <div class="log"></div>
        </div>
        <div id="message"></div>
    </div>
    <script>
        const growthTime = 5000;
        let namekos = []; // 成長したなめこの配列
        const maxNamekos = 24; // 最大のなめこの数

        // PHPから取得したキャラクター情報をJavaScriptに渡す
        const characters = <?php echo json_encode($characters, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

        const namekoContainer = document.getElementById('nameko-container');
        const message = document.getElementById('message');

        // なめこを自動で育てる関数
        function growNameko() {
            if (namekos.length < maxNamekos) {
                message.textContent = 'なめこが育っています...';

                setTimeout(() => {
                    // ランダムにキャラクターを選択して追加
                    const nameko = getRandomCharacter();
                    namekos.push(nameko);
                    message.textContent = 'なめこが成長しました！';
                    displayNamekos();
                }, growthTime);
            } else {
                message.textContent = 'なめこはもうこれ以上育ちません。';
            }
        }

        // レアリティに基づいてキャラクターを選択する関数
        function getRandomCharacter() {
            const probabilities = characters.map(character => 1 / character.rarity); // レアリティに基づく逆比例の確率
            const totalProbability = probabilities.reduce((sum, prob) => sum + prob, 0);
            const normalizedProbabilities = probabilities.map(prob => prob / totalProbability); // 確率を正規化

            const randomValue = Math.random();
            let cumulativeProbability = 0;

            for (let i = 0; i < normalizedProbabilities.length; i++) {
                cumulativeProbability += normalizedProbabilities[i];
                if (randomValue < cumulativeProbability) {
                    return characters[i]; // 選択されたキャラクターを返す
                }
            }

            return characters[0]; // デフォルト（この行には到達しないはず）
        }

        // 成長機能のセットアップ
        setInterval(growNameko, growthTime + 1000);

        // なめこを表示する関数
        function displayNamekos() {
            namekoContainer.innerHTML = '<div class="log"></div>';
            const logHeight = 460;
            const totalColumns = 12;
            const totalRows = 2;
            const columnWidth = namekoContainer.offsetWidth / totalColumns;
            const rowHeight = logHeight / (totalRows + 1);

            namekos.forEach((nameko, index) => {
                const namekoElement = document.createElement('span');
                namekoElement.classList.add('nameko');

                // キャラクターの画像を表示
                const imgElement = document.createElement('img');
                imgElement.src = nameko.character_image;
                imgElement.alt = nameko.name;
                imgElement.title = nameko.name + " - " + nameko.rarity;
                imgElement.style.width = '100px';
                imgElement.style.height = '100px';

                namekoElement.appendChild(imgElement);
                namekoElement.addEventListener('click', () => harvestNameko(index));

                const positionInRow = index % totalColumns;
                const rowIndex = Math.floor(index / totalColumns);
                const xPosition = positionInRow * columnWidth;
                const yPosition = logHeight - (rowHeight * (rowIndex + 1));

                namekoElement.style.left = `${xPosition}px`;
                namekoElement.style.bottom = `${yPosition}px`;

                namekoContainer.appendChild(namekoElement);
            });
        }

        // なめこを収穫する関数
        function harvestNameko(index) {
            const nameko = namekos[index]; // 収穫するなめこの情報
            namekos.splice(index, 1); // なめこを配列から削除
            message.textContent = 'なめこを収穫しました！';
            displayNamekos();

            // サーバーに収穫したなめこ情報を送信
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true); // 同じページにPOSTリクエストを送信
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log(xhr.responseText); // 成功メッセージ
                } else {
                    console.error('収穫ログの送信に失敗しました。');
                }
            };
            xhr.send(`name=${encodeURIComponent(nameko.name)}`);
        }
    </script>
</body>
</html>
