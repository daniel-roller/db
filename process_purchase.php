<?php
session_start();

// 引入資料庫連接
include "database_connection.php";

// 處理購買商品
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    // 檢查輸入的合法性
    if (empty($product_id) || empty($quantity) || empty($user_id)) {
        echo json_encode(['success' => false, 'error' => '輸入資料不完整']);
        exit();
    }

    // 將購買資訊存入資料庫
    $sql = "INSERT INTO orders (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => '購買成功!']);
    } else {
        echo json_encode(['success' => false, 'error' => '購買失敗,請稍後再試']);
    }
}
?>
