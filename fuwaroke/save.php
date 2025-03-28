<?php session_start(); ?>
<?php require 'db-connect.php'; ?>
<?php
if($_SERVER["REQUEST_METHOD"] != "POST"){
    header("Location: touroku.php");
    exit();
}
// フォームから送信されたデータを受け取る
$user_id = $_POST['user_id'];
$user_name = $_POST['user_name'];
$password = $_POST['password'];
$school_id = $_POST['school_id'];
$introduction = $_POST['introduction'];
$portfolio = $_POST['portfolio'];

$registration_success = false;

// ファイルが選択されたかどうかをチェック
if ($_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    // ファイルがアップロードされた場合の処理

    // アップロードされたファイル情報
    $file_name = $_FILES['profile_image']['name'];
    $file_tmp = $_FILES['profile_image']['tmp_name'];

    // 画像ファイルを保存するディレクトリ（適宜変更する）
    $upload_dir = 'image/';

    //アップロード先のパス
    $target_path = $upload_dir . $file_name;

    // ファイルを移動（アップロード）
    if (move_uploaded_file($file_tmp, $target_path)) {
        try {

            //パスワードをハッシュ化
            $hashed_password=password_hash($password,PASSWORD_DEFAULT);
            
            // SQLクエリを準備
            $stmt = $pdo->prepare("INSERT INTO user (user_id, user_name, password, school_id, introduction, portfolio, icon) VALUES (:user_id, :user_name, :password, :school_id, :introduction, :portfolio, :icon)");
            
            // パラメータをバインド
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':user_name', $user_name);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':school_id', $school_id);
            $stmt->bindParam(':introduction', $introduction);
            $stmt->bindParam(':portfolio', $portfolio);
            $stmt->bindParam(':icon', $target_path, PDO::PARAM_STR); // PDO::PARAM_LOB ではなく PDO::PARAM_STR を使う
            
            // クエリを実行
            $stmt->execute();
            $registration_success = true;
        } catch(PDOException $e) {
            // エラーが発生した場合の処理
            echo "エラー: " . $e->getMessage();
        }
    } else {
        echo "ファイルのアップロードに失敗しました。";
    }
} else {
    // ファイルが選択されなかった場合の処理

    // デフォルトの画像パス
    $default_image = 'image/default.jpg';

    try {

        //パスワードをハッシュ化
        $hashed_password=password_hash($password,PASSWORD_DEFAULT);
        
        // SQLクエリを準備
        $stmt = $pdo->prepare("INSERT INTO user (user_id, user_name, password, school_id, introduction, portfolio, icon) VALUES (:user_id, :user_name, :password, :school_id, :introduction, :portfolio, :icon)");
        
        // パラメータをバインド
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':school_id', $school_id);
        $stmt->bindParam(':introduction', $introduction);
        $stmt->bindParam(':portfolio', $portfolio);
        $stmt->bindParam(':icon', $default_image, PDO::PARAM_STR); // PDO::PARAM_LOB ではなく PDO::PARAM_STR を使う
        
        // クエリを実行
        $stmt->execute();
        $registration_success = true;
    } catch(PDOException $e) {
        // エラーが発生した場合の処理
        echo "エラー: " . $e->getMessage();
    }
}
?>
<?php require 'header.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>登録結果</title>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if ($registration_success): ?>
                alert("登録が完了しました！ログイン画面からログインしてください。");
                window.location.href = "login.php";
            <?php else: ?>
                alert("登録に失敗しました。もう一度お試しください。");
                window.location.href = "touroku.php";
            <?php endif; ?>
        });
    </script>
</head>
</html>

