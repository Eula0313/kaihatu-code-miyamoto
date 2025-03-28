<?php session_start(); ?>
<?php require 'header.php'; ?>
<?php require 'db-connect.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>商品詳細</title>
    <link rel="stylesheet" href="css/detail.css">
</head>
<body>
    <div class="product-container">

    <?php
        $pdo=new PDO($connect, USER, PASS);
            $sql=$pdo->prepare('select * from shohin where S_name=?');
            $sql->execute([$_GET['S_name']]);
        foreach($sql as $row) {
            echo '<div class="left-column">';
                echo '<img class="product-image" alt="image" width="240" height="320"  src="',$row['img_pass'],'">';
            echo '</div>';
            echo '<div class="right-column">';
                echo '<form action="cart-insert.php" method="post">';
                echo '<h2>',$row['S_name'],'</h2>';
                echo '<p>価格：¥',number_format($row['S_price']),'</p>';
                echo '<p>個数：<select name=count>';
                for($i=1;$i<=10;$i++){
                    echo '<option name="kosu" value="',$i,'">',$i,'</option>';
                }
                echo '</select></p>';

                if($row['S_color']!=null){
                    echo '<p>カラー：<select name=S_color>';
                        echo '<option value="ブラック">ブラック</option>';
                        echo '<option value="ホワイト">ホワイト</option>';
                        echo '<option value="ピンク">ピンク</option>';
                        echo '<option value="オレンジ">オレンジ</option>';
                    echo '</select></p>';
                }
                if($row['S_size']!=null){
                    echo '<ul class="size-inventory">';
                    echo '<p>サイズ：<select name=S_size >';
                        echo '<option value"S">S</option>';
                        echo '<option value"M">M</option>';
                        echo '<option value"L">L</option>';
                        echo '<option value"LL">LL</option>';
                    echo '</ul>';
                    echo '</select></p>';
                    
                }
                echo '<input type="hidden" name="S_ID" value="',$row['S_ID'],'">';
                echo '<input type="hidden" name="S_name" value="',$row['S_name'],'">';
                echo '<input type="hidden" name="S_price" value="',$row['S_price'],'">';
                echo '<p><input class="cart" type="submit" value="カートに追加"></p>';
                echo '</form>';
                echo '<p><a class="favo" href="favorite-insert.php? id=',$row['S_ID'],'">お気に入りに追加</a></p>';
                echo '</div>';
                break;
        }
    ?>
    </div>
    
</body>
<?php require 'footer.php'; ?>
</html>
