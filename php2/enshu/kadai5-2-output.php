<?php
$pass=password_hash(12345, PASSWORD_DEFAULT);
    if(password_verify($_POST['password'],$pass) == true){
   echo '保存されているパスワードと一致しました';
    }else{
        echo '保存されているパスワードと一致しません';
    }

?>
</body>
</html>