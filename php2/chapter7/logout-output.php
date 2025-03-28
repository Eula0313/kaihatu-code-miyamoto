<?php session_start (); ?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>
<?php
if (isset ($_SESSION ['customer'])) {
unset ($_SESSION ['customer']);
echo 'ログアウトしました。';
} else {
echo 'すでにログアウトしています。';
}
?>
<?php require 'footer.php'; ?>