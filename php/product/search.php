<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <title>商品検索システム</title>
    </head>
    <body>
        <div>
    <form action="result.php" method="post">
        <h1>商品検索</h1>
        <label>検索方法</label>
        <input type="radio" name="how" value="all" checked>全件
        <input type="radio" name="how" value="part">指定<br>
    
        <h3>指定された場合入力</h3>

        <p class = "kensaku">
        <input type="radio" name="shohin">商品名　　　　　<input type="text" name="name"><br>
        <input type="radio" name="shohin">カテゴリー　　　<input type="text" name="kategori"><br>
        <input type="radio" name="shohin">価格　　　　　　<input type="text" name="price"><br>
        </p>
        <br>

        <p><button class="btn" type="submit">検索</button></p>
</form>
</div> 
    </body>
</html>