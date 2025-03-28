<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>練習4-1-output</title>
</head>
<body>
<?php
if(isset($_POST['mail'])){
    echo 'お買い得情報のメールをお送りさせて頂きます。';
}else{
    echo 'お買い得情報のメールをお送りさせて頂きません。';
}
?>
</body>
</html>