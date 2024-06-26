<?php
include "database_connection.php"; // 引入資料庫連接檔案

session_start();

// 檢查使用者是否已登錄，否則重定向到登入頁面
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 登出處理
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// 從 cart 資料表中獲取購物車商品資訊
$sql = "SELECT * FROM cart WHERE username = :username";
$stmt = $db->prepare($sql);
$stmt->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
$stmt->execute();
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 處理刪除商品
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
    $productID = $_POST['remove_item'];
    $sql = "DELETE FROM cart WHERE id = :productID AND username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
    $stmt->bindParam(':username', $_SESSION['username'], PDO::PARAM_STR);
    $stmt->execute();
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購物車</title>
    <style>

    /* 結帳頁面的樣式 */
.checkout-content {
  max-width: 800px;
  margin: 0 auto;
  padding: 40px;
}

.checkout-list {
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 20px;
}

.checkout-item {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 20px;
  border-bottom: 1px solid #ddd;
}

.checkout-item .icon_1 {
  width: 80px;
  height: 80px;
  overflow: hidden;
  margin-right: 20px;
}

.checkout-item .icon_1 img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.checkout-item h2 {
  font-size: 18px;
  margin-bottom: 5px;
}

.checkout-item .price {
  font-size: 16px;
  font-weight: bold;
  margin-left: auto;
}

.total-amount {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 20px;
  padding-top: 20px;
  border-top: 1px solid #ddd;
}

.total-amount h2 {
  font-size: 20px;
}

.place-order-button {
  background-color: #4CAF50;
  color: white;
  border: none;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border-radius: 5px;
  cursor: pointer;
  position: absolute; /* 添加這一行 */
  bottom: 0; /* 添加這一行 */
  right: 0; /* 添加這一行 */
  margin-bottom: 20px; /* 添加這一行 */
}

.place-order-button:hover {
  background-color: #45a049;
}

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
    </style>
    <script>
        function updateCartItem(event, form) {
            event.preventDefault(); // 阻止表單的默認提交行為

            var itemID = form.querySelector('input[name="itemID"]').value;
            var newQuantity = form.querySelector('input[name="quantity"]').value;

            // 使用 AJAX 向 server 端發送請求
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    // 更新成功後,顯示提示消息
                    alert('商品數量已更新!');
                    location.reload(); // 重新載入頁面以更新購物車資訊
                }
            };
            xhr.send('itemID=' + encodeURIComponent(itemID) + '&quantity=' + encodeURIComponent(newQuantity));
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
        <h1>購物車</h1>

        <?php if (empty($cartItems)) : ?>
            <p class="no-items">您的購物車是空的。</p>
        <?php else : ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>商品名稱</th>
                        <th>價格</th>
                        <th>數量</th>
                        <th>圖片</th>
                        <th>說明</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item) : ?>
                        <tr>
                            <td><?php echo $item['productName']; ?></td>
                            <td>$<?php echo $item['price']; ?></td>
                            <td>
                                <form method="post" action="#" onsubmit="updateCartItem(event, this)">
                                    <input type="hidden" name="itemID" value="<?php echo $item['id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                    <button type="submit">更新</button>
                                </form>
                            </td>
                            <td><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['productName']; ?>"></td>
                            <td><?php echo $item['description']; ?></td>
                            <td>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                    <input type="hidden" name="remove_item" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="remove-button">刪除</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <div>
        <a href="checkout.php" class="place-order-button">結帳</a>
    </div>
</body>
</html>
