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
		<title>課題6-6-input</title>
	</head>
	<body>
		<div class="th0">商品番号</div>
		<div class="th1">商品名</div>
        <div class="th1">カテゴリ</div>
		<div class="th1">商品価格</div>
<?php
    $pdo=new PDO($connect, USER, PASS);

	foreach ($pdo->query('select * from Shohindata') as $row) {
		echo '<form action="kadai6-6-output.php" method="post">';
		//商品ID　作成３
		echo '<input type="hidden" name="data" value="',$row['data'].'">';
		echo '<div class="td0">',$row['data'],'</div>';
		echo '<div class="td1">';
		//商品名　作成４
		echo '<input type="text" name="data_name" value="',$row['data_name'].'">';
		echo '</div> ';

        echo '<div class="td1">';
        echo '<input type="text" name="data_kategori" value="',$row['data_kategori'].'">';
        echo '</div> ';
		//価格　作成５
        echo '<div class="td1">';
		echo '<input type="text" name="data_price" value="',$row['data_price'].'">';
		echo '</div> ';
		//更新ボタン　作成６
		echo '<div class="td2"><input type="submit" value="更新"></div>';
		echo '</form>';
		echo "\n";
	}
?>

    </body>
</html>
