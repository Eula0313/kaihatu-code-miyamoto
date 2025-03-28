<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>loop-array2-input</title>
</head>
<body>
    利用しているOS
    <br>
    <form action="loop-array2-output.php" method="post">
    <?php
    $os = [
        'Mac'=>1,
        'Windows'=>2,
        'Linax'=>3
    ];
    foreach($os as $key =>$value){
        echo '<input type="radio" name="os" value="'.$key.'">',$key;
    }
        ?>
    
    <br>
        利用しているブラウザ
    <br>
    <?php
    $burauza = [
        'Google Chrome'=>1,
        'Firefox'=>2,
        'Microsoft Edge'=>3,
        'Safari'=>4
    ];
    foreach($burauza as $a =>$value){
        echo '<input type="radio" name="burauza" value="'.$a.'">',$a;
         }
         ?>
         <br>
        <button type="submit">送信</button>
    </form>
</body>
</html>
