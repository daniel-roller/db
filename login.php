<?php
   session_start();
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
      <title>login</title>
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
         include "database_connection.php";
         if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["username"];
            $password = $_POST["password"];

            // 查詢資料庫驗證使用者
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $username, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {

               // 驗證密碼
               if ($password == $result['passwd']) {
                  // 登入成功
                  $_SESSION['user_id'] = $result["ID"];
                  $_SESSION['username'] = $result["username"];
                  $_SESSION['role'] = $result["role"];
                  
               
                  // 跳轉至商店頁面
                  if($_SESSION['role'] == "admin"){
                     header('Location: manageAccounts.php');
                     exit();
                  } elseif($_SESSION['role'] == "user") {
                     header('Location: shop.php');
                     exit(); 
                  }
                  
               } else {
                  // 密碼錯誤
                  $error_message = "帳號或密碼錯誤";
               }
            } else {
               // 沒有找到使用者
               $error_message = "帳號或密碼錯誤";
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
                     <h1 class="contact_text">使用者登入</h1>
                     <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                     <?php endif; ?>
                     <div class="mail_sectin">
                        <form action="login.php" method="POST">
                              <input type="text" class="email-bt" placeholder="帳號" name="username" required>
                              <input type="password" class="email-bt" placeholder="密碼" name="password" required>
                              <div class="send_bt"><button type="submit">登入</button></div>
                        </form>
                        <div class="send_bt"><a href="register.php">註冊新帳號</a></div>
                        <div class="send_bt"><a href="forgetpasswd.php">忘記密碼</a></div>
                     </div>
                  </div>
            </div>
         </div>
      </div> 
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
      <--Javascript files-->
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