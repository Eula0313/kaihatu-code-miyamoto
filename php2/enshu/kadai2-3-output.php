<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>kadai2-3-output</title>
</head>
<body>
<?php
$price = [
    '未就学児' =>1,
    '子供料金' =>2,
    '小学生～高校生料金' =>3,
    '大人料金' =>4,
    '65歳以上'=>5
    ];
   echo '入場する人は',$_POST['price'],'です';

?>
</body>
</html>