<?php
require 'db-connect.php';
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <title>商品登録</title>
        
    </head>
    <body>
        <div>
        <h1>商品登録画面</h1>
        <button onclick="location.href='itiran.php'" class="btn">トップへ戻る</button>
        <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = new PDO($connect, USER, PASS);

    $checkId = $pdo->prepare('SELECT COUNT(*) FROM Tenpo WHERE tenpo_id = ?');
    $checkId->execute([$_POST['tenpo_id']]);
    $count = $checkId->fetchColumn();

    if ($count > 0) {
        echo '<br><br><font color="red">エラー：店舗IDが既存のデータと重複しています。別のIDを使用してください。</font>';
    } else {
        $sql = $pdo->prepare('INSERT INTO Tenpo (tenpo_id, tenpo_name, tenpo_cate, category_id, tenpo_tel) VALUES (?, ?, ?, ?, ?)');

        if (
            preg_match('/^[0-9]+$/', $_POST['tenpo_id']) &&
            !empty($_POST['tenpo_name']) &&
            !empty($_POST['tenpo_cate']) &&
            !empty($_POST['category_id']) &&
            !empty($_POST['tenpo_tel']) &&
            $sql->execute([$_POST['tenpo_id'], $_POST['tenpo_name'], $_POST['tenpo_cate'], $_POST['category_id'], $_POST['tenpo_tel']])
        ) {
            echo '<br><br><font color="red" font-size: 20px;>追加に成功しました。</font>';
        } else {
            echo '<br><br><font color="red" font-size: 20px;>追加に失敗しました。</font>';
        }
    }
}
?>

<br><br><hr>
<table>
<tr><th>店舗ID</th><th>店舗名</th><th>カテゴリ</th><th>電話番号</th></tr>
<?php
foreach ($pdo->query('select * from Tenpo') as $row){
    echo '<tr>';
    echo '<td>', $row['tenpo_id'], '</td>';
    echo '<td>', $row['tenpo_name'], '</td>';
    echo '<td>', $row['tenpo_cate'],'</td>';
    echo '<td>', $row['tenpo_tel'], '</td>';
    echo '</tr>';
    echo "\n";
}
?>
</table>
<form action="toroku.php" method="post">
<br>
<button type="submit" class="btn">追加画面へ戻る</button>
</form>
</div>
</body>
</html>