<?php
// login.php

require 'db-connect.php';

// セッションを開始
session_start();

// クッキーがセットされているか確認
if (isset($_COOKIE['user_id'])) {
    // クッキーが存在すれば、セッションにユーザーIDを設定し top.php へリダイレクト
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    header('Location: top.php');
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = trim($_POST['user_name']);
    $password = trim($_POST['password']);

    // バリデーション
    if (!empty($user_name) && !empty($password)) {
        try {
            // DBからユーザーを取得
            $sql = "SELECT user_id, password FROM users WHERE user_name = :user_name";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_name', $user_name);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // クッキーにユーザーIDを保存（例: 30日間有効）
                setcookie('user_id', $user['user_id'], time() + (30 * 24 * 60 * 60), '/', '', false, true);

                // セッションにユーザーIDを保存
                $_SESSION['user_id'] = $user['user_id'];

                // ログイン成功後 top.php へリダイレクト
                header('Location: top.php');
                exit;
            } else {
                $error_message = 'ユーザー名かパスワードが違います。';
            }
        } catch (PDOException $e) {
            $error_message = 'ログイン中にエラーが発生しました。';
        }
    } else {
        $error_message = '全てのフィールドを入力してください。';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <style>
        /* リセットスタイル */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: url('image/toroku.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #ffffff;
        }

        .container {
            background: rgba(49 47 47 / 80%); /* 背景を濃く調整 */
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #ffffff;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            font-size: 16px; /* フォントサイズを調整 */
            color: #ffffff;
            margin-bottom: 5px;
            align-self: flex-start;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #ffffff; /* 白い枠線を追加 */
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.2); /* 半透明の白背景 */
            font-size: 16px;
            color: #ffffff;
            transition: background 0.3s, box-shadow 0.3s;
        }

        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: #dddddd; /* プレースホルダーを薄い灰色に */
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            background: rgba(255, 255, 255, 0.3); /* フォーカス時の背景を少し明るく */
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            background: linear-gradient(135deg, #0056b3, #004494); /* 濃い青に変更 */
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        button:hover {
            background: linear-gradient(135deg, #004494, #003377); /* ホバー時にさらに濃く */
            box-shadow: 0 5px 15px rgba(0, 51, 119, 0.4);
        }

        .back-button {
            width: 100%;
            margin-top: 10px;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid #ffffff;
            border-radius: 25px;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: #ffffff;
            color: #000;
        }

        p {
            font-size: 14px;
            color: #ff6b6b; /* エラーメッセージを目立たせる */
            margin-top: 10px;
        }
    </style>
    <?php include 'header.php'; ?>
</head>
<body>
<iframe src="bgm.html" style="display:none;" id="bgm-frame"></iframe>
    <div class="container">
        <h1>ログイン</h1>
        <form method="post">
            <label for="user_name">ユーザー名</label>
            <input type="text" name="user_name" id="user_name" placeholder="ユーザー名を入力" required>
            <label for="password">パスワード</label>
            <input type="password" name="password" id="password" placeholder="パスワードを入力" required>
            <?php if (!empty($error_message)) { echo '<p>' . htmlspecialchars($error_message) . '</p>'; } ?>
            <button type="submit">ログイン</button>
        </form>
        <button class="back-button" onclick="window.location.href='menu.php';">戻る</button>
    </div>
   
</body>
</html>
