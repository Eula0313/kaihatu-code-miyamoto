<?php
require 'db-connect.php';
session_start();

$videos = ["Miya1.mp4", "Miya2.mp4"];
$randomVideo = $videos[array_rand($videos)];

// ログインしているユーザーのIDを取得
$user_id = $_SESSION['user_id'];

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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>広告動画</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: black;
            overflow: hidden;
            position: relative;
        }

        video {
            max-width: 80%;    /* 横幅を画面の80%に制限 */
            max-height: 80%;   /* 高さを画面の80%に制限 */
            object-fit: contain; /* アスペクト比を維持して表示 */
        }

        /* ×マークのスタイル */
        #close-button {
            display: none;
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 30px;
            color: white;
            background: none;
            border: none;
            cursor: pointer;
            z-index: 100;
        }

        #close-button:hover {
            color: red;
        }

        /* 画面全体をクリックできるオーバーレイ */
        #overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 50;
        }

        /* 読み込み中メッセージ */
        #loading-message {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            text-align: center;
            color: white;
        }
    </style>
</head>
<body>

<!-- 読み込み中のメッセージ -->
<div id="loading-message">読み込み中...</div>

<!-- 動画再生エリア -->
<video id="ad-video" muted playsinline preload="auto">
    <source src="MP4/<?php echo $randomVideo; ?>" type="video/mp4">
    お使いのブラウザは動画タグに対応していません。
</video>

<!-- 動画終了後に表示される閉じるボタン（×マーク） -->
<button id="close-button" onclick="closeAd()">×</button>

<!-- 画面全体をクリック可能にするオーバーレイ -->
<div id="overlay"></div>

<script>
    const video = document.getElementById('ad-video');
    const overlay = document.getElementById('overlay');
    const loadingMessage = document.getElementById('loading-message');
    const closeButton = document.getElementById('close-button');

    // PHPから戻り先リンクをJavaScriptに渡す
    const backLink = <?php echo json_encode($back_link); ?>;

    // 動画のプリロードを完了したら再生
    video.addEventListener('canplaythrough', function() {
        loadingMessage.style.display = 'none'; // 読み込みメッセージを非表示
        video.style.display = 'block';         // 動画を表示
        video.play();                          // 再生を開始
    });

    // 動画が終了したら「広告を閉じる」ボタン（×）を表示
    video.onended = function() {
        closeButton.style.display = 'block';
    };

     // オーバーレイをクリックしたらYouTubeチャンネルを開く
     overlay.addEventListener('click', function() {
        window.open('https://youtube.com/@eula0313?si=5mbZ_jRELIrrYFek', '_blank');
    });

    async function fetchNewCharacters() {
        try {
            const response = await fetch('fill_slots.php');
            const result = await response.json();

            if (result.status === 'success') {
                const newCharacters = result.characters;
                localStorage.setItem('new_characters', JSON.stringify(newCharacters)); // ローカルストレージに保存
            } else {
                console.error('キャラクター生成エラー:', result.message);
            }
        } catch (error) {
            console.error('通信エラー:', error);
        }
    }

    function closeAd() {
        fetchNewCharacters().then(() => {
            window.location.href = backLink; // 現在のワールドにリダイレクト
        });
    }
</script>

</body>
</html>
