<?php
    const SERVER    = 'mysql218.phy.lolipop.lan';
    const DBNAME    = 'LAA1517459-ensyu';
    const USER      = 'LAA1517459';
    const PASS      = 'Pass0515';

    $connect = 'mysql:host='. SERVER .';dbname='. DBNAME .';charset=utf8';

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit();
}
?>
