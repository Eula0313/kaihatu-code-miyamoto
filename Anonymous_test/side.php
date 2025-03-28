<?php
// サンプルデータ
$red_team = [
    'operator' => 'オペレーター',
    'astronaut' => 'アストロノーツ',
    'photo' => 'img/universe3.jpg' // 実際の画像パスに置き換えてください
];

$blue_team = [
    'operator' => 'オペレーター',
    'astronaut' => 'アストロノーツ',
    'photo' => 'img/universe4.jpg' // 実際の画像パスに置き換えてください
];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>チーム表示</title>
    <style>
        body {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0 5%; /* 左右に少し余白を追加 */
            background-color: #f0f0f0;
        }
        .team-container {
            display: flex;
            justify-content: space-between;
            width: 100%; /* 幅を全体に広げる */
        }
        .team-box {
            width: 20%; /* ボックスの幅をさらに狭くする */
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            color: white; /* テキストの色を白に変更 */
        }
        .red-team {
            background-color: red; /* 背景色を赤に変更 */
        }
        .blue-team {
            background-color: blue; /* 背景色を青に変更 */
        }
        .photo-container {
            display: flex;
            align-items: center;
        }
        .team-photo {
            width: 66.67%; /* コンテナの3分の2の幅に設定 */
            height: auto;
            border-radius: 10px;
        }
        .number {
            font-size: 32px; /* 数字のサイズを大きく変更 */
            margin-left: 10px; /* 数字と画像の間のスペースを追加 */
            margin-right: 10px; /* 数字と画像の間のスペースを追加 */
        }
        .team-info {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="team-container">
    <!-- 赤チーム -->
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

    <!-- 青チーム -->
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
</div>

</body>
</html>
