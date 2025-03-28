<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $password = $_POST['password']; // パスワードも受け取る

    // 入力されたユーザIDがデータベース内に存在するか確認
    $sql = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
    $sql->execute([$user_id]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    // 結果の行数を取得
    $rowCount = $sql->rowCount();

    
    if ($rowCount > 0) {
        echo "<p id='errorMessage'style='color:red;'>※この学籍番号は既に使用されています。別の学籍番号を入力してください。</p>";
        //5秒間表示させる
        echo "<script>
        setTimeout(function(){
            var element = document.getElementById('errorMessage');
            element.parentNode.removeChild(element);
        }, 5000)
        </script>";
    } else {
        // ここでユーザIDとパスワードを次のページに渡す
        header("Location: touroku2.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <script type="text/javascript">
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
    <link rel="stylesheet" href="css/touroku.css">
    <title>新規登録</title>
</head>
<body>
    <div class="login-container">
        <h1 class="login-header">新規登録</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="user_id" class="form-label">学籍番号</label>
            <input type="text" id="user_id" name="user_id" oninput="limitInput()" class="input-field" placeholder="学籍番号" required><br>
            
            <label for="password" class="form-label">パスワード</label>
            <input type="password" id="password" name="password" class="input-field" placeholder="パスワード" required><br>
            <button type="submit" class="next-button">NEXT</button>
        </form>
        <p class="forgot-password-link"><a href="login.php">すでにアカウントをお持ちの方はこちら</a></p>
    </div>

    
</body>
</html>
