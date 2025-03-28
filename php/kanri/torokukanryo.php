<?php
const SERVER = 'mysql215.phy.lolipop.lan';
const DBNAME = 'LAA1517492-shop';
const USER = 'LAA1517492';
const PASS = 'Pass0313';

$connect = 'mysql:host='.SERVER . ';dbname='. DBNAME . ';charaset=utf8';
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <title>商品登録</title>
        
    </head>
    <body>
        <div>
        <h1>商品登録画面</h1>
        <button onclick="location.href='itiran.php'" class="btn">トップへ戻る</button>
        <?php
$pdo=new PDO($connect,USER,PASS);
$sql=$pdo->prepare('insert into Shohin(shohin_mei,shohin_ex,shohin_tanka,shohin_image,shohin_torokubi) value(?, ?, ?,?,CURRENT_DATE)');
if (empty($_POST['shohin_mei'])){
    echo '商品名を入力してください';
}else if (empty($_POST['shohin_ex'])){
    echo '商品説明を入力してください';
}else if (!preg_match('/^[0-9]+$/',$_POST['shohin_tanka'])){
    echo '商品価格を整数で入力して下さい。';
}else if ($sql->execute([$_POST['shohin_mei'],$_POST['shohin_ex'],$_POST['shohin_tanka'],$_POST['shohin_image'],])){
    echo '<font color="red">追加に成功しました。</font>';
}else {
    echo '<font color="red">追加に失敗しました。</font>';
}
?>
<br><hr><br>
<table>
<tr><th>商品id</th><th>商品名</th><th>商品説明</th><th>単価</th><th>商品画像</th><th>登録日</th><th>更新日</th></tr>
<?php
foreach ($pdo->query('select * from Shohin') as $row){
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
<form action="toroku.php" method="post">
<button type="submit" class="btn">追加画面へ戻る</button>
</form>
</div>
</body>
</html>