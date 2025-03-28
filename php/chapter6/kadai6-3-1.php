<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>kadai6-3-1</title>
</head>
<body>
<?php
$pdo=new PDO('mysql:host=mysql215.phy.lolipop.lan;dbname=LAA1517492-shop;charaset=utf8',
'LAA1517492','Pass0313');
foreach ($pdo->query('select*from product')as $row) {
    echo '<p>';
    echo $row['id'], ':';
    echo $row['name'], ':';
    echo $row['price'];
    echo '</p>';
}
?>
</body>
</html>