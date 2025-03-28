<?php
// クッキーから初期音量を取得（localStorageを主に利用する場合は不要）
$initialVolume = isset($_COOKIE['bgm_volume']) ? $_COOKIE['bgm_volume'] / 100 : 0.5;
$initialTrack = 'BGM/btl.mp3';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>BGMプレーヤー</title>
</head>
<body>
    <audio id="bgm-audio" src="<?= htmlspecialchars($initialTrack) ?>" loop autoplay></audio>
 
    <script>
        const audio = document.getElementById('bgm-audio');

        // 初期設定（localStorageを利用）
        const savedVolume = localStorage.getItem('bgmVolume');
        audio.volume = savedVolume ? savedVolume / 100 : <?= $initialVolume ?>;

        // 再生位置をlocalStorageから取得して適用
        const savedTime = localStorage.getItem('bgmCurrentTime');
        if (savedTime) {
            audio.currentTime = parseFloat(savedTime);
        }

        // ページ遷移時に再生位置を保存
        window.addEventListener('beforeunload', () => {
            localStorage.setItem('bgmCurrentTime', audio.currentTime);
        });

        // メッセージを受け取ってBGMを制御
        window.addEventListener('message', (event) => {
            // 新しいトラックを再生
            if (event.data.src) {
                const currentVolume = audio.volume; // 現在の音量を保持
                audio.src = event.data.src;
                audio.play();
                audio.volume = currentVolume; // 音量を再設定
            }

            // 音量を設定
            if (event.data.volume !== undefined) {
                audio.volume = event.data.volume;
                localStorage.setItem('bgmVolume', event.data.volume * 100); // 音量を保存
            }
        });
    </script>
</body>
</html>
