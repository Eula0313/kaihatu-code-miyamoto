<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>練習3-4output</title>
</head>
<body>
<?php
    echo $_POST['price'],'円×';
    echo $_POST['count'],'個=';
    echo $_POST['price']*$_POST['count'],'円';
?>
</body>
</html>