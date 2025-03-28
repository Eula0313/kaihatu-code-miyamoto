<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>loop1-3-output</title>
</head>
<body>
<?php
    $i=1;
    while($i<=$_POST['name']){
        echo $i,'<br>';
        $i++;
    }
?>
</body>
</html>