// 音源管理用スクリプト
(function () {
    if (!window.bgmPlayer) {
        // 音楽オブジェクトの作成 (初回ロード時)
        window.bgmPlayer = new Audio('btl.mp3');
        window.bgmPlayer.loop = true; // ループ再生
        window.bgmPlayer.volume = 0.5; // 音量調整 (0.0 ～ 1.0)
        window.bgmPlayer.play();
    }
})();
