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
    for ($i=0;$i<5;$i++){
        echo $color[$i],'<br>';
    }
    ?>
</body>
</html>