<?php session_start()?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>kadai4-3</title>
</head>
<body>
    <?php
    echo $_SESSION['user']['from'],'出身の',$_SESSION['user']['name'],'さん、さようなら。<br>';
    echo 'ログアウトしました。';
    session_destroy();
    ?>
<a href="kadai4-3-login.php">ログイン</a>
</body>
</html>
