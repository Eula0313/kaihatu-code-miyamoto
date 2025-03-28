<?php
session_start();
require '../db-connect.php';
 
function getRandomWords($pdo) {
    $sql = 'SELECT card_Eng FROM Card ORDER BY RAND() LIMIT 3';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $words = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $words;
}
 
function createDynamicUrl($pdo) {
    $baseUrl = 'http://aso2201238.chips.jp/Anonymous-test/Anonymous.game/room/';
    $words = getRandomWords($pdo);
    $path = implode('-', $words);
    $fullUrl = $baseUrl . $path;
    return $fullUrl;
}
 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nickname'])) {
    $nickname = $_POST['nickname'];
 
    try {
        $pdo = connectDB();
        $pdo->beginTransaction();
 
        // Roomを作成
        $stmt = $pdo->prepare("INSERT INTO Room (URL) VALUES ('temp')");
        $stmt->execute();
        $room_id = $pdo->lastInsertId();
 
        // RoomのURLを更新し、ランダムなカード名をroom_keyに登録
        $words = getRandomWords($pdo);
        $url = createDynamicUrl($pdo);
        $room_key = implode('-', $words);
        $stmt = $pdo->prepare("UPDATE Room SET URL = ?, room_key = ? WHERE room_ID = ?");
        $stmt->execute([$url, $room_key, $room_id]);
 
        // Userを作成 (team_IDとrole_IDをNULLで設定)
        $stmt = $pdo->prepare("INSERT INTO User (room_ID, user_name, team_ID, role_ID) VALUES (?, ?, NULL, NULL)");
        $stmt->execute([$room_id, $nickname]);
 
        $_SESSION['nickname'] = $nickname;
        $_SESSION['is_host'] = true;
        $_SESSION['room_id'] = $room_id;
        $_SESSION['room_key'] = $room_key;
        $_SESSION['last_room_key'] = $room_key;
        //追加要素
        $_SESSION['user_id'] = $pdo->lastInsertId();
 
        $pdo->commit();
 
        // room_idとroom_keyをURLに含めてリダイレクト
        header("Location: ../G1-3/G1-3.php?room_id=$room_id&room_key=$room_key");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error_message'] = 'データベースエラー: ' . $e->getMessage();
        header('Location: G1-2.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = 'ニックネームを入力してください。';
    header('Location: G1-2.php');
    exit();
}
?>