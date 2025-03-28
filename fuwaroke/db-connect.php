<?php
// データベース接続情報を定義します
const SERVER = 'mysql305.phy.lolipop.lan';
const DBNAME = 'LAA1517492-fuwaroke';
const USER = 'LAA1517492';
const PASS = 'Pass0313';

// PDO DSN (Data Source Name)
$dsn = 'mysql:host=' . SERVER . ';dbname=' . DBNAME . ';charset=utf8';

try {
    // PDOオブジェクトを作成し、データベースに接続します
    $pdo = new PDO($dsn, USER, PASS);
    // エラーモードを例外モードに設定します
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 接続エラーが発生した場合、エラーメッセージを表示します
    echo 'データベース接続エラー: ' . htmlspecialchars($e->getMessage());
    exit;
}
?>
