<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>kadai2-5-output</title>
</head>
<body>
<?php
$kubun = [
    '未就学児' =>0,
    '子供料金' =>500,
    '小学生～高校生料金' =>700,
    '大人料金' =>1000,
    '65歳以上'=>600
    ];
    $a = $_POST['kubun'];

    $b = $kubun[$a];
   echo '入場料は',$b,'円です';
   
?>
</body>
</html>