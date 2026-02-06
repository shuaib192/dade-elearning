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
