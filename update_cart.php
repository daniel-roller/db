<?php
include "database_connection.php"; // 引入資料庫連接檔案

session_start();

// 檢查使用者是否已登錄，否則重定向到登入頁面
if (!isset($_SESSION['username'])) {
    echo "您尚未登錄";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemID = $_POST['itemID'];
    $newQuantity = $_POST['quantity'];

    // 更新 cart 資料表中的 quantity 欄位
    $stmt = $db->prepare("UPDATE cart SET quantity = :quantity WHERE id = :itemID AND username = :username");
    $stmt->bindParam(":quantity", $newQuantity, PDO::PARAM_INT);
    $stmt->bindParam(":itemID", $itemID, PDO::PARAM_INT);
    $stmt->bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);
    $stmt->execute();

    echo "商品數量已更新";
}
?>
