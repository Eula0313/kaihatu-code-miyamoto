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
            <?php require "customer.php"; ?>
        </div>
    </div>
</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach(row => {
            row.addEventListener('click', () => {
                const userId = row.getAttribute('data-user-id');
                if (userId) {
                    window.location.href = `mypage_show.php?user_id=${userId}`;
                }
            });
        });
    });
</script>