-- -----------------------------------------------------
-- AUTHENTICATION CORE SCHEMA (MySQL/MariaDB)
-- -----------------------------------------------------
-- Created: 2025-10-19
-- Purpose: Common user/access/token structure (no app-specific tables)
-- -----------------------------------------------------

CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `email` VARCHAR(150) DEFAULT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','editor','user','api') DEFAULT 'user',
  `status` ENUM('active','disabled','pending') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user_tokens` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `token` VARCHAR(128) NOT NULL UNIQUE,
  `type` ENUM('api','session','reset','email_verify') DEFAULT 'session',
  `issued_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `expires_at` DATETIME DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  CONSTRAINT `fk_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user_log` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) DEFAULT NULL,
  `event` VARCHAR(50) NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed admin (replace password_hash and email before use)
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`)
VALUES ('admin', 'admin@example.com', '$2y$10$change_me', 'admin');

-- Sample token (replace token value)
INSERT INTO `user_tokens` (`user_id`, `token`, `type`, `expires_at`)
VALUES (1, 'change_me_token', 'session', DATE_ADD(NOW(), INTERVAL 7 DAY));
