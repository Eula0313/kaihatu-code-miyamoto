<?php require '../chapter7/db-connect.php'; ?>
<?php
echo '<table>';
echo'<tr><th>商品番号</th><th>商品名</th><th>カテゴリー</th><th>商品価格</th></tr>';
$pdo=new PDO($connect,USER,PASS);
if (isset($_POST['keyword'])) {
    $sql = $pdo->prepare('select * from item where name like ?');
    $sql->execute(['%' . $_POST['keyword'] . '%']);
} else {
    $sql = $pdo->query('select * from item');
}

$data = $sql->fetchAll();

if (empty($data)) {
    echo 'データが一件もありません';
} else {
    foreach ($data as $row) {
        $id = $row['id'];
        echo '<tr>';
        echo '<td>', $id, '</td>';
        echo '<td>', $row['name'], '</td>';
        echo '<td>', $row['kubun'], '</td>';
        echo '<td>', $row['price'], '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
}
?>
