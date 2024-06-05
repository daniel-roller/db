<?php
session_start();
include "database_connection.php";

// 從資料庫中獲取使用者的購物車資料
$sql = "SELECT * FROM cart WHERE username = :username";
$stmt = $db->prepare($sql);
$stmt->bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);
$stmt->execute();
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 計算總金額
$totalAmount = 0;
foreach ($cartItems as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}

// 處理下單請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 開始交易
        $db->beginTransaction();

        // 插入每一個購物車商品到 purchase 表
        foreach ($cartItems as $item) {
            $insertSql = "INSERT INTO purchase (username, productName, price, quantity, total)
                          VALUES (:username, :productName, :price, :quantity, :total)";
            $insertStmt = $db->prepare($insertSql);
            $insertStmt->bindParam(":username", $item['username'], PDO::PARAM_STR);
            $insertStmt->bindParam(":productName", $item['productName'], PDO::PARAM_STR);
            $insertStmt->bindParam(":price", $item['price'], PDO::PARAM_STR);
            $insertStmt->bindParam(":quantity", $item['quantity'], PDO::PARAM_INT);
            $total = $item['price'] * $item['quantity'];
            $insertStmt->bindParam(":total", $total, PDO::PARAM_STR);
            $insertStmt->execute();
        }

        // 刪除購物車中的商品
        $deleteSql = "DELETE FROM cart WHERE username = :username";
        $deleteStmt = $db->prepare($deleteSql);
        $deleteStmt->bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);
        $deleteStmt->execute();

        // 提交交易
        $db->commit();
        $orderSuccess = true;
    } catch (Exception $e) {
        // 回滾交易
        $db->rollBack();
        echo "結帳失敗: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購物車</title>
    <style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: #f4f4f4;
    }
    .header {
        background: #333;
        color: white;
        padding: 10px 0;
        position: relative;
    }
    .header .container {
        width: 90%;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header .container .username {
        font-size: 16px;
    }
    .header .container .logout {
        font-size: 16px;
    }
    .header .container .logout a {
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        background: #007bff;
        border-radius: 4px;
        transition: background 0.3s ease;
    }
    .header .container .logout a:hover {
        background: #0056b3;
    }
    .content {
        padding: 20px;
        text-align: center;
    }
    .product-list {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    .product {
        background: white;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        width: 200px;
        text-align: center;
    }
    .product img {
        max-height: 150px;  /* Set the desired maximum height */
        width: auto;        /* Maintain the aspect ratio */
        display: block;
        margin: 0 auto;
        border-radius: 8px;
    }
    .product h2 {
        font-size: 18px;
        margin: 10px 0;
    }
    .product p {
        font-size: 16px;
        color: #555;
    }
    .product .price {
        font-size: 20px;
        color: #000;
        margin: 10px 0;
    }
    .product .buy-button {
        display: inline-block;
        padding: 10px 20px;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        transition: background 0.3s ease;
        margin-top: 20px; 
    }
    .product .buy-button:hover {
        background: #0056b3;
    }

    .content {
        padding: 40px;
        font-family: Arial, sans-serif;
    }

    .cart-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 16px;
        text-align: center; /* 新增這一行 */
    }

    .cart-table th,
    .cart-table td {
        padding: 12px;
        text-align: center; /* 新增這一行 */
        border-bottom: 1px solid #ddd;
    }

    .cart-table th {
        background-color: #f5f5f5;
    }

    .cart-table td img {
        max-height: 80px;
        width: auto;
    }

    .cart-table td .remove-button {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 8px 16px;
        text-decoration: none;
        cursor: pointer;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .cart-table td .remove-button:hover {
        background-color: #c82333;
    }

    .no-items {
        text-align: center;
        font-size: 18px;
        color: #555;
        margin-top: 30px;
    }

    .checkout-list {
    margin: 20px auto;
    width: 90%;
    max-width: 800px;
}

.checkout-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.checkout-table th, .checkout-table td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

.checkout-table th {
    background-color: #f5f5f5;
}

.checkout-table td img {
    max-height: 80px;
    width: auto;
}

.total-amount {
    margin-top: 20px;
    font-size: 20px;
    font-weight: bold;
    text-align: right;
}

.place-order-button {
    display: inline-block;
    padding: 10px 20px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background 0.3s ease;
    cursor: pointer;
    text-align: center;
    margin-top: 20px;
}

.place-order-button:hover {
    background: #0056b3;
}

    </style>
    <script>
        function placeOrder() {
            document.getElementById('order-form').submit();
        }
    </script>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="username">
                <?php echo "歡迎, " . htmlspecialchars($_SESSION['username']); ?>
            </div>
            <div class="logout">
                <a href="shop.php">前往商店</a>
            </div>
            <div class="logout">
                <a href="shop.php?logout=true">登出</a>
            </div>
        </div>
    </div>

    <div class="content">
        <h1>結帳</h1>
        <div class="checkout-list">
            <?php
            if (count($cartItems) > 0) {
                echo '<form id="order-form" method="POST">';
                echo '<table class="checkout-table">';
                echo '<tr><th>商品名稱</th><th>數量</th><th>價格</th></tr>';
                foreach ($cartItems as $item) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($item['productName']) . '</td>';
                    echo '<td>' . htmlspecialchars($item['quantity']) . '</td>';
                    echo '<td>$' . htmlspecialchars($item['price']) . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '<div class="total-amount">總金額: $' . number_format($totalAmount, 2) . '</div>';
                echo '<button type="button" class="place-order-button" onclick="placeOrder()">下單</button>';
                echo '</form>';
                if (isset($orderSuccess) && $orderSuccess) {
                    echo '<div id="order-confirmation" style="margin-top: 20px; font-size: 18px; color: green;">已成功下單！</div>';
                }
            } else {
                echo '<p>購物車是空的</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>
