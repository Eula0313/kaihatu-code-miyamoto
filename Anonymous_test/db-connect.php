<?php
const SERVER = 'mysql305.phy.lolipop.lan';
const DBNAME = 'LAA1518876-anonymous24';
const USER = 'LAA1518876';
const PASS = 'Pass0309';

function connectDB() {
    $dsn = 'mysql:host=' . SERVER . ';dbname=' . DBNAME . ';charset=utf8';
    return new PDO($dsn, USER, PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
}
?>
