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
		<title>練習6-8-edit</title>
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body>
<div>
    <table>
    <tr><th>商品id</th><th>商品名</th><th>商品説明</th><th>単価</th><th>商品画像</th><th>登録日</th>  
    </tr>
<?php
    $pdo=new PDO($connect, USER, PASS);
$sql=$pdo->prepare('select*from Shohin where shohin_id=?');
$sql->execute([$_POST['shohin_id']]);
	foreach ($sql as $row) {
        echo '<tr>';
		echo '<form action="change.php" method="post">';
        echo '<td> ';
		echo '<input type="hidden" name="shohin_id" value="', $row['shohin_id'], '">';
		echo '</td> ';
		echo '<td>';
		echo '<input type="text" name="shohin_mei" value="', $row['shohin_mei'], '">';
		echo '</td> ';
		echo '<td>';
		echo ' <input type="text" name="shohin_ex" value="', $row['shohin_ex'], '">';
		echo '</td> ';
        echo '<td>';
		echo ' <input type="text" name="shohin_tanka" value="', $row['shohin_tanka'], '">';
		echo '</td> ';
        echo '<td>';
		echo ' <input type="text" name="shohin_image" value="', $row['shohin_image'], '">';
		echo '</td> ';
        echo '<td>';
		echo ' <input type="hidden" name="shohin_torokubi" value="', $row['shohin_torokubi'], '">';
		echo '</td> ';
		echo '<td><input type="submit" value="更新" class="btn"></td>';
		echo '</form>';
        echo '</tr>';
		echo "\n";
	}
?>
</table>
<button onclick="location.href='itiran.php'" class="btn">トップへ戻る</button>
</div>
    </body>
</html>

