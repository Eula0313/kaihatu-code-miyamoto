<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>kadai3-4</title>
</head>
<body>
<?php

$fruits =["りんご"=>120,"イチゴ"=>400,"なし"=>350,"バナナ"=>100];
$json = json_encode($fruits,JSON_UNESCAPED_UNICODE);
echo $json;

?>
</body>
</html>
   