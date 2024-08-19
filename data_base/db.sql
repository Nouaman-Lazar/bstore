-- Create database
CREATE DATABASE affiliate_marketing;

-- Use the created database
USE affiliate_marketing;

-- Table structure for table `admin`
CREATE TABLE `admin` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL, -- Store hashed passwords
  PRIMARY KEY (`id`)
);
CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `visits` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
);
-- Table structure for table `stores`
CREATE TABLE `stores` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `image_url1` VARCHAR(1000),
  `image_url2` VARCHAR(1000),
  `image_url3` VARCHAR(1000),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

-- Table structure for table `categories`
CREATE TABLE `categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `store_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `store_name` VARCHAR(100),
  `description` TEXT,
  `image_url1` VARCHAR(1000),
  `image_url2` VARCHAR(1000),
  `image_url3` VARCHAR(1000),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`store_id`) REFERENCES `stores`(`id`) ON DELETE CASCADE
);

-- Table structure for table `products`
CREATE TABLE `products` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `category_id` INT NOT NULL,
  `store_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `image_url1` VARCHAR(1000),
  `image_url2` VARCHAR(1000),
  `image_url3` VARCHAR(1000),
  `affiliate_link` VARCHAR(255),
   `price` DECIMAL(10, 2) NOT NULL,
  `affiliate_link_color` VARCHAR(7),
  `affiliate_link_name` VARCHAR(100),
  `likes` INT DEFAULT 0,
  `views` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`store_id`) REFERENCES `stores`(`id`) ON DELETE CASCADE
);

CREATE TABLE `publications` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `image_url` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `link` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);



-- Insert default admin user (password should be hashed in a real application)
INSERT INTO `admin` (`username`, `password`) VALUES 
('admin', 'password'); -- Replace 'password' with a hashed password