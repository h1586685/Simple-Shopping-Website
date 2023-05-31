-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2022-01-10 15:18:25
-- 伺服器版本： 10.1.38-MariaDB
-- PHP 版本： 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `final_term`
--

DELIMITER $$
--
-- 程序
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `cart` (IN `good_id` VARCHAR(8))  NO SQL
BEGIN

SELECT G.No,G.Name,G.Price,G.Color,GD.image_path,G.Quantity FROM goods AS G 
LEFT JOIN good_detail AS GD ON (G.No = GD.good_index)
WHERE G.No = good_id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CHECK_QUAN` (IN `good_id` VARCHAR(8), IN `cart_quan` VARCHAR(5))  NO SQL
BEGIN
DECLARE quan INT;
SELECT G.Quantity FROM goods AS G WHERE G.No = good_id INTO quan;

IF (quan - cart_quan <0)THEN
SELECT FALSE,G.Quantity FROM goods AS G WHERE G.No = good_id;

ELSE
SELECT TRUE;

END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `demo` ()  NO SQL
BEGIN

SELECT G.No, G.Name,G.Price,G.Quantity,G.Color,GD.goods_describe,GD.image_path FROM goods AS G 
LEFT JOIN good_detail AS GD ON (G.No = GD.good_index)
WHERE G.Quantity>0
ORDER BY G.No DESC
LIMIT 4;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `login` (IN `account` VARCHAR(30), IN `password` VARCHAR(30))  NO SQL
BEGIN
SELECT TRUE FROM members AS MEM WHERE account = MEM.Account && MEM.Password = password;

SELECT FALSE FROM members AS MEM WHERE account != MEM.Account || MEM.Password != password;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_order` (IN `_acconut` VARCHAR(8), IN `_items_list` VARCHAR(255), IN `_quantity` VARCHAR(255), IN `_total_amount` INT(40), IN `_address` VARCHAR(999), IN `_phone` VARCHAR(10), IN `_Name` VARCHAR(20), IN `_price` VARCHAR(255))  NO SQL
BEGIN
INSERT INTO `items_order`(`acconut`, `items_list`, `quantity`, `item_price`, `total_amount`, `Name`, `Shipping_address`, `phone`) VALUES (_acconut,_items_list,_quantity,_price,_total_amount,_Name,_address,_phone);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `order_detail` (IN `account` VARCHAR(30))  NO SQL
BEGIN
SELECT MEM.Address,MEM.Phone ,MEM.Name FROM members AS MEM WHERE MEM.Account = account;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `order_showing` (IN `_account` VARCHAR(30))  NO SQL
BEGIN
SELECT IO.No,IO.time,IO.items_list,IO.quantity,IO.item_price,IO.total_amount,IO.Name,IO.Shipping_address,IO.phone FROM items_order AS IO WHERE IO.acconut = _account;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `register` (IN `account_` VARCHAR(30), IN `password_` VARCHAR(30), IN `name_` VARCHAR(15), IN `email_` VARCHAR(40), IN `birth_` DATE, IN `phone_` VARCHAR(10), IN `address_` VARCHAR(50))  NO SQL
BEGIN
INSERT INTO `members`(`No`, `Account`, `Password`, `Name`, `Email`, `Phone`, `Birth`, `Address`) VALUES ("",account_,password_,name_,email_,phone_,birth_,address_);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `register_check` (IN `account` VARCHAR(30), IN `email` VARCHAR(40), IN `phone` VARCHAR(10))  NO SQL
BEGIN
SELECT FALSE FROM members AS MEM WHERE account = MEM.Account OR email = MEM.Email OR phone = MEM.Phone;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `search_goods` (IN `KEYWORD` VARCHAR(50))  NO SQL
BEGIN

SELECT G.No, G.Name,G.Price,G.Quantity,G.Color,GD.goods_describe,GD.image_path FROM goods AS G 
LEFT JOIN good_detail AS GD ON (G.No = GD.good_index)
WHERE (G.Name LIKE CONCAT('%',KEYWORD,'%') OR G.Color LIKE CONCAT('%',KEYWORD,'%') OR GD.goods_describe LIKE CONCAT('%',KEYWORD,'%'))&& G.Quantity>0;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `short_item_detail` (IN `good_id` VARCHAR(8))  NO SQL
BEGIN
SELECT goods.Name,goods.Color,good_detail.image_path FROM good_detail LEFT JOIN goods ON (good_detail.good_index = goods.No) WHERE good_detail.good_index = good_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sub_quan` (IN `good_id` VARCHAR(8), IN `quantity` VARCHAR(10))  NO SQL
BEGIN
DECLARE new_quan INT(10);
SELECT goods.Quantity - quantity FROM goods WHERE goods.No = good_id INTO new_quan;

UPDATE `goods`SET goods.Quantity = new_quan WHERE goods.No = good_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- 資料表結構 `goods`
--

CREATE TABLE `goods` (
  `No` int(8) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Price` int(10) NOT NULL,
  `Quantity` int(10) NOT NULL,
  `Color` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `goods`
--

INSERT INTO `goods` (`No`, `Name`, `Price`, `Quantity`, `Color`) VALUES
(1, 'ROG PHONE 5', 35900, 97, '黑'),
(2, 'ROG PHONE 5', 35900, 300, '白'),
(3, 'Apple iPhone 13 Pro (256G)', 36400, 82, '石墨色'),
(4, 'Apple iPhone SE (256G)', 17288, 18, '黑'),
(5, '羅技 G PRO Wireless', 4990, 152, '黑'),
(6, 'ASUS G733QSA-0041A5900H', 82900, 59, '黑');

-- --------------------------------------------------------

--
-- 資料表結構 `good_detail`
--

CREATE TABLE `good_detail` (
  `good_index` int(8) NOT NULL,
  `image_path` varchar(200) NOT NULL,
  `goods_describe` varchar(999) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `good_detail`
--

INSERT INTO `good_detail` (`good_index`, `image_path`, `goods_describe`) VALUES
(1, '../final_term/product_img/ROG_PHONE_5_BLACK.jpg', 'Cool game phone,nice knife'),
(2, '../final_term/product_img/ROG_PHONE_5_WHITE.jpg', 'Cool phone ,nice knife'),
(3, '../final_term/product_img/Apple_iPhone_13 Pro_(256G)_Graphite.jpg', 'apple'),
(4, '../final_term/product_img/Apple_iPhone_SE_ (256G)_black.jpg', 'apple'),
(5, '../final_term/product_img/G PRO Wireless.png', '適用於電子競技職業選手的羅技G 系列Pro 無線遊戲滑鼠'),
(6, '../final_term/product_img/ASUS G733QSA-0041A5900H.jpg', '【ASUS ROG Strix SCAR】');

-- --------------------------------------------------------

--
-- 資料表結構 `items_order`
--

CREATE TABLE `items_order` (
  `No` int(6) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `acconut` varchar(30) NOT NULL,
  `items_list` varchar(999) NOT NULL,
  `quantity` varchar(999) NOT NULL,
  `item_price` varchar(999) NOT NULL,
  `total_amount` int(40) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Shipping_address` varchar(999) NOT NULL,
  `phone` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `items_order`
--

INSERT INTO `items_order` (`No`, `time`, `acconut`, `items_list`, `quantity`, `item_price`, `total_amount`, `Name`, `Shipping_address`, `phone`) VALUES
(106, '2022-01-09 17:48:11', '4A8G0048', '5', '12', '4990', 59880, '王政權', '台南市東區', '0903312345'),
(107, '2022-01-09 17:48:25', '4A8G0048', '4', '1', '17288', 17288, '王政權', '台南市東區', '0903312345'),
(108, '2022-01-09 17:51:36', '4A8G0048', '6', '11', '82900', 911900, '王政權', '台南市東區', '0903312345'),
(109, '2022-01-09 17:53:52', '4A8G0048', '5', '2', '4990', 9980, '王政權', '台南市東區', '0903312345'),
(110, '2022-01-09 17:54:04', '4A8G0048', '4', '3', '17288', 51864, '王政權', '台南市東區', '0903312345'),
(111, '2022-01-10 07:14:39', '4A8G0048', '1', '8', '35900', 287200, '王政權', '台南市東區', '0903312345');

-- --------------------------------------------------------

--
-- 資料表結構 `members`
--

CREATE TABLE `members` (
  `No` int(8) NOT NULL,
  `Account` varchar(30) NOT NULL,
  `Password` varchar(30) NOT NULL,
  `Name` varchar(15) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `Phone` varchar(10) NOT NULL,
  `Birth` date NOT NULL,
  `Address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `members`
--

INSERT INTO `members` (`No`, `Account`, `Password`, `Name`, `Email`, `Phone`, `Birth`, `Address`) VALUES
(1, '4A8G0048', '123456', '王政權', '4A8G0048@stust.edu.tw', '0903312345', '2001-03-06', '台南市東區');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`No`);

--
-- 資料表索引 `good_detail`
--
ALTER TABLE `good_detail`
  ADD PRIMARY KEY (`good_index`);

--
-- 資料表索引 `items_order`
--
ALTER TABLE `items_order`
  ADD PRIMARY KEY (`No`);

--
-- 資料表索引 `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`No`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Account` (`Account`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `goods`
--
ALTER TABLE `goods`
  MODIFY `No` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `good_detail`
--
ALTER TABLE `good_detail`
  MODIFY `good_index` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `items_order`
--
ALTER TABLE `items_order`
  MODIFY `No` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `members`
--
ALTER TABLE `members`
  MODIFY `No` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
