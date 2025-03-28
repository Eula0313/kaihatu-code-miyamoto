<? session_start();?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>konyukanryo</title>
    <link rel="stylesheet" href="css/your.css"> 
    <hr>
</head>
<body>
    <h1>SHINE</h1>
    <div class='message'>ARIGATOU!!</div>
    <br>
    <?php

        if (isset($_SESSION['client'])) {
            $userName = $_SESSION['client']['name'];
            echo '<h2>', $userName, '様、ご注文の受付が完了しました</h2>';
        }
        ?>
    この度はSHINEでのお買い物ありがとうございます。<br>
    ご注文情報をお送りしますので、下記内容にてご確認ください。<br>
    発送手続き完了後、お知らせメールを再度配信いたしますので、<br>
    発送完了までもうしばらくお待ちください。<br>
    <form action="index.php" method="post">
    <p><button type="submit">戻る</button></p>
    </form>
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
</body>
</html>