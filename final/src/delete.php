<?php
require 'db-connect.php';
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
$sql=$pdo->prepare('delete from Tenpo where tenpo_id=?');
if($sql->execute([$_POST['tenpo_id']])) {
    echo '削除に成功しました。';
}else{
    echo '削除に失敗しました。';
}
?>
    <br><hr>
	<table>
    <tr><th>店舗ID</th><th>店舗名</th><th>カテゴリ</th><th>電話番号</th></tr>
    </tr>
<?php
    foreach ($pdo->query('select * from Tenpo') as $row) {
        echo '<tr>';
        echo '<td>',$row['tenpo_id'], '</td>';
        echo '<td>',$row['tenpo_name'], '</td>';
        echo '<td>',$row['tenpo_cate'], '</td>';
        echo '<td>',$row['tenpo_tel'], '</td>';
        echo '</tr>';
        echo "\n";
    }
?> 
</table>
<br>
        <button onclick="location.href='itiran.php'" class="btn">トップへ戻る</button>

    </body>
</html>

