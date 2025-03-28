<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題3-3-8output</title>
</head>
<body>
    <?php
    echo '<h1>会員登録確認画面</h1><br>';
    echo '氏名　　　',$_POST['name'],'<br>';
    echo '生年月日　',$_POST['birthday'],'<br>';
    echo '電話番号: ',$_POST['tel'],'<br>';
    echo '住所　　　', $_POST['address'],'<br><br>';
    echo '入力内容に問題がなければ「会員登録」をクリックしてください<br>';
    echo '訂正される方は「戻る」をクリックしてください<br>';
    echo "<a href=","kadai3-3-8-comp.php",">会員登録 </a>";
    echo "<a href=","kadai3-3-8-input.php","> 戻る</a>";
    ?>
</body>
</html>