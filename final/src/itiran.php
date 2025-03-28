<?php
session_start();
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);
$category = isset($_POST['category']) ? $_POST['category'] : 0;

if ($category == 0) {
    $sql = $pdo->query('SELECT * FROM Tenpo')->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sql = $pdo->prepare('SELECT * FROM Tenpo WHERE tenpo_cate = :category');
    $sql->bindParam(':category', $category, PDO::PARAM_STR);
    $sql->execute();
    $sql = $sql->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>店舗一覧</title>
</head>
<body>
    <div class="container">
        <h1 font-size: 20px;>店舗一覧</h1>
        <form action="itiran.php" method="post">
            <select name="category" id="category">
                <option value="0">全てのカテゴリ</option>
                <?php
                $categories = $pdo->query('SELECT DISTINCT category_name FROM Category')->fetchAll(PDO::FETCH_COLUMN);
                foreach ($categories as $cat) {
                    $selected = ($category == $cat) ? 'selected' : '';
                    echo "<option value=\"$cat\" $selected>$cat</option>";
                }
                ?>
            </select>
            <input type="submit" value="検索">
        </form>
        <button onclick="location.href='toroku.php'" class="btn">商品を登録する</button>
        <button onclick="location.href='category.php'" class="btn">カテゴリを登録する</button>
        <hr>
        <table>
            <tr><th>店舗ID</th><th>店舗名</th><th>カテゴリ</th><th>電話番号</th><th>更新</th><th>削除</th></tr>
            <?php
            foreach ($sql as $row) {
                echo '<tr>';
                echo '<td>', $row['tenpo_id'], '</td>';
                echo '<td>', $row['tenpo_name'], '</td>';
                echo '<td>', $row['tenpo_cate'], '</td>';
                echo '<td>', $row['tenpo_tel'], '</td>';
                echo '<td>';
                echo '<form action="kousin.php" method="post">';
                echo '<input type="hidden" name="tenpo_id" value="', $row['tenpo_id'], '">';
                echo '<button type="submit" class="btn">更新</button>';
                echo '</form>';
                echo '</td>';
                echo '<td>';
                echo '<form action="delete.php" method="post">';
                echo '<input type="hidden" name="tenpo_id" value="', $row['tenpo_id'], '">';
                echo '<button type="submit" class="btn">削除</button>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
                echo "\n";
            }
            ?>
        </table>
        <br>
       
    </div>
</body>
</html>
