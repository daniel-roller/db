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

// 查詢商品資料
$sql = "SELECT * FROM shop";
$result = $db->query($sql);

// 處理購買商品
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productID = $_POST['productID'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    // 從 shop 資料表中獲取商品詳細信息
    $stmt = $db->prepare("SELECT * FROM shop WHERE id = :productID");
    $stmt->bindParam(":productID", $productID, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $productName = $product['productName'];
        $price = $product['price'];
        $image = $product['image'];
        $description = $product['description'];

        // 將商品資訊寫入 cart 資料表
        $sql = "INSERT INTO cart (user_id, product_id, product_name, price, quantity, total) VALUES (:user_id, :product_id, :product_name, :price, :quantity, :total)";
        $stmt = $cart_db->prepare($sql);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":product_id", $productID);
        $stmt->bindParam(":product_name", $productName);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":total", $price * $quantity);
        $stmt->execute();

        echo "商品已成功加入購物車。";
    } else {
        echo "商品資訊不完整,無法加入購物車。";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商店</title>
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
            max-width: 100%;
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
        }
        .product .buy-button:hover {
            background: #0056b3;
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
                <a href="shop.php?logout=true">登出</a>
            </div>
        </div>
    </div>

    <div class="content">
        <h1>歡迎來到商店</h1>
        <div class="product-list">
            <?php
            // 顯示商品列表
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                // 確保資料庫中存在需要的欄位
                if (isset($row['id']) && isset($row['productName']) && isset($row['price']) && isset($row['quantity']) && isset($row['image'])) {
                    ?>
                    <div class="product">
                        <div class="icon_1"><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image"></div>
                        <h2><?php echo htmlspecialchars($row['productName']); ?></h2>
                        <p>剩餘數量: <?php echo htmlspecialchars($row['quantity']); ?></p>
                        <div class="price">$<?php echo htmlspecialchars($row['price']); ?></div>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <input type="hidden" name="productID" value="<?php echo $row['id']; ?>">
                            <input type="number" name="quantity" value="1" min="1">
                            <button type="submit" class="buy-button">加入購物車</button>
                        </form>
                    </div>
                    <?php
                } else {
                    echo "<p>商品資料不完整</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
