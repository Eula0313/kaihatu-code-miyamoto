<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題1-7-input</title>
</head>
<body>
    <h1>麻生動物園へようこそ！<br>
    どのチケットをご購入ですか？</h1>
    <form action="kadai1-7-output.php" method="post">

        <input type="radio" name="yen" value="未就学児" checked>未就学児<br>
        <input type="radio" name="yen" value="子供料金" >子供料金<br>
        <input type="radio" name="yen" value="小学生～高校生料金" >小学生～高校生料金<br>
        <input type="radio" name="yen" value="大人料金" >大人料金<br>
        <input type="radio" name="yen" value="65歳以上" >65歳以上<br>
        <input type="checkbox" name="vac" >土日祝日<br>
        <input type="checkbox" name="long" >長期休暇期間<br>
        <button type="submit">料金計算</button>
    </form>
</body>
</html>
