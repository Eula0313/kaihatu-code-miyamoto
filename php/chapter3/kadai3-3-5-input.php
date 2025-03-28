<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題3-3-5input</title>
</head>
<body>
    <form action="kadai3-3-5-output.php" method="post">
        名前を入力してください
        <br>
        <input type="text" name="user" >
        <br>
        <br>
        あなたの好きなことを10個入力してください
        <br>
        <?php
		for ($i = 1; $i <= 10; $i++) {
			echo "<input type='text' name='like{$i}'><br>";
		}
		?>
        <br>
        <button type="submit">送信</button>
    </form>
</body>
</html>