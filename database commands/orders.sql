
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `Total_price` decimal(10,2) NOT NULL,
  `Ordered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_number` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `client_id`, `Total_price`, `Ordered_at`, `order_number`) VALUES
(1, 2, 700.00, '2025-12-12 09:51:01', 1),
(2, 2, 700.00, '2025-12-12 10:00:26', 1),
(3, 7, 700.00, '2025-12-12 10:03:23', 1),
(4, 7, 700.00, '2025-12-12 10:08:15', 2),
(5, 1, 500.00, '2025-12-12 17:38:36', 1),
(6, 1, 3000.00, '2025-12-13 09:08:44', 2),
(7, 1, 5000.00, '2025-12-13 10:54:47', 3),
(8, 1, 900.00, '2025-12-15 14:53:25', 4),
(9, 2, 1000.00, '2025-12-21 17:05:10', 2),
(10, 1, 800.00, '2025-12-22 13:42:48', 5),
(11, 1, 1200.00, '2025-12-22 13:46:03', 6),
(12, 1, 6000.00, '2025-12-22 13:53:47', 7),
(13, 1, 2098.00, '2025-12-22 14:52:34', 8),
(14, 2, 1700.00, '2025-12-27 12:08:18', 3),
(15, 1, 700.00, '2025-12-28 16:11:34', 9);



ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);


ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;


ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;
COMMIT;

---orders table