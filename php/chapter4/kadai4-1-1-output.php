<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>課題4-1-1output</title>
</head>
<body>
<?php
if(isset($_POST['mail'])){
    echo $_POST['user'],'さん、確認ありがとうございます。';
}else{
    echo $_POST['user'],'さん、確認お願いします。<br>';
    echo '<br><button type="submit">再度確認する</button>';
}
?>
</body>
</html>