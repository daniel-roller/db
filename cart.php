<?php
session_start();

// 檢查使用者是否已登錄，否則重定向到登入頁面
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 處理添加商品到購物車
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_quantity = $_POST['product_quantity'];

    // 檢查購物車中是否已經存在該商品
    $cart_item_index = -1;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['id'] == $product_id) {
                $cart_item_index = $index;
                break;
            }
        }
    }

    if ($cart_item_index >= 0) {
        // 更新購物車中該商品的數量
        $_SESSION['cart'][$cart_item_index]['quantity'] += $product_quantity;
    } else {
        // 添加新的商品到購物車
        $cart_item = array(
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $product_quantity
        );
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        $_SESSION['cart'][] = $cart_item;
    }

    echo "商品已成功添加到購物車。";
}

// 顯示購物車中的商品
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
?>

<!DOCTYPE html>
<html>
<head>
    <title>購物車</title>
    <!-- 添加您的CSS和JS文件 -->
</head>
<body>
    <h1>購物車</h1>
    <table>
        <thead>
            <tr>
                <th>商品名稱</th>
                <th>價格</th>
                <th>數量</th>
                <th>總價</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_price = 0;
            foreach ($cart_items as $item) {
                $item_total = $item['price'] * $item['quantity'];
                $total_price += $item_total;
                echo "<tr>";
                echo "<td>{$item['name']}</td>";
                echo "<td>$" . number_format($item['price'], 2) . "</td>";
                echo "<td>{$item['quantity']}</td>";
                echo "<td>$" . number_format($item_total, 2) . "</td>";
                echo "<td><a href='#'>移除</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <p>總價: $<?php echo number_format($total_price, 2); ?></p>
    <a href="checkout.php">結帳</a>
</body>
</html>
