<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>loop-array1-input</title>
</head>
<body>
    <h1>誕生月を選択してください</h1>
    <form action="loop-array1-output.php" method="post">
    <select name='mouth'>
    <?php
    
    for ($i=1;$i<=12;$i++){
        echo '<option value="',$i,'">',$i,'</option>';
    }
    ?>
    </select>
    <button type="submit">選択</button>
</body>
</html>
