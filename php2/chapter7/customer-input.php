<?php session_start (); ?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>
<?php
$name=$address=$login=$password='';
if (isset($_SESSION['customer'])) {
    $name=$_SESSION['customer']['name'];
    $address=$_SESSION['customer']['address'];
    $login=$_SESSION['customer']['login'];
    $password=$_SESSION['customer']['password'];

}

echo '<form action="customer-output.php" method="post">';

echo '<table>';

echo '<tr><td>お名前</td><td>';

echo '<input type="text" name="name" value="', $name, '">';

echo '</td></tr>';

echo '<tr><td>ご住所</td><td>';

echo '<input type="text" name="address" value="', $address, '">';

echo'</td></tr>';

echo'<tr><td>ログイン名</td><td>';

echo '<input type="text" name="login" value="', $login, '">';

echo '</td></tr>';

echo'<tr><td>パスワード</td><td>';

echo '<input type="password" name="password" value="', $password, '">';

echo '</td></tr>';

echo '</table>';

echo '<input type="submit" value="確定">';

echo '</form>';
?>
<?php require 'footer.php' ; ?>
    