<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題3-3-5output</title>
</head>
<body>
    <?php
    echo 'こんにちは、',$_POST['user'], 'さん!<br><br>';
    echo '好きなことは、<br><br>';

    for ($i=1; $i<=10; $i++) {
        
        echo '・', $_POST['like'.$i], 'ですね。<br>';
}

    ?>
</body>
</html>