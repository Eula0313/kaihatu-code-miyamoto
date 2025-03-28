<?php
session_start();
require 'db-connect.php';

if (isset($_POST['client_id'])) {
    $client_id = $_POST['client_id'];

    try {
        $pdo = new PDO($connect, USER, PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $favorites_sql = 'DELETE FROM favorites WHERE client_id = :client_id';
        $favorites_stmt = $pdo->prepare($favorites_sql);
        $favorites_stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
        $favorites_stmt->execute();

        $cart_sql = 'DELETE FROM cart WHERE client_id = :client_id';
        $cart_stmt = $pdo->prepare($cart_sql);
        $cart_stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
        $cart_stmt->execute();

        $detalist_sql = 'DELETE FROM detalist WHERE client_id = :client_id';
        $detalist_stmt = $pdo->prepare($detalist_sql);
        $detalist_stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
        $detalist_stmt->execute();

        $client_sql = 'DELETE FROM client WHERE ID = :client_id';
        $client_stmt = $pdo->prepare($client_sql);
        $client_stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
        $client_stmt->execute();

        echo '削除が完了しました。<br>';
        echo '<a href="kanri2.php">ユーザー管理画面へ戻る</a>';

    } catch (PDOException $e) {
        echo '削除中にエラーが発生しました: ' . $e->getMessage();
    } finally {
        $pdo = null;
    }
} else {
    echo 'ユーザーIDが指定されていません。';
}
