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
		<title>change</title>
        <link rel="stylesheet" href="css/style.css">
	</head>
	<body>

    <div class="container">
    <button onclick="location.href='itiran.php'" class="btn">トップへ戻る</button>
<?php
    $pdo=new PDO($connect, USER, PASS);
    // SQL発行準備 prepareメソッド　作成２
    $sql=$pdo->prepare('update Shohin set shohin_mei=?,shohin_ex=?,shohin_tanka=?,shohin_image=?,shohin_up=CURRENT_DATE where shohin_id=?');
    if (empty($_POST['shohin_mei'])) {
        echo '商品名を入力してください。';
    } else
    if (empty($_POST['shohin_ex'])) {
        echo '商品説明を入力してください。';
    } else
    if (!preg_match('/[0-9]+/', $_POST['shohin_tanka'])) {
        echo '商品価格を整数で入力してください。';
    } else
    if (empty($_POST['shohin_image'])) {
        echo '商品画像を入力してください。';

    } else
   // SQLを発行 excuteメソッド 作成３
if ($sql->execute([htmlspecialchars($_POST['shohin_mei']),$_POST['shohin_ex'],$_POST['shohin_tanka'],$_POST['shohin_image'],$_POST['shohin_id']])) {
    // 更新に成功しました 作成４
    echo '更新に成功しました。';
    // 更新に失敗しました 作成５
} else {
    echo '更新に失敗しました。';
}

?>
        <hr>
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
</div>
    </body>
</html>

