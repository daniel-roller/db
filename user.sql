-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost:3306
-- 產生時間： 2024 年 06 月 11 日 14:02
-- 伺服器版本： 10.6.16-MariaDB-0ubuntu0.22.04.1
-- PHP 版本： 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫: `database`
--

-- --------------------------------------------------------

--
-- 資料表結構 `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `productName` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `purchase`
--

CREATE TABLE `purchase` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `purchase`
--

INSERT INTO `purchase` (`id`, `username`, `productName`, `price`, `quantity`, `total`) VALUES
(1, 'test', '電視櫃', '3499.99', 2, '6999.98'),
(2, 'test', '茶几', '1999.99', 1, '1999.99'),
(3, 'test', '電視櫃', '3499.99', 1, '3499.99'),
(4, 'test', '茶几', '1999.99', 5, '9999.95'),
(5, 'test', '電視櫃', '3499.99', 1, '3499.99');

-- --------------------------------------------------------

--
-- 資料表結構 `shop`
--

CREATE TABLE `shop` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `productName` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `shop`
--

INSERT INTO `shop` (`id`, `username`, `productName`, `price`, `quantity`, `image`, `description`) VALUES
(1, '', '沙發', '50000.00', 100, 'https://www.bludot.com/media/catalog/product/g/u/gu1_82sfwo_oa_frontlow_default.jpg?optimize=medium&fit=bounds&height=1200&width=1500&canvas=1500:1200', '這是沙發'),
(2, '', '椅子', '2000.00', 100, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQChCaHtKgmsazcQsnXUkkJ0S6D1k3onegdTA&s', '這是椅子'),
(4, '', '電視櫃', '3499.99', 1, 'https://lurl.cc/KrMbIU', '適用於客廳的電視櫃'),
(5, '', '茶几', '1999.99', 1, 'https://lurl.cc/qPyML0', '時尚簡約的茶几'),
(6, '', '書桌', '4999.99', 1, 'https://lurl.cc/qIygaT', '適用於辦公或學習的書桌'),
(7, '', '書架', '2499.99', 1, 'https://lurl.cc/k0GYJJ', '可放置書籍及裝飾品的書架'),
(8, '', '辦公椅', '1299.99', 1, 'https://lurl.cc/kUc3xM', '舒適的辦公室椅子'),
(9, '', '衣櫃', '6999.99', 1, 'https://lurl.cc/Lx6vcD', '可收納衣物的大型衣櫃'),
(10, '', '床頭櫃', '1799.99', 2, 'https://lurl.cc/I3nGmc', '放置床頭燈和小物品的櫃子'),
(11, '', '床墊', '8999.99', 1, 'https://lurl.cc/l8Odje', '優質舒適的床墊'),
(12, '', '餐桌', '5999.99', 1, 'https://www.vaxee.co/zh-hant/data/goods/gallery/202405/1715937603688946788.jpg', '適用於家庭或餐廳的餐桌'),
(13, '', '餐椅', '1299.99', 4, 'https://www.bludot.com/media/catalog/product/g/u/g.', '與餐桌配套的餐椅'),
(14, '', '電腦桌', '3999.99', 1, 'https://www.bludot.com/media/catalog/product/g/u/g.', '寬敞實用的電腦桌'),
(15, '', '打印機櫃', '1499.99', 1, 'https://www.bludot.com/media/catalog/product/g/u/g.', '放置打印機的小型儲物櫃'),
(16, '', '鞋櫃', '2499.99', 1, 'https://www.bludot.com/media/catalog/product/g/u/g.', '可收納鞋子的鞋櫃'),
(17, '', '花架', '799.99', 1, 'https://www.bludot.com/media/catalog/product/g/u/g.', '放置花草植物的裝飾架'),
(18, '', '置物架', '1299.99', 2, 'https://www.bludot.com/media/catalog/product/g/u/g.', '多層置物架,可存放雜物'),
(19, '', '衣帽架', '899.99', 1, 'https://www.bludot.com/media/catalog/product/g/u/g.', '掛衣服和掛包的衣帽架'),
(20, '', '雨傘架', '349.99', 1, 'https://www.bludot.com/media/catalog/product/g/u/g.', '放置雨傘的小型收納架'),
(21, '', '盆栽架', '599.99', 1, 'https://www.bludot.com/media/catalog/product/g/u/g.', '放置盆栽植物的架子'),
(22, '', '門口鞋架', '899.99', 1, 'https://www.bludot.com/media/catalog/product/g/u/g.', '置放鞋子的門口收納架'),
(23, '', '書報架', '1199.99', 1, 'https://www.bludot.com/media/catalog/product/g/u/g.', '放置書籍和雜誌的架子'),
(24, '', '衣物收納箱', '499.99', 2, 'https://www.bludot.com/media/catalog/product/g/u/g.', '可收納衣物的箱子'),
(25, '', '電視背景櫃', '3999.99', 1, 'https://www.bludot.com/media/catalog/product/g/u/g.', '可放置裝飾品的電視背景櫃'),
(26, '', '床頭櫃組', '2999.99', 1, 'https://www.bludot.com/media/catalog/product/g/u/g.', '成套的兩個床頭櫃'),
(27, '', '鐵藝置物架', '899.99', 1, 'https://www.bludot.com/media/catalog/product/g/u/g.', '具有工業風格的多層置物架'),
(28, '', '植物架', '1499.99', 1, 'https://www.bludot.com/media/catalog/product/g/u/g.', '放置盆栽植物的裝飾架'),
(29, '', '衣架收納架', '699.99', 1, 'https://example.com/images/closet-organizer.jpg', '可收納掛衣架的小型櫃子'),
(30, '', '斗櫃', '1999.99', 1, 'https://example.com/images/dresser.jpg', '具有抽屜的儲物柜子'),
(35, '', '辦公椅組', '3999.99', 1, 'https://example.com/images/office-chair-set.jpg', '成套的高級辦公椅'),
(36, '', '開放式書架', '2999.99', 1, 'https://example.com/images/open-bookshelf.jpg', '多層開放式的裝飾書架'),
(37, '', '落地燈', '899.99', 1, 'https://example.com/images/floor-lamp.jpg', '客廳或臥室常見的落地燈'),
(38, '', '桌燈', '499.99', 2, 'https://example.com/images/desk-lamp.jpg', '適用於桌面的裝飾台燈'),
(39, '', '床頭櫃組', '2999.99', 1, 'https://example.com/images/nightstand-set.jpg', '成套的兩個床頭櫃'),
(40, '', '床頭櫃組', '2999.99', 1, 'https://example.com/images/nightstand-set.jpg', '成套的兩個床頭櫃'),
(45, '', '鞋櫃', '2499.99', 1, 'https://example.com/images/shoe-cabinet.jpg', '可收納鞋子的鞋櫃'),
(47, 'test', 'aaa', '1.00', 1, 'https://www.bludot.com/media/catalog/product/g/u/g.', 'saa'),
(48, 'test', 'bbb', '1000.00', 100, 'https://www.bludot.com/media/catalog/product/g/u/g.', 'bbb');

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `role`, `name`, `email`, `tel`, `username`, `passwd`, `created_at`) VALUES
(1, 'admin', 'admin', 'admin', '0123456789', 'admin', 'admin', '2024-05-31 05:36:43'),
(10, 'user', 'test', 'test@test.com', '1234567890', 'test', 'test', '2024-05-31 07:45:39'),
(11, 'user', 'test1', 'test1@test1.com', 'test1', 'test1', 'test1', '2024-05-31 07:45:47'),
(18, 'user', 'test2', 'test2@test2.com', '234567891', 'test2', 'test2', '2024-06-11 05:47:02');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `shop`
--
ALTER TABLE `shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
