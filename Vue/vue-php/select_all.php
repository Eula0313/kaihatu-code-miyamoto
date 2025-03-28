<?php
const SERVER = 'mysql215.phy.lolipop.lan';
const DBNAME = 'LAA1517492-shop';
const USER = 'LAA1517492';
const PASS = 'Pass0313';

    $connect = 'mysql:host='. SERVER . ';dbname='. DBNAME . ';charset=utf8';
    $pdo=new PDO($connect, USER, PASS);

    if($pdo){
    }else{
      echo "データベースに接続できません";
      exit;
    }
    $sql=$pdo->query('select*from product');// productテーブルからデータをすべて取得するSQL
 
    if($sql->rowCount() == 0){
      echo "レコードが有りません";
    }else{
      foreach ($sql as $row) {
          $data[] = $row;
      } 
    }
    //値をjson形式で出力
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>

