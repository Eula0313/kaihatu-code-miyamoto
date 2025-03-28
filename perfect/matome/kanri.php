<?php
session_start();
require 'db-connect.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style2.css">
    <title>ユーザー管理</title>
</head>
<body class="b">

    <div class="header-logo">
        <h1 class="shine">SHINE</h1>
    </div>
    <div class="cnt">
        <h1>ユーザー管理</h1>
        <?php
            if (isset($_GET['client_id'])) {
                $client_id = $_GET['client_id'];
                $pdo = new PDO($connect, USER, PASS);
                $sql = 'SELECT * FROM client WHERE ID = :client_id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':client_id', $client_id, PDO::PARAM_STR); 
                $stmt->execute();
                $client = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($client) {
                    echo '<p>お名前： ','<span style="color: red;">' . $client['name'] . '</span> 生年月日： ','<span style="color: red;">' . $client['BD'] . '</span></p>';
                    echo '<p>住所： ','<span style="color: red;">' . $client['address'] . '</span></p>';
                    echo '<p>メールアドレス： ','<span style="color: red;">' . $client['mail'] . '</span></p>';
                    echo '<p>電話番号： ','<span style="color: red;">'  . $client['tell'] .  '</span></p>';
                } else {
                    echo '登録された情報が見つかりませんでした。';
                }
            } 

        ?>
       <hr>
            <form method="post" action="delete.php" id="deleteForm">
                <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
                <input type="submit" class="button" value="アカウント削除" style="background: red; color: white;">
            </form>

            <script>
                document.getElementById('deleteForm').addEventListener('submit', function (event) {
                    if (!confirm('本当にアカウントを削除しますか？')) {
                        event.preventDefault(); 
                    }
                });
            </script>

        </div>
        <hr>
            <?php
                $detail_sql = 'SELECT * FROM detalist WHERE client_id = :client_id';
                $detail_stmt = $pdo->prepare($detail_sql);
                $detail_stmt->bindParam(':client_id', $client_id, PDO::PARAM_STR);
                $detail_stmt->execute();

                    echo '<h1>購入した商品</h1>';

                        if ($detail_stmt->rowCount() > 0) {
                            while ($row = $detail_stmt->fetch(PDO::FETCH_ASSOC)) {
                
                            $shohin_sql = 'SELECT * FROM shohin WHERE S_ID = :shohin_id';
                            $shohin_stmt = $pdo->prepare($shohin_sql);
                            $shohin_stmt->bindParam(':shohin_id', $row['shohin_id'], PDO::PARAM_INT);
                            $shohin_stmt->execute();
                            $shohin = $shohin_stmt->fetch(PDO::FETCH_ASSOC);

                                echo '<div class="shohin">';
                                echo '<img src="' . $shohin['img_pass'] . '" width="200" height="200">';
                                echo '<p>商品名: ' . $shohin['S_name'] . '</p>';
                                echo '<p>カラー: ' . $shohin['S_color'] . '<span style="margin-right: 80px;"></span>数量: ' . $row['kosu'] . '</p>';
                                echo '<p>サイズ: ' . $shohin['S_size'] . '<span style="margin-right: 160px;"></span>価格: ￥' . $shohin['S_price'] . '</p>';
                                echo '</div>';
                                echo '<hr>';
                            }
                        } else {
                            echo '購入した商品はありません。';
                        }
            ?>
    </body>
</html>
