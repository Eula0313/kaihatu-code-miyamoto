<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>kadai2-6-output</title>
</head>
<body>
    <?php
    $kubun = [
        '織田信長' => 1,
        '坂本龍馬' => 2,
        '伊達政宗' => 3,
        '福沢諭吉' => 4,
        '源義経' => 5,
        '真田幸村' => 6,
        '上杉謙信' => 7
    ];

    $check = []; 

    if (isset($_POST['kubun']) && is_array($_POST['kubun'])) {
        foreach ($_POST['kubun'] as $select) {
            if (isset($kubun[$select])) {
                $check[] = $select; 
            }
        }
    }

    if (!empty($check)) {
        echo '<br>賛成のメンバー: ';
        echo implode(', ', $check);
    }

    if (count($check) >= 4) {
        echo '<br><br>賛成多数';
    } else {
        echo '<br><br>反対多数';
    }

    ?>
</body>
</html>
