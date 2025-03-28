<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>kadai4-1</title>
</head>
<body>
現在のセッションで使ってるID:
<?php
echo session_id();
?>
</body>
</html>