

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";




CREATE TABLE `categories` (
  `Categ_id` int(11) NOT NULL COMMENT 'Primary Key',
  `create_time` datetime DEFAULT NULL COMMENT 'Create Time',
  `Name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `categories` (`Categ_id`, `create_time`, `Name`, `image`) VALUES
(1, NULL, 'School', '1765906537_644730ca5b77.jpg'),
(2, NULL, 'Grocery', '1765909480_c57f7729844b.jpg'),
(3, NULL, 'Home/Furniture', '1765909492_f46aaf841417.jpg'),
(4, NULL, 'Clothes', '1765909138_be0856ce3ba8.jpg'),
(5, NULL, 'Toys', '1765909505_20ef2b4443bc.jpg'),
(6, NULL, 'Video Games', '1765909595_5e0121fd4e8e.jpg'),
(7, NULL, 'Electronics', '1765909668_5e9b678402bf.jpg');


ALTER TABLE `categories`
  ADD PRIMARY KEY (`Categ_id`);

-
--
ALTER TABLE `categories`
  MODIFY `Categ_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=14;
COMMIT;

select * from categories;
---categories table