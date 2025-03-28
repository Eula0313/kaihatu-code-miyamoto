<?php
session_start();
require '../db-connect.php';

try {
    $room_id = $_SESSION['room_id'] ?? null;
    if (!$room_id) {
        throw new Exception('Room ID not found in session.');
    }

    // データベースに接続する
    $dsn = 'mysql:host=' . SERVER . ';dbname=' . DBNAME . ';charset=utf8';
    $con = new PDO($dsn, USER, PASS);

    // エラーモードを例外モードに設定
    $con->beginTransaction();

    // テーブルのリセット
    $tables = ['Log', 'User', 'GameState', 'Board', 'Room'];
    foreach ($tables as $table) {
        $delete_sql = "DELETE FROM $table WHERE room_ID = :room_id";
        $delete_stmt = $con->prepare($delete_sql);
        $delete_stmt->execute([':room_id' => $room_id]);
    }

    // すべてのクエリが成功したらコミット
    $con->commit();

    // G1-2.phpにリダイレクト
    header("Location: ../G1-2/G1-2.php");
    exit();
} catch (PDOException $e) {
    // エラーが発生した場合はロールバック
    $con->rollBack();
    echo "削除中にエラーが発生しました: " . $e->getMessage();
    exit();
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage();
    exit();
}
?>
