<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>loop-array3-output</title>
</head>
<body>
<?php
    $time = [
    '一限目' => '9:30',
    '二限目' => '11:15',
    '三限目' => '13:30',
    '四限目' => '15:15'
    ];

    $select_time = $_POST['time'];

    $start_time = $time[$select_time];

   echo $_POST['time'],'の開始時刻は', $start_time,'です';

?>
</body>
</html>