<?php
session_start();

// データベース接続情報
$pdo = new PDO('mysql:host=mysql305.phy.lolipop.lan;dbname=LAA1517492-fuwaroke;charset=utf8', 'LAA1517492', 'Pass0313');

// パスワード変更処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // セッションから以前のエラーを削除
    unset($_SESSION['password_change_error']);

    // 入力データの確認
    $user_id = $_POST['user_id'] ?? null;
    $new_password = $_POST['new_password'] ?? null;
    $confirm_password = $_POST['confirm_password'] ?? null;

    // ユーザーIDが存在するかチェック
    $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
    $sql->execute([$user_id]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // 新しいパスワードが確認用と一致するかチェック
        if ($new_password === $confirm_password) {
            // パスワードをハッシュ化
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // ハッシュ化された新しいパスワードをデータベースに保存
            $update_sql = $pdo->prepare('UPDATE user SET password = ? WHERE user_id = ?');
            $update_sql->execute([$hashed_password, $user_id]);
            
            $_SESSION['password_change_success'] = "パスワードが変更されました";
            // セッションの書き込みを閉じる
            session_write_close();
            // 成功メッセージを表示するために、ユーザーをリダイレクトせずそのまま表示する
        } else {
            $_SESSION['password_change_error'] = "新しいパスワードと確認用パスワードが一致しません";
        }
    } else {
        $_SESSION['password_change_error'] = "ユーザーIDが見つかりません";
    }
}
require 'header.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="image/favicon.ico">
    <title>パスワード変更</title>
    <!-- CSSファイルをリンク -->
    <link rel="stylesheet" href="css/passchange.css">
</head>
<body>
    <div class="change-password-container">
        <h1 class="change-password-header">パスワード変更</h1>
        <?php
        if (isset($_SESSION['password_change_error'])) {
            echo '<p class="error">' . htmlspecialchars($_SESSION['password_change_error']) . '</p>';
            unset($_SESSION['password_change_error']); 
        } elseif (isset($_SESSION['password_change_success'])) {
            echo '<p class="success">' . htmlspecialchars($_SESSION['password_change_success']) . '</p>';
            unset($_SESSION['password_change_success']); 
        }
        ?>
        <form method="POST" action="passchange.php">
            <input type="text" name="user_id" class="input-field" placeholder="学籍番号" required><br>
            <input type="password" name="new_password" class="input-field" placeholder="新しいパスワード" required><br>
            <input type="password" name="confirm_password" class="input-field" placeholder="新しいパスワード（確認用）" required><br>
            <button type="submit" class="change-password-button">パスワード変更</button>
            <p class="passchange-link"><a href="login.php">ログイン画面へ</a></p>

        </form>
    </div>
</body>
</html>
