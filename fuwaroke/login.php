<?php
ob_start();
session_start();
require 'db-connect.php';
require 'header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($user_id && $password) {
        try {
            $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = :user_id');
            $sql->execute([':user_id' => $user_id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                if (password_verify($password, $row['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['user'] = ['user_id' => $row['user_id']];
                    header('Location: home.php');
                    exit;
                } else {
                    $_SESSION['login_error'] = "パスワードが一致しません";
                }
            } else {
                $_SESSION['login_error'] = "ユーザーIDが見つかりません";
            }
        } catch (PDOException $e) {
            error_log("データベース接続エラー: " . $e->getMessage());
            $_SESSION['login_error'] = "システムエラーが発生しました。後ほど再試行してください。";
        }
    } else {
        $_SESSION['login_error'] = "学籍番号とパスワードを入力してください";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="css/header.css"> <!-- headerのCSS -->
    <link rel="stylesheet" href="css/login.css?v=1.0"> <!-- loginのCSS（バージョンを追加） -->
</head>
<body class="login-page">
    <div class="login-container">
        <h1 class="login-header">ログイン</h1>
        <?php
        if (isset($_SESSION['login_error'])) {
            echo '<p class="error-message">' . htmlspecialchars($_SESSION['login_error']) . '</p>';
            unset($_SESSION['login_error']);
        }
        ?>
        <form method="POST" action="login.php">
            <label for="user_id" class="form-label">学籍番号</label>
            <input type="text" id="user_id" name="user_id" class="input-field" placeholder="学籍番号" required><br>
            <label for="password" class="form-label">パスワード</label>
            <input type="password" id="password" name="password" class="input-field" placeholder="パスワード" required><br>
            <button type="submit" class="login-button">ログイン</button>
        </form>
        <p class="forgot-password-link"><a href="passchange.php">パスワードを忘れた場合</a></p>
        <p class="toroku-link"><a href="touroku.php" class="register-link">新規アカウントを作成する</a></p>
    </div>
</body>
</html>
<?php ob_end_flush(); ?>
