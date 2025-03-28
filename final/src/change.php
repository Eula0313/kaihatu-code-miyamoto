<?php
require 'db-connect.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>change</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <button onclick="location.href='itiran.php'" class="btn">トップへ戻る</button>
    <?php
    $pdo = new PDO($connect, USER, PASS);
    $sql = $pdo->prepare('update Tenpo set tenpo_name=?,tenpo_cate=?,tenpo_tel=? where tenpo_id=?');
    if (!preg_match('/^[0-9]+$/', $_POST['tenpo_id'])) {
        echo '店舗IDを整数で入力してください';
    } else if (empty($_POST['tenpo_name'])) {
        echo '店舗名を入力してください';
    } else if (empty($_POST['tenpo_cate'])) {
        echo '店舗カテゴリを入力してください';
    } else if (empty($_POST['tenpo_tel'])) {
        echo '店舗電話番号をで入力して下さい。';
    } else {
        if ($sql->execute([$_POST['tenpo_name'], $_POST['tenpo_cate'], $_POST['tenpo_tel'], $_POST['tenpo_id']])) {
            echo '更新に成功しました。';
        } else {
            echo '更新に失敗しました。';
        }
    }
    ?>
    <hr>
    <table>
        <tr><th>店舗ID</th><th>店舗名</th><th>カテゴリ</th><th>電話番号</th></tr>

        <?php
        foreach ($pdo->query('select * from Tenpo') as $row) {
            echo '<tr>';
            echo '<td>', $row['tenpo_id'], '</td>';
            echo '<td>', $row['tenpo_name'], '</td>';
            echo '<td>', $row['tenpo_cate'], '</td>';
            echo '<td>', $row['tenpo_tel'], '</td>';
            echo '</tr>';
            echo "\n";
        }
        ?>
    </table>
</div>
</body>
</html>
