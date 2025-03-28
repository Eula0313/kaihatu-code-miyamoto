<?php
const SERVER = 'mysql215.phy.lolipop.lan';
const DBNAME = 'LAA1517492-shop';
const USER = 'LAA1517492';
const PASS = 'Pass0313';

$connect = 'mysql:host='.SERVER . ';dbname='. DBNAME . ';charaset=utf8';
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css">
        <title>商品登録</title>
    </head>
    <body>
    <div class="container">
        <h1>商品登録画面</h1>
        <button onclick="location.href='itiran.php'" class="btn">トップへ戻る</button>
        
<br><hr><br>
<script>
   jQuery(function($) {
	jQuery("#selectFileSample2").click(function() {
		var selectFileSample1 = document.getElementById("selectFileSample1").value;
		document.getElementById("selectFileSample3").value = selectFileSample1;
	});
});

    </script>
<table>
<form method="post" action="torokukanryo.php">
            商品名<input type="text" name="shohin_mei">
            商品説明<input type="text" name="shohin_ex" class="ex"><br>
            <br>
            単価<input type="text" name="shohin_tanka"><br>
        </p>
    
        <p class = "pasu"></p>
        商品画像パス
<input id="selectFileSample3" type="text" name="shohin_image" value="" size="50" />

<br>
        <br>
        <p><button type="submit" class="btn">登録</button></p>
    </div>
</form>
    </body>
</html>
