<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            font-size: 1rem;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
        }

        .button {
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            background-color: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 45%;
        }

        .button:hover {
            background-color: #555;
        }

        .button-back {
            background-color: #bbb;
        }

        .button-back:hover {
            background-color: #999;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>新規登録</h1>
        <iframe src="bgm_player.php" style="display:none;" id="bgm-frame"></iframe>

        <form action="/register" method="POST">
            <label for="username">ユーザー名</label>
            <input type="text" id="username" name="username" required>

            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" required>

            <div class="buttons">
                <button type="button" class="button button-back" onclick="history.back()">戻る</button>
                <button type="submit" class="button">登録</button>
            </div>
        </form>
    </div>
</body>
</html>
