<?php session_start()?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>kadai4-3</title>
</head>
<body>
    <?php
    echo $_POST['name'],'さん、こんにちは。<br>';
    echo '出身地は',$_POST['from'],'ですね。';
    $_SESSION['user']=['name'=>$_POST['name'],'from'=>$_POST['from']];
    ?>
    <br>
<a href="kadai4-3-logout.php">ログアウト</a>
</body>
</html>
