<?php session_start(); ?>
<?php require 'header.php'; ?>
<?php require 'db-connect.php'; ?>
<?php
if(isset($_SESSION['client'])){
    $pdo=new PDO($connect, USER, PASS);
    try{
        if(isset($_POST['S_color'])and isset($_POST['S_size'])){
            $getProductIdQuery = $pdo->prepare('SELECT S_ID FROM shohin WHERE S_name = ? AND S_color = ? AND S_size = ?');
            $getProductIdQuery->execute([$_POST['S_name'], $_POST['S_color'], $_POST['S_size']]);
        }else if(isset($_POST['S_color'])){
            $getProductIdQuery = $pdo->prepare('SELECT S_ID FROM shohin WHERE S_name = ? AND S_color = ?');
            $getProductIdQuery->execute([$_POST['S_name'], $_POST['S_color']]);
        }else if(isset($_POST['S_size'])){
            $getProductIdQuery = $pdo->prepare('SELECT S_ID FROM shohin WHERE S_name = ? AND S_size = ?');
            $getProductIdQuery->execute([$_POST['S_name'],$_POST['S_size']]);
        }else{
            $getProductIdQuery = $pdo->prepare('SELECT S_ID FROM shohin WHERE S_name = ?');
            $getProductIdQuery->execute([$_POST['S_name']]);
        }
        $productId = $getProductIdQuery->fetchColumn();
        $sql=$pdo->prepare('insert into cart values(?,?,?)');
        $sql->execute([$_SESSION['client']['ID'], $productId, $_POST['count']]);
        $id=$_POST['S_ID'];
        if(!isset($_SESSION['shohin'])){
            $_SESSION['shohin']=[];
        }
        $count=0;
        if(isset($_SESSION['shohin'][$id])){
            $count=$_SESSION['shohin'][$id]['count'];
        }
        $_SESSION['shohin'][$id]=[
        'name'=>$_POST['S_name'],
        'price'=>$_POST['S_price'],
        'count'=>$count+$_POST['count']
        ];
        echo '<h1 style="text-align: center; font-size: 25px;">カートに商品を追加しました。</h1>';
    }catch(PDOException $e){
        echo '<h1 style="text-align: center; font-size: 25px;">その商品はすでにカートに入っています。</h1>';
    }
    
echo '<hr>';
require 'cart.php';
}else{
    echo '<h1 style="text-align: center; font-size: 25px;">カートに商品を追加するには、ログインしてください。</h1><br>';
    echo '<a href="login-input.php"><h2 style="text-align: center; font-size: 25px;">ログイン</h2></a>';
}


?>

<?php require 'footer.php'; ?>
