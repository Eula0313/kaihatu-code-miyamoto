<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>課題4-1-2-1output</title>
</head>
<body>
<?php
if(isset($_POST['age']) && $_POST['age'] >= 18){
    echo '名前：',$_POST['user'];
    echo '<br>年齢：',$_POST['age'];
    echo '<br>成人です';
}else{
    echo '名前：',$_POST['user'];
    echo '<br>年齢：',$_POST['age'];
    echo '<br>未成年です';
}
?>
</body>
</html>