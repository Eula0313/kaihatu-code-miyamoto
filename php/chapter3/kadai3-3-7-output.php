<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題3-3-7output</title>
</head>
<body>
    <?php
    echo '<h1>',$_POST['user'], 'さん、こんにちは!<h1>';
    echo '<h2>好きな動物<h2>';
    echo '<h3>','・', $_POST['animal'],'<h3>';
    echo '<h3>','・', $_POST['animal2'],'<h3>';
    echo '<h3>','・', $_POST['animal3'],'<h3>';
    ?>
</body>
</html>