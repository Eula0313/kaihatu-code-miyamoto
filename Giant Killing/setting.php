<?php
require 'db-connect.php';
session_start();

// ログインユーザー情報取得
$user_id = $_SESSION['user_id'];


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

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>設定画面</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #d6a85f;
            text-align: center;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .settings-container {
            margin: 170px auto;
            padding: 60px 40px;
            width: 90%;
            max-width: 800px;
            background-color: #f4f4f4;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            position: relative;
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

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .slider-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .slider-container label {
            margin-right: 10px;
            font-size: 18px;
        }

        input[type="range"] {
            width: 300px;
            margin-right: 10px;
        }

        #bgm-value {
            font-size: 16px;
        }

        .bgm-selection {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        .bgm-selection hr {
            width: 100px;
        }

        .bgm-selection span {
            margin: 0 10px;
            font-size: 18px;
        }

        .bgm-options {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
        }

        .bgm-options button {
            font-size: 24px;
            background: none;
            border: none;
            cursor: pointer;
        }

        #bgm-title {
            font-size: 18px;
            margin: 0 20px;
        }

        .return-title {
            font-size: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<a href="<?= htmlspecialchars($backUrl) ?>" class="back-button">← 戻る</a>
    <div class="settings-container">
        <h2>サウンド</h2>

        <div class="slider-container">
            <label for="bgm-volume">BGM音量</label>
            <input type="range" id="bgm-volume" min="0" max="100" value="50">
            <span id="bgm-value">50</span>
        </div>

        <div class="return-title">
            <a href="menu.php">タイトルへ戻る</a>
        </div>
    </div>

    <!-- BGMプレーヤーをiframeに分離 -->
    <iframe src="bgm_player.php" style="display:none;" id="bgm-frame"></iframe>

    <script>
        // 音量スライダーの操作
        const bgmVolumeSlider = document.getElementById('bgm-volume');
        const bgmValueLabel = document.getElementById('bgm-value');
        const bgmFrame = document.getElementById('bgm-frame').contentWindow;

        // 初期設定
        const savedVolume = localStorage.getItem('bgmVolume') || 50;
        bgmVolumeSlider.value = savedVolume;
        bgmValueLabel.textContent = savedVolume;

        bgmVolumeSlider.addEventListener('input', function () {
            const volume = bgmVolumeSlider.value;
            bgmValueLabel.textContent = volume;
            localStorage.setItem('bgmVolume', volume);
            bgmFrame.postMessage({ volume: volume / 100 }, '*');
        });

        let currentTrackIndex = 0;

        document.getElementById('bgm-prev').addEventListener('click', function () {
            currentTrackIndex = (currentTrackIndex === 0) ? bgmTracks.length - 1 : currentTrackIndex - 1;
            changeBGM();
        });

        document.getElementById('bgm-next').addEventListener('click', function () {
            currentTrackIndex = (currentTrackIndex + 1) % bgmTracks.length;
            changeBGM();
        });

        function changeBGM() {
            const track = bgmTracks[currentTrackIndex];
            bgmFrame.postMessage({ src: track.src }, '*');
            document.getElementById('bgm-title').textContent = track.title;
        }
    </script>
</body>
</html>
