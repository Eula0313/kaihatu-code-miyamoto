<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>kadai6-4-1</title>
</head>
<body>
<table>
    <tr><th>商品番号</th><th>商品名</th><th>商品価格</th>
<?php
$pdo=new PDO('mysql:host=mysql215.phy.lolipop.lan;dbname=LAA1517492-shop;charaset=utf8',
'LAA1517492','Pass0313');
$sql=$pdo->prepare('select*from product where price >= ?');
$sql->execute([$_REQUEST['price']]);
foreach ($sql as $row) {
        echo '<tr>';
        echo '<td>',$row['id'], '</td>';
        echo '<td>',$row['name'], '</td>';
        echo '<td>',$row['price'],'</td>';
        echo '</tr>';
    }
?>
</body>
</html>