

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";




CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `subcategories` (`id`, `category_id`, `name`) VALUES
(208, 1, 'Books'),
(209, 1, 'Pens'),
(210, 1, 'Geometrical set'),
(211, 1, 'Calculators'),
(212, 1, 'Novels '),
(213, 1, 'Gowns'),
(214, 1, 'Cellotapes'),
(215, 1, 'Dusters'),
(216, 1, 'Mangas'),
(217, 1, 'Water Bottle'),
(218, 2, 'Proteins'),
(219, 2, 'Bevarages/other drinks'),
(220, 2, 'Fruits'),
(221, 2, 'Vegetables'),
(222, 2, 'Snacks'),
(223, 3, 'Chairs'),
(224, 3, 'Tables'),
(227, 3, 'Wardrobes'),
(228, 3, 'Shoe racks'),
(229, 3, 'Couches'),
(230, 4, 'Durags/Bandanas'),
(231, 4, 'Hats'),
(232, 4, 'T shirts'),
(233, 4, 'Shirts'),
(234, 4, 'Sweater'),
(235, 4, 'Hoodies'),
(236, 4, 'Socks'),
(237, 4, 'Pajamas'),
(238, 4, 'Shoes'),
(239, 4, 'Shorts'),
(240, 5, 'Action figure'),
(241, 5, 'Ball'),
(242, 5, 'Bean bag'),
(243, 5, 'Binoculars'),
(244, 5, 'Board game'),
(245, 5, 'Boat'),
(246, 5, 'Bubble'),
(247, 5, 'Electric vehicle'),
(248, 5, 'Globe'),
(249, 5, 'Skipping rope'),
(250, 5, 'Kite'),
(251, 5, 'Microscope'),
(252, 5, 'Model'),
(253, 5, 'Musical instruments'),
(254, 5, 'Play money'),
(255, 5, 'Puppet'),
(256, 5, 'Puzzle'),
(257, 6, 'Action'),
(258, 6, 'Roleplay'),
(259, 6, 'Adventure'),
(260, 6, 'First person'),
(261, 6, 'Strategy'),
(262, 7, 'Laptops'),
(263, 7, 'Televisions'),
(264, 7, 'Chargers'),
(265, 7, 'Music systems'),
(266, 7, 'Ovens'),
(267, 7, 'Wifi routers'),
(268, 7, 'Microwaves'),
(269, 7, 'Blenders'),
(270, 7, 'Ipads'),
(271, 7, 'Motors');


ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_cat_id` (`category_id`);


ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=272;


ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`Categ_id`) ON DELETE CASCADE;
COMMIT;

