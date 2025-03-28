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
		<title>課題6-7-input</title>
	</head>
	<body>
        <table>
    <tr><th>商品番号</th><th>商品名</th><th>カテゴリ</th><th>商品価格</th></tr>
<?php
    $pdo=new PDO($connect, USER, PASS);
    foreach ($pdo->query('select * from Shohindata') as $row) {
        echo '<tr>';
        echo '<td>',$row['data'], '</td>';
        echo '<td>',$row['data_name'], '</td>';
        echo '<td>',$row['data_kategori'], '</td>';
        echo '<td>',$row['data_price'], '</td>';
        echo '<td>';
        echo '<a href ="kadai6-7-output.php?data=',$row['data'], '">削除</a>';
        echo '</td>';
        echo '</tr>';
        echo "\n";
    }

?>
    </table>
    </body>
</html>
