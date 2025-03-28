<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>練習4-2-1-output</title>
</head>
<body>
<?php
switch ($_POST['meal']) {
    case '1';
    echo '利用してるOS<br>','Mac<br>';
    break;

    case '2';
    echo '利用してるOS<br>','Windows<br>';
    break;

    case '3';
    echo '利用してるOS<br>','Linax<br>';
    break;
}
switch ($_POST['a']) {

    case '1';
    echo '利用してるブラウザ<br>','Google Chrome<br>';
    break;

    case '2';
    echo '利用してるブラウザ<br>','Firefox<br>';
    break;

    case '3';
    echo '利用してるブラウザ<br>','Microsoft Edge<br>';
    break;

    case '4';
    echo '利用してるブラウザ<br>','Safari<br>';
    break;
}
    ?>
</body>
</html>