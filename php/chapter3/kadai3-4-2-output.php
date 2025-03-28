<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>課題3-4-2-output</title>
</head>
<body>
<?php
    echo '<h1>四則演算の結果を表示ます</h1>';
    echo '・足し算：',$_POST['a'],'+',$_POST['b'],'=',$_POST['a']+$_POST['b'],'<br>';
    echo '・引き算：',$_POST['a'],'-',$_POST['b'],'=',$_POST['a']-$_POST['b'],'<br>';
    echo '・掛け算：',$_POST['a'],'×',$_POST['b'],'=',$_POST['a']*$_POST['b'],'<br>';
    echo '・割り算：',$_POST['a'],'÷',$_POST['b'],'=',$_POST['a']/$_POST['b'],'<br>';
?>
</body>
</html>