<?php
session_start();
require '../db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_image'])) {
    $target_dir = "image/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        $stmt = $pdo->prepare("UPDATE user SET icon = ? WHERE user_id = ?");
        $stmt->execute([$target_file, $_SESSION['user']['user_id']]);
        $_SESSION['user']['icon'] = $target_file;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php require "admin_header.php"; ?>
    <div class="content-mypage">
        <?php require "admin_side.php"; ?>
        <div class="flex-content-mypage">
            <?php require "mypage.php"; ?>
            <form id="deleteForm" action="user_delete.php" method="post" onsubmit="return confirmDelete()">
                <input type="hidden" name="user_id" value="<?php echo $_GET['user_id']; ?>">
                <button type="submit" class="user-delete-button">削除</button>
            </form>

        </div>
    </div>
</body>
<script>
    function confirmDelete() {
        return confirm("本当にこのユーザーを削除しますか？この操作は元に戻せません。");
    }
</script>

</html>
