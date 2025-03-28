<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>課題4-2-2-output</title>
</head>
<body>
<?php
if(isset($_POST['meal']) && $_POST['meal'] == '和食'){
    echo '焼き魚、煮物、味噌汁、御飯、果物';

}else if(isset($_POST['meal']) && $_POST['meal'] == '洋食'){
    echo 'ジュース、オムレツ、ハッシュポテト、パン、コーヒー';

}else{
    echo '春巻き、餃子、卵スープ、炒飯、杏仁豆腐';
}
    echo 'をご提供します。';
?>
</body>
</html>