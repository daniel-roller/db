<?php
session_start();

$host = "localhost:3306";
$dbusername = "root";
$dbpassword = "594212Daniel";
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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $db->prepare("INSERT INTO users (username, name, password, role) VALUES (:username, :name, :password, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
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
</head>
<body>
    <div class="content">
        <h1>新增使用者</h1>
        <form action="insertUser.php" method="post">
            <label for="username">使用者名稱:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="name">姓名:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="password">密碼:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <label for="role">角色:</label>
            <select id="role" name="role" required>
                <option value="user">使用者</option>
                <option value="admin">管理員</option>
            </select>
            <br>
            <button type="submit">新增使用者</button>
        </form>
    </div>
</body>
</html>
