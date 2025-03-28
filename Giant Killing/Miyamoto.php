<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miyamoto.php</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #container {
            position: relative;
            width: 800px;
            height: 600px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
        #main-button {
            position: absolute;
            left: 20px;
            bottom: 20px;
            width: 120px;
            height: 120px;
            background-color: white;
            border: 2px solid black;
            border-radius: 50%;
            background-image: url('image/tin.png'); /* 1の画像 */
            background-size: cover;
            cursor: pointer;
        }
        .popup {
            position: absolute;
            bottom: 120px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 2px solid red;
            display: none;
            cursor: pointer;
            background-size: cover;
        }
        #popup2 {
            left: 10px;
            bottom: 160px;
            background-image: url('image/dan.png'); /* 2の画像 */
        }
        #popup3 {
            left: 125px;
            bottom: 130px;
            background-image: url('image/kana.png'); /* 3の画像 */
        }
        #popup4 {
            left: 170px;
            bottom: 20px;
            background-image: url('image/pro.png'); /* 4の画像! */
        }
    </style>
</head>
<body>

<div id="container">
    <!-- メインボタン -->
    <div id="main-button"></div>

    <!-- ポップアップボタン -->
    <div id="popup2" class="popup" onclick="navigateTo('start.php')"></div>
    <div id="popup3" class="popup" onclick="navigateTo('setting.php')"></div>
    <div id="popup4" class="popup" onclick="navigateTo('profile.php')"></div>
</div>

<script>
    let isVisible = false;

    document.getElementById('main-button').addEventListener('click', function() {
        isVisible = !isVisible;
        togglePopups(isVisible);
    });

    function togglePopups(show) {
        const popups = document.querySelectorAll('.popup');
        popups.forEach(popup => {
            popup.style.display = show ? 'block' : 'none';
        });
    }

    function navigateTo(page) {
        window.location.href = page;
    }
</script>

</body>
</html>
