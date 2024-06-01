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
    // 從購物車中刪除該使用者的所有商品
    $stmt = $db->prepare("DELETE FROM cart WHERE username = :username");
    $stmt->bindParam(":username", $_SESSION['username'], PDO::PARAM_STR);
    $stmt->execute();

    session_destroy();
    header("Location: login.php");
    exit();
}

// 每頁顯示的商品數量
$itemsPerPage = 10;

// 獲取當前頁碼
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

// 計算總頁數
$totalItems = $db->query("SELECT COUNT(*) FROM shop")->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

// 計算當前頁面的起始偏移量
$offset = ($currentPage - 1) * $itemsPerPage;

// 根據當前頁碼查詢商品
$stmt = $db->prepare("SELECT * FROM shop LIMIT :offset, :itemsPerPage");
$stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
$stmt->bindParam(":itemsPerPage", $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    }

    // 重定向到購物車頁面
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商店</title>
    <style>

.pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            color: #007bff;
            text-decoration: none;
            padding: 8px 16px;
            margin: 0 4px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
        }
        .pagination a:hover:not(.active) {
            background-color: #f4f4f4;
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
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            color: #007bff;
            text-decoration: none;
            padding: 8px 16px;
            margin: 0 4px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
        }
        .pagination a:hover:not(.active) {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="username">
                <?php echo "歡迎, " . htmlspecialchars($_SESSION['username']); ?>
            </div>
            <div class="logout">
                <a href="cart.php">前往購物車</a>
            </div>
            <div class="logout">
                <a href="shop.php?logout=true">登出</a>
            </div>
        </div>
    </div>

    <div class="content">
        <h1>歡迎來到商店</h1>
        <div class="product-list">
            <?php
            // 顯示商品列表
            foreach ($result as $row) {
                ?>
                <div class="product">
                    <div class="icon_1"><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image"></div>
                    <h2><?php echo htmlspecialchars($row['productName']); ?></h2>
                    <p>剩餘數量: <?php echo htmlspecialchars($row['quantity']); ?></p>
                    <div class="price">$<?php echo htmlspecialchars($row['price']); ?></div>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="addToCart(event, this)">
                        <input type="hidden" name="productID" value="<?php echo $row['id']; ?>">
                        <input type="number" name="quantity" value="1" min="1">
                        <button type="submit" class="buy-button">加入購物車</button>
                    </form>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="pagination">
            <?php
            // 顯示分頁按鈕
            for ($i = 1; $i <= $totalPages; $i++) {
                $activeClass = ($currentPage == $i) ? 'active' : '';
                echo "<a href='?page=$i' class='$activeClass'>$i</a>";
            }
            ?>
        </div>
    </div>
    <script>
        function addToCart(event, form) {
            event.preventDefault(); // 阻止表單的默認提交行為

            // 獲取表單數據
            var productID = form.querySelector('input[name="productID"]').value;
            var quantity = form.querySelector('input[name="quantity"]').value;

            // 使用 AJAX 向 server 端發送請求
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'add_to_cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    // 根據伺服器端的響應顯示不同的提示消息
                    if (xhr.responseText === "商品數量已更新") {
                        alert('商品數量已更新!');
                    } else {
                        alert('商品已加入購物車!');
                    }
                }
            };
            xhr.send('productID=' + encodeURIComponent(productID) + '&quantity=' + encodeURIComponent(quantity));
        }
    </script>


</body>
</html>
