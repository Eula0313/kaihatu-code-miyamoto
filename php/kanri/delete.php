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
		<title>delete</title>
        <link rel="stylesheet" href="css/style.css">
	</head>
	<body>
<?php
    $pdo=new PDO($connect, USER, PASS);
$sql=$pdo->prepare('delete from Shohin where shohin_id=?');
if($sql->execute([$_POST['shohin_id']])) {
    echo '削除に成功しました。';
}else{
    echo '削除に失敗しました。';
}
?>
    <br><hr><br>
	<table>
    <tr><th>商品id</th><th>商品名</th><th>商品説明</th><th>単価</th><th>商品画像</th><th>登録日</th><th>更新日</th>  
    </tr>
<?php
    foreach ($pdo->query('select * from Shohin') as $row) {
        echo '<tr>';
        echo '<td>',$row['shohin_id'], '</td>';
        echo '<td>',$row['shohin_mei'], '</td>';
        echo '<td>',$row['shohin_ex'], '</td>';
        echo '<td>',$row['shohin_tanka'], '</td>';
        echo '<td>',$row['shohin_image'], '</td>';
        echo '<td>',$row['shohin_torokubi'], '</td>';
        echo '<td>',$row['shohin_up'], '</td>';
        echo '</tr>';
        echo "\n";
    }
?> 
</table>
        <button onclick="location.href='itiran.php'" class="btn">トップへ戻る</button>

    </body>
</html>

