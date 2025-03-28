<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>課題4-2-3output</title>
</head>
<body>
<?php
if(isset($_POST['meal']) && $_POST['meal'] == '1'){

    echo '利用してるOS<br>','Mac<br>';

}else if(isset($_POST['meal']) && $_POST['meal'] == '2'){

    echo '利用してるOS<br>','Windows<br>';

}else{
    echo '利用してるOS<br>','Linax<br>';

}
if(isset($_POST['a']) && $_POST['a'] == '1'){

    echo '利用してるブラウザ<br>','Google Chrome<br>';

}else if(isset($_POST['a']) && $_POST['a'] == '2'){

    echo '利用してるブラウザ<br>','Firefox<br>';

}else if(isset($_POST['a']) && $_POST['a'] == '3'){
  
    echo '利用してるブラウザ<br>','Microsoft Edge<br>';

}else{
    echo '利用してるブラウザ<br>','Safari<br>';
  
}
    ?>
</body>
</html>