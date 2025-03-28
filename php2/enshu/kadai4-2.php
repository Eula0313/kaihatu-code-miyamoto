<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>kadai4-2</title>
</head>
<body>
セッションが開始されると、ブラウザにセッション名でセッションIDが保存されます。<br>
<?php
echo '保存されるセッション名:',session_name();
echo '<br>保存されるセッションID:',session_id();
?>
</body>
</html>