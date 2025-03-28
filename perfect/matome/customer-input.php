<?php session_start(); ?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/toroku.css">
    <title>新規会員登録</title>
</head>

<body>
    <div class="touroku">
    <h1>新規会員登録</h1>
    <?php
    $ID=$PW=$name=$BD=$mail=$yubin=$address=$tell='';

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
    echo '<form action="customer-output.php" method="post">';
    echo '<p>';
    echo '<h4><label for="signin-ID">ID</label></h4>';
    echo '<input id="signin-id" type="text" required name="ID" value="',$ID,'">';
    echo '</p>';
    echo '<p>';
    echo '<h4><label for="signin-pass">パスワード</label></h4>';
    echo '<input id="signin-pass" type="password" required name="PW" value="',$PW,'">';
    echo '</p>';
    echo '<p>';
    echo '<h4><label for="signin-name">名前</label></h4>';
    echo '<input type="text" name="name" required value="',$name,'">';
    echo '</p>';
    echo '<p>';
    echo '<h4><label for="signin-birth">生年月日</label></h4>';
    echo '<input id="signin-birth" type="date" required name="BD" value="',$BD,'">';
    echo '</p>';
    echo '<p>';
    echo '<h4><label for="signin-addres">メールアドレス</label></h4>';
    echo '<input id="signin-mail" type="text" required name="mail" value="',$mail,'">';
    echo '</p>';
    echo '<p>';
    echo '<h4><label for="signin-yubin">郵便番号 ※ハイフン区切りで入力してください <br> 例ooo-oooo</label></h4>';
    echo '<input id="signin-yubin" type="text" required minlength="8" maxlength="8" pattern="\d{3}-?\d{4}" name="yubin" value="',$yubin,'">';
    echo '</p>';
    echo '<p>';
    echo '<h4><label for="signin-addres2">ご住所</label></h4>';
    echo '<input type="text" name="address" required value="',$address,'">';
    echo '</p>';
    echo '<p>';
    echo '<h4><label for="signin-tel">電話番号 ※ハイフン区切りで入力してください <br> 例ooo-oooo-oooo</label></h4>';
    echo '<input id="signin-tell" type="text" required minlength="13" maxlength="13" pattern="\d{3}-?\d{4}-?\d{4}" name="tell" value="',$tell,'">';
    echo '</p>';
    echo '<br>';
    echo ' <p><button name="touroku-button"type="submit" class="cus">登録</button></p>';
    echo '</form>';
    ?>
    <div class="ori">
                <svg xmlns="http://www.w3.org/2000/svg" width="1100" height="800" viewBox="0 0 1253 855" fill="none">
                    <path
                        d="M0.5 34C18.2589 34 28.5791 33.845 33.8536 34M33.8536 34C61.3981 34.8093 -48.6607 44.0714 44.5 128C155.5 228 155.5 188.5 183 128C205 79.6 151.5 23.1667 122 1L33.8536 34ZM1162 654V706.5M1162 759H1250.5L1162 706.5M1162 759V706.5M1162 759L1190 853L1073.5 759L1129 706.5"
                        stroke="black" />
                </svg>
            </div>
            <div class="jagaimo-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="163" height="135" viewBox="0 0 163 135" fill="none">
                    <path d="M73 134L1 40L50.75 20.75M50.75 20.75L100.5 1.5L73 95.5L161.5 40L50.75 20.75Z" stroke="black" />
                </svg>
            </div>
            <div class="mimizu">
                <svg xmlns="http://www.w3.org/2000/svg" width="194" height="254" viewBox="0 0 194 254" fill="none">
                    <path d="M61.5 139.5L151.5 253.5L1.5 1.5L193.5 49.5" stroke="black" />
                </svg>
            </div>
            <div class="yama">
                <svg xmlns="http://www.w3.org/2000/svg" width="1440" height="619" viewBox="0 0 1440 619" fill="none">
                    <path
                        d="M1295 385.5L1090.5 258L1411 175.5V618L1184.5 484.999L1295 319V-400L1759.5 -466.5L930 -400H808C-1365.28 -366.001 -920.804 -505.268 747.5 -466.5C2415.8 -427.732 566.249 -452.66 482 -462.839M482 -462.839C471.794 -464.072 462.374 -465.312 454 -466.5L482 -462.839Z"
                        stroke="black" />
                </svg>
            </div>
    </div>
</body>

</html>

<style>
    button.cus{
            width: 300px;
            border: none;
            padding-bottom: 10px;
            padding: 10px 20px;
            cursor: pointer;
            color: #000;
            border: 2px solid #000;
            background: #fff;
            font-weight: bold;
   }

    button.cus:hover {
            background-color: rgb(0, 0, 0);
            color: #ffffff;
    }
</style>