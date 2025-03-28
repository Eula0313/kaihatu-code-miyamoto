<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>練習4-2-1-input</title>
</head>
<body>
    利用しているOS
    <br>
    <form action="kadai4-2-1-output.php" method="post">

        <input type="radio" name="meal" value="1" checked>Mac
        <input type="radio" name="meal" value="2" >Windows
        <input type="radio" name="meal" value="3" >Linax
    <br>
        利用しているブラウザ
    <br>
        <input type="radio" name="a" value="1" checked>Google Chrome
        <input type="radio" name="a" value="2" >Firefox
        <input type="radio" name="a" value="3" >Microsoft Edge
        <input type="radio" name="a" value="4" >Safari
        <br>
        <br>
        <button type="submit">送信</button>
    </form>
</body>
</html>
