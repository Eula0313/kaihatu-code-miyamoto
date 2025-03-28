<?php
require '../db-connect.php';

// 検索キーワードの取得
$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<div class="category-head">
    <h1 class="content-title">カテゴリーリスト</h1>
    <form class="search-form" method="get" action="category-show.php">
        <input type="text" name="search" class="search-input" placeholder="検索キーワード" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="submit" class="search-button" value="検索">
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>カテゴリーID</th>
            <th>カテゴリー名</th>
            <th>削除</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // 検索キーワードが指定されている場合は、LIKE句を使用してフィルタリング
        if (!empty($search)) {
            $stmt = $pdo->prepare('SELECT * FROM category WHERE cate_name LIKE ?');
            $stmt->execute(['%' . $search . '%']);
        } else {
            // 検索キーワードが指定されていない場合は、全件取得
            $stmt = $pdo->query('SELECT * FROM category');
        }

        // 結果の表示
        foreach ($stmt as $row) {
            $cateId = htmlspecialchars($row['cate_id'] ?? '', ENT_QUOTES, 'UTF-8');
            $cateName = htmlspecialchars($row['cate_name'] ?? '', ENT_QUOTES, 'UTF-8');

            echo '<tr data-category-id="' . $cateId . '">';
            echo '<td>' . $cateId . '</td>';
            echo '<td>' . $cateName . '</td>';
            echo '<td class="category-delete"><button class="category-delete-button" onclick="confirmDelete(' . $cateId . ')">削除</button></td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>

<script>
    function confirmDelete(categoryId) {
        if (confirm('本当にこのカテゴリーを削除しますか？このカテゴリーに属するすべての掲示板も削除されます。')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'category_delete.php';

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'category_id';
            input.value = categoryId;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
