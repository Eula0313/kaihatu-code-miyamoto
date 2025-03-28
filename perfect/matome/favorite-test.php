<?php require 'header.php' ; ?>
<?php require 'db-connect.php' ; ?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $pdo = new PDO($connect, USER, PASS);
$sql=$pdo->query('select * from shohin where S_ID=34');
    foreach($sql as $row){
        echo '<p>商品番号：',$row['S_ID'],'</p>';
        echo '<p>商品名：',$row['S_name'],'</p>';
        echo '<p>価格：',$row['S_price'],'</p>';
        echo '<p>個数：<select S_name ="count">';
        for($i=1;$i<=10;$i++){
            echo '<option value="',$i,'">',$i,'</option>';
        }
        echo '</select></p>';
        echo '<p><a href="favorite-insert.php?S_ID=',$row['S_ID'],
        '">お気に入りに追加</a></p>';
    }
        ?>
</body>
</html>
