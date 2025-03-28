<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>課題4-2-4-output</title>
</head>
<body>
<?php
    echo '<h1>',$_POST['name'],'</h1><br>';
    switch ($_POST['meal']) {

        case '1';
        echo '簡単<br><br>';
        break;
    
        case '2';
        echo '普通<br><br>';
        break;
    
        case '3';
        echo '難しい<br><br>';
        break;
    }
    echo $_POST['yen'],'<br><br>';
    echo nl2br($_POST['text']);
?>
</body>
</html>