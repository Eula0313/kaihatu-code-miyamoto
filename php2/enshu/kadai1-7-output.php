<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題1-7-output</title>
</head>
<body>
<?php
if(isset($_POST['yen'])){
    $price = 0;

    if($_POST['yen'] == '未就学児'){
        $price = 0;
    } else if($_POST['yen'] == '子供料金'){
        $price = 500;
    } else if($_POST['yen'] == '小学生～高校生料金'){
        $price = 700;
    } else if($_POST['yen'] == '大人料金'){
        $price = 1000;
    } else if($_POST['yen'] == '65歳以上'){
        $price = 600;
    }

    if(isset($_POST['long']) && ($_POST['yen'] == '子供料金' || $_POST['yen'] == '小学生～高校生料金')){
        $price = $price / 2;
    }

    if(isset($_POST['vac']) && $_POST['yen'] != '未就学児'){
        $price += 100;
    }

    echo 'ご来場ありがとうございます！料金は'. $price .'円です';

} 
?>
</body>
</html>
