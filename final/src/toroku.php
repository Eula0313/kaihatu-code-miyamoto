<?php
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);

$categoryMapping = [];
$stmt = $pdo->query('SELECT category_id, category_name FROM Category');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $categoryMapping[$row['category_name']] = $row['category_id'];
}

$initialCategoryId = 1;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/toroku.css">
    <title>店舗登録</title>
</head>
<body>
    <div class="container">
        <h1>店舗登録画面</h1>
        <button onclick="location.href='itiran.php'" class="btn">トップへ戻る</button>

        <br><hr>

        <form method="post" action="torokukanryo.php">
            <label for="tenpo_id">店舗ID:</label>
            <input type="text" name="tenpo_id"><br>

            <label for="tenpo_name">店舗名:</label>
            <input type="text" name="tenpo_name"><br>

            <label for="tenpo_cate">店舗カテゴリ:</label>
            <select name="tenpo_cate" onchange="setCategoryId(this.value)">
                <?php
                foreach ($categoryMapping as $categoryName => $categoryId) {
                    $selected = ($initialCategoryId == $categoryId) ? 'selected' : '';
                    echo "<option value=\"$categoryName\" $selected>$categoryName</option>";
                }
                ?>
            </select><br>

            <label for="category_id">カテゴリID:</label>
            <input type="text" name="category_id" id="category_id" readonly value="<?php echo $initialCategoryId; ?>"><br>

            <label for="tenpo_tel">店舗電話番号:</label>
            <input type="text" name="tenpo_tel"><br>

            <p><button type="submit" class="btn">登録</button></p>
        </form>
    </div>

    <script>
        function setCategoryId(selectedCategory) {
            var categoryIdInput = document.getElementById("category_id");
            var categoryMapping = <?php echo json_encode($categoryMapping); ?>;
            categoryIdInput.value = categoryMapping[selectedCategory] || "";
        }

        setCategoryId(document.querySelector('[name="tenpo_cate"]').value);
    </script>
</body>
</html>
