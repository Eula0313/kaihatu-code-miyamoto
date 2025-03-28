<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// デバッグメッセージを表示
//echo "<p>header.php内のpost_id: " . (isset($_SESSION['post_id']) ? htmlspecialchars($_SESSION['post_id'], ENT_QUOTES, 'UTF-8') : '未設定') . "</p>";
?>
<!-- ヘッダーのHTMLコード -->

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image/favicon.ico">
    <title>サイトのタイトル</title>
    <link rel="stylesheet" href="style.css"> <!-- スタイルシートへのリンク -->
    <style>
        /* ヘッダーのスタイルをここに追加 */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #0068b7;
            color: white;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .search {
            
            display: flex;
            align-items: center;
            margin-left: 850px;
            flex-grow: 1;
        }
        .search input[type="text"] {
            padding: 5px;
            margin-right: 10px;
        }
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }
        .hamburger div {
            width: 25px;
            height: 3px;
            background-color: white;
            margin: 4px 0;
        }
        .nav-menu {
            display: none;
            flex-direction: column;
            position: absolute;
            top: 50px;
            right: 20px;
            background-color: #333;
            border: 1px solid #444;
        }
        .nav-menu a {
            color: white;
            padding: 10px;
            text-decoration: none;
            text-align: center;
        }
        .nav-menu a:hover {
            background-color: #444;
        }
        .logo {
    display: flex;
    align-items: baseline;
}

.logo-large {
    font-size: 1.6em; /* 好みの大きさに調整してください */
    font-weight: bold;
}

.logo-small {
    font-size: 0.8em; /* 好みの大きさに調整してください */
    margin-left: 2px; /* 適度な間隔を追加 */
    letter-spacing: 0.0005em;
}

        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }
        }
    </style>
</head>
<body>
    <header class="header">
    <div class="logo">
    <span class="logo-large">ASO</span>
    <span class="logo-small">ちゃんねる</span>
</div>

        
        
        </nav>
    </header>
    <script>
        function toggleMenu() {
            var menu = document.getElementById('navMenu');
            if (menu.style.display === 'flex') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'flex';
            }
        }
    </script>
</body>
</html>
