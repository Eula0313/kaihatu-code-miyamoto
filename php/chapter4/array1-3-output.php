<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>array1-3-output</title>
</head>
<body>
    <h1>好きな果物</h1>
    あなたの選んだ果物
    <br>
    <br>
<?php
$fruits = [
    'りんご'=>10,
    'イチゴ'=>20,
    '桃'=>30,
    'なし'=>40,
    'ぶどう'=>50
];
$select_fruit = $_POST['fruits'];

$select_number = $fruits[$select_fruit];

echo '果物：', $_POST['fruits'], '<br>';
echo '番号：', $select_number;
?>
</body>
</html>