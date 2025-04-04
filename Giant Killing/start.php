<?php
session_start();
require 'db-connect.php';

if (!isset($_SESSION['user_id'])) {
    echo 'ユーザーがログインしていません。';
    exit;
}

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
    <title>ヒューマンバトル</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Nixie+One');
        html, body {
            margin: 0;
            height: 100%;
            width: 100%;
            display: table;
            overflow: hidden;
        }

        body {
            background: radial-gradient(ellipse at center, rgba(0,0,0,0.04) 0%,rgba(0,0,0,0.73) 100%);
        }

        div {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }

        i {
            font: normal 60px/ 100px 'Nixie One', Helvetica, Arial;
            color: #D0F4FF;
            text-shadow: 0 0 30px #00FFFF, 0 0 10px rgba(0, 255, 255, 0.6), 0 0 100px #00BFFF, 0 0 500px #00FFFF;
        }

        i.off {
            color: rgba(46, 46, 46, 0.61);
            text-shadow: 7px 7px 5px rgba(0, 0, 0, 0.5);
        }

        .container-wrapper {
            display: flex;
            justify-content: center;
            gap: 3px;
            margin-top: 10px;
            flex-direction: column;
        }

        .container {
            text-align: left;
            position: relative;
            z-index: 2;
            padding: 10px;
            max-width: 530px;
            width: 100%;
            left:450px;
            border:0px;
        }
        
        .conta{
            text-align: left;
            position: relative;
            z-index: 2;
            max-width: 500px;
            width: 100%;
            right:1000px;
        }

        .slide-container {
            opacity: 0;
            transition: opacity 0.5s ease;
            min-height: 80px;
            margin: 0 auto;
            text-align: center;
        }

        .slide-container.show {
            opacity: 1;
        }

        .explanation-text {
            padding: 10px;
            font-size: 20px;
            color: #D0F4FF;
            text-shadow: 0 0 5px #00FFFF, 0 0 10px #00BFFF;
        }

        .conta button {
            font-size: 20px;
            margin-top: 15px;
        }

        .monster {
            position: absolute;
            top: -50px;
            left: 25%;
            transform: translateX(-50%);
            width: 700px;
            height: 100vh;
            background-size: contain;
            opacity: 0.1;
            animation: monsterMovement 6s ease-in-out infinite;
            z-index: 1;
        }

        .monster2 {
            position: absolute;
            top: -7px;
            bottom: 20px;
            right: 20px;
            width: 600px;
            height: 900px;
            background-size: contain;
            opacity: 0.5;
            animation: monsterMovement2 4s ease-in-out infinite;
            z-index: 1;
            right:-470px;
        }

        @keyframes monsterMovement {            /*/↓これが移動　↓これがサイズ変更 */
            0% { opacity: 0.1; transform: translateX(0%) scale(0.5); }
            10% { opacity: 0.6; transform: translateX(0%) scale(3); }
            30% { opacity: 0.4; transform: translateX(-10%) scale(2); }
            30% { opacity: 0.2; transform: translateX(-60%) scale(1); }
            40% { opacity: 0.2; transform: translateX(-80%) scale(1); }
            100% { opacity: 0.1; transform: translateX(-120%) scale(1); }
        }

        @keyframes monsterMovement2 {
            0% { opacity: 0.1; transform: translateX(-20%) scale(1); }
            50% { opacity: 0.3; transform: translateX(-40%) scale(1); }
            100% { opacity: 0.0; transform: translateX(-40%) scale(1); }
        }
        
        button {
            font-family: 'Nixie One', Helvetica, Arial;
            font-size: 30px;
            color: #fff;
            background-color: transparent;
            border: 2px solid #00FFFF;
            padding: 10px 20px;
            border-radius: 10px;
            text-shadow: 0 0 5px #00FFFF, 0 0 10px #00BFFF;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.7), 0 0 40px rgba(0, 191, 255, 0.7);
            cursor: pointer;
            transition: all 0.2s ease;
            margin: 10px 0;
        }

        button:hover {
            box-shadow: 0 0 30px rgba(0, 255, 255, 1), 0 0 60px rgba(0, 191, 255, 1);
            transform: scale(1.1);
        }

        button:active {
            box-shadow: 0 0 40px rgba(0, 255, 255, 1), 0 0 80px rgba(0, 191, 255, 1);
            transform: scale(1.2);
        }

        h1 {
            font-family: 'Nixie One', Helvetica, Arial;
            font-size: 60px;
            color: #D0F4FF;
            text-shadow: 0 0 20px #00FFFF, 0 0 10px rgba(0, 255, 255, 0.6), 0 0 100px #00BFFF;
            margin: 20px 0;
        }
        html {
  /* subtlepatterns.com */background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAAAAABcFtGpAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDoxMTc4Nzc4N0EwQzMxMUUxOTZFOEJENTBGRDhDNDQwMSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDoxMTc4Nzc4OEEwQzMxMUUxOTZFOEJENTBGRDhDNDQwMSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjExNzg3Nzg1QTBDMzExRTE5NkU4QkQ1MEZEOEM0NDAxIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjExNzg3Nzg2QTBDMzExRTE5NkU4QkQ1MEZEOEM0NDAxIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+S8pQKwAAfE9JREFUGBkEwYGR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);  
}
 
    </style>
</head>
<body>
<button id="t" onclick="window.location.href='<?= htmlspecialchars($backUrl) ?>'" style="position: relative;
    z-index: 10; left:30px;">
↼戻る
</button>

    <div>GiantKilling</div>
    <h1>　ヒューマンバトル</h1>
    <div class="container-wrapper">
        <div class="container"><br><br>
            <button onclick="location.href='omiai.php'">スタート</button><br><br>
            <button onclick="location.href='hensei.php'">編成画面</button>
            <div class="conta">
                <button id="toggleButton">このゲームについて▽</button>
                <div id="slideContainer" class="slide-container">
                    <div class="explanation-text" id="explanationText">
                        ここに編成画面の説明やその他の情報を表示します。
                    </div>
                </div>
            </div>
        </div>
        <div class="monster" style="background: url('image/mon_1.gif') no-repeat center center;"></div>
        <div class="monster2" style="background: url('image/kawaii2-1.gif') no-repeat center center;"></div>
    </div>
    
    <iframe src="btlbgm_player.php" style="display:none;" id="bgm-frame"></iframe>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleButton = document.getElementById("toggleButton");
            const slideContainer = document.getElementById("slideContainer");
            const explanationTextElement = document.getElementById("explanationText");

            const explanationText = "[画面設定]ーーーーーーーーーーーーーーーーーーー推奨▶ズーム:100%　　　　全画面(F11)　　　　　　　　　　[ゲーム説明]ーーーーーーーーーーーーーーーーーーキャラによってHPやS(素早さ)、特性などが違うのでそれぞれの強みを生かしながら戦おう！　　　　　　ーーーーーーーーーーーーーーーーーーーーーーーーそれでは、楽しんでくださいね^^";
            
            toggleButton.addEventListener("click", function () {
                slideContainer.classList.toggle("show");
                if (slideContainer.classList.contains("show")) {
                    typeText();
                } else {
                    explanationTextElement.innerText = "";
                }ikik
            });

            let index = 0;

            function typeText() {
                explanationTextElement.innerText = "";
                index = 0;
                function typeNextChar() {
                    if (index < explanationText.length) {
                        explanationTextElement.innerText += explanationText.charAt(index);
                        index++;
                        setTimeout(typeNextChar, 40);
                    }
                }
                typeNextChar();
            }

            var textHolder = document.getElementsByTagName('div')[0],
                text = textHolder.innerHTML,
                chars = text.length,
                newText = '',
                i;

            for (i = 0; i < chars; i += 1) {
                newText += '<i>' + text.charAt(i) + '</i>';
            }

            textHolder.innerHTML = newText;

            var letters = document.getElementsByTagName('i'),
                flickers = [5, 7, 9, 11, 13, 15, 17],
                randomLetter,
                flickerNumber,
                counter;

            function randomFromInterval(from, to) {
                return Math.floor(Math.random() * (to - from + 1) + from);
            }

            function hasClass(element, cls) {
                return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
            }

            function flicker() {
                counter += 1;
                if (counter === flickerNumber) {
                    return;
                }
                setTimeout(function () {
                    if (!hasClass(randomLetter, 'off')) {
                        randomLetter.className = 'off';
                    } else {
                        randomLetter.className = '';
                    }
                    flicker();
                }, 30);
            }

            (function loop() {
                var rand = randomFromInterval(500, 3000);
                flickerNumber = flickers[randomFromInterval(0, flickers.length - 1)];
                counter = 0;
                randomLetter = letters[randomFromInterval(0, letters.length - 1)];
                flicker();
                setTimeout(loop, rand);
            })();
        });
        function playBattleBGM() {
    const bgmFrame = document.getElementById('bgm-frame');
    if (bgmFrame && bgmFrame.contentWindow) {
        bgmFrame.contentWindow.postMessage({ type: 'changeBGM', src: 'BGM/btl.mp3' }, '*');
    }
}
    </script>
</body>
</html>

