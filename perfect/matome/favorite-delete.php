<?php session_start(); ?>
<?php require 'header.php'; ?>
<?php require 'db-connect.php'; ?>
<?php

    if(isset($_SESSION['client'])){
        $pdo=new PDO($connect, USER, PASS);
        $sql=$pdo->prepare('delete from favorites where client_id=? and shohin_id=?');
        $sql->execute([$_SESSION['client']['ID'],$_GET['id']]);
        echo '<font size="5">お気に入りに商品を削除しました。</font>';
        echo '<hr>';
    }else{
        echo '<font size="5">お気に入りに商品を削除するには、ログインしてください。</font>';
    }
    require 'favorite.php';
?>

<?php require 'footer.php'; ?>
