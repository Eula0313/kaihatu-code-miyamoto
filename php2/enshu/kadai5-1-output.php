<?php
$hash=password_hash($_POST['password'], PASSWORD_DEFAULT);
   echo '入力されたパスワード：',$_POST['password'];
   echo '<br>ハッシュ値：'.$hash;
?>
</body>
</html>