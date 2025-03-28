<?php
require '../db-connect.php';

// 検索キーワードの取得
$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<div class="customer-head">
    <h1 class="content-title">ユーザーリスト</h1>
    <form class="search-form" method="get" action="customer-show.php">
        <input type="text" name="search" class="search-input" placeholder="検索キーワード"
            value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="submit" class="search-button" value="検索">
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>名前</th>
            <th>学校ID</th>
            <th>学校名</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // 検索キーワードが指定されている場合は、LIKE句を使用してフィルタリング
        if (!empty($search)) {
            $stmt = $pdo->prepare('SELECT user.*, school_category.school_name FROM user LEFT JOIN school_category ON user.school_id = school_category.school_id WHERE user.user_name LIKE ?');
            $stmt->execute(['%' . $search . '%']);
        } else {
            // 検索キーワードが指定されていない場合は、全件取得
            $stmt = $pdo->query('SELECT user.*, school_category.school_name FROM user LEFT JOIN school_category ON user.school_id = school_category.school_id');
        }

        // 結果の表示
        foreach ($stmt as $row) {
            $userId = htmlspecialchars($row['user_id'], ENT_QUOTES, 'UTF-8');
            $userName = htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8');
            $schoolId = htmlspecialchars($row['school_id'], ENT_QUOTES, 'UTF-8');
            $schoolName = htmlspecialchars($row['school_name'], ENT_QUOTES, 'UTF-8');

            echo '<tr data-user-id="' . $userId . '">';
            echo '<td>' . $userId . '</td>';
            echo '<td>' . $userName . '</td>';
            echo '<td>' . $schoolId . '</td>';
            echo '<td>' . $schoolName . '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>
