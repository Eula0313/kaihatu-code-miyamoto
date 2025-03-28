<?php
if(isset($_SESSION['client'])){
    echo '<table>';
    echo '<tr><th>商品番号</th><th>商品名</th>';
    echo '<th>個数</th><th>単価</th></tr>';
    $pdo = new PDO($connect, USER, PASS);
    $sql = $pdo->prepare('select * from detalist, shohin ' .
    'where client_id=? and shohin_id=S_ID');
    $sql->execute([$_SESSION['client']['ID']]);
    foreach($sql as $row){
        $id=$row['S_ID'];
        $name=$row['S_name'];
        echo '<tr>';
        echo '<td>',$id ,'</td>';
        echo '<td><a href="detail.php?S_name='.$name.'">',$row['S_name'],'</a></td>';
        echo '<td>',$row['kosu'],'</td>';
        echo '<td>',$row['S_price'],'</td>';
        echo '<tr>';
    }
    echo '</table>';
}else{
    echo '購入履歴を表示するには、ログインしてください。';
}
?>