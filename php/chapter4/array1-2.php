<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>array1-2</title>
</head>
<body>
<?php
    $fruits = [
        'りんご'=>100,
        'イチゴ'=>298,
        '桃'=>160,
        'なし'=>120,
        'ぶどう'=>210
    ];
    foreach($fruits as $key =>$value){
        echo $key,'は',$value,'円です<br>';
    }
    ?>
</body>
</html>