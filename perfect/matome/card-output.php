<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php require 'header.php'; ?>
<?php
    $pdo=new PDO($connect, USER, PASS);

        try{

            if(isset($_SESSION['client'])){
                $sql=$pdo->prepare('insert into card values(?,?,?)');
                $sql->execute([
                    $_SESSION['client']['ID'],$_POST['card_num'],
                    $_POST['card_date']]);
                echo '<h2 style="text-align: center; font-size: 25px;">カード情報を登録しました。</h2>';
                echo '<h2 style="text-align: center; font-size: 25px;"><a href="toroku.php">会員情報へ</a></h2>';
            }
        }catch(PDOException $e){
            echo '<h2 style="text-align: center; font-size: 25px;">すでにカード情報は登録されています。</h2>';
            echo '<h2 style="text-align: center; font-size: 25px;"><a href="card-change-input.php">カード情報変更はこちら</a></h2>';
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
