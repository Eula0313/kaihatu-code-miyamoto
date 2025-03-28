<?php
    //各自のDB接続情報を入力する　作成１ 
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
		<link rel="stylesheet" href="style.css">
		<title>練習6-6-input</title>
	</head>
	<body>
		<div class="th0">商品番号</div>
		<div class="th1">商品名</div>
		<div class="th1">商品価格</div>
<?php
    $pdo=new PDO($connect, USER, PASS);

	foreach ($pdo->query('select * from product') as $row) {
		echo '<form action="ren6-6-output.php" method="post">';
		//商品ID　作成３
		echo '<input type="hidden" name="id" value="',$row['id'].'">';
		echo '<div class="td0">',$row['id'],'</div>';
		echo '<div class="td1">';
		//商品名　作成４
		echo '<input type="text" name="name" value="',$row['name'].'">';
		echo '</div> ';
		echo '<div class="td1">';
		//価格　作成５
		echo '<input type="text" name="price" value="',$row['price'].'">';
		echo '</div> ';
		//更新ボタン　作成６
		echo '<div class="td2"><input type="submit" value="更新"></div>';
		echo '</form>';
		echo "\n";
	}
?>

    </body>
</html>
