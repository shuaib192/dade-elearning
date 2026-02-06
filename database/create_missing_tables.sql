-- ========================================
-- DADE Learn - Missing Tables Creation
-- Run this SQL in your MySQL/phpMyAdmin
-- ========================================

-- Create enrollments table if not exists
CREATE TABLE IF NOT EXISTS `enrollments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `course_id` INT NOT NULL,
    `enrolled_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `completed` TINYINT(1) DEFAULT 0,
    `completed_at` DATETIME NULL,
    `progress` INT DEFAULT 0,
    UNIQUE KEY `unique_enrollment` (`user_id`, `course_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create reviews table if not exists
CREATE TABLE IF NOT EXISTS `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `course_id` INT NOT NULL,
    `rating` DECIMAL(2,1) NOT NULL CHECK (rating >= 1 AND rating <= 5),
    `comment` TEXT,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_review` (`user_id`, `course_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create certificates table if not exists
CREATE TABLE IF NOT EXISTS `certificates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `course_id` INT NOT NULL,
    `certificate_number` VARCHAR(50) UNIQUE NOT NULL,
    `issued_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `pdf_path` VARCHAR(255) NULL,
    UNIQUE KEY `unique_certificate` (`user_id`, `course_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create lesson_progress table if not exists
CREATE TABLE IF NOT EXISTS `lesson_progress` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `lesson_id` INT NOT NULL,
    `completed` TINYINT(1) DEFAULT 0,
    `completed_at` DATETIME NULL,
    `time_spent` INT DEFAULT 0,
    UNIQUE KEY `unique_progress` (`user_id`, `lesson_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`lesson_id`) REFERENCES `lessons`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create password_resets table if not exists
CREATE TABLE IF NOT EXISTS `password_resets` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(64) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `expires_at` DATETIME NOT NULL,
    `used` TINYINT(1) DEFAULT 0,
    INDEX `idx_token` (`token`),
    INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Success! Tables created.
-- ========================================
