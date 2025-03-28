<?php session_start(); ?> 
<?php require 'db-connect.php'; ?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>
<?php
$pdo=new PDO($connect,USER,PASS);
$pass=password_hash($_POST['password'], PASSWORD_DEFAULT);

if (isset ($_SESSION ['customer'])) {
    $id=$_SESSION['customer']['id'];
    $sql=$pdo->prepare('select * from customer where id!=? and login=?');
    $sql->execute([$id, $_POST['login']]);

 } else {
    $sql=$pdo->prepare('select * from customer where login=?');
    $sql->execute([$_POST['login']]);
    }

if (empty ($sql->fetchAll())) {
if (isset($_SESSION['customer'])) {
    $sql=$pdo->prepare ('update customer set name=?, address=?, '.
    'login=?, password=? where id=?');
    $sql->execute ([
        $_POST['name'], $_POST['address'],
        $_POST ['login'], $pass, $id]);
    $_SESSION ['customer']=[
        'id'=>$id, 'name'=>$_POST['name'],
        'address'=>$_POST['address'], 'login'=>$_POST['login'],
        'password'=>$_POST['password']];
echo 'お客様情報を更新しました';
} else {
    $sql=$pdo->prepare('insert into customer values(null,?,?,?,?)');
    $pass=password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql->execute ([
        $_POST['name'], $_POST['address'],
        $_POST['login'], $pass]);
    echo 'お客様情報を登録しました。';
}
} else {
echo 'ログイン名がすでに使用されていますので、変更してください。';
}
?>
<?php require 'footer.php'; ?>
