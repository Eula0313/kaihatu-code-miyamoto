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
        <title>商品検索結果</title>
    </head>
    <body>
        <div>
            <h1>検索結果</h1>
            <table>
    <tr><th>商品名</th><th>カテゴリ</th><th>価格</th>
    <?php
$pdo = new PDO($connect,USER,PASS);

if ($_POST['how']=="all"){

    $str='select * from Shohindata;';

    $sql = $pdo->query($str);

}else{

    $str = 'select * from Shohindata where data_name=? or data_kategori=? or data_price=?;';

    $sql = $pdo->prepare($str);

    $sql->execute([$_POST['name'],$_POST['kategori'],$_POST['price']]);

}

foreach ($sql as $row){

    echo '<tr>';

    echo '<td>',$row['data_name'],'</td>';

    echo '<td>',$row['data_kategori'],'</td>';

    echo '<td>',$row['data_price'],'</td>';

    echo '</tr>';

    echo "\n";    

}

?>
<br>   
</table> 
<br>
<form action="search.php" method="post">
<br>
<button class="btn" type="submit">戻る</button>
</form>
</div>
</body>
</html>