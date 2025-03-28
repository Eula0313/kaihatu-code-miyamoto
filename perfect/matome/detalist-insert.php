<?php session_start(); ?>

<?php require 'db-connect.php'; ?>
<?php
    if(isset($_SESSION['client'])){
        $pdo=new PDO($connect, USER, PASS);
        try{
            $sql=$pdo->prepare('INSERT INTO detalist (client_id, shohin_id, kosu) SELECT client_id, shohin_id, kosu FROM cart WHERE client_id=?');
            $sql->execute([$_SESSION['client']['ID']]);
            $sql=$pdo->prepare('DELETE FROM cart WHERE client_id=?');
            $sql->execute([$_SESSION['client']['ID']]);
            echo '<hr>';
            require 'Konyukanryo.php';
        }catch(PDOException $e){
            require 'Konyukanryo.php';
        }
       
    }else{
        echo 'お気に入りに商品を追加するには、ログインしてください。';
    }
?>

<?php require 'footer.php'; ?>
