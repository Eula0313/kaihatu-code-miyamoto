<?php session_start () ; ?>
<?php require 'header.php'; ?>
<?php require 'db-connect.php'; ?>
<?php require 'menu.php'; ?>
<?php
if (isset ($_SESSION['customer'])) {
    $pdo=new PDO($connect,USER,PASS);
    $sql=$pdo->prepare (
        'delete from favorite where customer_id=? and product_id=?');
        $sql->execute([$_SESSION['customer']['id'], $_GET['id']]);
    echo 'お気に入りから商品を削除しました。';
    echo '<hr>';
} else {
    echo 'お気に入りから商品を削除するには、ログインしてください。';
}
require 'favorite.php';
?>
<?php require 'footer.php'; ?>