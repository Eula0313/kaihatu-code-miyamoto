<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>loop-array2-input</title>
</head>
<body>
    時間を選択してください
    <br>
    <br>
    <form action="loop-array3-output.php" method="post">
    <select name='time'>
    <?php
    $time = [
        '一限目'=>'9:30',
        '二限目'=>'11:15',
        '三限目'=>'13:30',
        '四限目'=>'15:15'
    ];
    foreach($time as $key =>$value){
        echo '<option value="',$key,'">',$key,'</option>';
    }
        ?>
        </select>
        <button type="submit">選択</button>
    </form>
</body>
</html>
