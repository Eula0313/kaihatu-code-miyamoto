<?php session_start(); ?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>
<?php require 'db-connect.php'; ?>
<hr>
<?php
    echo '<table>';
    echo '<tr><th>商品名</th><th>カテゴリ</th><th>価格</th><th>在庫</th><th>画像</th></tr>';
    $pdo=new PDO($connect, USER, PASS);
    if(isset($_POST['keyword'])){
        $sql=$pdo->prepare('select * from shohin where name like ?');
        $sql->execute(['%'.$_POST['keyword'].'%']);
    }else{
        $sql=$pdo->query('select distinct S_name,S_cate,S_price,S_zaiko from shohin');
    }
    foreach($sql as $row) {
        $name=$row['S_name'];
        echo '<tr>';
        echo '<td>';
        echo '<a href="detail.php?S_name=',$name,'">',$row['S_name'],'</a>';
        echo '</td>';
        echo '<td>',$row['S_cate'],'</td>';
        echo '<td>¥',number_format($row['S_price']),'</td>';
        echo '<td>',$row['S_zaiko'],'</td>';
        echo '<tr>';

    }
    echo '</table>'
?>
<?php require 'footer.php'; ?>