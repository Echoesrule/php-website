
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";





CREATE TABLE `ordered_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `ordered_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(5, 5, 83, 1, 500.00),
(6, 6, 158, 1, 3000.00),
(7, 7, 154, 1, 5000.00),
(8, 8, 89, 1, 900.00),
(9, 9, 352, 1, 1000.00),
(10, 10, 234, 2, 400.00),
(11, 11, 219, 1, 1200.00),
(12, 12, 378, 1, 6000.00),
(13, 13, 176, 2, 650.00),
(14, 13, 182, 2, 399.00),
(15, 14, 296, 1, 1700.00),
(16, 15, 298, 1, 700.00);


ALTER TABLE `ordered_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

ALTER TABLE `ordered_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;



ALTER TABLE `ordered_items`
  ADD CONSTRAINT `ordered_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ordered_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

