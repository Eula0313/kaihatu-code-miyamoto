<?php
require '../db-connect.php';

// 検索キーワードの取得
$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<div class="board-head">
    <h1 class="content-title">掲示板リスト</h1>
    <form class="search-form" method="get" action="board-show.php">
        <input type="text" name="search" class="search-input" placeholder="検索キーワード" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="submit" class="search-button" value="検索">
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>掲示板ID</th>
            <th>掲示板タイトル</th>
            <th>ユーザーID</th>
            <th>作成日</th>
            <th>カテゴリID</th>
            <th>カテゴリ名</th>
            <th>コメント</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // 検索キーワードが指定されている場合は、LIKE句を使用してフィルタリング
        if (!empty($search)) {
            $stmt = $pdo->prepare('SELECT post.*, category.cate_name FROM post LEFT JOIN category ON post.cate_id = category.cate_id WHERE post.post_title LIKE ?');
            $stmt->execute(['%' . $search . '%']);
        } else {
            // 検索キーワードが指定されていない場合は、全件取得
            $stmt = $pdo->query('SELECT post.*, category.cate_name FROM post LEFT JOIN category ON post.cate_id = category.cate_id');
        }

        // 結果の表示
        foreach ($stmt as $row) {
            $postId = htmlspecialchars($row['post_id'] ?? '', ENT_QUOTES, 'UTF-8');
            $postTitle = htmlspecialchars($row['post_title'] ?? '', ENT_QUOTES, 'UTF-8');
            $userId = htmlspecialchars($row['user_id'] ?? '', ENT_QUOTES, 'UTF-8');
            $createTime = htmlspecialchars($row['t_create_time'] ?? '', ENT_QUOTES, 'UTF-8');
            $cateId = htmlspecialchars($row['cate_id'] ?? '', ENT_QUOTES, 'UTF-8');
            $cateName = htmlspecialchars($row['cate_name'] ?? '', ENT_QUOTES, 'UTF-8');
            $comment = htmlspecialchars($row['comment'] ?? '', ENT_QUOTES, 'UTF-8');

            echo '<tr data-board-id="' . $postId . '">';
            echo '<td>' . $postId . '</td>';
            echo '<td>' . $postTitle . '</td>';
            echo '<td>' . $userId . '</td>';
            echo '<td>' . $createTime . '</td>';
            echo '<td>' . $cateId . '</td>';
            echo '<td>' . $cateName . '</td>';
            echo '<td>' . $comment . '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>
