<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ゲームの詳細</title>
    <style>
         h1 {
            text-align: center; 
        }
        h2 {
            text-align: center; 
        }
        
        body {
            
            font-family: Arial, sans-serif;
        }
        .popup, .skill-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 400px;
            height: 60%; /* 高さを指定 */
            overflow-y: auto; /* 縦スクロールバーを有効にする */
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.8); /* 背景を透過させる */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            color: white; /* 文字色を白にする */
            background-image: url('https://www.yuu-diaryblog.com/wp-content/uploads/2017/10/space-background13.jpg');
            background-size: cover;
            background-position: center;
        }
        h1, h2, p {
    position: relative; /* 相対配置を設定します。 */
    border: 2px solid black; /* 黒い外枠を追加します。 */
    padding: 10px; /* コンテンツと外枠の間隔を設定します。 */
}

h1::before, h2::before, p::before {
    content: ''; /* 疑似要素のコンテンツを空にします。 */
    position: absolute; /* 絶対配置を設定します。 */
    top: 0; /* 上端に配置します。 */
    left: 0; /* 左端に配置します。 */
    width: 100%; /* 要素の幅いっぱいに広げます。 */
    height: 100%; /* 要素の高さいっぱいに広げます。 */
    z-index: -1; /* テキストの背面に配置します。 */
    background: rgba(0, 0, 0, 0.5); /* 黒色の半透明の背景を設定します。 */
}
        
        .popup h2, .skill-popup h2 {
            margin-top: 0;
        }

        .popup .buttons, .skill-popup .buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 500;
        }

        #rulesButton {
            padding: 10px 20px;
            border-radius: 50%;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        #rulesButton:hover {
            background-color: #0056b3;
        }

        .icon-button {
            width: 40px;
            height: 40px;
            background-color: #007BFF;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border-radius: 10px !important;
        }

        .icon-button:hover {
            background-color: #0056b3;
        }

        .next-icon {
            width: 0;
            height: 0;
            border-left: 10px solid white;
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
        }

        .back-icon {
            width: 0;
            height: 0;
            border-right: 10px solid white;
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<button id="rulesButton">ルール</button>

<div id="popup" class="popup">
    <h1>ゲームのルール</h1>
    <h2>ゲームの基本設定</h2>
        <p>チーム分け：プレイヤーは2つのチーム（赤チームと青チーム）に分かれます。それぞれのチームには1人のオペレーターと数人のアストロノーツがいます。
        <br>
        カード配置：25枚のカードが5x5のグリッドに配置されます。それぞれのカードには1つの単語が書かれています。</p>
    <h2>ゲームの進行</h2>
    <p>ヒントを出す：各ラウンド、スパイマスターは自分のチームにヒントを1つ出します。ヒントは1つの単語と数字からなります（例：「動物 3」）。このヒントはチームの単語を推測するための手がかりとなります。</p>
    <p>推測：フィールドエージェントはスパイマスターのヒントに基づいて、カードを推測します。推測は1つずつ行い、カードをクリックして確認します。<br>
        ・正しい単語（チームの色のカード）を当てた場合、推測を続けるかどうかを決めます。<br>
        ・間違った単語（相手チームの色のカード、または中立カード）を当てた場合、そのターンは終了します。<br>
   <br>
    ターンの終了：チームの推測が終わるとターンが終了し、次のチームに移ります。</p>
    <h2>勝利条件</h2>
    <p>全ての単語を当てる：先に全ての自分のチームの単語を正しく推測したチームが勝利します。
    <br>
    <br>
    エイリアンを避ける：エイリアンの単語を避け続けることが重要です。エイリアンの単語を当てたチームは即座に敗北します。</p>
    <div class="buttons">
        <button id="nextButton" class="icon-button">
            <div class="next-icon"></div>
        </button>
        <span class="close-button">×</span>
    </div>
</div>

<div id="skillPopup" class="skill-popup">
    <h2>スキルの詳細</h2>
    <p>ここにスキルの詳細を記述します。</p>
    <div class="buttons">
        <button id="backButton" class="icon-button">
            <div class="back-icon"></div>
        </button>
        <span class="close-button">×</span>
    </div>
</div>
<div id="overlay" class="overlay"></div>

<script>
    document.getElementById('rulesButton').addEventListener('click', function() {
        document.getElementById('popup').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    });

    document.getElementById('overlay').addEventListener('click', function() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('skillPopup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    });

    document.getElementById('nextButton').addEventListener('click', function() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('skillPopup').style.display = 'block';
    });

    document.getElementById('backButton').addEventListener('click', function() {
        document.getElementById('skillPopup').style.display = 'none';
        document.getElementById('popup').style.display = 'block';
    });

    // 追加：closeボタンのクリックイベント
    var closeButtons = document.querySelectorAll('.close-button');
    closeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('skillPopup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        });
    });
</script>
</body>
</html>
