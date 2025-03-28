<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>kadai3-2</title>
</head>
<body>
<table>
    <tr><th>商品番号</th><th>商品名</th><th>カテゴリー</th><th>商品価格</th></tr>
<?php
$pdo = new PDO('mysql:host=mysql215.phy.lolipop.lan;dbname=LAA1517492-shop;charaset=utf8',
'LAA1517492', 'Pass0313');

$sql = $pdo->query('select * FROM item');
$sql->execute();
$result = $sql->fetchAll();
foreach ($result as $row) {
    echo '<tr>';
    echo '<td>', $row['id'], '</td>';
    echo '<td>', $row['name'], '</td>';
    echo '<td>', $row['kubun'], '</td>';
    echo '<td>', $row['price'], '</td>';
    echo '</tr>';
}

?>
</table> 
</body>
</html>
