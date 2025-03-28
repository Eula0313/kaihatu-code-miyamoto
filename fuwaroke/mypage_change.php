<?php
ob_start();
session_start();
require 'db-connect.php';
require 'header.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['user_id'];

$stmt = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$schools = $pdo->query('SELECT school_id, school_name FROM school_category')->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['password'];
    if (!empty($new_password) && $new_password !== $user['password']) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    } else {
        $hashed_password = $user['password'];
    }

    $stmt = $pdo->prepare("UPDATE user SET user_name=?, password=?, school_id=?, introduction=?, portfolio=? WHERE user_id=?");
    $stmt->execute([
        $_POST['user_name'] ?? '',
        $hashed_password,
        $_POST['school_id'] ?? '',
        $_POST['introduction'] ?? '',
        $_POST['portfolio'] ?? '',
        $user_id
    ]);

    $updatedData = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
    $updatedData->execute([$user_id]);
    $upuser = $updatedData->fetch(PDO::FETCH_ASSOC);
    $_SESSION['user'] = $upuser;

    header("Location: mypage.php"); 
    exit();
}

$user_name = $user['user_name'] ?? '';
$password = ''; 
$school_id = $user['school_id'] ?? '';
$introduction = $user['introduction'] ?? '';
$portfolio = $user['portfolio'] ?? '';
$icon = $user['icon'] ?? '/image/default.jpg';
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/change.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>マイページ変更</title>
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('profileImage');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);

            const formData = new FormData();
            formData.append('profile_image', event.target.files[0]);

            fetch('upload_image.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('profileImage').src = data.url;
                } else {
                    alert('画像のアップロードに失敗しました。');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('画像のアップロードに失敗しました。');
            });
        }
    </script>
</head>
<body>
<?php require 'side.php'; ?>
<?php require 'right.php'; ?>
<div class="container">
    <div class="content">
        <h1>Profile 変更</h1>
        <form method="post" action="mypage_change.php" enctype="multipart/form-data">
            <div class="profile-image">
                <img src="<?= htmlspecialchars($icon) ?>" alt="プロフィール画像" id="profileImage" onclick="document.getElementById('profileImageInput').click();">
                <input type="file" name="profile_image" id="profileImageInput" style="display: none;" onchange="previewImage(event);">
                <br>
                <br>
                <br>
            </div>
            <div class="form-group">
                <label for="user_name">名前</label>
                <input type="text" id="user_name" name="user_name" placeholder="名前を入力" required value="<?= htmlspecialchars($user_name) ?>">
            </div>
            <div class="form-group">
                <label for="password">新しいパスワード（変更が必要な場合のみ）</label>
                <input type="password" id="password" name="password" placeholder="パスワードを入力">
            </div>
            <div class="form-group">
                <label for="school_id">学校名</label>
                <select id="school_id" name="school_id" required>
                    <?php foreach ($schools as $school): ?>
                        <option value="<?= htmlspecialchars($school['school_id']) ?>" <?= $school['school_id'] == $school_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($school['school_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="introduction">自己紹介</label>
                <textarea id="introduction" name="introduction" placeholder="(趣味・特技、学んでいること・取得検定、就活状況など、1000文字以内)" rows="5" cols="50" maxlength="1000"><?= htmlspecialchars($introduction) ?></textarea>
            </div>
            <div class="form-group">
                <label for="portfolio">ポートフォリオ</label>
                <textarea id="portfolio" name="portfolio" placeholder="1000文字以内" rows="5" cols="50" maxlength="1000"><?= htmlspecialchars($portfolio) ?></textarea>
            </div>
            <div class="button-group">
                <button type="button" class="btn" onclick="history.back()">戻る</button>
                <button type="submit" class="btn">変更を確定</button>
                <br>
                
            </div>
        </form>
    </div>
</div>
</body>
</html>
