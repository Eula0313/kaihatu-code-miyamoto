<? require '../db-connect.php';

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user_sql = 'DELETE FROM user WHERE client_id = :client_id';
    $user_stmt = $pdo->prepare($user_sql);
    $user_stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
    $user_stmt->execute();

    $board_sql = 'DELETE FROM board WHERE client_id = :client_id';
    $board_stmt = $pdo->prepare($board_sql);
    $board_stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
    $board_stmt->execute();

    // $detalist_sql = 'DELETE FROM detalist WHERE client_id = :client_id';
    // $detalist_stmt = $pdo->prepare($detalist_sql);
    // $detalist_stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
    // $detalist_stmt->execute();

    // $client_sql = 'DELETE FROM client WHERE ID = :client_id';
    // $client_stmt = $pdo->prepare($client_sql);
    // $client_stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
    // $client_stmt->execute();

    echo '削除が完了しました。<br>';
    echo '<a href="admin.php">管理者メニューへ戻る</a>';
    echo '<a href="admin.php">ユーザーリストへ戻る</a>';
} catch (PDOException $e) {
    echo '削除中にエラーが発生しました: ' . $e->getMessage();
} finally {
    $pdo = null;
}
