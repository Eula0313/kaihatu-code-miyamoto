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
		<title>課題6-6-output</title>
	</head>
	<body>
<?php
    $pdo=new PDO($connect, USER, PASS);
    // SQL発行準備 prepareメソッド　作成２
    $sql=$pdo->prepare('update Shohindata set data_name=?,data_kategori=?,data_price=? where data=?');
    if (empty($_POST['data_name'])) {
        echo '商品名を入力してください。';
    } else
    if (empty($_POST['data_kategori'])) {
        echo '商品名を入力してください。';
    } else
    if (!preg_match('/[0-9]+/', $_POST['data_price'])) {
        echo '商品価格を整数で入力してください。';
    } else
    //SQLを発行 excuteメソッド　作成３
    if ($sql->execute([htmlspecialchars($_POST['data_name']),$_POST['data_kategori'],$_POST['data_price'],$_POST['data']])){
    //更新に成功しました　作成４
    echo '更新に成功しました。';
    //更新に失敗しました　作成５
}else{
    echo '更新に失敗しました。';
}
?>
        <hr>
        <table>
        <tr><th>商品番号</th><th>商品名</th><th>カテゴリ</th><th>商品価格</th></tr>

<?php
foreach ($pdo->query('select * from Shohindata') as $row) {
    echo '<tr>';
    echo '<td>', $row['data'], '</td>';
    echo '<td>', $row['data_name'], '</td>';
    echo '<td>', $row['data_kategori'], '</td>';
    echo '<td>', $row['data_price'], '</td>';
    echo '</tr>';
    echo "\n";
}
?>
        </table>
        <button onclick="location.href='kadai6-6-input.php'">更新画面へ戻る</button>
    </body>
</html>

