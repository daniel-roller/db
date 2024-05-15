<?php
    // 開始 session
    session_start();

    // 檢查使用者是否已登入
    if (isset($_SESSION['user_id'])) {
        // 使用者已登入

        // 銷毀 session
        session_destroy();

        // 清除 cookie
        setcookie("user_id", "", time() - 3600, "/");
        setcookie("username", "", time() - 3600, "/");

        // 顯示成功登出的訊息
        echo "您已成功登出。";

        // 導向至登入頁面
        header("Location: login.php");
        exit();
    } else {
        // 使用者未登入
        // 導向至登入頁面
        header("Location: login.php");
        exit();
}
