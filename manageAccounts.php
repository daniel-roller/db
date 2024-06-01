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

    $records_per_page = 10;

    $stmt = $db->prepare("SELECT COUNT(*) FROM `users`");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_NUM);
    $total_records = $row[0];

    $total_pages = ceil($total_records / $records_per_page);

    if (isset($_GET["page"]) && is_numeric($_GET["page"])) {
        $current_page = (int) $_GET["page"];
    } else {
        $current_page = 1;
    }

    $start_from = ($current_page - 1) * $records_per_page;

    $stmt = $db->prepare("SELECT id, role, name, username FROM `users` LIMIT :start_from, :records_per_page");
    $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
    $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();

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
    <title>管理帳戶</title>
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
        <button onclick="location.href='insertUser.php'" class="insertUser">新增使用者</button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>身分組</th>
                    <th>使用者姓名</th>
                    <th>使用者名稱</th>
                    <th>使用者管理</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                        if ($user['role'] === "admin"){
                            echo "<td></td>";
                        } else {
                            echo "<td><form action=\"manageAccounts.php\" method=\"post\" onsubmit=\"return confirmDelete('" . htmlspecialchars($user['username']) . "');\">";
                            echo "<input type=\"hidden\" name=\"deleteid\" value=\"" . htmlspecialchars($user['id']) . "\">";
                            echo "<button type=\"submit\" name=\"remove_user\" value=\"true\" class=\"delete-button\">刪除使用者</button>";
                            echo "</form></td>";
                        }
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['remove_user']) && $_POST['remove_user'] === 'true') {
        include "database_connection.php";
        $deleteUserid = $_POST['deleteid'];
        $stmt = $db->prepare("DELETE FROM `users` WHERE id = :deleteid"); //Remove user from users table
        $stmt->bindParam(':deleteid', $deleteUserid);
        $stmt->execute();
        echo "<script>alert('已刪除使用者相關所有資料'); window.location.href='manageAccounts.php';</script>";
        exit;
    }
    ?>

    <script>
        function confirmDelete(username) {
            return confirm("確定要刪除使用者 " + username + " 及其相關資料嗎?");
        }
    </script>
</body>
</html>
