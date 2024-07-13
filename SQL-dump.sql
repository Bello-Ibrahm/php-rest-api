-- Drop database if exists
DROP DATABASE IF EXISTS php_rest_api_db;

-- Create database and user if not exists
CREATE DATABASE IF NOT EXISTS php_rest_api_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'php_rest_api_dev'@'localhost' IDENTIFIED BY 'php_rest_api_123';
GRANT ALL ON php_rest_api_db.* TO 'php_rest_api_dev'@'localhost';
GRANT SELECT ON performance_schema.* TO 'php_rest_api_dev'@'localhost';
FLUSH PRIVILEGES;

-- ALTER USER 'php_rest_api_dev'@'localhost' IDENTIFIED BY 'php_rest_api_123';
-- Switch to the newly created database
USE php_rest_api_db;

-- Table structure for `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int AUTO_INCREMENT NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL UNIQUE,
  `password` varchar(250) NOT NULL,
  `role` TINYINT DEFAULT 0 COMMENT '0 -> User, 1 -> Admin',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;