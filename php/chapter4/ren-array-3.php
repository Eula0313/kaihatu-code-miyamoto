<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>for文を使って配列の値を表示</title>
</head>
<body>
<?php
    $color = [
        'ホワイト'=>100,
        'ブルー'=>200,
        'レッド'=>300,
        'イエロー'=>400,
        'ブラック'=>500
    ];
    foreach($color as $key =>$value){
        echo $key,'の値は',$value,'です<br>';
    }
    ?>
</body>
</html>