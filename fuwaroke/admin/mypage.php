<?php
// if (isset($_SESSION['user'])) {
$user_id = $_GET['user_id'];
$sql = $pdo->prepare('
                SELECT user.*, school_category.school_name
                FROM user
                JOIN school_category ON user.school_id = school_category.school_id
                WHERE user.user_id = ?');
$sql->execute([$user_id]);
$row = $sql->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo '<div class="profile">
            <h1>Profile</h1>
                <div class="photo-left">
                    <form id="profileImageForm" action="mypage.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="profile_image" id="profileImageInput" style="display: none;" onchange="document.getElementById(\'profileImageForm\').submit();">
                        <img class="photo" src="../' . htmlspecialchars($row['icon']) . '" alt="プロフィール画像" id="profileImage" onclick="document.getElementById(\'profileImageInput\').click();">
                    </form>
                <br>
                <h2 class="name">' . htmlspecialchars($row['user_name']) . '</h2>
                <p class="desc"></p>
                <p class="desc">学校名：' . htmlspecialchars($row['school_name']) . '</p>
                <p class="desc">自己紹介：' . htmlspecialchars($row['introduction']) . '</p>
                <p class="desc">ポートフォリオ：' . htmlspecialchars($row['portfolio']) . '</p>';
    echo '<div class="social">
                        <a href="https://www.facebook.com/"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
                        <a href="https://twitter.com/"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
                        <a href="https://www.instagram.com/"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                        <a href="https://www.github.com/"><i class="fa fa-github" aria-hidden="true"></i></a>
                    </div>
                </div>
                <p class="desc"></p>';

    $post_sql = $pdo->prepare('SELECT post_id, post_title FROM post WHERE user_id = ?');
    $post_sql->execute([$user_id]);
    $posts = $post_sql->fetchAll(PDO::FETCH_ASSOC);

    if ($posts) {
        echo '<div class="posts"><h3>投稿履歴</h3>';
        foreach ($posts as $post) {
            echo '<p><a href="board_detail.php?board_id=' . $post['post_id'] . '">' . htmlspecialchars($post['post_title']) . '</a></p>';
        }
        echo '</div>';
    } else {
        echo '<p>このユーザーは掲示板を<br>
                作成したことがありません</p>';
    }
} else {
    echo '<h2 class="error-message">ユーザーの情報が見つかりませんでした。</h2>';
}
// } else {
//     echo '<br><h2 class="error-message">ユーザーはログインしていません。情報を表示するにはログインしてください。</h2><br>';
//     echo '<h2 class="error-message"><a href="login.php">ログインはこちら</a></h2>';
// }
