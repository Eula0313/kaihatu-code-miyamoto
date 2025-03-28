<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>練習4-2-output</title>
</head>
<body>
<?php
switch ($_POST['meal']) {
    case '和食';
    echo '焼き魚、煮物、味噌汁、御飯、果物';
    break;

    case '洋食';
    echo 'ジュース、オムレツ、ハッシュポテト、パン、コーヒー';
    break;

    case '中華';
    echo '春巻き、餃子、卵スープ、炒飯、杏仁豆腐';
    break;
}
    echo 'をご提供します。';
?>
</body>
</html>