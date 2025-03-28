<?php
// サンプルデータ
$blue_team = [
    'operator' => 'オペレーター',
    'astronaut' => 'アストロノーツ',
    'photo' => 'img/universe4.jpg' // 実際の画像パスに置き換えてください
];
?>

<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="css/side.css">
<head>
    <meta charset="UTF-8">
    <title>青チーム</title>
    
</head>
<body>

<div class="team-box blue-team">
    <h2>青チーム</h2>
    <div class="photo-container">
        <img src="<?php echo $blue_team['photo']; ?>" alt="青チーム写真" class="team-photo">
        <span class="number">9</span>
    </div>
    <div class="team-info">
        <p>オペレーター: <?php echo $blue_team['operator']; ?></p>
        <p>アストロノーツ: <?php echo $blue_team['astronaut']; ?></p>
    </div>
</div>

</body>
</html>
