-- =============================================
-- CSDL Website Bán Hàng Điện Tử (Laragon/MySQL)
-- Import file này trong HeidiSQL hoặc phpMyAdmin
-- =============================================

CREATE DATABASE IF NOT EXISTS `ecommerce_db`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `ecommerce_db`;

-- Danh mục sản phẩm
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Điện thoại'),
(2, 'Laptop'),
(3, 'Phụ kiện')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Sản phẩm
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` int NOT NULL,
  `description` text,
  `category_id` int DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_category` (`category_id`),
  CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` (`name`, `price`, `description`, `category_id`, `image`) VALUES
('iPhone 15 Pro Max 256GB', 29990000, 'Chip A17 Pro, camera 48MP, pin trâu.', 1, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/i/p/iphone-15-pro-max_1.png'),
('Samsung Galaxy S24 Ultra', 27990000, 'Bút S Pen, màn hình Dynamic AMOLED 2X.', 1, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/s/a/samsung-s24-ultra.png'),
('MacBook Air M3 15 inch', 32990000, 'Chip Apple M3, RAM 8GB, SSD 256GB.', 2, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/m/a/macbook-air-m3-15.png'),
('Tai nghe AirPods Pro 2', 5490000, 'Chống ồn chủ động, sạc USB-C.', 3, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/a/i/airpods-pro-2.png'),
('Xiaomi Redmi Note 13 Pro', 7490000, 'Camera 200MP, sạc nhanh 67W.', 1, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/x/i/xiaomi-redmi-note-13-pro.png');

-- Bảng Users (đầy đủ các trường mới)
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL UNIQUE,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','User') NOT NULL DEFAULT 'User',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `avatar` varchar(500) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tài khoản Admin mặc định (password: Admin@123)
INSERT INTO `users` (`fullname`, `username`, `email`, `password`, `role`, `is_active`, `email_verified_at`) VALUES
('Quản trị viên', 'admin', 'admin@shop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 1, NOW())
ON DUPLICATE KEY UPDATE `role` = 'Admin';

-- Đơn hàng
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `total_amount` int NOT NULL,
  `payment_method` varchar(50) NOT NULL DEFAULT 'cod',
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Chi tiết đơn hàng
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` int NOT NULL,
  `quantity` int NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_order` (`order_id`),
  CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Đánh giá sản phẩm
CREATE TABLE IF NOT EXISTS `product_reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `rating` tinyint NOT NULL DEFAULT 5,
  `comment` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Liên hệ khách hàng
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SCRIPT ALTER để cập nhật bảng users nếu đã tồn tại
-- Chạy phần này nếu bảng users đã có sẵn:
-- ============================================================
-- ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `avatar` varchar(500) DEFAULT NULL;
-- ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `phone` varchar(20) DEFAULT NULL;
-- ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `address` text DEFAULT NULL;
-- ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `remember_token` varchar(255) DEFAULT NULL;
-- ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `email_verified_at` timestamp NULL DEFAULT NULL;
-- ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `verification_token` varchar(255) DEFAULT NULL;
-- ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
-- ALTER TABLE `users` MODIFY COLUMN `role` enum('Admin','User') NOT NULL DEFAULT 'User';
