<?php
require 'db-connect.php';
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>更新</title>
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body>
        <div>
    <table>
    <tr><th>店舗ID</th><th>店舗名</th><th>カテゴリ</th><th>電話番号</th></tr>
<?php
    $pdo=new PDO($connect, USER, PASS);
$sql=$pdo->prepare('select*from Tenpo where tenpo_id=?');
$sql->execute([$_POST['tenpo_id']]);
	foreach ($sql as $row) {
        echo '<tr>';
		echo '<form action="change.php" method="post">';
        echo '<td> ';
		echo '<input type="text" name="tenpo_id" readonly value="', $row['tenpo_id'], '">';
		echo '</td> ';
		echo '<td>';
		echo '<input type="text" name="tenpo_name" value="', $row['tenpo_name'], '">';
		echo '</td> ';
		echo '<td>';
		echo '<select name="tenpo_cate">';
                    $categories = $pdo->query('SELECT DISTINCT category_name FROM Category')->fetchAll(PDO::FETCH_COLUMN);
                    foreach ($categories as $category) {
                        $selected = ($category === $row['tenpo_cate']) ? 'selected' : '';
                        echo "<option value=\"$category\" $selected>$category</option>";
                    }
        echo '</select>';
        echo '</td>';
        echo '<td>';
		echo ' <input type="text" name="tenpo_tel" value="', $row['tenpo_tel'], '">';
		echo '</td> ';
		echo '<td><input type="submit" value="更新" class="btn"></td>';
		echo '</form>';
        echo '</tr>';
		echo "\n";
	}
?>
</table>
<br>
<button onclick="location.href='itiran.php'" class="btn">トップへ戻る</button>
</div>
    </body>
</html>

