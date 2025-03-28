<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>kadai3-4</title>
</head>
<body>
<?php
$store = [
    "新宿" => 100,
    "秋葉原" => 101,
    "上野" => 102,
    "横浜" => 200,
    "川崎" => 201,
    "札幌" => 300,
    "仙台" => 400,
    "名古屋" => 500,
    "京都" => 600,
    "博多" => 700,
];
$json = json_encode($store,JSON_UNESCAPED_UNICODE);
echo $json;

?>
</body>
</html>
   