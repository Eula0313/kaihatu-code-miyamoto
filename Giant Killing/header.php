<!-- header.php -->
<iframe src="bgm_player.php" style="display:none;" id="bgm-frame"></iframe>
<script>
    const bgmFrame = document.getElementById('bgm-frame').contentWindow;
 
    // 保存された音量を適用
    const savedVolume = localStorage.getItem('bgmVolume') || 50;
    bgmFrame.postMessage({ volume: savedVolume / 100 }, '*');
</script>