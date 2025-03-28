<?php
const SERVER = 'mysql212.phy.lolipop.lan';
const DBNAME = 'LAA1517492-sample';
const USER = 'LAA1517492';
const PASS = 'Pass0313';

$connect = 'mysql:host='.SERVER . ';dbname='. DBNAME . ';charaset=utf8';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>kadai6-4-3</title>
</head>
<body>
<table>
    <tr><th>商品ID</th><th>商品名</th><th>商品分類</th><th>販売単価</th><th>仕入単価</th><th>登録日</th>
<?php
$pdo=new PDO($connect,USER,PASS);
$str = 'select*from Shohin where shohin_bunrui = ? and torokubi >= ? <=torokubi';
$sql=$pdo->prepare($str);
$sql->execute([$_POST['shohin_bunrui'],$_POST['torokubi']]);
foreach ($sql as $row) {
        echo '<tr>';
        echo '<td>',$row['shohin_id'], '</td>';
        echo '<td>',$row['shohin_mei'], '</td>';
        echo '<td>',$row['shohin_bunrui'],'</td>';
        echo '<td>',$row['hanbai_tanka'],'</td>';
        echo '<td>',$row['shiire_tanka'],'</td>';
        echo '<td>',$row['torokubi'],'</td>';
        echo '</tr>';
        echo "\n";
    }
?>
</body>
</html>