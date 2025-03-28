<?php
session_start();
require 'db-connect.php';
$pdo = new PDO($connect, USER, PASS);
$sql = 'SELECT * FROM client ORDER BY ID ASC';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style2.css">
    <title>ユーザー管理2</title>
</head>
<body class="c">

    <div class="header-logo">
        <h1 class="shine">SHINE</h1>
    </div>
    <div class="cnt">
        <h1>ユーザー管理</h1>
        <hr>
        <h2>ログインID <span style="margin-right: 280px;"></span> メールアドレス</h2>
    </div>
    <hr>
    
    <form action="kanri.php" method="get">
        <?php
        foreach ($pdo->query($sql) as $row) {
            echo '<div class="radio_cnt">';
            echo '<div class="radio">';
            echo '<input type="radio" name="client_id" value="' . $row['ID'] . '" style="transform: scale(2.0);">';
            echo '</div>';
            echo $row['ID'];
            echo '<div class="kuhaku">',$row['mail'];
            echo '</div>';
            echo '</div>';
            echo '<hr>';
        }
        ?>
    </form>

    <script>
        const radioButtons = document.querySelectorAll('input[type="radio"]');
        radioButtons.forEach((radioButton) => {
            radioButton.addEventListener('change', (event) => {
                event.target.closest('form').submit();
            });
        });
    </script>
</body>
</html>
