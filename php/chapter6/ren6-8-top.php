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
    <meta http-equiv="Cache-Control" content="no-cache">
		<meta charset="UTF-8">
		<title>練習6-8-top</title>
	</head>
	<body>
        <h1>商品一覧</h1>
        <hr>
        <button onclick="location.href='ren6-5-input.php'">商品を登録する</button>
        <table>
    <tr><th>商品番号</th><th>商品名</th><th>商品価格</th><th>更新</th><th>削除</th></tr>
<?php
    $pdo=new PDO($connect, USER, PASS);
    foreach ($pdo->query('select * from product') as $row) {
        echo '<tr>';
        echo '<td>', $row['id'], '</td>';
        echo '<td>', $row['name'], '</td>';
        echo '<td>', $row['price'], '</td>';
        echo '<td>';
        echo'<form action="ren6-8-edit.php" method="post">';
        echo'<input type="hidden" name="id" value="',$row['id'], '">';
        echo '<button type="sumbit">更新</button>';
        echo '</form>';
        echo '</td>';
        echo '<td>';
        echo '<form action="ren6-8-delete.php" method="post">';
        echo '<input type="hidden" name="id" value="',$row['id'], '">';
        echo '<button type="submit">削除</button>';
        echo '</form>';
        echo'</td>';
        echo '</tr>';
        echo "\n";
    }
?>
    </table>
    </body>
</html>
