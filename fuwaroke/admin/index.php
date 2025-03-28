<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホームページ</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <h1>ホームページ</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>ようこそ、<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん</p>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <p><a href="admin.php">管理者メニュー</a></p>
        <?php endif; ?>
        <p><a href="logout.php">ログアウト</a></p>
    <?php else: ?>
        <p><a href="login.php">ログイン</a></p>
    <?php endif; ?>
</body>
</html>
