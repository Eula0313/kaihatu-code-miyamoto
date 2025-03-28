<?php
const SERVER = 'mysql215.phy.lolipop.lan';
const DBNAME = 'LAA1517492-shop';
const USER = 'LAA1517492';
const PASS = 'Pass0313';

    $connect = 'mysql:host='. SERVER . ';dbname='. DBNAME . ';charset=utf8';
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>課題6-7-output</title>
	</head>
	<body>
<?php
    $pdo=new PDO($connect, USER, PASS);
$sql=$pdo->prepare('delete from Shohindata where data=?');
if($sql->execute([$_GET['data']])) {
    echo '削除に成功しました。';
}else{
    echo '削除に失敗しました。';
}
?>
    <br><hr><br>
	<table>
		<tr><th>商品番号</th><th>商品名</th><th>カテゴリ</th><th>価格</th></tr>
<?php
    foreach ($pdo->query('select * from Shohindata') as $row) {
        echo '<tr>';
        echo '<td>',$row['data'], '</td>';
        echo '<td>',$row['data_name'], '</td>';
        echo '<td>',$row['data_kategori'], '</td>';
        echo '<td>',$row['data_price'], '</td>';
        echo '</tr>';
        echo "\n";
    }
?> 
</table>
    <form action="kadai6-7-input.php" method="post">
        <button type="submit">削除画面へ戻る</button>
    </form>
    </body>
</html>

