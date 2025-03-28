<?php session_start(); ?>
<?php require 'header.php'; ?>
<?php require 'db-connect.php'; ?>
<?php
if (isset($_SESSION['client'])) {
    $pdo = new PDO($connect, USER, PASS);
    try {
        $sql = $pdo->prepare('insert into favorites values(?,?)');
        $sql->execute([$_SESSION['client']['ID'], $_GET['id']]);
        echo '<h1 style="text-align: center; font-size: 25px;">お気に入りに商品を追加しました。</h1>';
    } catch (PDOException $e) {
        echo '<h1 style="text-align: center; font-size: 25px;">すでにお気に入りに入っています。</h1>';
    }
    echo '<hr>';
    require 'favorite.php';
} else {
    echo '<h1 style="text-align: center; font-size: 25px;">お気に入りに商品を追加するには、ログインしてください。</h1><br>';
    echo '<a href="login-input.php"><h2 style="text-align: center; font-size: 25px;">ログイン</h2></a>';
}
?>

<?php require 'footer.php'; ?>

