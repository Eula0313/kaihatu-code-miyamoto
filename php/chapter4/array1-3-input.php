<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>array1-3-input</title>
</head>
<body>
    <h1>好きな果物</h1>

    好きな果物を選んでください
    <br>
    <br>
    <form action="array1-3-output.php" method="post">
    <?php
    $fruits = [
        'りんご'=>10,
        'イチゴ'=>20,
        '桃'=>30,
        'なし'=>40,
        'ぶどう'=>50
    ];
    foreach($fruits as $key =>$value){
        echo '<input type="radio" name="fruits" value="'.$key.'">',$key,'<br>';
        
    }
    
    ?>
    <button type="submit">送信</button>
    
</body>
</html>