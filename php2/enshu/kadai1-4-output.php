<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>課題1-4-output</title>
</head>
<body>
<?php
if(isset($_POST['aka'])){
    echo '赤：選択<br>';
}else{
    echo '赤：未選択<br>';
}
if(isset($_POST['ao'])){
    echo '青：選択<br>';
}else{
    echo '青：未選択<br>';
}
if(isset($_POST['midori'])){
    echo '緑：選択<br>';
}else{
    echo '緑：未選択<br>';
}
?>
</body>
</html>