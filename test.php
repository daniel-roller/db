<?php
// 連接資料庫
include "database_connection.php";

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account = $_POST['account'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // 查詢用戶是否存在
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username AND email = :email AND tel = :tel");
    $stmt->bindParam(':username', $account, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':tel', $tel, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // 驗證新密碼與確認密碼是否一致
        if ($newPassword === $confirmPassword) {
            // 更新密碼
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET passwd = :passwd WHERE id = :userId");
            $stmt->bindParam(':passwd', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $user['id'], PDO::PARAM_INT);
            if ($stmt->execute()) {
                echo "密碼已成功重設!";
            } else {
                echo "重設密碼失敗.";
            }
        } else {
            echo "新密碼與確認密碼不一致.";
        }
    } else {
        echo "找不到與該帳號、電子郵件地址和電話號碼相關的帳戶.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- 您之前提供的 HTML 頭部 -->
</head>
<body>
    <div class="contact_section layout_padding">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="contact_text">重設密碼</h1>
                    <div class="mail_sectin">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <input type="text" class="email-bt" placeholder="帳號" name="account" required>
                            <input type="email" class="email-bt" placeholder="電子郵件" name="email" required>
                            <input type="tel" class="email-bt" placeholder="電話號碼" name="tel" required>
                            <input type="password" class="email-bt" placeholder="新密碼" name="newPassword" required>
                            <input type="password" class="email-bt" placeholder="確認新密碼" name="confirmPassword" required>
                            <div class="send_bt"><button type="submit">重設密碼</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
