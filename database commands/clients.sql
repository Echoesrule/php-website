
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";




CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `username`, `email`, `password`, `created_at`, `is_admin`) VALUES
(1, 'admin', 'kipronoemmanuel911@gmail.com', '$2y$10$saVO1iV2vOY432Qd9k/yaurKIhhkpvxy82G1dEt.sZmpPopQAJVNm', '2025-12-10 14:58:08', 1),
(2, 'retro', 'iamhim@gmail.com', '$2y$10$A8ZcnBgzj2/xJGW8ctNyq.Pt/G3wkVYLIhFmIpgnUJQzFWMwzTAMu', '2025-12-10 15:47:23', 0),
(3, 's.k.r.e.a.m.z', 'radar@gmail.com', '$2y$10$PO1g0ZKaf6h2UMDIxPqVd.umnIeVpNpfo6tFujDAfD.tmlPz3MRdi', '2025-12-11 13:32:24', 0),
(4, 'sukuna', 'sukuna@gmail.com', '$2y$10$eheOQQ8fGE9d/CC5lzizf.b.YArqLL20XktNBj2ODQEVmLtrD1dIe', '2025-12-11 14:12:46', 0),
(5, 'shady walker', 'slimshady@gmail.com', '$2y$10$7mZeLf/4d6F3CbJnSOKT2.J37M5MOm8pd7N1pBBftK5pGIrnm1pY.', '2025-12-11 15:37:53', 0),
(6, 'sample_bot', 'werttet@wer', '$2y$10$wPt.CGXwJQUGH8AYuB9L1uCntHk4IIdPIFTUzncIxeUVrVohzLf32', '2025-12-11 16:12:18', 0),
(7, 'satoru gojo', 'iamthehonouredone@gmail.com', '$2y$10$MyE8xwWx9ltTWZZFTtfqQOcw2YqYBX71rngp.PGjKOv3Z4SSlw94O', '2025-12-12 10:02:53', 0),
(8, 'retromania', 'sendhelp@gmail.com', '$2y$10$8nhsmSijAPiVBOcwfmWIz.A57wSUSmfsNF394JBhJKpsY8kb5teVC', '2025-12-22 15:34:13', 0),
(9, 'testing db', 'juju@gmail.com', '$2y$10$KqHonkv0rM9p9Iu16V./I.yqEPO/Plf8pCnMw4vyHYNzA1E4oE0gy', '2025-12-28 15:21:50', 0);


ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--

ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;
---clients table