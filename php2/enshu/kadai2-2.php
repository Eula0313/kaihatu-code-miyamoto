<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>kadai2-2</title>
</head>
<body>
<?php
    $kubun = [
    '未就学児' =>1,
    '子供料金' =>2,
    '小学生～高校生料金' =>3,
    '大人料金' =>4,
    '65歳以上'=>5
    ];
    foreach($kubun as $key =>$value){
        echo'<option value="',$key,'">',$key,'</option>';;
    }

?>
</body>
</html>
