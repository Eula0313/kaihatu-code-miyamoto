<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者画面</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <?php require "admin_header.php"; ?>
    <div class="content">
        <?php require "admin_side.php"; ?>
        <div class="flex-content">
            <?php require "category.php"; ?>
        </div>
    </div>

    <script>
        // URLSearchParams を使ってクエリパラメータを取得
        const params = new URLSearchParams(window.location.search);
        if (params.get('delete_success') === '1') {
            alert('カテゴリーの削除に成功しました。');
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            const deleteButtons = document.querySelectorAll('.delete-button');
            deleteButtons.forEach(button => {
                button.addEventListener('click', (event) => {
                    if (!confirm('本当にこのカテゴリーを削除しますか？')) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
</body>

</html>
