<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>課題3-4-1-output</title>
</head>
<body>
<?php
    echo '商品名：',$_POST['name'],'<br>';
    echo '金額：',$_POST['price'],'円<br>';
    echo '税込み金額：',$_POST['price']*1.1,'円';
?>
</body>
</html>