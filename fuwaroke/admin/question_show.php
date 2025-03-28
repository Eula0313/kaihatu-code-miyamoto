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
            <?php require "question.php"; ?>
        </div>
    </div>
</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach(row => {
            row.addEventListener('click', () => {
                const boardId = row.getAttribute('data-board-id');
                if (boardId) {
                    window.location.href = `board_detail.php?board_id=${boardId}`;
                }
            });
        });
    });
</script>
