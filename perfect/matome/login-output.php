<?php session_start(); ?>
<?php require 'db-connect.php'; ?> 
<?php require 'header.php'; ?>


<?php
unset($_SESSION['client']);
$pdo = new PDO($connect, USER, PASS);
$sql = $pdo->prepare('select * from client where ID=?');
$sql->execute([$_POST['ID']]);

if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
    if ($_POST['PW'] == $row['PW']) {
        $_SESSION['client'] = [
            'ID' => $row['ID'], 'PW' => $row['PW'], 'name' => $row['name'], 'BD' => $row['BD'],
            'address' => $row['address'], 'mail' => $row['mail'] ,'yubin'=>$row['yubin'] ,'tell'=>$row['tell']
        ];

        echo '<h1 class="login-success">いらっしゃいませ!',$_SESSION['client']['name'],'様</h1>';

        require 'menu.php';
    } else {
        echo 'ログインIDまたはパスワードが違います。';
    }
} else {
    echo 'ログインIDまたはパスワードが違います。';
}
?>
<?php require 'footer.php'; ?>


<style>
    h1.login-success{
        margin: 0 auto;
        text-align: center;
    }
</style>