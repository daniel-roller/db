<?php
// 連接資料庫
include "database_connection.php";

// 處理重設密碼表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['account'], $_POST['email'], $_POST['tel'], $_POST['newPassword'], $_POST['confirmPassword'])) {
    $account = $_POST['account'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // 驗證輸入資料是否合法
    if (empty($account) || empty($email) || empty($tel) || empty($newPassword) || empty($confirmPassword)) {
        echo "請填寫所有欄位.";
        exit;
    }

    if ($newPassword !== $confirmPassword) {
        echo "新密碼與確認密碼不一致.";
        exit;
    }

    // 查詢用戶是否存在
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username AND email = :email AND tel = :tel");
    $stmt->bindParam(':username', $account, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':tel', $tel, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // 更新密碼
        $newHashedPassword = $newPassword; // 這裡不進行密碼加密
        $stmt = $db->prepare("UPDATE users SET passwd = :passwd WHERE id = :id");
        $stmt->bindParam(':passwd', $newHashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "密碼已成功重設!";
    
            // 再次查詢用戶資料,確認密碼是否已更新
            $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
            $stmt->execute();
            $updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($updatedUser && $updatedUser['passwd'] === $newPassword) {
                echo "密碼更新成功!";
                // 重定向到登入頁面
                header("Location: login.php");
                exit;
            } else {
                echo "密碼更新失敗.";
            }
        } else {
            echo "重設密碼失敗.";
        }
    } else {
        echo "找不到與該帳號、電子郵件地址和電話號碼相關的帳戶.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title>Forgetpasswd</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- bootstrap css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!-- Responsive-->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- fevicon -->
    <link rel="icon" href="images/fevicon.png" type="image/gif" />
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
    <!-- Tweaks for older IEs-->
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <!-- owl stylesheets -->
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
</head>
<body>
   
<div class="header_section">
         <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
               <div class="logo"><a href="index.php"><img src="images/logo.png"></a></div>
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav mr-auto">
                     <li class="nav-item">
                        <a class="nav-link" href="index.php">首頁</a>
                     </li>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     <li class="nav-item">
                        <a class="nav-link" href="services.php">服務</a>
                     </li>
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     <li class="nav-item">
                        <a class="nav-link" href="about.php">關於</a>
                     </li>
                     <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     <li class="nav-item">
                        <a class="nav-link" href="shop.php">商店</a>
                     </li> -->
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                  </ul>
                  <form class="form-inline my-2 my-lg-0">
                     <h1 class="call_text">Call Us : +01 23456789</h1>
                  </form>
                  <div class="search_icon">
                     <ul>
                        <li><a href="login.php">登入</a></li>
                     </ul>
                  </div>
               </div>
            </nav>
         </div>
      </div>
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

    <!-- Javascript files-->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.0.0.min.js"></script>
    <script src="js/plugin.js"></script>
    <!-- sidebar -->
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/custom.js"></script>
    <!-- javascript -->
    <script src="js/owl.carousel.js"></script>
    <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
</body>
</html>
