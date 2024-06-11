<?php
include "database_connection.php"; // 引入資料庫連接檔案

session_start();

// 檢查使用者是否已登錄，否則重定向到登入頁面
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['productName'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_POST['image'];
    $description = $_POST['description'];
    $username = $_SESSION['username'];

    $stmt = $db->prepare("INSERT INTO shop (productName, price, quantity, image, description, username) VALUES (:productName, :price, :quantity, :image, :description, :username)");
    $stmt->bindParam(":productName", $productName, PDO::PARAM_STR);
    $stmt->bindParam(":price", $price, PDO::PARAM_STR);
    $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
    $stmt->bindParam(":image", $image, PDO::PARAM_STR);
    $stmt->bindParam(":description", $description, PDO::PARAM_STR);
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "商品已成功上架!";
    } else {
        echo "新增商品失敗.";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增商品</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type=text], input[type=number], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #333;
            text-decoration: none;
        }

        a:hover {
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <h1>新增您的商品</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="productName">商品名稱:</label>
        <input type="text" name="productName" id="productName" required>
        <label for="price">價格:</label>
        <input type="number" name="price" id="price" step="0.01" required>
        <label for="quantity">數量:</label>
        <input type="number" name="quantity" id="quantity" min="1" required>
        <label for="image">商品圖片 (URL):</label>
        <input type="text" name="image" id="image" required>
        <label for="description">商品描述:</label>
        <textarea name="description" id="description" required></textarea>
        <button type="submit">新增商品</button>
    </form>
    <a href="shop.php">返回商店</a>
</body>
</html>
