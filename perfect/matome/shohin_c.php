<?php require 'db-connect.php'; ?>
<?php require 'header.php' ?>
<link rel="stylesheet" href="css/menu.css">
<div class="menu">
    <div class="main">
        <div class="sidebar">
            <div class="category">
                <p class="category-name">カテゴリー</p>
                <ul>
                    <li><a href="T-shirts.php" name="cate" value="Tシャツ" class="btn2">Tシャツ</a></li>
                    <li><a href="Y-shirts.php" name="cate" value="Yシャツ" class="btn2">Yシャツ</a></li>
                    <li><a href="shirts.php" name="cate" value="シャツ" class="btn2">シャツ</a></li>
                    <li><a href="skirt.php" name="cate" value="スカート" class="btn2">スカート</a></li>
                    <li><a href="onepiece.php" name="cate" value="ワンピース" class="btn2">ワンピース</a></li>
                    <li><a href="pants.php" name="cate" value="パンツ" class="btn2">パンツ</a></li>
                </ul>
            </div>
        </div>
        <div class="main-contents">
            <div class="menu-ber">
                <div class="menu-link">
                    <a href="product.php" class="sentaku">全て</a>
                    <a href="favorite-show.php" class="sentaku">メンズ</a>
                    <a href="history.php" class="sentaku">レディース</a>
                    <a href="cart-show.php" class="sentaku">シニア</a>
                    <a href="purchase-input.php" class="sentaku">キッズ</a>
                </div>
            </div>
            <div class="shohin-img">
                <?php
                $pdo = new PDO($connect, USER, PASS);
                $sql = $pdo->prepare('select distinct * from shohin where S_cate=?');
                $sql->execute($GET['cate']);
                foreach ($sql as $row) {
                    echo '<table>';
                    echo '<tr>';
                    echo '<th>';
                    echo '<img class="img" src="' . $row['img_pass'] . '">';
                    echo '</th>';
                    echo '</tr>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
