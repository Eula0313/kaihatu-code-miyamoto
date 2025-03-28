<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>kadai2-6-input</title>
</head>
<body>
    賛成のメンバーを選択
    <br>
    <br>
    <form action="kadai2-6-output.php" method="post">
    <?php
        $kubun = [
            '織田信長' => 1,
            '坂本龍馬' => 2,
            '伊達政宗' => 3,
            '福沢諭吉' => 4,
            '源義経' => 5,
            '真田幸村' => 6,
            '上杉謙信' => 7
        ];
        foreach($kubun as $key => $value) {
            echo '<input type="checkbox" name="kubun[]" value="', $key, '">', $key, '<br>';
        }
    ?>
    <br>
    <button type="submit">投票結果</button>
    </form>
</body>
</html>
