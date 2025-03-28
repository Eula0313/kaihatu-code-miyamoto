<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題4-2-2-input</title>
</head>
<body>
    お食事を選択してください
    <form action="kadai4-2-2-output.php" method="post">

        <P><input type="radio" name="meal" value="和食" checked>和食</P>
        <P><input type="radio" name="meal" value="洋食" >洋食</P>
        <P><input type="radio" name="meal" value="中華" >中華</P>
    
        <P><button type="submit">確定</button></p>
    </form>
</body>
</html>
