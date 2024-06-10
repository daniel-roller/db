<?php
session_start();

include "database_connection.php";

// 檢查使用者是否已登錄，否則重定向到登入頁面
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 獲取要修改密碼的使用者 ID
$userId = isset($_GET['userid']) ? $_GET['userid'] : null;

// 處理密碼修改表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // 驗證新密碼與確認密碼是否一致
    if ($newPassword === $confirmPassword) {
        // 更新密碼
        $stmt = $db->prepare("UPDATE users SET passwd = :passwd WHERE id = :userId");
        $stmt->bindParam(':passwd', $newPassword, PDO::PARAM_STR);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "密碼已成功更新!";
            header("Location: manageAccounts.php");
            exit;
        } else {
            echo "更新密碼失敗.";
        }
    } else {
        echo "新密碼與確認密碼不一致.";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改密碼</title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 30px;
    background-color: white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 10px;
    font-weight: bold;
}

input[type="password"] {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 20px;
}

button[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

a {
    display: block;
    text-align: center;
    color: #007bff;
    text-decoration: none;
    margin-top: 20px;
}

a:hover {
    color: #0056b3;
}
    </style>
</head>
<body>
    <div class="container">
        <h1>修改密碼</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?userid=" . $userId;?>">
            <label for="newPassword">新密碼:</label>
            <input type="password" name="newPassword" id="newPassword" required>
            <label for="confirmPassword">確認新密碼:</label>
            <input type="password" name="confirmPassword" id="confirmPassword" required>
            <button type="submit">修改密碼</button>
        </form>
        <a href="manageAccounts.php">返回帳戶管理</a>
    </div>
</body>
</html>