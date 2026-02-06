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
-- ========================================
-- DADE Learn - Feature Tables
-- Run in phpMyAdmin
-- ========================================

-- Bookmarks table
CREATE TABLE IF NOT EXISTS `bookmarks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `course_id` INT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_bookmark` (`user_id`, `course_id`),
    KEY `idx_user_bookmarks` (`user_id`),
    KEY `idx_course_bookmarks` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Badges table  
CREATE TABLE IF NOT EXISTS `badges` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `icon` VARCHAR(50) DEFAULT 'fas fa-award',
    `color` VARCHAR(20) DEFAULT '#FFD700',
    `criteria` VARCHAR(50) NOT NULL,
    `criteria_value` INT DEFAULT 1,
    `points` INT DEFAULT 10,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- User badges (junction table)
CREATE TABLE IF NOT EXISTS `user_badges` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `badge_id` INT NOT NULL,
    `earned_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_user_badge` (`user_id`, `badge_id`),
    KEY `idx_user_badges` (`user_id`),
    KEY `idx_badge_users` (`badge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add certificate course columns to courses
ALTER TABLE `courses` ADD COLUMN IF NOT EXISTS `is_certificate_course` TINYINT(1) DEFAULT 0;
ALTER TABLE `courses` ADD COLUMN IF NOT EXISTS `passing_grade` INT DEFAULT 70;

-- Add status column to certificates if not exists
ALTER TABLE `certificates` ADD COLUMN IF NOT EXISTS `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'approved';
ALTER TABLE `certificates` ADD COLUMN IF NOT EXISTS `certificate_code` VARCHAR(50) NULL;
ALTER TABLE `certificates` ADD COLUMN IF NOT EXISTS `certificate_number` VARCHAR(50) NULL;

-- Insert default badges
INSERT INTO `badges` (`name`, `description`, `icon`, `color`, `criteria`, `criteria_value`, `points`) VALUES
('First Steps', 'Complete your first course', 'fas fa-shoe-prints', '#10b981', 'courses_completed', 1, 10),
('Scholar', 'Complete 5 courses', 'fas fa-graduation-cap', '#3b82f6', 'courses_completed', 5, 50),
('Quiz Novice', 'Pass 3 quizzes', 'fas fa-brain', '#f59e0b', 'quizzes_passed', 3, 30),
('Perfectionist', 'Score 100% on a quiz', 'fas fa-bullseye', '#ef4444', 'perfect_quiz', 1, 100),
('Certified Pro', 'Earn 3 certificates', 'fas fa-certificate', '#8b5cf6', 'certificates_earned', 3, 75),
('Reviewer', 'Write 5 reviews', 'fas fa-star', '#ec4899', 'reviews_written', 5, 25),
('Curator', 'Bookmark 10 courses', 'fas fa-heart', '#f472b6', 'bookmarks_created', 10, 15)
ON DUPLICATE KEY UPDATE name=name;
-- ========================================
-- DADE Learn - New Features Database Schema
-- Bookmarks, Badges, Certificates & Analytics
-- ========================================

-- 1. Bookmarks Table
CREATE TABLE IF NOT EXISTS `bookmarks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `course_id` INT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_bookmark` (`user_id`, `course_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Badges Table
CREATE TABLE IF NOT EXISTS `badges` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `icon` VARCHAR(50) DEFAULT 'fas fa-award',
    `color` VARCHAR(7) DEFAULT '#FFD700',
    `criteria` VARCHAR(255),
    `criteria_value` INT DEFAULT 1,
    `points` INT DEFAULT 10,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. User Badges Table
CREATE TABLE IF NOT EXISTS `user_badges` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `badge_id` INT NOT NULL,
    `earned_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_user_badge` (`user_id`, `badge_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`badge_id`) REFERENCES `badges`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Update Courses Table for Certificate Support
ALTER TABLE `courses` ADD COLUMN IF NOT EXISTS `is_certificate_course` TINYINT(1) DEFAULT 0;
ALTER TABLE `courses` ADD COLUMN IF NOT EXISTS `passing_grade` INT DEFAULT 70;

-- 5. Update Certificates Table
ALTER TABLE `certificates` ADD COLUMN IF NOT EXISTS `certificate_code` VARCHAR(32) UNIQUE AFTER `id`;
ALTER TABLE `certificates` ADD COLUMN IF NOT EXISTS `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending';

-- 6. Insert Default Badges
INSERT INTO `badges` (`name`, `description`, `icon`, `color`, `criteria`, `criteria_value`, `points`) VALUES
('First Steps', 'Complete your first course', 'fas fa-shoe-prints', '#10B981', 'courses_completed', 1, 10),
('Knowledge Seeker', 'Complete 5 courses', 'fas fa-book-reader', '#3B82F6', 'courses_completed', 5, 50),
('Scholar', 'Complete 10 courses', 'fas fa-graduation-cap', '#8B5CF6', 'courses_completed', 10, 100),
('Quiz Novice', 'Pass your first quiz', 'fas fa-check-circle', '#22C55E', 'quizzes_passed', 1, 10),
('Quiz Master', 'Pass 10 quizzes', 'fas fa-brain', '#F59E0B', 'quizzes_passed', 10, 100),
('Perfect Score', 'Get 100% on any quiz', 'fas fa-star', '#EAB308', 'perfect_quiz', 1, 50),
('Dedicated Learner', 'Login 7 days in a row', 'fas fa-fire', '#EF4444', 'login_streak', 7, 75),
('Community Helper', 'Write 5 course reviews', 'fas fa-comments', '#06B6D4', 'reviews_written', 5, 50),
('Certificate Holder', 'Earn your first certificate', 'fas fa-certificate', '#F97316', 'certificates_earned', 1, 100),
('Bookworm', 'Bookmark 10 courses', 'fas fa-bookmark', '#EC4899', 'bookmarks_created', 10, 25)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- 7. Analytics Tracking Table (for detailed stats)
CREATE TABLE IF NOT EXISTS `analytics_daily` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `date` DATE NOT NULL UNIQUE,
    `new_users` INT DEFAULT 0,
    `new_enrollments` INT DEFAULT 0,
    `completions` INT DEFAULT 0,
    `quizzes_taken` INT DEFAULT 0,
    `certificates_issued` INT DEFAULT 0,
    `active_users` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Run this SQL in phpMyAdmin
-- ========================================
