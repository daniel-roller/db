<?php
// database_connection.php

// 資料庫連接設置
$host = "localhost:3306";
$dbusername = "root";
$dbpassword = "594212Daniel";
$dbname = "database";

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    // 設置 PDO 錯誤模式為異常
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "資料庫連接失敗: " . $e->getMessage();
}

// function getProducts() {
//     global $db;
//     $sql = "SELECT * FROM products";
//     $stmt = $db->prepare($sql);
//     $stmt->execute();
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }
