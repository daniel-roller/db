<?php
session_start();

$host = "localhost:3306";
$dbusername = "root";
$dbpassword = "root";
$dbname = "database";

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "資料庫連接失敗: " . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $passwd = password_hash($_POST['passwd'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];

    // 檢查電話號碼是否為十碼
    if (!preg_match('/^\d{10}$/', $tel)) {
        echo "<script>alert('電話號碼必須為十碼'); window.history.back();</script>";
        exit;
    }

    // 檢查是否有重複的使用者名稱
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_NUM);
    if ($row[0] > 0) {
        echo "<script>alert('使用者名稱已存在'); window.history.back();</script>";
        exit;
    }

    // 插入新使用者資料
    $stmt = $db->prepare("INSERT INTO users (username, name, passwd, role, email, tel) VALUES (:username, :name, :passwd, :role, :email, :tel)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':passwd', $passwd);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':tel', $tel);
    $stmt->execute();

    echo "<script>alert('使用者已新增'); window.location.href='manageAccounts.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增使用者</title>
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
        .content button.insertUser {
            display: inline-block;
            padding: 10px 20px;
            margin-bottom: 20px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            transition: background 0.3s ease;
            cursor: pointer;
        }
        .content button.insertUser:hover {
            background: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #f5f5f5;
        }
        .delete-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            text-decoration: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .delete-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="username">
                <?php 
                    echo "歡迎 " . htmlspecialchars($_SESSION['username']); 
                    echo " 您是管理員";
                ?>
            </div>
            <div class="logout">
                <a href="shop.php?logout=true">登出</a>
            </div>  
        </div>
    </div>

    <div class="content">
        <div class="form-container">
            <h1>新增使用者</h1>
            <form action="insertUser.php" method="post">
                <label for="username">使用者名稱:</label>
                <input type="text" id="username" name="username" required>

                <label for="name">姓名:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">電子郵件:</label>
                <input type="email" id="email" name="email" required>

                <label for="tel">電話號碼:</label>
                <input type="tel" id="tel" name="tel" required>

                <label for="passwd">密碼:</label>
                <input type="passwd" id="passwd" name="passwd" required>

                <label for="role">角色:</label>
                <select id="role" name="role" required>
                    <option value="user">使用者</option>
                    <option value="admin">管理員</option>
                </select>

                <button type="submit">新增使用者</button>
            </form>
        </div>
    </div>
</body>
</html>
