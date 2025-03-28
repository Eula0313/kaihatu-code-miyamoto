<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // ログインしていないか、管理者でない場合はリダイレクト
    header("Location: login.php");
    exit();
}

// 管理者専用のコンテンツ
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ダッシュボード</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <?php require "admin_header.php"; ?>
    <div class="content">
        <?php require "admin_side.php"; ?>
        <div class="flex-content">
            <h1>管理者ダッシュボード</h1>
            <p>ようこそ、<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん</p>
            <!-- 管理者専用のコンテンツをここに追加 -->
        </div>
    </div>
</body>

</html>