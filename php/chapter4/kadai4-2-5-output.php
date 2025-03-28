<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charaset="UTF-8">
    <title>課題4-2-5-output</title>
</head>
<body>
        <h1>じゃんけんゲーム</h1>
<?php
    if (isset($_POST['a'])) {
        $b = ['グー', 'チョキ', 'パー'];
        $c_hand = $b[rand(0, 2)];
        $p_hand_value= $_POST['a'];
        $p_hand = $b[$p_hand_value];

        echo 'あなたの手:', $p_hand, '<br>';
        echo 'コンピュータの手:', $c_hand, '<br>';

        if ($p_hand == $c_hand) {
            echo '引き分けです<br>';

        }else if ($p_hand_value==0 && $c_hand=='チョキ'){
            echo 'あなたの勝ちです<br>';

        }else if ($p_hand_value==1 && $c_hand=='パー'){
            echo 'あなたの勝ちです<br>';

        }else if ($p_hand_value==2 && $c_hand=='グー'){
            echo 'あなたの勝ちです<br>';

        }else if ($p_hand_value == $c_hand) {
                echo '引き分けです<br>';

        }else{
            echo 'あなたの負けです<br>';
        }
    }

?>
</body>
</html>