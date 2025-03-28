<?php session_start(); ?>
<?php require 'db-connect.php'; 
if(empty($_SERVER["HTTP_REFERER"])){
    header("Location: touroku.php");
    exit();
}
?>

<?php
    $user_id = $_SESSION['user_id'];
    $password = $_SESSION['password'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/touroku2.css">
    <title>新規登録</title>
</head>
<body>
<?php require 'header.php'; ?>
<?php
    $user_name = $school_id = $introduction =$portfolio =$icon= '';

    echo '<div class="touroku1-container">';

    echo'<h2 class="touroku1-header">新規登録</h2>';
    echo'<form action="save.php" method="post" enctype="multipart/form-data">';

    echo '<input type="hidden" name="user_id" value="', $user_id, '">';
    echo '<input type="hidden" name="password" value="', $password, '">';

    echo '<span style="opacity: 0.5;">(ログイン後、マイページから変更できます。)</span>';

    echo'<label>';
    echo'<span class="form-label">ニックネーム<span style="color: red;">*</span></span>';
    echo'<input type="text" name="user_name" class="input-field" maxlength="50" required/>';
    echo'</label>';

    echo'<label class="form-label">学校名<span style="color: red;">*</span></label>';
    echo '<select name="school_id" class="input-field" required>';
    $sql = $pdo->query('select * from school_category');
    foreach ($sql as $row) {
        echo '<option value="', $row['school_id'], '">', $row['school_name'], '</option>';
        echo '\n';
    }
    echo'</select>';
    echo'</label>';

    echo '<label>';
    echo '<span class="form-label">自己紹介</span>';
    echo '<textarea name="introduction" class="input-field" placeholder="(趣味・特技、学んでいること・取得検定・就活状況など、1000文字以内)" rows="5" cols="50" maxlength="1000"></textarea>';
    echo '</label>';

    echo '<label>';
    echo '<span class="form-label">ポートフォリオ</span>';
    echo '<textarea name="portfolio" class="input-field"  placeholder="1000文字以内" rows="5" cols="50" maxlength="1000"/></textarea>';
    echo '</label>';
    
    echo '<label for="icon">';
    echo '<span class="form-label">アイコン画像( jpeg・jpg・png・jfif 形式のみ)</span>';
    echo '<span style="opacity: 0.5;">(選択しなかった場合は、自動的にデフォルトの画像が表示されます。)</span>';
    echo '<input type="file" name="profile_image" accept="image/jpeg,image/jpg,image/png,image/jfif"  id="profileImageInput"  class="input-field" />';
    echo '</label>';

    
    echo '<button class="next-button">登録</button>';

    echo '</form>';

    echo'</div>';
    ?>
</body>
<script>
    document.getElementById('profileImageInput').addEventListener('change', function
        (event){
            const file = event.target.files[0];
            const allowedExtensions = ['jpg', 'jpeg', 'png', 'jfif'];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if(!allowedExtensions.includes(fileExtension)){
                alert('許可されていないファイル形式です。');
                this.value = ''; // ファイル選択欄をクリア
            }
        }
    )
</script>
</html>