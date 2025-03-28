<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>kadai2-1-input</title>
</head>
<body>
    <h1>今日の日付を選んでください</h1>
    <form action="kadai2-1-output.php" method="post">
    <select name='mouth'>
    <?php
    
    for ($i=1;$i<=12;$i++){
        echo '<option value="',$i,'">',$i,'</option>';
    }
    ?>
    </select>月
    <select name='day'>
    <?php
    for ($a=1;$a<=31;$a++){
        echo '<option value="',$a,'">',$a,'</option>','日';
    }
    ?>
</select>日
    <button type="submit">送信</button>
</form>
</body>
</html>
