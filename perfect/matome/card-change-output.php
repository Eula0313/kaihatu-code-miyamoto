<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php require 'header.php'; ?>
<?php
    $pdo=new PDO($connect, USER, PASS);
        if(isset($_SESSION['client'])){
            $sql=$pdo->prepare('UPDATE card SET card_num=?,card_date=? WHERE ID=?');
            $sql->execute([
                $_POST['card_num'],
                $_POST['card_date'],
                $_SESSION['client']['ID']]);
            echo '<h2 style="text-align: center; font-size: 25px;">カード情報を変更しました。</h2>';
            echo '<h2 style="text-align: center; font-size: 25px;"><a href="toroku.php">会員情報へ</a></h2>';
        }
?>
<?php require 'footer.php'; ?>

<style>
p{
    font-size: 15px;
}

a{
    font-size: 15px;
}

</style>
