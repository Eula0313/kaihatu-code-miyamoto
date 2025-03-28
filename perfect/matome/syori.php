<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>SHINE</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <?php require 'header.php'; ?>
        

        <div class="syori">
            <h1 class="syori">ARIGATOU!</h1>
        </div>

        <?php
            $ID=$PW=$name=$BD=$mail=$yubin=$address=$tell='';

            $client_id = $_SESSION['client']['ID'];
            if(isset($_SESSION['client'])){
                $ID=$_SESSION['client']['ID'];
                $PW=$_SESSION['client']['PW'];
                $name=$_SESSION['client']['name'];
                $BD=$_SESSION['client']['BD'];
                $mail=$_SESSION['client']['mail'];
                $yubin=$_SESSION['client']['yubin'];
                $address=$_SESSION['client']['address'];
                $tell = $_SESSION['client']['tell'];
            }
            
            
        echo '<form action="detalist-insert.php" method="post">';
        echo '<p class="syori">郵便番号</p>';
        echo '<p class="syori"><input type="text" name="yubin" required class="syoritext" value="',$yubin,'"></p>';
        echo '<p class="syori">住所</p>';
        echo '<p class="syori"><input type="text" name="address1" class="syoritext" value="',$address,'"></p>';
        echo '<p class="syori">建物・マンション名</p>';
        echo '<p class="syori"><input type="text" name="address2" required class="syoritext"></p>';
        echo '<p class="syori">メールアドレス</p>';
        echo '<p class="syori"><input type="text" name="mail" required  class="syoritext" value="',$mail,'"></p>';

        echo '<hr>';
        echo '<p class="syori">お支払方法</p>';
        echo '<p class="syori"><input type="radio" name="way" value="credit" onclick="showHidePaymentForm()">クレジットカード　';
        echo '<input type="radio" name="way" value="cash" onclick="showHidePaymentForm()" checked>代金引換</p>';

        
        echo '<div id="creditCardForm" style="display: none;">';
        $sql2 = $pdo->prepare('select * from card where ID = ?');
            $sql2->execute([$client_id]);
            
            if($row2 = $sql2->fetch(PDO::FETCH_ASSOC)) {
                if(isset($row2['card_num'])&& isset($row2['card_date'])){
                    $last4 = substr($row2['card_num'], -4);
                    $card_num = $last4;
                    echo '<p class="syori">カード番号</p>';
                    echo '<p class="syori">************',$card_num,'</p>';
                    $card_date = $row2['card_date'];
                    echo '<p class="syori">有効期限</p>';
                    echo '<p class="syori">',$card_date,'</p>';
                    echo '<p class="syori">セキュリティコード</p>';
                    echo '<p class="syori"><input type="text" required name="card_sec"></p>';
                }
            }else{
                echo '<p class="error">※カード情報が登録されていません。</p>';
                echo '<p class="toroku"><a href="card-input.php" class="toroku">カード情報の登録はこちら</a></p>';
            }
        
        
        
        
        echo '</div>';

        if(isset($_SESSION['client'])){

        
        $pdo = new PDO($connect, USER, PASS);
        $sql = $pdo->prepare('select * from cart, shohin ' .
        'where client_id=? and shohin_id=S_ID');
        $sql->execute([$_SESSION['client']['ID']]);

        $rowCount = $sql->rowCount();
        if($rowCount > 0){ // If there are items in the cart
            echo '<table class="syori">';
            $total=0;
            foreach($sql as $row){
                $id=$row['S_ID'];
                $name=$row['S_name'];
                echo '<tr class="syori">';
                echo '<td class="syori"><h3>購入商品</h3></td>';
                echo '<td class="img"><img alt="image" src="',$row['img_pass'],'" width="200" height="300"></td>';
                echo '<td class="product-info">';
                echo '<div class="product-info">';
                echo '<p class="product-name"><a href="detail.php?S_name='.$name.'">',$row['S_name'],'</a></p>';
                if($row['S_color']!=null){
                    echo '色：',$row['S_color'],'<br>';
                }
                if($row['S_size']!=null){
                    echo 'サイズ：',$row['S_size'],'<br>';
                }   
                echo '個数：',$row['kosu'],'<br>';
                echo '価格：¥',number_format($row['S_price']),'<br>';
                echo '</td>';
                echo '</div>';
                $subtotal=$row['kosu']*$row['S_price'];
                $total+=$subtotal;
                echo '<td class="syori">';
                echo '<h3>小計<br>';
                echo '¥',number_format($subtotal),'<br>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '<div class="total">';
                $total_received = $_POST['total'];
                echo '商品合計：¥' . number_format($total_received),'<br>';

                if($total_received<15000){
                    $soryo = 380;
                }else{
                    $soryo = 0;

                }
                echo '送料：¥' . number_format($soryo),'<br>';
                echo '<h2 class="total2">合計金額：¥' . number_format($total_received+$soryo),'</h2>';

            echo '</div>';
            
            
                
                echo '<a href="detalist-insert.php" class="btn btn-3d-flip btn-3d-flip2">
                <span class="btn-3d-flip-box2">
                <span class="btn-3d-flip-box-face btn-3d-flip-box-face--front2">注文する<i class="fas fa-angle-right fa-position-right"></i></span>
                <span class="btn-3d-flip-box-face  btn-3d-flip-box-face--back2">いいセンス！<i class="fas fa-angle-right fa-position-right"></i></span>
                </span>
                </a>';
                echo '</form>';

                echo '<p>　　</p>';
            
            }
        }
        ?>
    </body>
</html>


<script>
    function showHidePaymentForm() {
        var paymentMethod = document.querySelector('input[name="way"]:checked').value;

        if (paymentMethod === 'credit') {
            document.getElementById('creditCardForm').style.display = 'block';
        } else {
            document.getElementById('creditCardForm').style.display = 'none';
        }
    }
</script>
