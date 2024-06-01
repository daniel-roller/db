<?php
include "database_connection.php"; // 引入資料庫連接檔案

session_start();

// 檢查使用者是否已登錄，否則重定向到登入頁面
if (!isset($_SESSION['username'])) {
    echo "您尚未登錄";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productID = $_POST['productID'];
    $quantity = $_POST['quantity'];

    // 從 shop 資料表中獲取商品詳細信息
    $stmt = $db->prepare("SELECT * FROM shop WHERE id = :productID");
    $stmt->bindParam(":productID", $productID, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // 檢查 cart 資料表中是否已經存在該商品
    $stmt = $db->prepare("SELECT * FROM cart WHERE username = :username AND productName = :productName");
    $stmt->bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);
    $stmt->bindParam(":productName", $product['productName'], PDO::PARAM_STR);
    $stmt->execute();
    $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingItem) {
        // 如果已經存在,則更新 quantity 欄位
        $newQuantity = $existingItem['quantity'] + $quantity;
        $stmt = $db->prepare("UPDATE cart SET quantity = :quantity WHERE id = :id");
        $stmt->bindParam(":quantity", $newQuantity, PDO::PARAM_INT);
        $stmt->bindParam(":id", $existingItem['id'], PDO::PARAM_INT);
        $stmt->execute();
        echo "商品數量已更新";
    } else {
        // 如果不存在,則插入新的資料列
        $stmt = $db->prepare("INSERT INTO cart (username, productName, price, quantity, image, description) VALUES (:username, :productName, :price, :quantity, :image, :description)");
        $stmt->bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);
        $stmt->bindParam(":productName", $product['productName'], PDO::PARAM_STR);
        $stmt->bindParam(":price", $product['price'], PDO::PARAM_STR);
        $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
        $stmt->bindParam(":image", $product['image'], PDO::PARAM_STR);
        $stmt->bindParam(":description", $product['description'], PDO::PARAM_STR);
        $stmt->execute();
        echo "商品已加入購物車";
    }
}
?>
