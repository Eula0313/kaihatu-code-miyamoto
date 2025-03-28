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
    <title>ren6-5-output</title>
</head>
<body>
<button onclick="location.href='ren6-8-top.php'">トップへ戻る</button>
<?php
$pdo=new PDO($connect,USER,PASS);
$sql=$pdo->prepare('insert into product(id, name, price) value(?, ?, ?)');
if(!preg_match('/^\d+$/',$_POST['id'])){
    echo '商品番号を整数で入力して下さい';
}else if (empty($_POST['name'])){
    echo '商品名を入力してください';
}else if (!preg_match('/^[0-9]+$/',$_POST['price'])){
    echo '商品価格を整数で入力して下さい。';
}else if ($sql->execute([$_POST['id'],$_POST['name'],$_POST['price']])){
    echo '<font color="red">追加に成功しました。</font>';
}else {
    echo '<font color="red">追加に失敗しました。</font>';
}
?>
<br><hr><br>
<table>
    <tr><th>商品番号</th><th>商品名</th><th>価格</th></tr>
<?php
foreach ($pdo->query('select * from product') as $row){
        echo '<tr>';
        echo '<td>',$row['id'], '</td>';
        echo '<td>',$row['name'], '</td>';
        echo '<td>',$row['price'],'</td>';
        echo '</tr>';
        echo "\n";
    }
?>
</table>
<form action="ren6-5-input.php" method="post">
<button type="submit">追加画面へ戻る</button>
</form>
</body>
</html>