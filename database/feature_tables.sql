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
