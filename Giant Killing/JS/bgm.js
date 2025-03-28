// BGM管理用のグローバル変数
let bgmAudio = null;
 
// BGMを初期化する関数
function initializeBGM() {
    if (!bgmAudio) {
        bgmAudio = new Audio(localStorage.getItem('bgmSrc') || 'BGM/Morning.mp3');
        bgmAudio.loop = true;
        bgmAudio.volume = parseFloat(localStorage.getItem('bgmVolume')) || 0.5;
        bgmAudio.currentTime = parseFloat(localStorage.getItem('bgmCurrentTime')) || 0; // 再生位置を復元
        bgmAudio.play().catch(() => {
            console.log("BGMの自動再生がブロックされました。");
        });
    }
}
 
 
// BGMを変更する関数
function changeBGM(src) {
    if (bgmAudio) {
        bgmAudio.src = src;
        bgmAudio.currentTime = 0; // 別トラックの場合、再生位置をリセット
        bgmAudio.play();
        localStorage.setItem('bgmSrc', src); // 現在のトラックを保存
    }
}
 
// 音量を変更する関数
function saveVolume(volume) {
    localStorage.setItem('bgmVolume', volume);
    if (bgmAudio) {
        bgmAudio.volume = volume;
    }
}
 
// ページを閉じる前に現在の再生状態を保存
window.addEventListener('beforeunload', () => {
    if (bgmAudio) {
        localStorage.setItem('bgmCurrentTime', bgmAudio.currentTime); // 再生位置を保存
    }
});
 
// ページ読み込み時にBGMを初期化
window.onload = function () {
    initializeBGM();
};