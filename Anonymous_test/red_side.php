<?php
// サンプルデータ
$red_team = [
    'operator' => 'オペレーター',
    'astronaut' => 'アストロノーツ',
    'photo' => 'img/universe3.jpg' // 実際の画像パスに置き換えてください
];
?>

<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="css/side.css">
<head>
    <meta charset="UTF-8">
    <title>赤チーム</title>
    <style>
        /* スタイルは省略 */
    </style>
</head>
<body>

<div class="team-box red-team">
    <h2>赤チーム</h2>
    <div class="photo-container">
        <img src="<?php echo $red_team['photo']; ?>" alt="赤チーム写真" class="team-photo">
        <span class="number">9</span>
    </div>
    <div class="team-info">
        <p>オペレーター: <?php echo $red_team['operator']; ?></p>
        <p>アストロノーツ: <?php echo $red_team['astronaut']; ?></p>
    </div>
</div>

</body>
</html>
