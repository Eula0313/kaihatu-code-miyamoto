<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>for文を使って配列の値を表示</title>
</head>
<body>
<?php
    $color = [
        'ホワイト',
        'ブルー',
        'レッド',
        'イエロー',
        'ブラック',
    ];
    foreach($color as $c){
        echo $c,'<br>';
    }
    ?>
</body>
</html>