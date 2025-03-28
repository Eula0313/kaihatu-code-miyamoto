<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>kadai6-3-1</title>
</head>
<body>
    <table>
    <tr><th>商品ID</th><th>商品名</th><th>商品分類</th><th>販売単価</th><th>仕入単価</th><th>登録日</th>
<?php 
$pdo=new PDO('mysql:host=mysql212.phy.lolipop.lan;dbname=LAA1517492-sample;charaset=utf8',
'LAA1517492','Pass0313');
foreach ($pdo->query('select*from Shohin')as $row) {
    echo '<tr>';
    echo '<td>',$row['shohin_id'], '</td>';
    echo '<td>',$row['shohin_mei'], '</td>';
    echo '<td>',$row['shohin_bunrui'],'</td>';
    echo '<td>',$row['hanbai_tanka'],'</td>';
    echo '<td>',$row['shiire_tanka'],'</td>';
    echo '<td>',$row['torokubi'],'</td>';
    echo '</tr>';
}
?>
</table>
</body>
</html>