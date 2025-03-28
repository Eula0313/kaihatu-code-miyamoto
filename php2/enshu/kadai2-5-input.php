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
    <form action="kadai2-5-output.php" method="post">
    <select name='kubun'>
<?php
    $kubun = [
        '未就学児' =>0,
        '子供料金' =>500,
        '小学生～高校生料金' =>700,
        '大人料金' =>1000,
        '65歳以上'=>600
        ];
    foreach($kubun as $key =>$value){
        echo '<option value="',$key,'">',$key,'</option>';
    }

?>
</select>
<br>
<br>
<button type="submit">確定</button>
</form>
</body>
</html>
