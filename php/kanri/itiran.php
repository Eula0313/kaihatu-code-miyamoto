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
        <link rel="stylesheet" href="css/style.css">
        <title>商品一覧</title>
    </head>
    <body>
    <div class="container">
            <h1>商品一覧</h1>

            <button class="btn" type="submit">ログアウト</button>
            <table>
    <tr><th>商品id</th><th>商品名</th><th>商品説明</th><th>単価</th><th>商品画像</th><th>登録日</th><th>更新日</th>  
    </tr>
            <td class="action-cell">
            </td>
        <?php
    $pdo=new PDO($connect, USER, PASS);
    foreach ($pdo->query('select * from Shohin') as $row) {
        echo '<tr>';
        echo '<td>', $row['shohin_id'], '</td>';
        echo '<td>', $row['shohin_mei'], '</td>';
        echo '<td>', $row['shohin_ex'],'</td>';
        echo '<td>', $row['shohin_tanka'], '</td>';
        echo '<td>', $row['shohin_image'], '</td>';
        echo '<td>', $row['shohin_torokubi'], '</td>';
        echo '<td>', $row['shohin_up'], '</td>';
        echo '<td>';
        echo'<form action="kousin.php" method="post">';
        echo'<input type="hidden" name="shohin_id" value="',$row['shohin_id'], '">';
        echo '<button type="submit" class="btn">更新</button>';
        echo '</form>';
        echo '</td>';
        echo '<td>';
        echo '<form action="delete.php" method="post">';
        echo '<input type="hidden" name="shohin_id" value="',$row['shohin_id'], '">';
        echo '<button type="submit" class="btn">削除</button>';
        echo '</form>';
        echo'</td>';
        echo '</tr>';
        echo "\n";
    }
    ?>
</table>
<button onclick="location.href='toroku.php'" class="btn">商品を登録する</button>
</div>
</body>
</html>