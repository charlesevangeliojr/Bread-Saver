-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2025 at 06:59 PM
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
-- Database: `breadsaver`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `bakeshop_applications`
--

CREATE TABLE `bakeshop_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `bakeshop_address` varchar(255) NOT NULL,
  `bakeshop_city` varchar(100) NOT NULL,
  `bakeshop_province` varchar(100) NOT NULL,
  `bakeshop_postal_code` varchar(20) NOT NULL,
  `business_permit_photo` varchar(255) DEFAULT NULL,
  `health_permit_photo` varchar(255) DEFAULT NULL,
  `bakeshop_photo` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','disapproved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bread_items`
--

CREATE TABLE `bread_items` (
  `id` int(11) NOT NULL,
  `bakeshop_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients_inventory`
--

CREATE TABLE `ingredients_inventory` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `min_stock` int(11) NOT NULL,
  `stock_on_hand` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `expiration_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_audits`
--

CREATE TABLE `ingredient_audits` (
  `id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `stock_on_hand` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `expiration_date` date NOT NULL,
  `checked_datetime` datetime NOT NULL,
  `checked_by` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ingredient_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `ingredient_audits`
--
DELIMITER $$
CREATE TRIGGER `update_inventory_stock_on_audit` AFTER INSERT ON `ingredient_audits` FOR EACH ROW BEGIN
    
    UPDATE `ingredients_inventory`
    SET `stock_on_hand` = NEW.stock_on_hand
    WHERE `id` = NEW.ingredient_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `market_suppliers`
--

CREATE TABLE `market_suppliers` (
  `supplier_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'Philippines',
  `company_name` varchar(255) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('to pay','pending','confirmed','canceled') DEFAULT 'to pay',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `quantity` int(11) NOT NULL,
  `ingredient_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_per_unit` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_orders`
--

CREATE TABLE `supplier_orders` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `confirmed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplies`
--

CREATE TABLE `supplies` (
  `SupplyID` int(11) NOT NULL,
  `IngredientName` varchar(255) NOT NULL,
  `Picture` varchar(255) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Stock` decimal(10,2) NOT NULL,
  `Category` varchar(100) NOT NULL,
  `PricePerUnit` decimal(10,2) NOT NULL,
  `Unit` varchar(50) NOT NULL,
  `SupplierID` int(11) NOT NULL,
  `SupplyDate` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `gender` enum('male','female') NOT NULL,
  `dob` date NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('bakeshop','customer') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bakeshop_applications`
--
ALTER TABLE `bakeshop_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bread_items`
--
ALTER TABLE `bread_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bakeshop_id` (`bakeshop_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Indexes for table `ingredients_inventory`
--
ALTER TABLE `ingredients_inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ingredient_audits`
--
ALTER TABLE `ingredient_audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_ingredient_id` (`ingredient_id`);

--
-- Indexes for table `market_suppliers`
--
ALTER TABLE `market_suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `email` (`email`) USING BTREE;

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `fk_order_items_order_id` (`order_id`),
  ADD KEY `fk_order_items_ingredient_id` (`ingredient_id`);

--
-- Indexes for table `supplier_orders`
--
ALTER TABLE `supplier_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `supplies`
--
ALTER TABLE `supplies`
  ADD PRIMARY KEY (`SupplyID`),
  ADD KEY `SupplierID` (`SupplierID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bakeshop_applications`
--
ALTER TABLE `bakeshop_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bread_items`
--
ALTER TABLE `bread_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ingredients_inventory`
--
ALTER TABLE `ingredients_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ingredient_audits`
--
ALTER TABLE `ingredient_audits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `market_suppliers`
--
ALTER TABLE `market_suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_orders`
--
ALTER TABLE `supplier_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplies`
--
ALTER TABLE `supplies`
  MODIFY `SupplyID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bakeshop_applications`
--
ALTER TABLE `bakeshop_applications`
  ADD CONSTRAINT `bakeshop_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `bread_items`
--
ALTER TABLE `bread_items`
  ADD CONSTRAINT `bread_items_ibfk_1` FOREIGN KEY (`bakeshop_id`) REFERENCES `bakeshop_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bakeshop_applications` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `supplies` (`SupplyID`) ON DELETE CASCADE;

--
-- Constraints for table `ingredients_inventory`
--
ALTER TABLE `ingredients_inventory`
  ADD CONSTRAINT `ingredients_inventory_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bakeshop_applications` (`user_id`);

--
-- Constraints for table `ingredient_audits`
--
ALTER TABLE `ingredient_audits`
  ADD CONSTRAINT `fk_ingredient_id` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients_inventory` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ingredient_audits_ibfk_1` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients_inventory` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ingredient_audits_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `bakeshop_applications` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_ingredient_id` FOREIGN KEY (`ingredient_id`) REFERENCES `supplies` (`SupplyID`),
  ADD CONSTRAINT `fk_order_items_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `supplier_orders`
--
ALTER TABLE `supplier_orders`
  ADD CONSTRAINT `supplier_orders_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `supplier_orders_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `market_suppliers` (`supplier_id`);

--
-- Constraints for table `supplies`
--
ALTER TABLE `supplies`
  ADD CONSTRAINT `supplies_ibfk_1` FOREIGN KEY (`SupplierID`) REFERENCES `market_suppliers` (`supplier_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
