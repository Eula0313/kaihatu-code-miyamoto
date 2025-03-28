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
    <title>kadai6-5-1-output</title>
</head>
<body>
<?php
$pdo=new PDO($connect,USER,PASS);
$sql=$pdo->prepare('insert into Shohindata(data, data_kategori, data_name, data_price) value(?, ?, ?, ?)');
$existingData = $pdo->prepare('select count(*) from Shohindata where data = ?');
$existingData->execute([$_POST['data']]);
$count = $existingData->fetchColumn();
if ($count > 0) {
    echo '<font color="red">既に登録されている商品番号です</font>';
}else if(!preg_match('/^\d+$/',$_POST['data'])){
    echo '商品番号を整数で入力して下さい';
}else if (empty($_POST['data_kategori'])){
    echo 'カテゴリを入力してください';
}else if (empty($_POST['data_name'])){
    echo '商品名を入力してください';
}else if (!preg_match('/^[0-9]+$/',$_POST['data_price'])){
    echo '商品価格を整数で入力して下さい。';
}else if ($sql->execute([$_POST['data'],$_POST['data_kategori'],$_POST['data_name'],$_POST['data_price']])){
    echo '<font color="red">追加に成功しました。</font>';
}else {
    echo '<font color="red">追加に失敗しました。</font>';
}
?>
<br><hr><br>
<table>
    <tr><th>商品番号</th><th>カテゴリ</th><th>商品名</th><th>価格</th></tr>
<?php
foreach ($pdo->query('select * from Shohindata') as $row){
        echo '<tr>';
        echo '<td>',$row['data'], '</td>';
        echo '<td>',$row['data_kategori'], '</td>';
        echo '<td>',$row['data_name'], '</td>';
        echo '<td>',$row['data_price'],'</td>';
        echo '</tr>';
        echo "\n";
    }
?>
</table>
<form action="kadai6-5-1-input.php" method="post">
<button type="submit">追加画面へ戻る</button>
</form>
</body>
</html>