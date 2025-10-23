-- --------------------------------------------------------
-- Database: audiverse_db
-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS audiverse_db;
USE audiverse_db;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('user','admin') DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `categories`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `products`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `category_id` INT(11) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10,2) NOT NULL,
  `stock` INT(11) DEFAULT 0,
  `image` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `orders`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending','processing','completed','cancelled') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `order_items`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `cart`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cart` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Sample data for `categories`
-- --------------------------------------------------------
INSERT INTO `categories` (`name`, `slug`) VALUES
('Engine', 'engine'),
('Brakes', 'brakes'),
('Suspension', 'suspension'),
('Exhaust', 'exhaust'),
('Interior', 'interior'),
('Exterior', 'exterior');

-- --------------------------------------------------------
-- Sample data for `products`
-- --------------------------------------------------------
INSERT INTO `products` (`name`, `category_id`, `description`, `price`, `stock`, `image`) VALUES
('Audi A4 Brake Pads', 2, 'High-performance ceramic brake pads for Audi A4 2016-2020.', 129.99, 25, 'brakepads_a4.jpg'),
('Audi RS6 Exhaust System', 4, 'Stainless steel performance exhaust system for RS6.', 799.00, 10, 'rs6_exhaust.jpg'),
('Audi TT Coilover Kit', 3, 'Adjustable coilover suspension kit for Audi TT.', 999.99, 15, 'tt_coilover.jpg'),
('Audi A3 Air Filter', 1, 'Reusable high-flow air filter for Audi A3.', 49.99, 50, 'a3_airfilter.jpg'),
('Audi S4 Carbon Fiber Spoiler', 6, 'Stylish carbon fiber rear spoiler for Audi S4.', 399.00, 5, 's4_spoiler.jpg');

-- --------------------------------------------------------
-- Sample admin user
-- Password = "admin123" (bcrypt hashed)
-- --------------------------------------------------------
INSERT INTO `users` (`username`, `email`, `password`, `role`) VALUES
('admin', 'admin@audiverse.com', '$2y$10$3b9fDxnDJP/5S3hrEKgFvO.PpQdzEUIv6e.Pn3VvJ6CHGcQZltDT6', 'admin');
