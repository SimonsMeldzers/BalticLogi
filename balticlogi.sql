-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Dec 27, 2024 at 02:40 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `balticlogi`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'PVC logi', 'PVC logu apraksts'),
(2, 'Koka logi', 'Koka logu apraksts'),
(3, 'Alumīnija logi', 'Alumīnija logu apraksts');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `user_id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 8, 'Simons', 'test@gmail.com', 'This a test', '2024-12-27 12:17:31'),
(6, 8, 'Simons', 'test@gmail.com', 'Labdien, vēlos pasūtīt 10 logus uz uzņēmumu.', '2024-12-27 13:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(1, 8, 250.00, 'pending', '2024-12-06 15:53:31', '2024-12-10 12:49:56'),
(2, 9, 170.02, 'completed', '2024-12-06 15:53:37', '2024-12-10 12:55:20'),
(4, 3, 6999.00, 'pending', '2024-12-10 13:15:03', '2024-12-10 13:15:03'),
(9, 1, 1.00, 'pending', '2024-12-27 10:07:29', '2024-12-27 10:07:29'),
(10, 1, 0.00, 'completed', '2024-12-27 10:12:48', '2024-12-27 10:13:00'),
(11, 8, 1375.00, 'pending', '2024-12-27 11:30:47', '2024-12-27 11:30:47'),
(15, 11, 0.00, 'cancelled', '2024-12-27 11:50:37', '2024-12-27 11:54:15'),
(16, 8, 1060.00, 'pending', '2024-12-27 13:27:49', '2024-12-27 13:27:49');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(4, 1, 1, 2, 50.00),
(5, 1, 2, 1, 60.00),
(6, 1, 3, 3, 30.00),
(7, 2, 1, 1, 50.00),
(8, 2, 2, 2, 60.00),
(9, 4, 1, 2, 150.00),
(10, 9, 1, 4, 150.00),
(11, 9, 4, 2, 125.00),
(12, 9, 3, 1, 350.00),
(13, 10, 4, 10, 125.00),
(14, 11, 1, 1, 150.00),
(15, 11, 2, 3, 250.00),
(16, 11, 3, 1, 350.00),
(17, 11, 4, 1, 125.00),
(21, 15, 2, 2, 250.00),
(22, 16, 2, 3, 250.00),
(23, 16, 7, 2, 155.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `dimensions` varchar(255) DEFAULT NULL,
  `energy_rating` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `image_path`, `material`, `dimensions`, `energy_rating`) VALUES
(1, 'Logs Modelis A', 'PVC logs parasti ir izgatavots no augstākās kvalitātes vinila, kas nodrošinās jums vislabāko aizsardzību pret visiem izplatītajiem laikapstākļiem, kas rodas šajā reģionā. Faktiski, atšķirībā no daudziem parastajiem logu stiliem, PVC nerada kondensāciju un nekad nepūst vai nepelēs, ja tiek pakļauts mitrumam.', 150.00, 1, '../images/logs_a.jpg', 'PVC', '100x150 cm', 'A++'),
(2, 'Logs Modelis B', 'Elegants koka logs', 250.00, 2, '../images/logs_b.jpg', 'Koks', '120x160 cm', 'A+'),
(3, 'Logs Modelis C', 'Izturīgs alumīnija logs', 350.00, 3, '../images/logs_c.jpg', 'Alumīnijs', '140x170 cm', 'A'),
(4, 'ALUPLAST 4000', 'Aluplast logi – mūsdienīgs un kvalitatīvs risinājums jūsu mājas iekārtošanai. Tos izgatavo no augstas kvalitātes materiāliem, kas nodrošina gan izturību, gan augstu enerģijas taupīšanas pakāpi.\r\n\r\nAluplast ir vairāk nekā 30 gadu pieredze logu sistēmu ražošanā, kas ļauj uzņēmumam radīt produktus, kas atbilst augstākajiem kvalitātes un funkcionalitātes standartiem. Aluplast logiem ir daudzas priekšrocības, ieskaitot labu skaņas izolāciju, izcilu siltumizolāciju, aizsardzību pret ielaušanos un iespēju izvēlēties dažādas krāsas un dizainus.\r\n\r\nJa jums ir nepieciešami jauni logi, kas lieliski iederēsies jūsu mājas interjerā un samazinās apkures izmaksas, tad Aluplast logi ir tieši tas, kas jums nepieciešams.\r\nprofila platums -70mm\r\nkameru skaits profilā – 5\r\narmatūra – cinkots slēgts armatūras profils rāmī (1.5mm) un vērtnē П (1.25mm)\r\ndubultstikli – 4TPS/16A/4TPS (2-glāzes)\r\nstikla pakešu logu biezums – 24mm\r\nstikla pakešu loga siltumvadītspējas koeficients – Ug=1,1 W/m2K\r\nprofila siltumvadītspējas koeficients – U=1,3W/m2*K\r\narmatūra – “MACO Multi-Matic”', 125.00, 1, 'https://logijums.eu/wp-content/uploads/a4-1024x1024.jpg', 'PVC', '150x200 cm', 'A++'),
(7, 'Enerģijas efektīvs koka logs', 'Mūsu dubultā stikla uPVC logi ir izstrādāti, lai nodrošinātu izcilas siltuma priekšrocības, saglabājot jūsu māju siltu ziemā un vēsu vasarā. Pateicoties piecu kameru sistēmai, mūsu logi pārsniedz valdības noteikumus un nodrošina, ka jūsu māja joprojām ir energoefektīva. Vakuums starp rūtīm darbojas kā barjera, novēršot aukstā gaisa iekļūšanu un saglabājot siltu gaisu. Tas nozīmē, ka varat baudīt zemākus enerģijas rēķinus un samazinātu oglekļa pēdu.', 155.00, 2, 'https://www.justvaluedoors.co.uk/images_new/gallery/windows-upvc/upvc-window-4.jpg', 'Koks', '150x200 cm', 'B++');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Anna Anna', 'anna@rtu.lv', '$2y$10$D4dhAUfURUi.sLZta.vWNeqzEJxJMtuUyAaBr2o1LhJZ/mh9USXPS', 'user'),
(2, 'Jānis Priede', 'janis@rtu.lv', '$2y$10$9U/Mb71Kq0q9ePnSwTwqye/Zp8M.I5/IOLOq1UmRGGsmBrZrhQmMW', 'user'),
(3, 'Ilze Liepa', 'ilze@rtu.lv', '$2y$10$dXfH0VMLGNMmrNqlbP3VhuPWikMeHoo4aAxtaxG7LmIY.Zz46z3J6', 'user'),
(8, 'Simons', 'test@gmail.com', '$2y$10$59TFHYqSEfSdj6JByCkUJe7b04oB.1ybZhmSqrMjuy96S757h9Tqq', 'admin'),
(9, 'Testeris', 'tester@gmail.com-OLD', '$2y$10$wcPxgHGQJWCXGazl1vquSeCT9EFWFc7iwoaAMfWHWARV2od4T4cAa', 'user'),
(10, 'Tester 2', 'tester@gmail.com', '$2y$10$s2U.51RET6E5aERSdWdl6e/O7odhOtuD18XfcierS8fWZs75qYVfm', 'user'),
(11, 'Karlis', 'karlis@gmail.com', '$2y$10$c20JV4HEF6j15TpIR/.O/eYJPNbF/FmM8Ci3j900CiiCYQuDSPGc2', 'user'),
(12, 'Administrators', 'admin@balticlogi.lv', '$2y$10$PkTJknBrfKgHZVmSf8Uu4OhfAWpOjGCAdvg56yG93P9rfvCwKcU6q', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `contact_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
