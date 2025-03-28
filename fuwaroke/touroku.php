<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $password = $_POST['password']; // パスワードも受け取る
    
    //数値のみかつ7桁であるかをチェック
    if(!preg_match('/^\d{7}$/', $user_id)){
        $_SESSION['touroku_error'] = "※学籍番号は7桁で入力してください。";
        header("Location: touroku.php"); // リダイレクト
        exit(); // 処理を終了してリダイレクトさせる
    }
    // 入力されたユーザIDがデータベース内に存在するか確認
    $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
    $sql->execute([$user_id]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    // 結果の行数を取得
    $rowCount = $sql->rowCount();

    //結果の行数が0より多ければエラーメッセージがでて、無ければ次の画面に遷移する
    if ($rowCount > 0) {
        $_SESSION['touroku_error'] = "※この学籍番号は既に使用されています。別の学籍番号を入力してください。";
        header("Location: touroku.php"); // リダイレクト
        exit(); // 処理を終了してリダイレクトさせる
    } else {
        // 学籍番号が使用されていない場合の処理
        // ユーザーIDとパスワードをセッションに保存
        $_SESSION['user_id'] = $user_id;
        $_SESSION['password'] = $password;
        header("Location: touroku2.php"); // リダイレクト
        exit(); // 処理を終了してリダイレクトさせる
    }
}
?>




<!DOCTYPE html>
<html lang="ja">
<head>
    <script type="text/javascript">
        //7桁まで
        function limitInput(){
            var textbox = document.getElementById("user_id");
            var value = textbox.value;

            value = value.replace(/[^\d]/g, '');

            if(value.length > 7){
                value = value.slice(0, 7);
            }
            textbox.value = value;
        }

        
        </script>

    <meta charset="UTF-8">
    <title>新規登録</title>
</head>
<?php require 'header.php'; ?>
<body>
<link rel="stylesheet" href="css/touroku.css">
<div class="touroku1-container">
        <h1 class="touroku1-header">新規登録</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <?php 
            if(isset($_SESSION['touroku_error'])){
                echo'<p class="error-message">' .htmlspecialchars($_SESSION['touroku_error']) . '</P>';
                unset($_SESSION['touroku_error']);
            }
            ?>
            <label class="form-label">
            <span style="color: red;" >*</span>必須<br>
            <span style="opacity: 0.5;">(ログイン後、<u style="color: blue"><span style="color: red;">学籍番号以外</span></u>をマイページから変更できます。)</span><br>
            </label>

            <label for="user_id" class="form-label">学籍番号<span style="color: red;">*</span></label>
            <input type="text" id="user_id" name="user_id" oninput="limitInput()"  class="input-field" placeholder="学籍番号" required><br>
            
            <label for="password" class="form-label">パスワード<span style="color: red;">*</span></label>
            <input type="password" id="password" name="password" class="input-field" placeholder="パスワード" maxlength="100" required><br>
            <button type="submit" class="next-button">次へ</button>
        </form>
        <form action="login.php">
            <button type="submit" class="login">アカウントをお持ちの方はこちら</button>
        </form>
    </div>
</body>
</html>
