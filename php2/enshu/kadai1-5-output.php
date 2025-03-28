<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>課題1-5-output</title>
</head>
<body>
<?php
if(isset($_POST['yen']) && $_POST['yen'] == '未就学児'){
    echo 'ご来場ありがとうございます！料金は0円です';

}else if(isset($_POST['yen']) && $_POST['yen'] == '子供料金'){
    echo 'ご来場ありがとうございます！料金は500円です';

}else if(isset($_POST['yen']) && $_POST['yen'] == '小学生～高校生料金'){
    echo 'ご来場ありがとうございます！料金は700円です';

}else if(isset($_POST['yen']) && $_POST['yen'] == '大人料金'){
    echo 'ご来場ありがとうございます！料金は1000円です';

}else{
    echo 'ご来場ありがとうございます！料金は600円です';
}

?>
</body>
</html>