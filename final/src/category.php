<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/category.css">
    <title>新規カテゴリ作成</title>
</head>
<body>
    <div class="container">
        <h1>新規カテゴリ作成画面</h1>
        <br>
        <button onclick="location.href='itiran.php'" class="btn">トップへ戻る</button>
        <br>
        <form method="post" action="final_category.php">
            <br>
            <label for="category_name">新規カテゴリ名:</label>
            <input type="text" name="category_name" required><br>
            <br>
            <button type="submit" class="btn">作成</button>
        </form>
    </div>
</body>
</html>
