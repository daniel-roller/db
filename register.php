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
      <title>Register</title>
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

      <?php
         if ($_SERVER['REQUEST_METHOD'] === "POST") {
            include "database_connection.php";
        
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $tel = $_POST['tel'] ?? '';
            $username = $_POST['username'] ?? '';
            $passwd = $_POST['passwd'] ?? '';
            $passwd1 = $_POST['passwd1'] ?? '';
            $role = "user";
            
            //錯誤資料
            $errors = '';
        
            // 輸入限制
            
            if (empty($name)) {
               $errors .= "使用者姓名不得為空\\n";
            } else if (strlen($name) < 2 || strlen($name) > 20) {
               $errors .= "使用者姓名的長度必須至少>=4 && <=20\\n";
            }
            
            if (empty($email)) {
               $errors .= "電子郵箱不得為空\\n";
            } else if (strlen($email) < 4 || strlen($email) > 50) {
                  $errors .= "電子郵箱的長度必須至少>=4 && <=50\\n";
            } else if (strpos($email, '@') === false) {
                  $errors .= "電子郵箱格式不正確，請包含 '@' 符號\\n";
            }
           
            if (empty($tel)) {
               $errors .= "手機號碼不得為空\\n";
            } else if (strlen($tel) != 10) {
               $errors .= "手機號碼的長度必須等於10\\n";
            }

            if (empty($username)) {
               $errors .= "使用者名稱不得為空\\n";
            } else if (strlen($username) < 4 || strlen($username) > 20) {
               $errors .= "使用者ID的長度必須至少4個字元且少於20個字元\\n";
            }
            
            if (empty($passwd)) {
               $errors .= "你的密碼不得為空\\n";
            } else if (strlen($passwd) < 4 || strlen($passwd) > 30) {
               $errors .= "密碼的長度必須 >=4 && <=30\\n";
            }
            
            if (empty($passwd1)) {
               $errors .= "再次輸入密碼不得為空\\n";
            } else if ($passwd != $passwd1) {
               $errors .= "你的密碼與再次確認密碼不同\\n";
            } else if (strlen($passwd1) < 4 || strlen($passwd1) > 30) {
               $errors .= "密碼的長度必須 >=4 && <=30 \n";
            }
            // 再次確認密碼
            if ($passwd != $passwd1) {
               $errors .= "你的密碼與再次確認密碼不同\\n";
            }

            // 检查用户名是否已经存在
            $checkUser = $db->prepare("SELECT COUNT(*) FROM users WHERE name = :name");
            $checkUser->bindParam(':name', $name);
            $checkUser->execute();
            if ($checkUser->fetchColumn() > 0) {
               $errors .= "您的姓名已經被註冊\\n";
            }

            // 检查邮箱是否已经存在
            $checkEmail = $db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $checkEmail->bindParam(':email', $email);
            $checkEmail->execute();
            if ($checkEmail->fetchColumn() > 0) {
               $errors .= "電子郵箱已經被註冊\\n";
            }

            // 检查电话号码是否已经存在
            $checkTel = $db->prepare("SELECT COUNT(*) FROM users WHERE tel = :tel");
            //用來查詢資料庫中是否已經存在與填入的手機號碼相同的記錄。:tel 是一個佔位符，稍後會被實際的手機號碼替代。
            $checkTel->bindParam(':tel', $tel);
            $checkTel->execute();
            if ($checkTel->fetchColumn() > 0) {
               $errors .= "手機號碼已經被註冊\\n";
            }

            $checkTel = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
            $checkTel->bindParam(':username', $username);
            $checkTel->execute();
            if ($checkTel->fetchColumn() > 0) {
               $errors .= "使用者名稱已經被註冊\\n";
            }

            /*
            
            1. $checkTel = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
            這行程式碼創建了一個 SQL 查詢準備語句，用來從名為 users 的資料表中選擇所有符合條件的記錄數量。
            :username 是一個佔位符，稍後我們會用實際的使用者名稱值來替換它。

            2. $checkTel->bindParam(':username', $username);
            這行程式碼將 :username 佔位符綁定到實際的使用者名稱變數 $username 上，這樣可以避免 SQL 注入攻擊。

            3. $checkTel->execute();
            這行程式碼執行了 SQL 查詢。

            4. if ($checkTel->fetchColumn() > 0) {
            這行程式碼檢查 SQL 查詢返回的結果是否有符合條件的記錄。fetchColumn() 方法用於檢索查詢結果集中的第一個欄位值，即符合條件的記錄數量。
            如果查詢返回的記錄數量大於 0，表示資料庫中已經存在使用者名稱為 $username 的記錄。

            5. $errors .= "使用者名稱已經被註冊\\n";
            如果已經存在符合條件的記錄，則將錯誤訊息附加到 $errors 變數中，表示使用者名稱已經被註冊過了。
            */
        
            if (empty($errors)) {
        
                $sql = "INSERT INTO `users`(`role`, `name`, `email`, `tel`, `username`, `passwd`) VALUES (:role, :name, :email, :tel, :username, :passwd)";
                $description = $db->prepare($sql);
                $description->bindParam(':role', $role);
                $description->bindParam(':name', $name);
                $description->bindParam(':email', $email);
                $description->bindParam(':tel', $tel);
                $description->bindParam(':username', $username);
                $description->bindParam(':passwd', $passwd);
                $description->execute();
        
                echo "<script>alert('success');</script>";
            } else {
                echo "<script>alert('$errors');</script>";
            }
        }
      ?>
      
   </head>
   <body>
      <!-- header section start -->

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
      <!-- header section end -->
      <!-- contact section start -->
      <div class="contact_section layout_padding">
         <div class="container">
            <div class="row">
               <div class="col-md-6">
                  <h1 class="contact_text">註冊新帳號</h1>
                  <div class="mail_sectin">
                     <form method = "POST" action = "register.php">  
                     <input type="text" class="email-bt" placeholder="你的姓名" name="name">
                     <input type="text" class="email-bt" placeholder="你的email" name="email">
                     <input type="text" class="email-bt" placeholder="你的手機" name="tel">
                     <!-- <input type="text" class="email-bt" placeholder="你的生日(西元)" name="bd"> -->
                     <input type="text" class="email-bt" placeholder="使用者名稱" name="username">
                     <input type="text" class="email-bt" placeholder="請輸入密碼" name="passwd">
                     <input type="text" class="email-bt" placeholder="再次確認密碼" name="passwd1">
                     <div class="send_bt"><button>註冊新帳號</button></div> 
                     </form>
                  </div>
               </div>
               <!-- <div class="col-md-6">
                  <div class="image_9"><img src="images/img-9.png"></div> -->
            </div>
         </div>
      </div>
      <!-- contact section end -->
      <div class="copyright_section">
         <div class="container">
            <div class="social_icon">
               <ul>
                  <li><a href="#"><img src="images/fb-icon.png"></a></li>
                  <li><a href="#"><img src="images/twitter-icon.png"></a></li>
                  <li><a href="#"><img src="images/instagram-icon.png"></a></li>
                  <li><a href="#"><img src="images/linkedin-icon.png"></a></li>
               </ul>
            </div>
            <p class="copyright_text">2020 All Rights Reserved. Design by Free html Templates</p>
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