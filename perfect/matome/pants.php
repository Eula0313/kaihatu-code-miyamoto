<?php require 'db-connect.php'; ?>
<?php require 'header.php' ?>
<link rel="stylesheet" href="css/menu_k.css">
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
                    <li><a href="bag.php" name="cate" value="バッグ" class="btn2">バッグ</a></li>
                </ul>
            </div>
        </div>
        <div class="main-contents">
            <div class="menu-ber">
                <div class="menu-link">
                    <a href="index.php" class="custom-btn btn-11">ALL</a><span style="margin-right: 40px;"></span>
                    <a href="mens.php" class="custom-btn btn-11">MENS</a><span style="margin-right: 40px;"></span>
                    <a href="womans.php" class="custom-btn btn-11">WOMEN</a>
                </div>
            </div>
            <div class="shohin-img">
                <div class="shohin-category-img">
                    <?php
                    $pdo = new PDO($connect, USER, PASS);
                    $sql = $pdo->query('select distinct S_name, img_pass from shohin where S_cate="パンツ" && S_size="S"');
                    foreach ($sql as $row) {
                        $name = $row['S_name'];
                        echo '<a class="detail" href="detail.php?S_name=', $name, '"><img id="s_img" class="img" src="' . $row['img_pass'] . '"></a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
