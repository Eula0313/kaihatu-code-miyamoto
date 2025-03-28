<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>kadai2-2</title>
</head>
<body>
    入場する人の区分を選択してください
    <br>
    <br>
    <form action="kadai2-3-output.php" method="post">
    <select name='price'>
<?php
    $price = [
    '未就学児' =>1,
    '子供料金' =>2,
    '小学生～高校生料金' =>3,
    '大人料金' =>4,
    '65歳以上'=>5
    ];
    foreach($price as $key =>$value){
        echo '<option value="',$key,'">',$key,'</option>';
    }

?>
</select>
<button type="submit">確定</button>
</form>
</body>
</html>
