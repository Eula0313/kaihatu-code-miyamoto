<?php
session_start();
require 'header.php';
require 'db-connect.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="./css/style3.css">
<title>登録情報</title>
</head>
<body>
 
<?php
if (isset($_SESSION['client'])) {
    $client_id = $_SESSION['client']['ID'];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['client']['ID'];
        $stmt = $pdo->prepare("UPDATE client SET name=?, PW=?,  yubin=?, address=?, mail=?, tell=? WHERE ID=?");
        $stmt->execute([
            $_POST['name'],
            $_POST['PW'],
            $_POST['yubin'],
            $_POST['address'],
            $_POST['mail'],
            $_POST['tell'],
            $user_id,
        ]);
    }
    $sql = $pdo->prepare('SELECT * FROM client WHERE ID = ?');
    $sql->execute([$client_id]);
 
    $sql2 = $pdo->prepare('select * from card where ID = ?');
    $sql2->execute([$client_id]);
 
    if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        echo '

    <h1 font-size: 15px;>', $row['name'], '様の登録情報</h1>
    <div class="container">

    <div class="info-section">
        <p>名前: ', $row['name'], '</p>
        <hr>
        <p>郵便番号: ', $row['yubin'], '</p>
        <hr>
        <p>ご住所: ', $row['address'], '</p>
        <hr>
        <p>電話番号: ', $row['tell'], '</p>
        <hr>
        <p>生年月日: ', $row['BD'], '</p>
        <hr>
        <p>メールアドレス: ', $row['mail'], '</p>
        <hr>';
                // カード番号と有効期限を表示
                if($row2 = $sql2->fetch(PDO::FETCH_ASSOC)) {
                    if(isset($row2['card_num'])&& isset($row2['card_date'])){
                        $last4 = substr($row2['card_num'], -4);
                        echo '<p>カード番号: ****',$last4, '</p>
                    <hr>
                    <p>有効期限: ', $row2['card_date'], '</p>
                    <hr>';
                    } else {
                        echo '<p>カード情報は登録されていません。</p>';
                    }
                }else{
                    echo '<p>カード情報は登録されていません。</p>';
                }
        echo '
    </div>
    <hr>
    <div>
<form action="logout-output.php" method="post">
    <span style="margin-right:20px;"></span>
        <a class="toroku" href="rireki.php?client_id=', $row['ID'], '">注文履歴</a>
        <span style="margin-right: 20px;"></span>
        <a class="toroku" href="toroku1.php">登録情報を変更する</a>
        <span style="margin-right: 20px;"></span>';
        if($row2) {
            echo '<a class="toroku" href="card-change-input.php">カード情報を変更する</a>';
        } else {
            echo '<a class="toroku" href="card-input.php">カード情報を登録する</a>';
        }
       
        echo
        '<span style="margin-right: 200px;"></span>
        <input type="submit" class="toroku1" value="ログアウト">
        </form>
    </div>
</body>
</html>';
    } else {
        echo 'ユーザーの情報が見つかりませんでした。';
    }
} else {
 
    echo '<h1 font-size: 25px;>登録情報</h1>';
    echo '<br><h2 style="text-align: center; font-size: 20px;">ユーザーはログインしていません。情報を表示するにはログインしてください。</h2><br>';
    echo '<h2 style="text-align: center; font-size: 20px;"><a href="login-input.php">ログインはこちら</h2></a>';
}
?>
