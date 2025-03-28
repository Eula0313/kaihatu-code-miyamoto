<?php
session_start();
require '../db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST['userid'];
    $password = $_POST['password'];

    // IDが「0000」のユーザーのみログインを許可
    if ($userid === '2201205' || '5555555') {
        $stmt = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
        $stmt->execute([$userid]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['user_name'];
            header("Location: admin.php");
            exit();
        } else {
            $error = "ユーザー名またはパスワードが違います。";
        }
    } else {
        $error = "このユーザーIDではログインできません。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <div class="header">
        <h1>ログイン</h1>
    </div>
    <div class="login-content">
        <div class="login-flex-content">
            <?php if (isset($error)) echo '<p style="color:red;">' . $error . '</p>'; ?>
            <form method="post" class="login-form" action="">
                <label for="userid">ユーザーID</label>
                <input type="text" name="userid" class="login-id" id="userid" required>
                <br>
                <label for="password">パスワード</label>
                <input type="password" name="password" class="login-pass" id="password" required>
                <br>
                <input type="submit" class="login-button" value="ログイン">
            </form>
        </div>
    </div>
</body>

</html>
