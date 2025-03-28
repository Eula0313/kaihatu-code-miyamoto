<?php require '../chapter7/db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>kadai3-5</title>
</head>
<body>
<?php
$pdo=new PDO($connect,USER,PASS);
$sql=$pdo->query('select*from product');
foreach ($sql as $row) {
    $data[] = $row;
}
echo json_encode($data,JSON_UNESCAPED_UNICODE);
?>
</body>
</html>