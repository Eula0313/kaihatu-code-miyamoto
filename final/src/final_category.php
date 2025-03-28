<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <title>カテゴリ登録</title>
        
    </head>
    <body>
        <div>
        <h1>カテゴリ登録画面</h1>
        <button onclick="location.href='itiran.php'" class="btn">トップへ戻る</button>
<?php
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = $_POST['category_name'];

    $pdo = new PDO($connect, USER, PASS);
    $sql = $pdo->prepare('INSERT INTO Category (category_name) VALUES (?)');
    
    if ($sql->execute([$categoryName])) {
        echo '<font color="red">新しいカテゴリが作成されました。</font>';
    } else {
        echo '<font color="red">カテゴリの作成に失敗しました。</font>';
    }
}
?>
<br><hr><br>
<table>
<tr><th>カテゴリID</th><th>カテゴリ名</th></tr>
<?php
foreach ($pdo->query('select * from Category') as $row){
    echo '<tr>';
    echo '<td>', $row['category_id'], '</td>';
    echo '<td>', $row['category_name'], '</td>';
    echo '</tr>';
    echo "\n";
}
?>
</table>
<form action="category.php" method="post">
<button type="submit" class="btn">追加画面へ戻る</button>
</form>
</div>
</body>
</html>
