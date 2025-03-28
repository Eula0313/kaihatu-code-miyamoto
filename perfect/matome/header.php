<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style.css">
    <title>SHINE</title>
    <style>
        body {
            margin: 0;
        }

        .header {
            position: relative;
            width: 100%;
            z-index: 2;
            color: white;
            text-align: center;
            padding: 0px;
            overflow: hidden; 
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: rgba(0, 0, 0, 0.5); 
            position: relative;
        }

        .header-logo img {
        max-width: 100%;
        filter: brightness(100%) saturate(0%) grayscale(100%) invert(100%);
        }
        .header-cart img,
        .header-mypage img,
        .header-favo img {
        max-width: 100%;
        filter: brightness(100%) saturate(0%) grayscale(100%) invert(100%);
        }


        .header-kensaku {
            flex-grow: 1;
            margin: 0 10px;
        }

    

        .header-button {
            display: flex;
            gap: 10px;
        }

        .header-button img {
            max-width: 100%;
        }

        .video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1; /* 動画をヘッダーの背後に配置 */
        }

        video {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    <div class="header">
        <header>
            <div class="video-container">
                <video autoplay muted loop>
                    <source src="./mp4/oonishi111.mp4" type="video/mp4">
                </video>
            </div>
            <div class="header-logo">
                <a href="index.php">
                    <img src="./image/header/logo.png" class="logo" alt="">
                </a>
            </div>
            <div class="header-kensaku">
                <form action="index.php" method="post">
                    <input id="kensaku" name="kensaku" type="text" placeholder="キーワードを入力">
                </form>
            </div>
            <div class="header-button">
                <div class="header-cart">
                    <a href="cart-show.php"><img src="./image/header/cart.png" width="50px" height="50px"></a>
                </div>
                <div class="header-mypage">
                    <a href="toroku.php"><img src="./image/header/mypage.png" width="50px" height="50px"></a>
                </div>
                <div class="header-favo">
                    <a href="favorite-show.php"><img src="./image/header/favo.png" width="50px" height="50px"></a>
                </div>
            </div>
        </header>
    </div>
</body>
</html>
