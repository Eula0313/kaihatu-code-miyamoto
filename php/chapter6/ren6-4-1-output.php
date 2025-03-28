<?php
const SERVER = 'mysql215.phy.lolipop.lan';
const DBNAME = 'LAA1517492-shop';
const USER = 'LAA1517492';
const PASS = 'Pass0313';

$connect = 'mysql:host='.SERVER . ';dbname='. DBNAME . ';charaset=utf8';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ren6-4-1ooutput</title>
</head>
<body>
<table>
    <tr><th>商品番号</th><th>商品名</th><th>商品価格</th>
<?php
$pdo=new PDO($connect,USER,PASS);
$str = 'select*from product where name = ? or price >= ?';
$sql=$pdo->prepare($str);
$sql->execute([$_POST['keyword'],$_POST['price']]);
foreach ($sql as $row) {
        echo '<tr>';
        echo '<td>',$row['id'], '</td>';
        echo '<td>',$row['name'], '</td>';
        echo '<td>',$row['price'],'</td>';
        echo '</tr>';
        echo "\n";
    }
?>
</body>
</html>