<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題4-2-4input</title>
</head>
<body>
    <h1>利用しているレシピ</h1>
    <h2>入力ファーム</h2>
    <form action="kadai4-2-4-output.php" method="post">
    料理名：
    <input type="text" name="name" required >
    <br>
    <br>
    カテゴリ：
    <select name='meal'>
    <option value='a'>選択してください</option>
    <option value='1'>和食</option>
    <option value='2'>洋食</option>
    <option value='3'>中華</option>
    </select>
    <br>
    <br>
    難易度：
    <input type="radio" name="a" value="1" >簡単
        <input type="radio" name="a" value="2" >普通
        <input type="radio" name="a" value="3" >難しい
        <br>
        <br>
    予算：
    <input type="number" name="yen" min="0">
    円
    <br>
    <br>
    作り方：
    <textarea id="text" name="text"rows="5" cols="33"></textarea>
    <br>
        <button type="submit">送信</button>
    </form>
</body>
</html>
