<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題1-6-output</title>
</head>
<body>
    <?php
    if (empty($_POST['name'])) {
        echo 'ログイン名が入力されていません';
    } else if (empty($_POST['pass'])) {
        echo 'パスワードが入力されていません';
    } else if ($_POST['name'] == 'admin' && $_POST['pass'] == '1234') {
        echo $_POST['name'], 'さん、ようこそ！';
    } else {
        echo 'ログイン名かパスワードが間違っています';
    }
    
    ?>
</body>
</html>
