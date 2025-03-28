<?php

$msg_no = "学籍番号： ".$_POST['no'];
$msg_score = "点数： ".$_POST['score'];
$msg_name = "氏名： ".$_POST['name'];

echo json_encode(['messages' => [$msg_no, $msg_score,$msg_name]]);
exit;

// 参考ページ
// https://codelikes.com/jquery-php-ajax/
