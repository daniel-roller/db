<?php
session_start();

// 檢查使用者是否已登錄，否則重定向到登入頁面
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 登出處理
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
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
    <?php
        // 處理購買商品
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $user_id = $_SESSION['user_id'];
        

        $sql = "INSERT INTO orders (user_id, product_id, quantity) VALUES (, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
        }
        echo "購買成功!";
    ?>
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
            <div class="product">
                <div class="icon_1"><img src="images/bed.jpg"></div>
                <h2>超好躺的床</h2>
                <p>這是一個很棒的商品。</p>
                <div class="price">$100</div>
                <a href="cart.php" class="buy-button">購買</a>
                
            </div>
            <div class="product">
            <div class="icon_1"><img src="images/sofa.jpg"></div>
                <h2>頂級沙發</h2>
                <p>這是一個很棒的商品。</p>
                <div class="price">$200</div>
                <a href="cart.php" class="buy-button">購買</a>
            </div>
            <div class="product">
            <div class="icon_1"><img src="images/chair.jpg"></div>
                <h2>坐下去就起不來的椅子    </h2>
                <p>這是一個很棒的商品。</p>
                <div class="price">$300</div>
                <a href="cart.php" class="buy-button">購買</a>
            </div>
            <!-- 添加更多商品 -->
        </div>
    </div>
</body>
</html>
