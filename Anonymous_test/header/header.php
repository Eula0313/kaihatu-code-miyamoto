<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$userCount = $_SESSION['user_count'] ?? 0;
?>
<header>
    <nav>
        <h5>参加人数: <span id="userCount"><?php echo $userCount; ?></span></h5>
        <div class="nav-buttons">
            <div class="btn"><a id="urlButton" href="#" onclick="togglePopup('urlPopup')">U R L</a></div>
            <div class="btn"><a id="rulesButton" href="#" onclick="togglePopup('rulesPopup')">ルール</a></div>
            <div class="btn"><a id="resetButton" href="#" onclick="resetGame()">リセット</a></div>
        </div>
    </nav>
</header>
<div id="rulesPopup" class="popup">
    <h2>ゲームのルール</h2>
    <h3>ゲームの基本設定</h3>
    <h4>チーム分け：プレイヤーは2つのチーム（赤チームと青チーム）に分かれます。それぞれのチームには1人のオペレーターと1人のアストロノーツがいます。<br>カード配置：25枚のカードが5x5のグリッドに配置されます。それぞれのカードには1つの単語が書かれています。</h4>
    <h2>ゲームの進行</h2>
    <h4>白のカードを選ぶと即座にそのターンが終了します。</h4>
    <h4>ヒントを出す：各ラウンド、オペレーターは自分のチームにヒントを1つ出します。ヒントは1つの単語と数字からなります（例：「動物3とヒントを入力すると、猫、犬、サルのカードを選ぶ）。このヒントはチームの単語を推測するための手がかりとなります。</h4>
    <h4>推測：アストロノーツはオペレーターのヒントに基づいて、カードを推測します。推測は1つずつ行い、カードをクリックして確認します。<br>・正しい単語（チームの色のカード）を当てた場合、推測を続けるかどうかを決めます。<br>・間違った単語（相手チームの色のカード、または中立カード）を当てた場合、そのターンは終了します。<br><br>ターンの終了：チームの推測が終わるとターンが終了し、次のチームに移ります。</h4>
    <h2>勝利条件</h2>
    <h4>全ての単語を当てる：先に全ての自分のチームの単語を正しく推測したチームが勝利します。<br><br>黒色のカードを当てたチームは即座に敗北します。</h4>
    <span class="close-button" onclick="closePopup('rulesPopup')">×</span>
</div>
<div id="urlPopup" class="popup">
    <div>
        <h6>URL: <span id="websiteUrl"></span></h6>
        <div class="center-button">
            <button onclick="copyUrl()" class="Button-style">URLコピー</button>
        </div>
    </div>
    <span class="close-button" onclick="closePopup('urlPopup')">×</span>
</div>
<div id="overlay" class="overlay" onclick="closePopup()"></div>
<script>
    function togglePopup(popupId) {
        var popup = document.getElementById(popupId);
        var overlay = document.getElementById('overlay');
        var websiteUrl = document.getElementById('websiteUrl');
        if (popupId === 'urlPopup') {
            websiteUrl.textContent = window.location.href;
        }
        popup.style.display = "block";
        overlay.style.display = "block";
    }

    function closePopup(popupId) {
        if (popupId) {
            document.getElementById(popupId).style.display = 'none';
        } else {
            document.querySelectorAll('.popup').forEach(function(popup) {
                popup.style.display = 'none';
            });
        }
        document.getElementById('overlay').style.display = 'none';
    }

    function copyUrl() {
        var url = document.getElementById('websiteUrl').textContent;
        var tempInput = document.createElement("input");
        document.body.appendChild(tempInput);
        tempInput.value = url;
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        alert("URLがコピーされました: " + url);
    }

    function resetGame() {
        if (confirm('本当にリセットしますか？')) {
            location.href = '../header/reset2.php';
        }
    }
</script>
