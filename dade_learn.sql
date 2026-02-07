-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 07, 2026 at 12:25 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dade_learn`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `name`, `points`) VALUES
(1, 'Completed onboarding & entry quiz', 10),
(2, 'Attended a monthly webinar', 10),
(3, 'Shared a webinar reflection', 5),
(4, 'Participated in a campaign', 10),
(5, 'Created an inclusion-focused social media post', 5),
(6, 'Organized a local outreach', 20),
(7, 'Mentored a new volunteer', 15),
(8, 'Submitted an impact story/testimonial', 10),
(9, 'Proposed a micro-project idea', 10),
(10, 'Completed a specialized course', 10),
(11, 'Served as a project team lead', 25),
(12, 'Featured as Volunteer of the Month', 15),
(14, 'Referred a new volunteer', 5),
(15, 'Shared on Social Media', 5);

-- --------------------------------------------------------

--
-- Table structure for table `analytics_daily`
--

CREATE TABLE `analytics_daily` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `new_users` int(11) DEFAULT 0,
  `new_enrollments` int(11) DEFAULT 0,
  `completions` int(11) DEFAULT 0,
  `quizzes_taken` int(11) DEFAULT 0,
  `certificates_issued` int(11) DEFAULT 0,
  `active_users` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `message`, `is_active`, `created_at`) VALUES
(1, 'Hello Everyone', 0, '2025-08-03 20:20:56'),
(2, 'Hello Every One', 0, '2025-08-03 20:21:06'),
(3, 'Volunteers!!!!', 0, '2025-09-19 13:42:39'),
(4, 'Hello Volunteers', 0, '2025-10-06 13:06:08'),
(5, 'We Are great Volunteers', 0, '2025-10-15 09:56:35'),
(6, 'Stronger Together, Inclusive Forever! Your dedication is building a community where everyone belongs and thrives.', 1, '2025-10-28 18:58:58');

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'fas fa-award',
  `color` varchar(20) DEFAULT '#FFD700',
  `criteria` varchar(50) NOT NULL,
  `criteria_value` int(11) DEFAULT 1,
  `points` int(11) DEFAULT 10,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `badges`
--

INSERT INTO `badges` (`id`, `name`, `description`, `icon`, `color`, `criteria`, `criteria_value`, `points`, `created_at`) VALUES
(1, 'First Steps', 'Complete your first course', 'fas fa-shoe-prints', '#10b981', 'courses_completed', 1, 10, '2026-02-06 10:18:50'),
(3, 'Quiz Novice', 'Pass 3 quizzes', 'fas fa-brain', '#f59e0b', 'quizzes_passed', 3, 30, '2026-02-06 10:18:50'),
(4, 'Perfectionist', 'Score 100% on a quiz', 'fas fa-bullseye', '#ef4444', 'perfect_quiz', 1, 100, '2026-02-06 10:18:50'),
(5, 'Certified Pro', 'Earn 3 certificates', 'fas fa-certificate', '#8b5cf6', 'certificates_earned', 3, 75, '2026-02-06 10:18:50'),
(6, 'Reviewer', 'Write 5 reviews', 'fas fa-star', '#ec4899', 'reviews_written', 5, 25, '2026-02-06 10:18:50'),
(7, 'Curator', 'Bookmark 10 courses', 'fas fa-heart', '#f472b6', 'bookmarks_created', 10, 15, '2026-02-06 10:18:50'),
(9, 'Knowledge Seeker', 'Complete 5 courses', 'fas fa-book-reader', '#3B82F6', 'courses_completed', 5, 50, '2026-02-06 18:05:41'),
(10, 'Scholar', 'Complete 10 courses', 'fas fa-graduation-cap', '#8B5CF6', 'courses_completed', 10, 100, '2026-02-06 18:05:41'),
(11, 'Quiz Novice', 'Pass your first quiz', 'fas fa-check-circle', '#22C55E', 'quizzes_passed', 1, 10, '2026-02-06 18:05:41'),
(12, 'Quiz Master', 'Pass 10 quizzes', 'fas fa-brain', '#F59E0B', 'quizzes_passed', 10, 100, '2026-02-06 18:05:41'),
(13, 'Perfect Score', 'Get 100% on any quiz', 'fas fa-star', '#EAB308', 'perfect_quiz', 1, 50, '2026-02-06 18:05:41'),
(14, 'Dedicated Learner', 'Login 7 days in a row', 'fas fa-fire', '#EF4444', 'login_streak', 7, 75, '2026-02-06 18:05:41'),
(15, 'Community Helper', 'Write 5 course reviews', 'fas fa-comments', '#06B6D4', 'reviews_written', 5, 50, '2026-02-06 18:05:41'),
(17, 'Bookworm', 'Bookmark 10 courses', 'fas fa-bookmark', '#EC4899', 'bookmarks_created', 10, 25, '2026-02-06 18:05:41');

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cadre_levels`
--

CREATE TABLE `cadre_levels` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `minimum_duration_months` int(11) NOT NULL DEFAULT 0,
  `min_points` int(11) NOT NULL,
  `badge_color` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cadre_levels`
--

INSERT INTO `cadre_levels` (`id`, `name`, `minimum_duration_months`, `min_points`, `badge_color`) VALUES
(1, 'Inclusion Ally', 0, 0, 'Yellow'),
(2, 'Inclusion Advocate', 3, 50, 'Blue'),
(3, 'Inclusive Mobilizer', 6, 150, 'Green'),
(4, 'Inclusion Catalyst', 12, 300, 'Gold'),
(5, 'Inclusion Strategist', 12, 500, 'Platinum');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`) VALUES
(1, 'Digital Skills', 'digital-skills', 'Technology and digital literacy courses', '2026-02-04 22:04:38'),
(2, 'Advocacy', 'advocacy', 'Disability rights and advocacy training', '2026-02-04 22:04:38'),
(3, 'Entrepreneurship', 'entrepreneurship', 'Business and startup courses', '2026-02-04 22:04:38'),
(4, 'Personal Development', 'personal-development', 'Self-improvement and life skills', '2026-02-04 22:04:38'),
(5, 'Accessibility', 'accessibility', 'Accessibility tools and techniques', '2026-02-04 22:04:38');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `certificate_number` varchar(50) NOT NULL,
  `issued_at` datetime DEFAULT current_timestamp(),
  `pdf_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'approved',
  `certificate_code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `user_id`, `course_id`, `certificate_number`, `issued_at`, `pdf_path`, `status`, `certificate_code`) VALUES
(1, 320, 9, 'DADE-2026-0E3938', '2026-02-06 11:06:22', NULL, 'approved', 'DADE-5BA2579B204E');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `level` enum('beginner','intermediate','advanced','expert') DEFAULT 'beginner',
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `cover_image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `is_certificate_course` tinyint(1) DEFAULT 0,
  `certificate_status` enum('none','pending','approved','rejected') NOT NULL DEFAULT 'none',
  `final_quiz_lesson_id` int(11) DEFAULT NULL,
  `passing_grade` int(11) DEFAULT 70,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `avg_rating` decimal(2,1) DEFAULT 0.0,
  `review_count` int(11) DEFAULT 0,
  `enrolled_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `instructor_id`, `category_id`, `level`, `title`, `slug`, `description`, `price`, `cover_image`, `status`, `is_certificate_course`, `certificate_status`, `final_quiz_lesson_id`, `passing_grade`, `created_at`, `updated_at`, `avg_rating`, `review_count`, `enrolled_count`) VALUES
(2, 34, 1, 'intermediate', 'Microsoft Office Essentials', 'microsoft-office-essentials', 'Master Word, Excel, and PowerPoint for professional and personal productivity. Includes hands-on exercises and real-world projects.', 0.00, NULL, 'published', 0, 'none', NULL, 70, '2026-02-05 07:40:41', '2026-02-05 18:56:14', 0.0, 0, 0),
(6, 34, 5, 'beginner', 'Introduction to Assistive Technology', 'introduction-to-assistive-technology', 'Discover tools and technologies that make digital content accessible. Learn about screen readers, voice control, and more.', 0.00, NULL, 'published', 0, 'none', NULL, 70, '2026-02-05 07:40:41', '2026-02-06 17:08:37', 0.0, 0, 1),
(7, 34, 1, 'advanced', 'Web Development Basics', 'web-development-basics', 'Learn HTML, CSS, and JavaScript fundamentals to build your first website. Beginner-friendly with step-by-step guidance.', 0.00, NULL, 'published', 0, 'none', NULL, 70, '2026-02-05 07:40:41', '2026-02-05 18:56:14', 0.0, 0, 0),
(9, 320, 5, 'intermediate', 'TEST COURSE', 'test-course', 'TEST COURSETEST COURSETEST COURSETEST COURSETEST COURSETEST COURSETEST COURSE', 2000.00, 'course_1770321463_6984f637ba1c8.jpg', 'published', 1, 'approved', NULL, 70, '2026-02-05 18:42:27', '2026-02-06 11:33:41', 3.0, 0, 0),
(10, 320, 1, 'advanced', 'SECOND TEST COURSE', 'second-test-course', 'SECOND TEST COURSE SECOND TEST COURSE SECOND TEST COURSE', 30000.00, 'course_1770378418_6985d4b283cb0.jpg', 'published', 1, 'pending', NULL, 70, '2026-02-06 11:46:58', '2026-02-06 11:59:08', 0.0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `course_ratings`
--

CREATE TABLE `course_ratings` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_forms`
--

CREATE TABLE `custom_forms` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `success_message` text DEFAULT NULL,
  `auto_reply_subject` varchar(255) DEFAULT NULL,
  `auto_reply_body` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_forms`
--

INSERT INTO `custom_forms` (`id`, `title`, `description`, `banner_image`, `success_message`, `auto_reply_subject`, `auto_reply_body`, `created_by`, `created_at`) VALUES
(6, 'DADE Foundation Empowerment Hub JOB READINESS TRAINING REGISTRATION', 'Your Journey to Opportunity Starts Here. We are excited to help you achieve your career goals. Please fill out this form to register for our upcoming training.', '', 'Thank You! Your Submission Have Been Received.', 'JOB READINESS TRAINING', '<table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color:#f7f9fb;padding:20px 0;\">\r\n    <tr>\r\n      <td>\r\n        <table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"background-color:#ffffff;border-radius:8px;overflow:hidden;\">\r\n          \r\n          <!-- HEADER -->\r\n          <tr>\r\n            <td align=\"center\" style=\"background-color:#005F73;padding:30px 20px;color:#ffffff;\">\r\n              <h1 style=\"margin:0;font-size:24px;font-weight:bold;\">DADE Foundation Empowerment Hub</h1>\r\n              <p style=\"margin:5px 0 0;font-size:15px;\">Your Journey to Opportunity Starts Here</p>\r\n            </td>\r\n          </tr>\r\n          \r\n          <!-- CONTENT -->\r\n          <tr>\r\n            <td style=\"padding:30px 25px;font-size:16px;line-height:1.6;\">\r\n              <h2 style=\"color:#005F73;margin-top:0;font-size:20px;\">Hello,</h2>\r\n              <p>Thank you for registering for our <strong>Job Readiness Training</strong>! 🎉</p>\r\n              <p>We’re truly excited to have you on board. This training is designed to help you gain the skills, confidence and insights you need to unlock new career opportunities.</p>\r\n\r\n              <p><strong>Here’s what happens next:</strong></p>\r\n              <ul>\r\n                <li>We’ll review your registration details and send you updates about the training schedule.</li>\r\n                <li>You’ll receive important resources and preparation tips in the coming days.</li>\r\n                <li>If you indicated any accessibility needs, our team will reach out to ensure full support.</li>\r\n              </ul>\r\n\r\n              <p>If you have questions in the meantime, simply reply to this email — our team is happy to help.</p>\r\n\r\n              <p style=\"text-align:center;\">\r\n                <a href=\"https://dadefoundation.com/\" style=\"display:inline-block;background-color:#005F73;color:#ffffff;text-decoration:none;padding:14px 26px;border-radius:5px;font-size:16px;font-weight:bold;margin:20px 0;\">Visit Our Website</a>\r\n              </p>\r\n\r\n              <p>We’re thrilled to support you on your journey.<br>\r\n              Warm regards,<br>\r\n              <strong>The DADE Foundation Empowerment Hub Team</strong></p>\r\n            </td>\r\n          </tr>\r\n\r\n          <!-- SOCIAL MEDIA ICONS -->\r\n          <tr>\r\n            <td align=\"center\" style=\"padding:20px 10px;background-color:#f1f1f1;\">\r\n              <p style=\"font-size:14px;color:#333;margin-bottom:10px;\">Connect with us:</p>\r\n              <a href=\"https://web.facebook.com/dadefoundationng\" style=\"margin:0 5px;display:inline-block;\">\r\n                <img src=\"https://cdn-icons-png.flaticon.com/512/733/733547.png\" alt=\"Facebook\" width=\"30\" height=\"30\" style=\"vertical-align:middle;\">\r\n              </a>\r\n              <a href=\"https://x.com/DADEFoundation\" style=\"margin:0 5px;display:inline-block;\">\r\n                <img src=\"https://cdn-icons-png.flaticon.com/512/733/733579.png\" alt=\"Twitter\" width=\"30\" height=\"30\" style=\"vertical-align:middle;\">\r\n              </a>\r\n              <a href=\"https://www.instagram.com/dadefoundation/\" style=\"margin:0 5px;display:inline-block;\">\r\n                <img src=\"https://cdn-icons-png.flaticon.com/512/2111/2111463.png\" alt=\"Instagram\" width=\"30\" height=\"30\" style=\"vertical-align:middle;\">\r\n              </a>\r\n              <a href=\"https://www.linkedin.com/company/dadefoundation/\r\n\" style=\"margin:0 5px;display:inline-block;\">\r\n                <img src=\"https://cdn-icons-png.flaticon.com/512/174/174857.png\" alt=\"LinkedIn\" width=\"30\" height=\"30\" style=\"vertical-align:middle;\">\r\n              </a>\r\n              <a href=\"https://youtube.com\" style=\"margin:0 5px;display:inline-block;\">\r\n                <img src=\"https://cdn-icons-png.flaticon.com/512/1384/1384060.png\" alt=\"YouTube\" width=\"30\" height=\"30\" style=\"vertical-align:middle;\">\r\n              </a>\r\n            </td>\r\n          </tr>\r\n\r\n          <!-- FOOTER -->\r\n          <tr>\r\n            <td align=\"center\" style=\"background-color:#f1f1f1;padding:15px;font-size:13px;color:#666666;\">\r\n              © 2025 DADE Foundation | Empowerment Hub <br>\r\n              Follow us on social media for updates and tips.\r\n            </td>\r\n          </tr>\r\n\r\n        </table>\r\n      </td>\r\n    </tr>\r\n  </table>', 34, '2025-09-28 18:19:38'),
(8, 'test', 'jfddjhfhjf', '', 'dhdf', 'Join Our Official DADE Foundation Volunteer WhatsApp Group', ' <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n        <tr>\r\n            <td style=\"padding: 20px 0;\">\r\n                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"background-color: #ffffff; border-collapse: collapse; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;\">\r\n                    \r\n                    <!-- Header with Logo -->\r\n                    <tr>\r\n                        <td align=\"center\" style=\"padding: 30px 20px; background-color: #005F73;\">\r\n                            <h1 style=\"color: white;\">DADE FOUNDATION </h1>\r\n                        </td>\r\n                    </tr>\r\n                    \r\n                    <!-- Main Content Body -->\r\n                    <tr>\r\n                        <td style=\"padding: 40px 30px; color: #343a40; font-size: 16px; line-height: 1.7;\">\r\n                            \r\n                            <h2 style=\"color: #005F73; font-family: Georgia, serif; margin: 0 0 20px 0;\">Hello There,</h2>\r\n                            \r\n                            <p style=\"margin: 0 0 20px 0;\">Warm greetings from DADE Foundation. We\'re delighted to have you as part of our growing volunteer community. To make communication easier and keep you updated on upcoming activities, events, and opportunities, we\'ve created an official WhatsApp group for all registered volunteers.</p>\r\n                            \r\n                            <p style=\"margin: 0 0 25px 0;\">You can join the group using the button below:</p>\r\n                            \r\n                            <!-- Themed Button -->\r\n                            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n                                <tr>\r\n                                    <td align=\"center\" style=\"padding-bottom: 30px;\">\r\n                                        <a href=\"https://chat.whatsapp.com/Gp22or4pPm0Dp6AhZDFHmY\" target=\"_blank\" style=\"display: inline-block; padding: 15px 35px; font-size: 18px; color: #005F73; background-color: #FFB703; border-radius: 50px; text-decoration: none; font-weight: bold;\">\r\n                                            Join WhatsApp Group\r\n                                        </a>\r\n                                    </td>\r\n                                </tr>\r\n                            </table>\r\n\r\n                            <p style=\"margin: 0 0 20px 0; font-size: 14px; color: #555;\">Please note that this link is exclusive to registered volunteers. We kindly ask that you do not share it outside the community. We look forward to working together to create meaningful impact through service and inclusion.</p>\r\n                            \r\n                            <p style=\"margin: 30px 0 0 0;\">Warm regards,</p>\r\n                            <p style=\"margin: 5px 0 0 0; font-weight: bold; color: #005F73;\">Volunteer Management Team</p>\r\n                            <p style=\"margin: 0; font-weight: bold; color: #005F73;\">DADE Foundation</p>\r\n                        </td>\r\n                    </tr>\r\n\r\n                    <!-- Footer -->\r\n                    <tr>\r\n                        <td align=\"center\" style=\"padding: 20px 30px; background-color: #005F73; color: #FFEFC8; font-size: 12px; line-height: 1.5;\">\r\n                            <p style=\"margin: 0 0 5px 0;\"><strong>Dam and Deb Youth Empowerment and People with disability Foundation</strong></p>\r\n                            <p style=\"margin: 0;\">\r\n                                <a href=\"https://www.dadefoundation.com\" target=\"_blank\" style=\"color: #FFB703; text-decoration: none;\">www.dadefoundation.com</a>\r\n                            </p>\r\n                        </td>\r\n                    </tr>\r\n\r\n                </table>\r\n            </td>\r\n        </tr>\r\n    </table>', 34, '2025-10-04 11:39:48'),
(12, 'Snail Farming for Sustainable Livelihoods Training Registration Form', '', '', 'Your submission have been recieved', 'Youre IN! Snail Farming Masterclass Confirmation (WhatsApp Link Enclosed)', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n<head>\r\n  <meta charset=\"utf-8\">\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\r\n  <title>Snail Farming Masterclass Confirmation</title>\r\n</head>\r\n<body style=\"margin:0;padding:0;background-color:#f4f6f8;font-family:Arial,sans-serif\">\r\n  <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n    <tr>\r\n      <td align=\"center\" style=\"padding:24px 16px\">\r\n        <table role=\"presentation\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"max-width:600px;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,0.08)\">\r\n          <tr>\r\n            <td style=\"padding:24px 32px;background:linear-gradient(90deg,#e67e22 0%,#8dbe4f 100%)\">\r\n              <h1 style=\"margin:0;color:#ffffff;font-size:20px;line-height:1.2\">Youre IN Snail Farming Masterclass Confirmation WhatsApp Link Enclosed</h1>\r\n            </td>\r\n          </tr>\r\n          <tr>\r\n            <td style=\"padding:28px 32px\">\r\n              <p style=\"margin:0 0 16px 0;color:#111827;font-size:16px;line-height:1.5\">Hello</p>\r\n\r\n              <p style=\"margin:0 0 16px 0;color:#111827;font-size:16px;line-height:1.5\">\r\n                Great news Your spot for the Snail Farming Masterclass on Saturday Dec 13th at 600 PM WAT is confirmed\r\n              </p>\r\n\r\n              <p style=\"margin:0 0 16px 0;color:#111827;font-size:16px;line-height:1.5;font-weight:600\">\r\n                ACTION REQUIRED CLASS IS ON WHATSAPP\r\n              </p>\r\n\r\n              <p style=\"margin:0 0 20px 0;color:#111827;font-size:15px;line-height:1.5\">\r\n                To ensure the best lagfree training experience and easy documentation we are running the Masterclass in a dedicated WhatsApp Group No Zoom Meet needed\r\n              </p>\r\n\r\n              <p style=\"margin:0 0 18px 0;color:#111827;font-size:15px;line-height:1.5;font-weight:700\">\r\n                This is where the class happens\r\n              </p>\r\n\r\n              <div style=\"text-align:left;margin-bottom:24px\">\r\n                <a href=\"https://chat.whatsapp.com/FzKAjaJdyAG9dhHoppPxqQ\" style=\"display:inline-block;padding:12px 18px;border-radius:6px;background:#8dbe4f;color:#ffffff;text-decoration:none;font-weight:600;font-size:15px\">\r\n                  JOIN THE WHATSAPP CLASS GROUP NOW\r\n                </a>\r\n              </div>\r\n\r\n              <p style=\"margin:0 0 16px 0;color:#374151;font-size:14px;line-height:1.5\">\r\n                Note This group is temporary and will be deleted on January 30th 2026 Please download all materials before then\r\n              </p>\r\n\r\n              <p style=\"margin:0 0 6px 0;color:#374151;font-size:14px;line-height:1.5\">\r\n                We look forward to seeing you\r\n              </p>\r\n\r\n              <p style=\"margin:18px 0 0 0;color:#374151;font-size:14px;line-height:1.5\">\r\n                Best regards\r\n                <br>\r\n                The DADE Foundation Programmes Team\r\n              </p>\r\n            </td>\r\n          </tr>\r\n\r\n          <tr>\r\n            <td style=\"padding:16px 32px;background:#f9fafb;color:#9ca3af;font-size:12px\">\r\n              <p style=\"margin:0\">This message was sent by The DADE Foundation Programmes Team</p>\r\n            </td>\r\n          </tr>\r\n\r\n        </table>\r\n      </td>\r\n    </tr>\r\n  </table>\r\n</body>\r\n</html>\r\n', 34, '2025-11-25 22:10:07'),
(13, 'TRAINERS AND FACILITATORS FORM', 'TRAINERS AND FACILITATORS FORM', '', '', '', '', 34, '2025-12-11 22:25:44');

-- --------------------------------------------------------

--
-- Table structure for table `custom_form_fields`
--

CREATE TABLE `custom_form_fields` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `field_type` enum('text','email','phone','textarea','checkbox','radio','select') NOT NULL,
  `options` text DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `field_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_form_fields`
--

INSERT INTO `custom_form_fields` (`id`, `form_id`, `label`, `field_type`, `options`, `is_required`, `field_order`) VALUES
(14, 6, 'Full Name', 'text', '', 1, 0),
(16, 6, 'Email Address', 'email', '', 1, 0),
(17, 6, 'Phone Number', 'phone', '', 1, 0),
(19, 6, 'Do you identify as a person with a disability?', 'radio', 'Yes, No, Prefer not to say', 1, 0),
(20, 6, 'What are you hoping to gain from this training? (Brief response)', 'textarea', '', 1, 0),
(23, 6, 'How did you hear about this program?', 'checkbox', 'Social Media, Friend/Family, Organization, Other:', 1, 0),
(24, 6, 'Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.', 'textarea', '', 1, 0),
(27, 8, 'Email Address', 'email', '', 1, 0),
(42, 12, 'Full Name', 'text', '', 1, 0),
(43, 12, 'Email Address', 'email', '', 1, 0),
(44, 12, 'Phone Number', 'phone', '', 1, 0),
(45, 12, 'Location (City/State)', 'text', '', 1, 0),
(46, 12, 'Disability Status', 'radio', 'Person With Disability, Not Person With Disability', 1, 0),
(47, 12, 'Experience Level', 'radio', 'Beginner, Intermediate, Advanced', 1, 0),
(48, 12, 'Accessibility Needs: Do you require Sign Language Interpretation?', 'text', '', 1, 0),
(49, 12, 'What do you hope to learn from this training?', 'text', '', 1, 0),
(50, 12, 'Business Intent: Do you intend to start a snail farming business?', 'radio', 'Yes, Maybe, No', 1, 0),
(51, 12, 'Confirmation: I would like to receive updates via email or WhatsApp', 'radio', 'Yes', 1, 0),
(52, 13, 'Full Name', 'text', '', 1, 0),
(53, 13, 'Email Address', 'email', '', 1, 0),
(54, 13, 'Phone Number', 'phone', '', 1, 0),
(55, 13, 'Location (City/State)', 'text', '', 1, 0),
(56, 13, 'Expertise Sector', 'select', 'Education, Healthcare, Agriculture, Medical, Law, Entrepreneur, Tourism, Others', 1, 0),
(57, 13, 'What do you know about disability and inclusion?', 'textarea', '', 1, 0),
(59, 13, 'Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?', 'radio', 'Yes, No', 1, 0),
(60, 13, 'if you answered yes for the question before this please specify and  describe your experience in training/facilitation', 'textarea', '', 0, 0),
(62, 13, 'What is your availability for conducting training sessions?', 'text', '', 1, 0),
(63, 13, 'Are you willing to commit to a minimum number of training sessions?', 'radio', 'Yes, No', 1, 0),
(65, 13, 'Is there any additional information you would like to share with us?', 'text', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `custom_form_submissions`
--

CREATE TABLE `custom_form_submissions` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `submission_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `submitted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_form_submissions`
--

INSERT INTO `custom_form_submissions` (`id`, `form_id`, `submission_data`, `submitted_at`) VALUES
(4, 6, '{\"Full Name\": \"SHUAIB ABDOOL\", \"Phone Number\": \"08122598372\", \"Email Address\": \"shuaibabdul192@gmail.com\", \"How did you hear about this program?\": \"Friend/Family, Organization, Other:\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"nnbmj,jbmn\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"hjbkjnbm\"}', '2025-09-28 18:36:01'),
(5, 6, '{\"Full Name\": \"SHUAIB ABDOOL\", \"Phone Number\": \"08122598372\", \"Email Address\": \"shuaibabdul192@gmail.com\", \"How did you hear about this program?\": \"Social Media, Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"jkjhjhkm\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"hj,bhljhgb\"}', '2025-09-28 18:43:20'),
(6, 6, '{\"Full Name\": \"SHUAIB ABDOOL\", \"Phone Number\": \"08122598372\", \"Email Address\": \"shuaibabdul192@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"b\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"b ,nhj,nmb\"}', '2025-09-28 18:44:04'),
(7, 6, '{\"Full Name\": \"SHUAIB ABDOOL\", \"Phone Number\": \"08122598372\", \"Email Address\": \"shuaibabdul192@gmail.com\", \"How did you hear about this program?\": \"Social Media, Friend/Family, Other:\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"shjddbfhjd\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"jkddjdfhfjhge\"}', '2025-09-29 17:57:34'),
(8, 6, '{\"Full Name\": \"Abdulazeez Muideen\", \"Phone Number\": \"08127248097\", \"Email Address\": \"olamiolami57@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To learn about how to craft documents\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Mobile data\"}', '2025-09-30 13:11:18'),
(9, 6, '{\"Full Name\": \"Samson Ayodeji\", \"Phone Number\": \"09116033935\", \"Email Address\": \"ayodejis84@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"How to package my CV and some knowhow of job interviews.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-09-30 14:20:48'),
(10, 6, '{\"Full Name\": \"Komolafe Praise Oluwatomilola\", \"Phone Number\": \"09152825587\", \"Email Address\": \"komolafepraise1@gmail.com\", \"How did you hear about this program?\": \"Social Media, Friend/Family\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Experience on how to deal with people who have disabilities\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No accessibility problems\"}', '2025-09-30 14:44:37'),
(11, 6, '{\"Full Name\": \"Bamodu Oluwole Adesakin\", \"Phone Number\": \"08141171876\", \"Email Address\": \"magicdc321@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-09-30 14:46:14'),
(12, 6, '{\"Full Name\": \"Esther Oluyemi Essien\", \"Phone Number\": \"08103235871\", \"Email Address\": \"estheressien0201@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I hope to gain how to write an outstanding CV/resume to secure a good job\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes.\\r\\nSign language interpreter\"}', '2025-09-30 15:57:15'),
(13, 6, '{\"Full Name\": \"Chioma Joan Okeke\", \"Phone Number\": \"08118573017\", \"Email Address\": \"ochioma408@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To learn about CV and résumé\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Data subscription. Th\"}', '2025-09-30 16:15:19'),
(14, 6, '{\"Full Name\": \"Bolarinwa Segun Michael\", \"Phone Number\": \"08134666698\", \"Email Address\": \"segunmichaelbolarinwa4@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Experience\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-09-30 17:18:44'),
(15, 6, '{\"Full Name\": \"Juliet Nwaeze\", \"Phone Number\": \"08065940218\", \"Email Address\": \"julietnwaeze7@gmail.com\", \"How did you hear about this program?\": \"Social Media, Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Interview technique\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-01 06:30:02'),
(16, 6, '{\"Full Name\": \"Anukwu Izuchukwu Noah\", \"Phone Number\": \"07037987558\", \"Email Address\": \"noahconsult2018@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Hoping to learn how to do better in cv writting, do well in interview, and learn from the professionals skills that will be useful to me in life\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I&#039;m a person with visual impairment readable materials and clear audio will be highly helpful\"}', '2025-10-01 06:32:22'),
(17, 6, '{\"Full Name\": \"Abekwen Cynthia\", \"Phone Number\": \"08125857201\", \"Email Address\": \"abekwencynthia@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I’m hoping to gain new skills, knowledge, and practical experience that will help me grow personally and professionally.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes, I would appreciate [describe the need, e.g., written materials in large print / wheelchair access / a quiet space, etc.], to help me fully participate in the training.\"}', '2025-10-01 06:32:46'),
(18, 6, '{\"Full Name\": \"Joshua Achi Maji\", \"Phone Number\": \"07036047239\", \"Email Address\": \"majimaji49@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To master CV writing and know how to answer difficult questions during interviews\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-01 06:45:47'),
(19, 6, '{\"Full Name\": \"Auwalu Dankano\", \"Phone Number\": \"07068325194\", \"Email Address\": \"auwaludankano2013@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I want to acquire skills necessary to secure stable employment, such as resume writing, interview preparation and job placement assistance etc\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes, I need an interpreter during the session\"}', '2025-10-01 06:50:01'),
(20, 6, '{\"Full Name\": \"Esther Isaac\", \"Phone Number\": \"08036711303\", \"Email Address\": \"estherisaac2016@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To gain more knowledge on how to answer questions during interviews and how to write resume. To also know the difference between CV and resume.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Not really\"}', '2025-10-01 06:58:54'),
(21, 6, '{\"Full Name\": \"Hamza Alhassan Dantata\", \"Phone Number\": \"08148783565\", \"Email Address\": \"hamzaalhassandantata@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I help me to gain for my career professioal and secure job opportunities\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Interpretation of the Deaf\"}', '2025-10-01 07:10:58'),
(22, 6, '{\"Full Name\": \"Muhammad Kabir Abubakar\", \"Phone Number\": \"08099051569\", \"Email Address\": \"mkabirabubakar@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To gain more information and knowledge about the CV / Resume creation\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Sign language interpretation\"}', '2025-10-01 07:14:53'),
(23, 6, '{\"Full Name\": \"Isah Salamatu\", \"Phone Number\": \"08038802192\", \"Email Address\": \"isahsalamatu02@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"&quot;I&#039;m hoping to better understand my own strengths and areas for growth, particularly in leadership and communication, so I can be more effective in collaborative environments.&quot;\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes — I am a hearing impairment and would appreciate a quiet room or assistive listening devices.&quot;\"}', '2025-10-01 07:34:30'),
(24, 6, '{\"Full Name\": \"Ahmed Deedat\", \"Phone Number\": \"07033484883\", \"Email Address\": \"deedatahmed42@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Joining Dade Foundation will give me the opportunity to gain valuable knowledge, mentorship, and practical skills that will help me grow personally and professionally. It will also connect me with networks and resources that can support my career and future goals.”\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No, I do not have any accessibility needs at the moment.\"}', '2025-10-01 07:35:41'),
(25, 6, '{\"Full Name\": \"IDOWU OYEWOLE OLANREWAJU\", \"Phone Number\": \"07061969789\", \"Email Address\": \"idowuoyewole1992@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I am hoping to have more knowledge on how to write an application letter and how to handle an interview\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Not really than Internet network and data\"}', '2025-10-01 07:48:56'),
(26, 6, '{\"Full Name\": \"Amina Musa\", \"Phone Number\": \"08105236919\", \"Email Address\": \"amina91musa@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I am hoping to gain a deeper understanding of the key skills and knowledge this training offers so I can apply them effectively in my role.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yea I would benefit from having environmental of the training or event are inclusive and accessibility.\"}', '2025-10-01 08:05:23'),
(27, 6, '{\"Full Name\": \"Rayyanu Alhassan\", \"Phone Number\": \"08036536949\", \"Email Address\": \"alhassanrayyanu63@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"I have to go for it to me and training in the creating a job in my life my rules and rulegulation to get the training\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes  accessibility I have to do that capture the job in the investment in my room is the best for your about two months is a very happy new job and a great day and I will tell you that I am in a training in the business.\"}', '2025-10-01 08:16:29'),
(28, 6, '{\"Full Name\": \"IBRAHIM ZANTAGNA HAPPINESS\", \"Phone Number\": \"08189399518\", \"Email Address\": \"zantagna@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Please help me a interpreter, I&#039;m a Deaf. \\r\\nYou can do to make our conversation more comfort or help for me. Thanks\"}', '2025-10-01 09:05:19'),
(29, 6, '{\"Full Name\": \"Vivian Timothy\", \"Phone Number\": \"07057653206\", \"Email Address\": \"viviantimothy311@gamil.coc\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes, I&#039;m hoping to gain the program\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I&#039;m a Deaf please i need your help with interpreter\"}', '2025-10-01 09:12:53'),
(30, 6, '{\"Full Name\": \"Philomena Edomwandagbon\", \"Phone Number\": \"08073981445\", \"Email Address\": \"phinaba75@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Finding\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Well I&#039;m a physically challenged person and I also need data to participate fully in this program and also need a mobility aid\"}', '2025-10-01 09:58:49'),
(31, 6, '{\"Full Name\": \"Ibironke Michael Olanrewaju\", \"Phone Number\": \"+2348035655652\", \"Email Address\": \"ibironkemichael247@gmail.com\", \"How did you hear about this program?\": \"Social Media, Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge on how to successfully gain employment\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"None\"}', '2025-10-01 10:21:16'),
(32, 6, '{\"Full Name\": \"Ogundahunsi wemimo\", \"Phone Number\": \"08168714839\", \"Email Address\": \"temidoladahunsi@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I will to gain more experience\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-01 10:47:54'),
(33, 6, '{\"Full Name\": \"Solomon John Akoji\", \"Phone Number\": \"+2348038315479\", \"Email Address\": \"Akojisolomon@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Skills upgrade\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-01 10:58:20'),
(34, 6, '{\"Full Name\": \"Chelsea Solomon\", \"Phone Number\": \"09033162533\", \"Email Address\": \"chelseasolomon815@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes I want to get a gain to training\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-01 11:32:47'),
(35, 6, '{\"Full Name\": \"Fatima Shamsiyyya Abdullahi\", \"Phone Number\": \"08078381424\", \"Email Address\": \"fatimaabdullahi106@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To gain skills needed to stand out when I am applying for jobs\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-01 11:32:48'),
(36, 6, '{\"Full Name\": \"Paul Jael\", \"Phone Number\": \"08164835684\", \"Email Address\": \"jaelpaul2020.jp@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I&#039;m hoping to aquire knowlege that will better my chances in the future.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Nil\"}', '2025-10-01 12:00:11'),
(37, 6, '{\"Full Name\": \"Komolafe Esther Oluwatayo\", \"Phone Number\": \"07049897358\", \"Email Address\": \"estherkomolafe01@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"I hope to get the best from the programme in order to brighten my horizon and bridge the communication gap for the deaf community as a Sign Language Interpreter.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-01 14:21:36'),
(38, 6, '{\"Full Name\": \"Agatha Kuyet Paul\", \"Phone Number\": \"07063246805\", \"Email Address\": \"paulagatha45@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Awareness\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Nil\"}', '2025-10-01 14:33:25'),
(39, 6, '{\"Full Name\": \"Abubakar Ibrahim garba\", \"Phone Number\": \"07086077702\", \"Email Address\": \"abubakaribrahimgarba5@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I need attached\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Me seek find\"}', '2025-10-01 14:40:52'),
(40, 6, '{\"Full Name\": \"Musa Maxwell Kure\", \"Phone Number\": \"0803 200 2445\", \"Email Address\": \"maxwellkure@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Optimization of my Resume and all that the training has to offer\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"N/A\"}', '2025-10-01 14:47:15'),
(41, 6, '{\"Full Name\": \"Bello Auwal\", \"Phone Number\": \"09123022831\", \"Email Address\": \"belloauwal232@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Confidence,being outspoken and set my résumé’ right\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes I do, I need a stable internet in order to complete all the training sessions.\"}', '2025-10-01 14:52:47'),
(42, 6, '{\"Full Name\": \"Fatima Muhammad Suleiman\", \"Phone Number\": \"08062850418\", \"Email Address\": \"xaraahhbabayo@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-01 16:52:05'),
(43, 6, '{\"Full Name\": \"Maryann ijeoma Izuegbunam\", \"Phone Number\": \"08082682851\", \"Email Address\": \"maryannizuegbunam@hotmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Learn new things and secure a job easily\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"video caption, interpreter.\"}', '2025-10-01 17:33:37'),
(44, 6, '{\"Full Name\": \"Muhammad Anas Nuhu\", \"Phone Number\": \"07038986367\", \"Email Address\": \"muhammadanaskano466@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I hope to acquire knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"May be\"}', '2025-10-01 18:27:11'),
(45, 6, '{\"Full Name\": \"Fauziyah Nuhu Umar\", \"Phone Number\": \"09037075553\", \"Email Address\": \"fauxee050@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Prefer not to say\", \"What are you hoping to gain from this training? (Brief response)\": \"I expect to gain practical knowledge that will prepare me for the empowerment opportunities provided by Dade Foundation.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-01 19:19:35'),
(46, 6, '{\"Full Name\": \"USMAN\", \"Phone Number\": \"08107369433\", \"Email Address\": \"larabausman10@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Training and Empowerment\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Wheelchair accessibility\"}', '2025-10-01 19:48:56'),
(47, 6, '{\"Full Name\": \"Helen sabastine\", \"Phone Number\": \"08052941564\", \"Email Address\": \"helensabastine81@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I hope to achieve or learn more about how to bake\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-01 20:20:29'),
(48, 6, '{\"Full Name\": \"OJO-OLUDARE IYANU OLUWASEUN\", \"Phone Number\": \"08064880203\", \"Email Address\": \"iyanuojooludare@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Be able to build my skills\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Bold print\"}', '2025-10-01 20:42:32'),
(49, 6, '{\"Full Name\": \"TOKUNBO FAMILUSI\", \"Phone Number\": \"08038959786\", \"Email Address\": \"familusitokunbo@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"How to write a perfect cv\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-01 21:09:09'),
(50, 6, '{\"Full Name\": \"Patricia Nenadi Pam\", \"Phone Number\": \"08032896665\", \"Email Address\": \"nenadipam@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Gain skills on Resume/CV writing,gain knowledge on interview techniques and presentation skills.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Nil\"}', '2025-10-01 22:14:00'),
(51, 6, '{\"Full Name\": \"Patricia Nenadi Pam\", \"Phone Number\": \"08032896665\", \"Email Address\": \"nenadipam@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Gain skills on Resume/CV writing,gain knowledge on interview techniques and presentation skills.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Nil\"}', '2025-10-01 22:14:03'),
(52, 6, '{\"Full Name\": \"Grace Oluwayemisi Anafi\", \"Phone Number\": \"09068483532\", \"Email Address\": \"o.anafigrace@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"No\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-01 22:32:17'),
(53, 6, '{\"Full Name\": \"FATIMA ABDULHAMEED MUHAMMAD\", \"Phone Number\": \"08030539655\", \"Email Address\": \"fatimaabdulhameed66@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-01 22:48:09'),
(54, 6, '{\"Full Name\": \"Ibrahim Hamisu Abdullahi\", \"Phone Number\": \"08145470895\", \"Email Address\": \"ibrahimhamisuhafsat@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Work or something that can help me get my health supplies\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-02 02:46:00'),
(55, 6, '{\"Full Name\": \"NYIZOGEMBI AARON\", \"Phone Number\": \"09121257629\", \"Email Address\": \"nyizogembiaaron64@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To build up my skill and empowerment\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes, Crutches accessible\"}', '2025-10-02 03:37:38'),
(56, 6, '{\"Full Name\": \"Philomena Edomwandagbon\", \"Phone Number\": \"08073981445\", \"Email Address\": \"phinaba75@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Funds to start business\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I&#039;m on crutches\"}', '2025-10-02 06:57:23'),
(57, 6, '{\"Full Name\": \"Emelyne shemezimana\", \"Phone Number\": \"+25776856185\", \"Email Address\": \"emelyne1shemeza@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To get experiences\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yess I have small smartphone, for accepting internet full, it is impossible for me\"}', '2025-10-02 07:16:25'),
(58, 6, '{\"Full Name\": \"David Umar\", \"Phone Number\": \"09061689580\", \"Email Address\": \"kingdavegodstime@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To change my life\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-02 07:37:12'),
(59, 6, '{\"Full Name\": \"Abubakar Halima\", \"Phone Number\": \"08108195772\", \"Email Address\": \"halimaabubakar5772@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Have the knowledge and learn more about Resume and CV writing\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No, I have laptop to learn with\"}', '2025-10-02 08:20:36'),
(60, 6, '{\"Full Name\": \"HALIMA SALEH IBRAHIM\", \"Phone Number\": \"08038638974\", \"Email Address\": \"salehhalima2020@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Any one\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"None\"}', '2025-10-02 08:25:24'),
(61, 6, '{\"Full Name\": \"Ntchachwe Delphine\", \"Phone Number\": \"677656661\", \"Email Address\": \"ntchachwe1@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"How to pray\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes mobility\"}', '2025-10-02 08:49:43'),
(62, 6, '{\"Full Name\": \"Charles Okoro\", \"Phone Number\": \"09061554156\", \"Email Address\": \"charlesemeka147@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Skills in acquiring a remote job in data analytics\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-02 08:51:52'),
(63, 6, '{\"Full Name\": \"Obi Christiana\", \"Phone Number\": \"08133647699\", \"Email Address\": \"oc1470383@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To learn more in order to impact more knowledge to those who needs and be useful to myself\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Not really but I am pleasing on transportation for physical meetings or data in order to be fully active\"}', '2025-10-02 08:57:47'),
(64, 6, '{\"Full Name\": \"Haruna Babangida Iliyasu\", \"Phone Number\": \"08052232991\", \"Email Address\": \"mrabbey93@gmail.com\", \"How did you hear about this program?\": \"Social Media, Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To improve my future business and Skil development\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes I need interpreter to enable deaf people understand clearly and successful\"}', '2025-10-02 09:12:10'),
(65, 6, '{\"Full Name\": \"Ohize Rofiat Ozohu\", \"Phone Number\": \"09034227000\", \"Email Address\": \"ohizerofiat@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I want to gain alot by been inspire and get work as soon as possible\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-02 09:23:39'),
(66, 6, '{\"Full Name\": \"Abigail Micheal\", \"Phone Number\": \"07086154073\", \"Email Address\": \"abgmicheal3@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Training on how to get a job\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-02 09:24:44'),
(67, 6, '{\"Full Name\": \"Aisha Aminu\", \"Phone Number\": \"07033985685\", \"Email Address\": \"aminuaisha5685@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Hoping to gain valuable knowledge on how to maximize carrier opportunities and progression\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"To make it easy for me to go through the materials I will prefer it in word document\"}', '2025-10-02 09:37:05'),
(68, 6, '{\"Full Name\": \"Ibrahim Olalekan\", \"Phone Number\": \"07032739803\", \"Email Address\": \"olalekanibrahim911@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I want to gain skills to prepare quality CV/resume. I would also like to learn good presentation skills.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I am a screen reader user.\"}', '2025-10-02 10:28:07'),
(69, 6, '{\"Full Name\": \"Adeola Anifowose\", \"Phone Number\": \"08101641025\", \"Email Address\": \"anifowosecrown96@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I hope to gain knowledge on practical experiences\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Maybe data subscription. Thank you\"}', '2025-10-02 11:19:05'),
(70, 6, '{\"Full Name\": \"Hammed risikat olayemi\", \"Phone Number\": \"09030293947\", \"Email Address\": \"hammedriskatu@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I won&#039;t to gain better\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-02 16:42:29'),
(71, 6, '{\"Full Name\": \"Adedolapo kosoko king\", \"Phone Number\": \"08119603888\", \"Email Address\": \"adedolapokosokoking@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Skills\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-02 18:01:47'),
(72, 6, '{\"Full Name\": \"Gift Watchful\", \"Phone Number\": \"08138239162\", \"Email Address\": \"giftwatchful@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Hoping to gain wider range of knowledge on IT skills which will be an added advantage to me as a content creator.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"None\"}', '2025-10-02 18:40:55'),
(73, 6, '{\"Full Name\": \"Khadija Abubakar Abdullahi\", \"Phone Number\": \"08103478319\", \"Email Address\": \"khadija006@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes indeed\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No any other needs.\\r\\nOk\"}', '2025-10-02 18:41:49'),
(74, 6, '{\"Full Name\": \"Hindatu Amin Adam\", \"Phone Number\": \"08069638477\", \"Email Address\": \"hindatuamin1994@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To gain insight on how to write a comprehensive CV\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-02 18:57:25'),
(75, 6, '{\"Full Name\": \"UMAR JIBRIL BINDAWA\", \"Phone Number\": \"08081542030\", \"Email Address\": \"umarjibrilbindawa@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I need u to support our Disability Community in katsina State for the training Skills etc\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Like computer, tailor, others things please\"}', '2025-10-02 19:19:29'),
(76, 6, '{\"Full Name\": \"UMAR JIBRIL BINDAWA\", \"Phone Number\": \"08081542030\", \"Email Address\": \"umarjibrilbindawa@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I need u to support our Disability Community in katsina State for the training Skills etc\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Like computer, tailor, others things please\"}', '2025-10-02 19:20:14'),
(77, 6, '{\"Full Name\": \"Benjamin Dorcas\", \"Phone Number\": \"08115646955\", \"Email Address\": \"benjamindorcas6955@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I will make it\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-03 00:36:43'),
(78, 6, '{\"Full Name\": \"Leo monjok\", \"Phone Number\": \"07066610724\", \"Email Address\": \"leoashaba@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Great knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Just a good gadget and an internet connection\"}', '2025-10-03 06:00:48'),
(79, 6, '{\"Full Name\": \"Maryjane uUunwa Okeke\", \"Phone Number\": \"08060959638\", \"Email Address\": \"mjaneokeke@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I expect to equip myself with relevant skills that will keep me competitive in today’s workforce, enabling me to stay updated and maximize my full potential.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-03 07:58:02'),
(80, 6, '{\"Full Name\": \"Maryjane uUunwa Okeke\", \"Phone Number\": \"08060959638\", \"Email Address\": \"mjaneokeke@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I expect to equip myself with relevant skills that will keep me competitive in today’s workforce, enabling me to stay updated and maximize my full potential.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-03 07:58:04'),
(81, 6, '{\"Full Name\": \"Amina Ado Turajo\", \"Phone Number\": \"09069076403\", \"Email Address\": \"Aminaturajo35@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes I want training business\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes of course\"}', '2025-10-03 14:22:50'),
(82, 6, '{\"Full Name\": \"Amina Ado Turajo\", \"Phone Number\": \"09069076403\", \"Email Address\": \"Aminaturajo35@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Business\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes of course\"}', '2025-10-03 14:33:27'),
(83, 6, '{\"Full Name\": \"Amina Ado Turajo\", \"Phone Number\": \"09069076403\", \"Email Address\": \"Aminaturajo35@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Business\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes of course\"}', '2025-10-03 14:33:35'),
(84, 6, '{\"Full Name\": \"Hussaini Aminu\", \"Phone Number\": \"09038098449\", \"Email Address\": \"hussainiaminu406@gmail.com\", \"How did you hear about this program?\": \"Friend/Family, Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Sir.i am a deaf and I am a student of my sch,I want to my training for development\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I am interested in my program for training development\"}', '2025-10-03 16:11:18'),
(85, 6, '{\"Full Name\": \"KHALID MUSA ISA\", \"Phone Number\": \"08065174906\", \"Email Address\": \"khalidmusaisa@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"A skill that will help me to achieve my Career goals\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-03 20:56:26'),
(86, 6, '{\"Full Name\": \"KHALID MUSA ISA\", \"Phone Number\": \"08065174906\", \"Email Address\": \"khalidmusaisa@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"A skill that will help me to achieve my Career goals\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-03 20:56:34'),
(87, 6, '{\"Full Name\": \"KHALID MUSA ISA\", \"Phone Number\": \"08065174906\", \"Email Address\": \"khalidmusaisa@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"A skill that will help me to achieve my Career goals\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-03 20:56:42'),
(88, 6, '{\"Full Name\": \"Izang Bala Bulus\", \"Phone Number\": \"070 3660 7184\", \"Email Address\": \"izangbalabulus@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To have skills that would better equipped me to function better in life and my community.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I have little knowledge in using social media/plats,with exception of WhatsApp.\"}', '2025-10-03 21:28:08'),
(89, 6, '{\"Full Name\": \"Izang Bala Bulus\", \"Phone Number\": \"070 3660 7184\", \"Email Address\": \"izangbalabulus@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To have skills that would better equipped me to function better in life and my community.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I have little knowledge in using social media/plats,with exception of WhatsApp.\"}', '2025-10-03 21:28:56');
INSERT INTO `custom_form_submissions` (`id`, `form_id`, `submission_data`, `submitted_at`) VALUES
(90, 6, '{\"Full Name\": \"Izang Bala Bulus\", \"Phone Number\": \"070 3660 7184\", \"Email Address\": \"izangbalabulus@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To have skills that would better equipped me to function better in life and my community.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I have little knowledge in using social media/plats,with exception of WhatsApp.\"}', '2025-10-03 21:29:56'),
(91, 6, '{\"Full Name\": \"Wisdom Sossou Peter\", \"Phone Number\": \"07067610926\", \"Email Address\": \"mrwisdomp@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To enquire more knowledge and skills to be able to help and empower others as well\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No not any I can think of now\"}', '2025-10-03 21:54:47'),
(92, 6, '{\"Full Name\": \"Wisdom Sossou Peter\", \"Phone Number\": \"07067610926\", \"Email Address\": \"mrwisdomp@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To enquire more knowledge and skills to be able to help and empower others as well\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No not any I can think of now\"}', '2025-10-03 21:55:04'),
(93, 6, '{\"Full Name\": \"Zainab Yusuf\", \"Phone Number\": \"07038777702\", \"Email Address\": \"zainaby068@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"First of all is how to organise my curriculum vitea and how to give smart response to interview questions that will secure my dream job\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-03 23:09:16'),
(94, 6, '{\"Full Name\": \"Zainab Yusuf\", \"Phone Number\": \"07038777702\", \"Email Address\": \"zainaby068@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"First of all is how to organise my curriculum vitea and how to give smart response to interview questions that will secure my dream job\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-03 23:09:35'),
(95, 6, '{\"Full Name\": \"Attah Folayemi Margaret\", \"Phone Number\": \"08032370453\", \"Email Address\": \"folamag4sure@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To achieve my career goal and to improve my knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-04 00:20:52'),
(96, 6, '{\"Full Name\": \"Attah Folayemi Margaret\", \"Phone Number\": \"08032370453\", \"Email Address\": \"folamag4sure@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To achieve my career goal and to improve my knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes, I need laptop to be able to access the training and participate well\"}', '2025-10-04 00:23:51'),
(97, 6, '{\"Full Name\": \"Attah Folayemi Margaret\", \"Phone Number\": \"08032370453\", \"Email Address\": \"folamag4sure@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To achieve my career goal and to improve my knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes, I need laptop to be able to access the training and participate well\"}', '2025-10-04 00:25:52'),
(98, 6, '{\"Full Name\": \"Hamza Aminu Fagge\", \"Phone Number\": \"07061659483\", \"Email Address\": \"hamzafagge69@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To learn about the practical experience on developing a good CV.\\r\\nInterview skills and job searching techniques.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes.\\r\\nI need mobility aid because I have visuals Impairment.\"}', '2025-10-04 09:01:52'),
(99, 6, '{\"Full Name\": \"Hamza Aminu Fagge\", \"Phone Number\": \"07061659483\", \"Email Address\": \"hamzafagge69@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To learn about the practical experience on developing a good CV.\\r\\nInterview skills and job searching techniques.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes.\\r\\nI need mobility aid because I have visuals Impairment.\"}', '2025-10-04 09:02:32'),
(100, 6, '{\"Full Name\": \"Hamza Aminu Fagge\", \"Phone Number\": \"07061659483\", \"Email Address\": \"hamzafagge69@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To learn about the practical experience on developing a good CV.\\r\\nInterview skills and job searching techniques.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes.\\r\\nI need mobility aid because I have visuals Impairment.\"}', '2025-10-04 09:07:12'),
(101, 6, '{\"Full Name\": \"Hamza Aminu Fagge\", \"Phone Number\": \"07061659483\", \"Email Address\": \"hamzafagge69@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To learn about the practical experience on developing a good CV.\\r\\nInterview skills and job searching techniques.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes.\\r\\nI need mobility aid because I have visuals Impairment.\"}', '2025-10-04 09:10:01'),
(102, 6, '{\"Full Name\": \"Hamza Aminu Fagge\", \"Phone Number\": \"07061659483\", \"Email Address\": \"hamzafagge69@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To learn about the practical experience on developing a good CV.\\r\\nInterview skills and job searching techniques.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes.\\r\\nI need mobility aid because I have visual Impairment.\"}', '2025-10-04 09:12:07'),
(103, 6, '{\"Full Name\": \"Adananu yunusa idiris\", \"Phone Number\": \"09030292551\", \"Email Address\": \"adananyunusa@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 09:35:55'),
(104, 6, '{\"Full Name\": \"Adananu yunusa idiris\", \"Phone Number\": \"09030292551\", \"Email Address\": \"adananyunusa@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 09:36:25'),
(105, 6, '{\"Full Name\": \"Adananu yunusa idiris\", \"Phone Number\": \"09030292551\", \"Email Address\": \"adananyunusa@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 09:36:29'),
(106, 6, '{\"Full Name\": \"Adananu yunusa idiris\", \"Phone Number\": \"09030292551\", \"Email Address\": \"adananyunusa@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 09:36:40'),
(107, 6, '{\"Full Name\": \"Adananu yunusa idiris\", \"Phone Number\": \"09030292551\", \"Email Address\": \"adananyunusa@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 09:36:44'),
(108, 6, '{\"Full Name\": \"Adananu yunusa idiris\", \"Phone Number\": \"09030292551\", \"Email Address\": \"adananyunusa@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 09:36:47'),
(109, 6, '{\"Full Name\": \"Adananu yunusa idiris\", \"Phone Number\": \"09030292551\", \"Email Address\": \"adananyunusa@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 09:37:33'),
(110, 6, '{\"Full Name\": \"Adananu yunusa idiris\", \"Phone Number\": \"09030292551\", \"Email Address\": \"adananyunusa@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 09:37:58'),
(111, 6, '{\"Full Name\": \"Adananu yunusa idiris\", \"Phone Number\": \"09030292551\", \"Email Address\": \"adananyunusa@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 09:44:16'),
(112, 6, '{\"Full Name\": \"HALIMA SALEH IBRAHIM\", \"Phone Number\": \"08038638974\", \"Email Address\": \"salehhalima2020@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To acquire knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"None\"}', '2025-10-04 09:47:34'),
(113, 6, '{\"Full Name\": \"HALIMA SALEH IBRAHIM\", \"Phone Number\": \"08038638974\", \"Email Address\": \"salehhalima2020@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To acquire knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"None\"}', '2025-10-04 09:47:43'),
(114, 6, '{\"Full Name\": \"HALIMA SALEH IBRAHIM\", \"Phone Number\": \"08038638974\", \"Email Address\": \"salehhalima2020@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To acquire knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"None\"}', '2025-10-04 09:47:46'),
(115, 6, '{\"Full Name\": \"Patience\", \"Phone Number\": \"08123126362\", \"Email Address\": \"patiencegideon1@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-04 10:09:07'),
(116, 6, '{\"Full Name\": \"Patience\", \"Phone Number\": \"08123126362\", \"Email Address\": \"patiencegideon1@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-04 10:09:13'),
(117, 6, '{\"Full Name\": \"Patience\", \"Phone Number\": \"08123126362\", \"Email Address\": \"patiencegideon1@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-04 10:09:16'),
(118, 6, '{\"Full Name\": \"Aminu Abdullahi Khalid\", \"Phone Number\": \"08039164582\", \"Email Address\": \"aminuabdullahikhalid9@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Depend on myself\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-04 10:14:53'),
(119, 6, '{\"Full Name\": \"Aminu Abdullahi Khalid\", \"Phone Number\": \"08039164582\", \"Email Address\": \"aminuabdullahikhalid9@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Depend on myself\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-04 10:16:33'),
(120, 6, '{\"Full Name\": \"Aminu Abdullahi Khalid\", \"Phone Number\": \"08039164582\", \"Email Address\": \"aminuabdullahikhalid9@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Depend on myself\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-04 10:17:34'),
(121, 6, '{\"Full Name\": \"Umar Said Muhammad\", \"Phone Number\": \"07087174217\", \"Email Address\": \"uumarsaidmuhammad@gmail.com\", \"How did you hear about this program?\": \"Social Media, Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Good knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Blind\"}', '2025-10-04 10:27:12'),
(129, 6, '{\"Full Name\": \"John.   Paul\", \"Phone Number\": \"07036634326\", \"Email Address\": \"ministerjp2015@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To gain knowledge and skills and serve as advocate for others.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes guide cane, and fund for business\"}', '2025-10-04 11:11:34'),
(132, 6, '{\"Full Name\": \"Nasiru Hamisu Garko\", \"Phone Number\": \"08129117196\", \"Email Address\": \"nasiruhamisugarko@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Skills to compront challenge in working place\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-04 11:18:52'),
(133, 6, '{\"Full Name\": \"JANET BUKOLA FAGBUYI\", \"Phone Number\": \"07044571166\", \"Email Address\": \"janetfagbuyi6@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Am hoping to gain insight about the career goals and to take it to my door step\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Fund for business, guide cane and empowerment.\"}', '2025-10-04 11:27:45'),
(134, 6, '{\"Full Name\": \"Sarah   Isaac\", \"Phone Number\": \"07061209605\", \"Email Address\": \"sarah4dlord@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To have knowledge on goals and opportunities so as to advocate for others\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Empowerment,\"}', '2025-10-04 11:38:52'),
(135, 8, '{\"Email Address\": \"shuaibabdul192@gmail.com\"}', '2025-10-04 11:40:22'),
(136, 6, '{\"Full Name\": \"Yusuf Adam muhammad\", \"Phone Number\": \"09037203435\", \"Email Address\": \"yusufadam4743@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I hope get more experience on this special programm\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-04 11:48:16'),
(137, 6, '{\"Full Name\": \"Umar said Muhammad\", \"Phone Number\": \"07087174217\", \"Email Address\": \"uumarsaidmuhammad@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Get no knolange\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Blind\"}', '2025-10-04 11:49:10'),
(138, 6, '{\"Full Name\": \"Hamza Aminu Fagge\", \"Phone Number\": \"07061659483\", \"Email Address\": \"hamzafagge69@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To learn about the practical experience on developing a good CV, interview skills and job searching techniques.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes. I need mobility aid because of my visual Impairment.\"}', '2025-10-04 13:21:13'),
(139, 6, '{\"Full Name\": \"Izang Bala Bulus\", \"Phone Number\": \"070 3660 7184\", \"Email Address\": \"izangbalabulus@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To acquire skills that would better equipped me to function better in life and my community.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I don&#039;t have much knowledge on the use on social media, with the exception of WhatsApp media.\"}', '2025-10-04 14:27:08'),
(140, 6, '{\"Full Name\": \"LADI GIMBA\", \"Phone Number\": \"08169871198\", \"Email Address\": \"lilibabyg9@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"More knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Easy to read  write up\"}', '2025-10-04 15:06:25'),
(141, 6, '{\"Full Name\": \"Amina Ahmed Abu\", \"Phone Number\": \"07035567269\", \"Email Address\": \"aminaahmed0186@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To help me have the knowledge of how to build my CV to the appropriate job opportunity\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No, I don&#039;t have any accessibility need\"}', '2025-10-04 17:05:50'),
(142, 6, '{\"Full Name\": \"ROSE DANIEL\", \"Phone Number\": \"08061504534\", \"Email Address\": \"roselynyoms@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"More knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 17:51:49'),
(143, 6, '{\"Full Name\": \"ROSE DANIEL\", \"Phone Number\": \"08061504534\", \"Email Address\": \"roselynyoms@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"More knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 17:51:49'),
(144, 6, '{\"Full Name\": \"Jerushah Ketah Paul\", \"Phone Number\": \"08173323337\", \"Email Address\": \"jerushahpaul2000@gmail.com\", \"How did you hear about this program?\": \"Social Media, Friend/Family\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"I hope to learn how to create my own resume\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 18:04:19'),
(145, 6, '{\"Full Name\": \"Hassan Aminu Zubairu\", \"Phone Number\": \"07037335288\", \"Email Address\": \"hassanwaire1998@gmail.com\", \"How did you hear about this program?\": \"Friend/Family, Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I&#039;m knowledge how to available in poultry farming skills. I just say\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes I do\"}', '2025-10-04 19:12:02'),
(146, 6, '{\"Full Name\": \"Anigor mercy chikodili\", \"Phone Number\": \"08069768059\", \"Email Address\": \"chikodilimercy@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To acquire knowledge and skill\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 19:28:57'),
(147, 6, '{\"Full Name\": \"Victoria Samuel Sani\", \"Phone Number\": \"08135509968\", \"Email Address\": \"vicksamsanik@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To be enlighten and more education on my carrier. And also so I can volunteer inane organisation\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 20:13:32'),
(148, 6, '{\"Full Name\": \"Adananu yunusa idiris\", \"Phone Number\": \"09030292551\", \"Email Address\": \"Adananyunusa@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Kwnoledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-04 20:17:17'),
(149, 6, '{\"Full Name\": \"Orgu Ezichi Glory\", \"Phone Number\": \"08105442093\", \"Email Address\": \"oorguezichi@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"I was training for crochet pattern, drawing and canva design\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I don&#039;t know\"}', '2025-10-04 21:52:24'),
(150, 6, '{\"Full Name\": \"Suleiman Saadu\", \"Phone Number\": \"08105529401\", \"Email Address\": \"Suleimansaadu229@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I&#039;m here to assist with information and tasks. What specific area do you need help with?\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I&#039;m here to help! 😊 If you&#039;re asking about accessibility needs, I&#039;d be happy to assist. Some common accessibility needs include:\\r\\n\\r\\n1. Screen reader compatibility\\r\\n2. Closed captions for videos\\r\\n3. Keyboard navigation\\r\\n4. Color contrast adjustments\\r\\n5. Assistive technology support\\r\\n\\r\\nIf you have specific needs or requirements, please let me know, and I&#039;ll do my best to accommodate them.\"}', '2025-10-04 23:46:38'),
(151, 6, '{\"Full Name\": \"Saadu Suleiman\", \"Phone Number\": \"08105529401\", \"Email Address\": \"Suleimansaadu885@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I&#039;m here to assist with information and tasks. What specific area do you need help with? 🤔\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I&#039;m here to help! 😊 If you&#039;re asking about accessibility needs, I&#039;d be happy to assist. Some common accessibility needs include:\\r\\n\\r\\n1. Screen reader compatibility\\r\\n2. Closed captions for videos\\r\\n3. Keyboard navigation\\r\\n4. Color contrast adjustments\\r\\n5. Assistive technology support\\r\\n\\r\\nIf you have specific needs or requirements, please let me know, and I&#039;ll do my best to accommodate them.\"}', '2025-10-05 00:11:32'),
(152, 6, '{\"Full Name\": \"Shamsu yakubu\", \"Phone Number\": \"07038774458\", \"Email Address\": \"shamsuyakubu538@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To achieve success in my life\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-05 07:47:16'),
(153, 6, '{\"Full Name\": \"Ebuka Nwaeze\", \"Phone Number\": \"08139730559\", \"Email Address\": \"ebukanwaeze040@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I want to be learning about human rights for PWD\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes, I will like to learn more eg WCW academy\"}', '2025-10-06 08:33:30'),
(154, 6, '{\"Full Name\": \"Michael Temidayo Arigbede\", \"Phone Number\": \"08133807192\", \"Email Address\": \"temidayomichael@gmail.com\", \"How did you hear about this program?\": \"Social Media, Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"A lot\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I wont to be empower\"}', '2025-10-06 09:52:06'),
(155, 6, '{\"Full Name\": \"Onyinyechi Winner Ethelbert\", \"Phone Number\": \"07069352262\", \"Email Address\": \"genteeldeevine@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"How to write a more compelling CV and improve my writing skills.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes! An accessible accommodation. Thank you.\"}', '2025-10-06 10:28:43'),
(156, 6, '{\"Full Name\": \"Aminu Abdullahi Khalid\", \"Phone Number\": \"08039164582\", \"Email Address\": \"aminuabdullahikhalid9@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To depend on myself\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-06 16:06:17'),
(157, 6, '{\"Full Name\": \"Aminatu shu&#039;aibu Idris\", \"Phone Number\": \"09064454582\", \"Email Address\": \"aminatushuaibuidris@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To depend on myself\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-06 16:09:10'),
(158, 6, '{\"Full Name\": \"Aminatu shu&#039;aibu Idris\", \"Phone Number\": \"09064454582\", \"Email Address\": \"aminatushuaibuidris@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To depend on myself\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-06 16:10:10'),
(159, 6, '{\"Full Name\": \"Aina&#039;u shu&#039;aibu Idris\", \"Phone Number\": \"08084423070\", \"Email Address\": \"ainaushuaibuidris2004@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To depend on myself\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-06 16:14:20'),
(160, 6, '{\"Full Name\": \"Fahad Ibrahim\", \"Phone Number\": \"08103527239\", \"Email Address\": \"ibrahimfahad902@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I would like to learn some skills that can help me to secure a job.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Sign language interpreter is my urgently needed.\"}', '2025-10-06 22:30:07'),
(161, 6, '{\"Full Name\": \"AGYESOPA Justina Kenneth\", \"Phone Number\": \"07067532984\", \"Email Address\": \"jagyesopa@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I&#039;m hoping to get employerbility skills.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes because am a person with visual impairment.\"}', '2025-10-06 23:40:10'),
(162, 6, '{\"Full Name\": \"Adejoh Joy Moses\", \"Phone Number\": \"07035052379\", \"Email Address\": \"joychavi2000@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge Impactation\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-07 08:17:16'),
(163, 6, '{\"Full Name\": \"Lovelyn Odivwri\", \"Phone Number\": \"08102559675\", \"Email Address\": \"odivwrilovelyn8945@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I am optimistic that by applying for this training, I will be better equipped to successfully apply for and secure jobs. Specifically, I hope to learn how to tailor my CV for each job opportunity, prepare adequately for interviews, and be generally job-ready.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes. Sighted guide, visual description of print, audio-described videos, and Braille/accessible digital documents and files.\"}', '2025-10-07 09:10:32'),
(164, 6, '{\"Full Name\": \"Ahmad Sambo Garba\", \"Phone Number\": \"08063418554\", \"Email Address\": \"uxuhsermbo@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge on how I can apply and secure a job\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes, I need a ramp\"}', '2025-10-08 10:02:51'),
(165, 6, '{\"Full Name\": \"Monday Jummai Rante\", \"Phone Number\": \"09069669273\", \"Email Address\": \"jummaimonday2018@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Learn how to prepare a good CV\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Data\"}', '2025-10-09 13:50:12'),
(166, 8, '{\"Email Address\": \"shuaibabdul192@gmail.com\"}', '2025-10-09 17:40:35'),
(167, 8, '{\"Email Address\": \"usmanadammikail@gmail.com\"}', '2025-10-09 17:42:43'),
(168, 8, '{\"Email Address\": \"shuaibabdul192@gmail.com\"}', '2025-10-09 17:53:57'),
(169, 8, '{\"Email Address\": \"shuaibabdul192@gmail.com\"}', '2025-10-09 17:58:01'),
(170, 6, '{\"Full Name\": \"Joy Michael uyo\", \"Phone Number\": \"08160682604\", \"Email Address\": \"joymichael247@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I have more knowledge and understanding to enable me sustain myself\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-09 19:40:43'),
(171, 6, '{\"Full Name\": \"Victoria Samuel Sani\", \"Phone Number\": \"08135509968\", \"Email Address\": \"vicksamsanik@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Be able to make a current cv and write a good application\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-10 11:02:55'),
(172, 6, '{\"Full Name\": \"Ephraim Elam Filibus\", \"Phone Number\": \"08143370806\", \"Email Address\": \"ephraimelam@gmail.com\", \"How did you hear about this program?\": \"Social Media, Friend/Family\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"How to write my owned story in a professional manner that will land me to my dream job.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Data rebursement\"}', '2025-10-11 08:36:09'),
(173, 6, '{\"Full Name\": \"Queen Odede Christopher\", \"Phone Number\": \"07068185821\", \"Email Address\": \"christopherqueen095@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To achieve my career goal in life and to be able to add the value I learned into the community of persons with disability and in the open world\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Data\"}', '2025-10-11 08:36:58'),
(174, 6, '{\"Full Name\": \"Habila Gizo\", \"Phone Number\": \"09055037015\", \"Email Address\": \"gizohabila11@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To learn and share  my experience.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-11 09:02:57'),
(175, 6, '{\"Full Name\": \"Ogundele Sodeeq\", \"Phone Number\": \"08128533958\", \"Email Address\": \"hawllar101@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 09:08:03'),
(176, 6, '{\"Full Name\": \"HALIMA SALEH IBRAHIM\", \"Phone Number\": \"08038638974\", \"Email Address\": \"salehhalima2020@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To acquire knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"None\"}', '2025-10-11 09:09:07'),
(177, 6, '{\"Full Name\": \"Barry Paul Goni\", \"Phone Number\": \"07037399253\", \"Email Address\": \"barrypaul991@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Improve myself especially in arrears that will help me easily access job opportunities.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 09:09:50'),
(178, 6, '{\"Full Name\": \"Ugochi Nwadike\", \"Phone Number\": \"08057718036\", \"Email Address\": \"ulove8866@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I hope to gain knowledge and skills on how to boost my career goals\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Nile\"}', '2025-10-11 09:13:27'),
(179, 6, '{\"Full Name\": \"Azeez Ganiyu\", \"Phone Number\": \"07064207713\", \"Email Address\": \"azeezganiuabiola55@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Gain job opportunity out there and make my cv be standout and best suit for the job I&#039;m placing for.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes if giving the opportunity\"}', '2025-10-11 09:14:36'),
(180, 6, '{\"Full Name\": \"Tijani Azeezat Ibukunoluwa\", \"Phone Number\": \"08140869515\", \"Email Address\": \"tijaniazeezat134@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To know more about CV writing\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 09:24:18'),
(181, 6, '{\"Full Name\": \"Ezekiel Akpan\", \"Phone Number\": \"08106396125\", \"Email Address\": \"ezekielakpan1980@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To gain more insight and knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Data funding\"}', '2025-10-11 09:26:00'),
(182, 6, '{\"Full Name\": \"Abubakar sani\", \"Phone Number\": \"08100779411\", \"Email Address\": \"abubakarsani1960@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Good morning my people ❤️, I am motivation to interesting of this programs to make it for me gain a passionate about access to education.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes, I am a passionate of this programs to help us with access to education ☺️\"}', '2025-10-11 09:41:30'),
(183, 6, '{\"Full Name\": \"Naanwul Sylvester\", \"Phone Number\": \"07035998713\", \"Email Address\": \"sylvesternaanwul@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To enable me gain knowledge and experience to improve myself and contribute to my community\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 09:51:25'),
(184, 6, '{\"Full Name\": \"Egekeze Jovita Amaka\", \"Phone Number\": \"08051183841\", \"Email Address\": \"egekezeamaka2@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I want to gain knowledge and skills on how to boost my career\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 10:07:44'),
(185, 6, '{\"Full Name\": \"Catherine Akor\", \"Phone Number\": \"08168322853\", \"Email Address\": \"akorcatherine@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Social Media Advocacy\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 10:09:35'),
(186, 6, '{\"Full Name\": \"Unyime Isidore Udoka\", \"Phone Number\": \"08100311049\", \"Email Address\": \"unyimeisidoreudoka1987@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To learn\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes, enhance network connection\"}', '2025-10-11 10:11:35'),
(187, 6, '{\"Full Name\": \"Hayatu Hamza\", \"Phone Number\": \"07034609869\", \"Email Address\": \"hhayatuddeen1@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"This training is an opportunity to strengthen skills for building a more inclusive and respectful workplace culture. Individuals hope to gain a deeper understanding of allyship and how to use it to foster a more equitable environment for all.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"By asking about your needs, the training program you&#039;re referencing aims to ensure all participants, with or without disabilities, can fully engage and benefit. The information provided is used to: \\r\\nMake adjustments to training materials.\\r\\nProvide necessary support or aids.\\r\\nMake the overall learning experience more inclusive. \\r\\nFor instance, a participant might need: \\r\\nVisual aids for those with hearing impairments.\\r\\nAudio descriptions for those with visual impairments.\\r\\nWritten transcripts for video content.\\r\\nLarger text for better readability.\\r\\nSign language interpreters. \\r\\nUltimately, the goal is to create a fully accessible and inclusive learning environment for everyone.\"}', '2025-10-11 10:34:41');
INSERT INTO `custom_form_submissions` (`id`, `form_id`, `submission_data`, `submitted_at`) VALUES
(188, 6, '{\"Full Name\": \"Yakubu Garba\", \"Phone Number\": \"09030799776\", \"Email Address\": \"yakubugarba2003@gmail.com\", \"How did you hear about this program?\": \"Social Media, Organization\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"I&#039;m interested to gain new knowledge and network with like-minded people to Garner experience.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No, I don&#039;t have\"}', '2025-10-11 10:35:40'),
(189, 6, '{\"Full Name\": \"Johnson Olakanmi Bankole\", \"Phone Number\": \"08144289510\", \"Email Address\": \"bankyj55@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Insight on how to improve on CV writing\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Non\"}', '2025-10-11 10:57:30'),
(190, 6, '{\"Full Name\": \"Celestina Ezekwudo\", \"Phone Number\": \"07060789707\", \"Email Address\": \"ezekwudocelestinachy@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To get a standard CV for easy recognition and job offers from employees\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Nil\"}', '2025-10-11 11:14:41'),
(191, 6, '{\"Full Name\": \"Celestina Ezekwudo\", \"Phone Number\": \"07060789707\", \"Email Address\": \"ezekwudocelestinachy@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To get a standard CV for easy recognition and job offers from employees\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Nil\"}', '2025-10-11 11:14:41'),
(192, 6, '{\"Full Name\": \"Timothy Ayuba\", \"Phone Number\": \"08032793967\", \"Email Address\": \"timothyayuba445@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"I hope to gain  more light on people with disabilities to further enhance my knowledge.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-11 11:52:40'),
(193, 6, '{\"Full Name\": \"David Uwello Joshua\", \"Phone Number\": \"09067617853\", \"Email Address\": \"davidjoshua1060@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Gain insightful and meaningful discussions on cv writing.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 11:53:16'),
(194, 6, '{\"Full Name\": \"Ogbor john chimuanya\", \"Phone Number\": \"08104059136\", \"Email Address\": \"johnchimuanya24@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge gain here will be use for the betterment of the society, am giving back to the society by working closely with the disability\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-11 12:04:01'),
(195, 6, '{\"Full Name\": \"Nasiba Abdullahi Alhassan\", \"Phone Number\": \"08143182685\", \"Email Address\": \"nasibaabdullahialhassan@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"How do teach of training so business\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I was help to training so success of disability\"}', '2025-10-11 12:31:57'),
(196, 6, '{\"Full Name\": \"Bridget Eze Donatus\", \"Phone Number\": \"08162024126\", \"Email Address\": \"bridgeteze4u@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"I&#039;m hoping to learn effective strategies and best practices for supporting persons with disabilities in vocational training, enhancing their employability, and promoting inclusive practices.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 12:34:02'),
(197, 6, '{\"Full Name\": \"Jeremiah Tata\", \"Phone Number\": \"07039787173\", \"Email Address\": \"jerrydantata1@gmail.com\", \"How did you hear about this program?\": \"Social Media, Organization\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"I want to able to create a mouth watering CV that is in resistable\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-11 12:36:56'),
(198, 6, '{\"Full Name\": \"Yosi Danjuma\", \"Phone Number\": \"08125973916\", \"Email Address\": \"yosidanjuma15@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To gain More insight on how to get hired.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 12:42:40'),
(199, 6, '{\"Full Name\": \"Abraham buba\", \"Phone Number\": \"07049033439\", \"Email Address\": \"abrahmsino@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"More knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 13:08:02'),
(200, 6, '{\"Full Name\": \"Olusola David Ojo\", \"Phone Number\": \"07037565236\", \"Email Address\": \"Honourableolusoladavid@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge and experience good enough to foster my inclusion in the work space.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes Wheelchair and Aid\"}', '2025-10-11 13:11:24'),
(201, 6, '{\"Full Name\": \"Saidu Yakubu\", \"Phone Number\": \"07068064226\", \"Email Address\": \"saiduyakubu224@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Advance skills and networking experience to make impact in my cimn\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Sign Language interpreter\"}', '2025-10-11 13:25:58'),
(202, 6, '{\"Full Name\": \"Olugbosun Ariyo\", \"Phone Number\": \"08025392225\", \"Email Address\": \"olugbosunariyo@yahoo.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 14:10:25'),
(203, 6, '{\"Full Name\": \"Chinonso Onuoha\", \"Phone Number\": \"07084117823\", \"Email Address\": \"chinonsobuchy@outlook.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To have knowledge on how to deal with diversity in working place and relate different people from different background\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Hope is online?\"}', '2025-10-11 14:49:19'),
(204, 6, '{\"Full Name\": \"Samson Adeola Adejumo\", \"Phone Number\": \"08108448897\", \"Email Address\": \"samsonadeolaadejumo@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"More insightful information about social works and volunteer opportunities\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Nothing\"}', '2025-10-11 15:06:33'),
(205, 6, '{\"Full Name\": \"Oteikwu Samuel Amedu\", \"Phone Number\": \"08050513076\", \"Email Address\": \"samuelamedu77@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Hoping on getting employed\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Non\"}', '2025-10-11 15:36:07'),
(206, 6, '{\"Full Name\": \"Eva Linda Oshaju\", \"Phone Number\": \"08033146221\", \"Email Address\": \"vava20042000@yahoo.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Training/experience\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 15:43:35'),
(207, 6, '{\"Full Name\": \"Abdullahi Umar\", \"Phone Number\": \"07034497151\", \"Email Address\": \"abdullahiumar559@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Skills acquisition and career development\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"None\"}', '2025-10-11 15:50:58'),
(208, 6, '{\"Full Name\": \"Sidiq Olamide Sidiq\", \"Phone Number\": \"09067251242\", \"Email Address\": \"ibrahimsidiqolamide@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Enlightenment and encouragement\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 16:49:25'),
(209, 6, '{\"Full Name\": \"Oluwa Aleje David\", \"Phone Number\": \"08140775732\", \"Email Address\": \"davidprosper1816@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Want to learn new things\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-11 16:52:46'),
(210, 6, '{\"Full Name\": \"Moses Agbo\", \"Phone Number\": \"09067615837\", \"Email Address\": \"agbom377@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Exposure and to enhance my skills\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 17:31:24'),
(211, 6, '{\"Full Name\": \"ABUBAKAR GBAGBA MOHAMMED\", \"Phone Number\": \"07025280529\", \"Email Address\": \"abubakarmgbagba@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Learn new innovation an skills t\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Nop\"}', '2025-10-11 17:34:28'),
(212, 6, '{\"Full Name\": \"Ayanyemi Barakah Adedoyin\", \"Phone Number\": \"09161433204\", \"Email Address\": \"ayanyemibarakah2022@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 18:03:04'),
(213, 6, '{\"Full Name\": \"Mustapha Ayuba\", \"Phone Number\": \"08022033292\", \"Email Address\": \"ansaar01@googlemail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Good knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-11 18:31:41'),
(214, 6, '{\"Full Name\": \"Adebayo Ibrahim olalekan\", \"Phone Number\": \"08135255948\", \"Email Address\": \"adebayoibrahim984@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To have in-depth understanding of what it entails to in securing job opportunities\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-11 18:58:11'),
(215, 6, '{\"Full Name\": \"Emeka Emmanuel Ezeanyika\", \"Phone Number\": \"07045475489\", \"Email Address\": \"emekaemmanuelezeanyika@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To explore various opportunities to learn vital information to help improve lives of people with disabilities and also \\r\\nAchieve my dreams of becoming a progressive change agent in my society\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I believe I can have access to the programs either online or offline\"}', '2025-10-11 19:44:30'),
(216, 6, '{\"Full Name\": \"Biem Tersoo Simeon\", \"Phone Number\": \"08134709987\", \"Email Address\": \"biemtersoo1@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To gain adequate knowledge on how to care for people with special needs and how to promote their needs in the society.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 19:55:21'),
(217, 6, '{\"Full Name\": \"Fatimah Usman\", \"Phone Number\": \"08109571340\", \"Email Address\": \"albatulusman93@gmail.com\", \"How did you hear about this program?\": \"Social Media, Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Self development\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 20:01:21'),
(218, 6, '{\"Full Name\": \"Daniel Sao\", \"Phone Number\": \"07032730052\", \"Email Address\": \"saodan2004@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To learn how to be of help to position the person&#039;s with disabilities for job opportunities.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-11 20:03:06'),
(219, 6, '{\"Full Name\": \"Cyril Offende Oglegba\", \"Phone Number\": \"07063337278\", \"Email Address\": \"cyriloglegba@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"I am hoping to gain knowledge and carer Development\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-11 21:42:32'),
(220, 6, '{\"Full Name\": \"Fatima Kabir mustapha\", \"Phone Number\": \"08025436198\", \"Email Address\": \"fatimakabir326@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To improve my future experience\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I need u increase interpreter to enable deaf people understand clearly\"}', '2025-10-11 23:36:53'),
(221, 6, '{\"Full Name\": \"Fatima Kabir mustapha\", \"Phone Number\": \"08025436198\", \"Email Address\": \"fatimakabir326@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To improve my future experience\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I need u increase interpreter to enable deaf people understand clearly\"}', '2025-10-11 23:37:00'),
(222, 6, '{\"Full Name\": \"Enyinnah victor\", \"Phone Number\": \"08134417327\", \"Email Address\": \"enyinnahvictor38@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge and skills that will help me work towards the vision and mission of the foundation.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes,I need a tablet or laptop to help me participate in the training fully.My smartphone is faulty, which makes it difficult to download some app.\"}', '2025-10-11 23:49:58'),
(223, 6, '{\"Full Name\": \"Enyinnah victor\", \"Phone Number\": \"08134417327\", \"Email Address\": \"enyinnahvictor38@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge and skills that will help me work towards the vision and mission of the foundation.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes,I need a tablet or laptop to help me participate in the training fully.My smartphone is faulty, which makes it difficult to download some app.\"}', '2025-10-11 23:51:34'),
(224, 6, '{\"Full Name\": \"Sonoiki omowunmi olukorede\", \"Phone Number\": \"07064451754\", \"Email Address\": \"wunmiolukorede@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Am interested in working as staff\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes of course am ready for it please\"}', '2025-10-12 01:12:37'),
(225, 6, '{\"Full Name\": \"Edeh Chika Confidence\", \"Phone Number\": \"07080305786\", \"Email Address\": \"Confibless86@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Cv writing\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-12 03:58:08'),
(226, 6, '{\"Full Name\": \"Sarah Pam\", \"Phone Number\": \"08065687530\", \"Email Address\": \"sajohnsbakings@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"First hand information on job readiness.\\r\\nKnow all I need to do to be qualified for employment \\r\\nKnow credentials to be handy when seeking employment\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Friendly location\"}', '2025-10-12 08:09:28'),
(227, 6, '{\"Full Name\": \"Tongshak Rabo Yakwal\", \"Phone Number\": \"08138917686\", \"Email Address\": \"tyakwal@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"More insight into the field\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-12 08:33:35'),
(228, 6, '{\"Full Name\": \"Douglas Barde Danladi\", \"Phone Number\": \"07039074372\", \"Email Address\": \"douglasbarde08@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"I want to achieve a career goals at the end of this program which will be used in my community for the benefit of all.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-12 08:37:37'),
(229, 6, '{\"Full Name\": \"Hauwa Uwais Abdulhameed\", \"Phone Number\": \"08161876293\", \"Email Address\": \"hauwauwais2016@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I want to expand my business and to be come civil servants\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-12 11:53:26'),
(230, 6, '{\"Full Name\": \"Alhamdu Yake Yusuf\", \"Phone Number\": \"07038409216\", \"Email Address\": \"yusufalhamdu@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Knowledge that i will impact in the life of people irrespective of their gender, religion and class of life which they all belongs too.\\r\\nMy aim and objective is to bring everybody under one umbrella as one, so that we can make the world a better place and also impact people with knowledge and skills.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Funds that i will use in carrying out this educative and skilled programe.\"}', '2025-10-12 15:06:17'),
(231, 6, '{\"Full Name\": \"Biem Tersoo Simeon\", \"Phone Number\": \"08134709987\", \"Email Address\": \"biemtersoo1@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To gain adequate knowledge on how to care for people with special needs and how to promote their needs in the society.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-13 11:07:34'),
(232, 6, '{\"Full Name\": \"GLORY ONU\", \"Phone Number\": \"09060561534\", \"Email Address\": \"onuglory1@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Professional skills\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-13 12:21:24'),
(233, 6, '{\"Full Name\": \"Yosi Danjuma\", \"Phone Number\": \"08125973916\", \"Email Address\": \"yosidanjuma15@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"To gain More insight on how to get hired.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-14 08:21:50'),
(234, 6, '{\"Full Name\": \"Shamsuddeni Dahiru Musa\", \"Phone Number\": \"08133362060\", \"Email Address\": \"shamsuddenidaheer@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Make shoe or tailor\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I interested of it training because am person with disability\"}', '2025-10-14 08:46:14'),
(235, 6, '{\"Full Name\": \"HADIZA YAHAYA AHMED\", \"Phone Number\": \"+12347068201652\", \"Email Address\": \"hadizayahayaahmed2@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes I wish be interested\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes best do anything\"}', '2025-10-14 08:49:16'),
(236, 6, '{\"Full Name\": \"Rahinatu yau muaz\", \"Phone Number\": \"07067084162\", \"Email Address\": \"rahinamuazuyau@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"It is okay\"}', '2025-10-14 08:51:47'),
(237, 6, '{\"Full Name\": \"FATIMA ABDULHAMEED MUHAMMAD\", \"Phone Number\": \"08030539655\", \"Email Address\": \"fatimaabdulhameed66@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Tes\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Graphic design &amp; Business &amp; Teaching\"}', '2025-10-14 09:01:11'),
(238, 6, '{\"Full Name\": \"Mamuda Ahmad\", \"Phone Number\": \"08034609512\", \"Email Address\": \"mamudaahmad920@gmail.com\", \"How did you hear about this program?\": \"Social Media, Other:\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Need Business\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Need Business\"}', '2025-10-14 09:06:42'),
(239, 6, '{\"Full Name\": \"Mariya ladan Ibrahim\", \"Phone Number\": \"09048649732\", \"Email Address\": \"mariyaladan55@gmail.com\", \"How did you hear about this program?\": \"Other:\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Business\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Program important\"}', '2025-10-14 10:19:33'),
(240, 6, '{\"Full Name\": \"Mubarak Abdulhamid Ismail\", \"Phone Number\": \"07012225004\", \"Email Address\": \"mubarakbdlhmd@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Business\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Business\"}', '2025-10-14 10:20:34'),
(241, 6, '{\"Full Name\": \"Mariya ladan Ibrahim\", \"Phone Number\": \"09048649732\", \"Email Address\": \"mariyaladan55@gmail.com\", \"How did you hear about this program?\": \"Other:\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Business\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Program important\"}', '2025-10-14 10:22:34'),
(242, 6, '{\"Full Name\": \"Dahiru rabiu\", \"Phone Number\": \"09161155797\", \"Email Address\": \"dahirurimirabiu@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"All the of above program Tarning\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Agriculture training program other above wish help with all in need want  good social media disability\"}', '2025-10-14 10:28:18'),
(243, 6, '{\"Full Name\": \"Sunusi Yunusa Isah\", \"Phone Number\": \"07077633604\", \"Email Address\": \"sunusiyunusa1234@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Dear sir i need more skill program in to your organisation program thank participate\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-14 10:50:47'),
(244, 6, '{\"Full Name\": \"Sunusi Yunusa Isah\", \"Phone Number\": \"07077633604\", \"Email Address\": \"sunusiyunusa1234@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Dear sir i need more skill program in to your organisation program thank participate\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-14 10:50:47'),
(245, 6, '{\"Full Name\": \"Fatima\", \"Phone Number\": \"09068080503\", \"Email Address\": \"sanusifatima348@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yss\"}', '2025-10-14 11:28:09'),
(246, 6, '{\"Full Name\": \"Fatima\", \"Phone Number\": \"09068080503\", \"Email Address\": \"sanusifatima348@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-14 11:28:14'),
(247, 6, '{\"Full Name\": \"Fatima\", \"Phone Number\": \"09068080503\", \"Email Address\": \"sanusifatima348@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-14 11:28:43'),
(248, 6, '{\"Full Name\": \"Abdul Usman\", \"Phone Number\": \"08122951220\", \"Email Address\": \"usmanabdul3050@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I hope this training content more benefits\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No need\"}', '2025-10-14 11:41:36'),
(249, 6, '{\"Full Name\": \"Abdul Usman\", \"Phone Number\": \"08122951220\", \"Email Address\": \"usmanabdul3050@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I hope this training content more benefits\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No need\"}', '2025-10-14 12:59:57'),
(250, 6, '{\"Full Name\": \"Abdussalam\", \"Phone Number\": \"08035292970\", \"Email Address\": \"abdussalamusmanali@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I’m hoping to gain new skills, practical knowledge, and experience that will help me grow professionally and improve my performance in my field.\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No, I don’t have any accessibility needs at the moment.\"}', '2025-10-14 13:05:25'),
(251, 6, '{\"Full Name\": \"Nura Umar Ibrahim\", \"Phone Number\": \"08035493593\", \"Email Address\": \"umarchilanura@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I&#039;m a rather to improve my ability to assist and provide value to use through tasks\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes,I&#039;m designed to function optimally across various platforms and interfaces, ensuring seamless interactions for users.\"}', '2025-10-14 13:38:48'),
(252, 6, '{\"Full Name\": \"Nura Umar Ibrahim\", \"Phone Number\": \"08035493593\", \"Email Address\": \"umarchilanura@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I&#039;m a rather to improve my ability to assist and provide value to use through tasks\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes,I&#039;m designed to function optimally across various platforms and interfaces, ensuring seamless interactions for users.\"}', '2025-10-14 13:38:56'),
(253, 6, '{\"Full Name\": \"Muhammad Ashiru\", \"Phone Number\": \"08126125098\", \"Email Address\": \"muhammadashir320@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Prefer not to say\", \"What are you hoping to gain from this training? (Brief response)\": \"Free job\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Thank com\"}', '2025-10-14 19:40:01'),
(254, 6, '{\"Full Name\": \"Shafi&#039;u yusha&#039;u Sulaiman\", \"Phone Number\": \"08160173688\", \"Email Address\": \"shafiuyushaudangoro@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I&#039;m hoping to gain new skills, deepen my understanding of the same\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Kano State association of the deaf\"}', '2025-10-14 20:03:13'),
(255, 6, '{\"Full Name\": \"Usamatu saidu Abdullahi\", \"Phone Number\": \"08143468281\", \"Email Address\": \"saiduusamatu553@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Business\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Business\"}', '2025-10-14 20:55:50'),
(256, 6, '{\"Full Name\": \"Zuwaira Tijjani hassan\", \"Phone Number\": \"09064682781\", \"Email Address\": \"Zuwairatijjanih@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Project training\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Business\"}', '2025-10-15 01:22:17'),
(257, 6, '{\"Full Name\": \"Aisha Shu aibu salisu\", \"Phone Number\": \"08103828440\", \"Email Address\": \"aishashuaubu06@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Programm\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Programm training\"}', '2025-10-15 11:16:56'),
(258, 6, '{\"Full Name\": \"Aisha Shu aibu salisu\", \"Phone Number\": \"08103828440\", \"Email Address\": \"aishashuaubu06@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Programm\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Programm training\"}', '2025-10-15 11:17:19'),
(259, 6, '{\"Full Name\": \"Aisha Shu aibu salisu\", \"Phone Number\": \"08103828440\", \"Email Address\": \"aishashuaubu06@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Programm\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Programm training\"}', '2025-10-15 17:43:58'),
(260, 6, '{\"Full Name\": \"Aisha Shu aibu salisu\", \"Phone Number\": \"08103828440\", \"Email Address\": \"aishashuaubu06@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Programm\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Programm training\"}', '2025-10-15 17:44:19'),
(261, 6, '{\"Full Name\": \"Mariya ladan Ibrahim\", \"Phone Number\": \"09048649732\", \"Email Address\": \"mariyaladan55@gmail.com\", \"How did you hear about this program?\": \"Other:\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Business\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Program important\"}', '2025-10-15 17:57:02'),
(262, 6, '{\"Full Name\": \"Rabiatu Ahmad Haruna\", \"Phone Number\": \"07077353960\", \"Email Address\": \"rabiatuahmadharuna462@gmail.com\", \"How did you hear about this program?\": \"Other:\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I wish you doing tonight you should get a chance please give me your welcome\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I need to get some of that people are going well for everything my life is good you\"}', '2025-10-15 21:52:30'),
(263, 6, '{\"Full Name\": \"Rabiatu Ahmad Haruna\", \"Phone Number\": \"07077353960\", \"Email Address\": \"rabiatuahmadharuna462@gmail.com\", \"How did you hear about this program?\": \"Other:\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I wish you doing tonight you should get a chance please give me your welcome\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I need to get some of that people are going well for everything my life is good you\"}', '2025-10-15 21:53:44'),
(264, 6, '{\"Full Name\": \"Aliyu Kabiru jibrin\", \"Phone Number\": \"08067802208\", \"Email Address\": \"kabirujibrinaliyu909@gmail.com\", \"How did you hear about this program?\": \"Friend/Family, Other:\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Bussiness\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-16 00:45:27'),
(265, 6, '{\"Full Name\": \"Ahmad Abdullahi Ahmad\", \"Phone Number\": \"09037771861\", \"Email Address\": \"ahmadahmadabdullahi626@gmail.com\", \"How did you hear about this program?\": \"Other:\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Housekeeper\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-16 01:38:45'),
(266, 6, '{\"Full Name\": \"Sadiya Abdulhadi Lawan\", \"Phone Number\": \"07049231464\", \"Email Address\": \"abdulhadilawansadiya@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-16 10:28:03'),
(267, 6, '{\"Full Name\": \"Abdullahi Ado\", \"Phone Number\": \"08141187906\", \"Email Address\": \"abdullahiado938@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Growing personal with financial. I am happy to be here\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Growing personal\"}', '2025-10-16 10:31:31'),
(268, 6, '{\"Full Name\": \"Sadiya Ahmad Ja&#039;afar\", \"Phone Number\": \"07035772545\", \"Email Address\": \"sadiyaahmad3315@gmail.com\", \"How did you hear about this program?\": \"Other:\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I want to add my knowledge\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I a def and dump\"}', '2025-10-16 10:42:02'),
(269, 6, '{\"Full Name\": \"Anguna Mariana\", \"Phone Number\": \"677529231\", \"Email Address\": \"marianaanguna@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I want to gain knowledge  to handle  future  challenges\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-16 10:43:59'),
(270, 6, '{\"Full Name\": \"Muhammad Ashiru\", \"Phone Number\": \"08126125098\", \"Email Address\": \"muhammadashir320@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Prefer not to say\", \"What are you hoping to gain from this training? (Brief response)\": \"Dade\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-16 10:46:15'),
(271, 6, '{\"Full Name\": \"Abubakar haruna Muhammad\", \"Phone Number\": \"0911 753 8560\", \"Email Address\": \"Sadiqharunamuhammad51@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I am interested\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes I will attend there\"}', '2025-10-16 11:37:09'),
(272, 6, '{\"Full Name\": \"Zakariyya Shehu\", \"Phone Number\": \"09121720839\", \"Email Address\": \"zakariyyashehu02@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I&#039;m interested\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I&#039;m interested\"}', '2025-10-16 11:46:27'),
(273, 6, '{\"Full Name\": \"Zakariyya Shehu\", \"Phone Number\": \"09121720839\", \"Email Address\": \"zakariyyashehu02@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I am interested\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I am interested\"}', '2025-10-16 11:48:42'),
(274, 6, '{\"Full Name\": \"Isah Haruna ismaila\", \"Phone Number\": \"08037400327\", \"Email Address\": \"isahh4323@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Skill program\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"We want to experience with training\"}', '2025-10-16 13:10:00'),
(275, 6, '{\"Full Name\": \"Ibrahim Baffa Banghi\", \"Phone Number\": \"07031104076\", \"Email Address\": \"bappaharewa@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Textile industry\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes. I need to ensure the training\"}', '2025-10-16 13:12:55');
INSERT INTO `custom_form_submissions` (`id`, `form_id`, `submission_data`, `submitted_at`) VALUES
(276, 6, '{\"Full Name\": \"Amina Idris Yusuf\", \"Phone Number\": \"07048043655\", \"Email Address\": \"aminaidrisyusuf72@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"I wish my business very important seriously\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"I wish all friend good I&#039;m personally\"}', '2025-10-16 16:23:40'),
(277, 6, '{\"Full Name\": \"Yahanasu Abdussalam Bala\", \"Phone Number\": \"07060618147\", \"Email Address\": \"abdussalamyahanasu3@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Brief response\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-16 18:58:14'),
(278, 6, '{\"Full Name\": \"Marawiyya adamu salihu\", \"Phone Number\": \"09042552052\", \"Email Address\": \"marawiyyaadamu01@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To get employment\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-16 20:31:22'),
(279, 6, '{\"Full Name\": \"Rabiu Auwal Garba\", \"Phone Number\": \"08033791147\", \"Email Address\": \"rabiugarba90@gmail.com\", \"How did you hear about this program?\": \"Social Media, Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"To attain great skills\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Phone\"}', '2025-10-16 20:37:29'),
(280, 6, '{\"Full Name\": \"Mukhtar Abdu Adam\", \"Phone Number\": \"08132714428\", \"Email Address\": \"mukhtaralkanawi@gmail.com\", \"How did you hear about this program?\": \"Social Media\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Job\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"No\"}', '2025-10-22 07:20:22'),
(281, 6, '{\"Full Name\": \"Bilkisu Salisu Musa\", \"Phone Number\": \"07035927251\", \"Email Address\": \"bilkisusaliumusa@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-22 21:47:50'),
(282, 6, '{\"Full Name\": \"Sadeeq Yahaya Ahmad\", \"Phone Number\": \"08160799898\", \"Email Address\": \"yahayaahmadsadeeq@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Approved\"}', '2025-10-22 22:00:18'),
(283, 6, '{\"Full Name\": \"Sadeeq Yahaya Ahmad\", \"Phone Number\": \"08160799898\", \"Email Address\": \"yahayaahmadsadeeq@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Yes\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-22 22:01:43'),
(284, 6, '{\"Full Name\": \"Ibrahim alhassan\", \"Phone Number\": \"09069978699\", \"Email Address\": \"ibrahimabdussalamalhasan@gmail.com\", \"How did you hear about this program?\": \"Friend/Family\", \"Do you identify as a person with a disability?\": \"Yes\", \"What are you hoping to gain from this training? (Brief response)\": \"Social media\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"Yes\"}', '2025-10-24 12:41:14'),
(289, 6, '{\"Full Name\": \"aw\", \"Phone Number\": \"123456789\", \"Email Address\": \"usmanadammikail@gmail.com\", \"How did you hear about this program?\": \"Organization\", \"Do you identify as a person with a disability?\": \"No\", \"What are you hoping to gain from this training? (Brief response)\": \"ni\", \"Do you have any accessibility needs? (Optional), This information helps us ensure the training is fully accessible to you.\": \"asdfghj\"}', '2025-11-18 12:12:22'),
(290, 12, '{\"Full Name\": \"SHUAIB ABDOOL\", \"Phone Number\": \"08122598372\", \"Email Address\": \"shuaibabdul192@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"kANO\", \"What do you hope to learn from this training?\": \"jkbkjb\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-11-25 22:15:20'),
(291, 12, '{\"Full Name\": \"Hayatu Hamza\", \"Phone Number\": \"07034609869\", \"Email Address\": \"hayatuhamza24@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"katsina state\", \"What do you hope to learn from this training?\": \"farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes I do\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 19:37:07'),
(292, 12, '{\"Full Name\": \"Egekeze Jovita\", \"Phone Number\": \"08051183841\", \"Email Address\": \"egekezeamaka2@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"FCT\", \"What do you hope to learn from this training?\": \"To Learn how to rea snail\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 20:02:49'),
(293, 12, '{\"Full Name\": \"Perpetua Ada Anyanwu\", \"Phone Number\": \"08097593528\", \"Email Address\": \"adaperpetua33@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"To acquire new knowledge\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 20:07:24'),
(294, 12, '{\"Full Name\": \"Philomena Edomwandagbon\", \"Phone Number\": \"08073981445\", \"Email Address\": \"phinaba75@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Benin city\", \"What do you hope to learn from this training?\": \"The idea and how to go about it\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 20:28:00'),
(295, 12, '{\"Full Name\": \"Dorcas Tanimu\", \"Phone Number\": \"08065650994\", \"Email Address\": \"dorcastanimu93@gmail.com\", \"Experience Level\": \"Advanced\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kuje\", \"What do you hope to learn from this training?\": \"A very good training I will appreciate\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 20:37:56'),
(296, 12, '{\"Full Name\": \"Ogundiya Seun\", \"Phone Number\": \"07036338071\", \"Email Address\": \"ogundiyaomowumiseun@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ibadan, Oyo state\", \"What do you hope to learn from this training?\": \"How to farm snail\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 20:40:38'),
(297, 12, '{\"Full Name\": \"Ayo Awobona\", \"Phone Number\": \"08056181329\", \"Email Address\": \"awobonaayo@yahoo.co.uk\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ago Iwoye/Ogun State\", \"What do you hope to learn from this training?\": \"Learn to be a guru in snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Nope\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 20:50:57'),
(298, 12, '{\"Full Name\": \"Anyanwu Nkechi Joy\", \"Phone Number\": \"08034861603\", \"Email Address\": \"talk2joy2013@yahoo.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Igando lagos\", \"What do you hope to learn from this training?\": \"To learn how to farm snail for business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 20:54:43'),
(299, 12, '{\"Full Name\": \"Timothy Ayuba\", \"Phone Number\": \"08032793967\", \"Email Address\": \"timothyayuba445@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"I hope to learn the process of breeding snails for profit\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 20:55:39'),
(300, 12, '{\"Full Name\": \"Abdulazeez Muideen\", \"Phone Number\": \"08127248097\", \"Email Address\": \"olamiolami57@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ilorin\", \"What do you hope to learn from this training?\": \"To learn how to start Snail Farming for Sustainable Livelihoods\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 21:01:05'),
(301, 12, '{\"Full Name\": \"Ismail\", \"Phone Number\": \"08060405280\", \"Email Address\": \"sodiqismail2018@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ilorin West\", \"What do you hope to learn from this training?\": \"Yes\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 21:10:16'),
(302, 12, '{\"Full Name\": \"Ebehitale Deborah oku-williams\", \"Phone Number\": \"08158175057\", \"Email Address\": \"ebehitalewilliams@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Benin city,edo state\", \"What do you hope to learn from this training?\": \"Everything Dat can make me elevate my status\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 21:16:08'),
(303, 12, '{\"Full Name\": \"Sanusi Abdulrahman Olatunde\", \"Phone Number\": \"08140853744\", \"Email Address\": \"abdulrahamolatundesanusi@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kwara\", \"What do you hope to learn from this training?\": \"Trader\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Interested\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 21:30:58'),
(304, 12, '{\"Full Name\": \"Sanusi Abdulrahman Olatunde\", \"Phone Number\": \"08140853744\", \"Email Address\": \"abdulrahamolatundesanusi@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kwara\", \"What do you hope to learn from this training?\": \"Trader\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Interested\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 21:30:58'),
(305, 12, '{\"Full Name\": \"Helen sabastine\", \"Phone Number\": \"08052941564\", \"Email Address\": \"helensabastine81@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kuje FCT Abuja\", \"What do you hope to learn from this training?\": \"I want to learn about it program\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes Deaf\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 21:49:23'),
(306, 12, '{\"Full Name\": \"Adeyemi Temitope Margaret\", \"Phone Number\": \"09034117527\", \"Email Address\": \"temitopemaragret180@yahoo.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Oyo state\", \"What do you hope to learn from this training?\": \"Basics and Advanced how to start up snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 22:46:26'),
(307, 12, '{\"Full Name\": \"Victoria Adenike Adeosun\", \"Phone Number\": \"08055268528\", \"Email Address\": \"vicnikky01@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ijebu Ode\", \"What do you hope to learn from this training?\": \"To acquire fish farming skills\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 22:58:28'),
(308, 12, '{\"Full Name\": \"Suleiman Saadu\", \"Phone Number\": \"08105529401\", \"Email Address\": \"suleimansaadu885@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna North\", \"What do you hope to learn from this training?\": \"No\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-01 23:25:23'),
(309, 12, '{\"Full Name\": \"Oteikwu Samuel Amedu\", \"Phone Number\": \"08036240456\", \"Email Address\": \"samuelamedu77@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Benue State\", \"What do you hope to learn from this training?\": \"Alot\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 00:01:12'),
(310, 12, '{\"Full Name\": \"Lasisi Monsuru Akande\", \"Phone Number\": \"08035840120\", \"Email Address\": \"lamslive1@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Lagos\", \"What do you hope to learn from this training?\": \"Everything to know about snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 00:02:03'),
(311, 12, '{\"Full Name\": \"Kazeem usman\", \"Phone Number\": \"09138317804\", \"Email Address\": \"kazeemusman10@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ago iwoye\", \"What do you hope to learn from this training?\": \"Anything useful\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 00:23:52'),
(312, 12, '{\"Full Name\": \"OLORUNOJE SIKIRAT ABENI\", \"Phone Number\": \"08063451729\", \"Email Address\": \"oladiposikirat@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ilorin\", \"What do you hope to learn from this training?\": \"ALL ASPECTS FROM A_Z\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"NO\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 03:47:44'),
(313, 12, '{\"Full Name\": \"Chidinma ezemah\", \"Phone Number\": \"08068815999\", \"Email Address\": \"chidinmaeze486@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kubwa village Abuja\", \"What do you hope to learn from this training?\": \"Bakery and catering\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 04:22:55'),
(314, 12, '{\"Full Name\": \"Raji Akanmode Bashir\", \"Phone Number\": \"08039700860\", \"Email Address\": \"rajibashy@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ilorin\", \"What do you hope to learn from this training?\": \"I need to be mentored to set up a snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 04:56:48'),
(315, 12, '{\"Full Name\": \"TOLULOPE OLUWASEYI OLOYEDE\", \"Phone Number\": \"08167263562\", \"Email Address\": \"summitunigraphix@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Lagos\", \"What do you hope to learn from this training?\": \"To be self reliance and contribute to the development of the community\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 06:26:19'),
(316, 12, '{\"Full Name\": \"ADEBAYO JAMIU\", \"Phone Number\": \"08066991801\", \"Email Address\": \"olaitainjamiu@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"KWARA STATE\", \"What do you hope to learn from this training?\": \"To have knowledge about it\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"NO\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 06:33:09'),
(317, 12, '{\"Full Name\": \"HALIMA SALEH IBRAHIM\", \"Phone Number\": \"08038638974\", \"Email Address\": \"salehhalima2020@gmail.com\", \"Experience Level\": \"Advanced\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Jos North\", \"What do you hope to learn from this training?\": \"None\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 06:57:59'),
(318, 12, '{\"Full Name\": \"HALIMA SALEH IBRAHIM\", \"Phone Number\": \"08038638974\", \"Email Address\": \"salehhalima2020@gmail.com\", \"Experience Level\": \"Advanced\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Jos North\", \"What do you hope to learn from this training?\": \"None\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 07:03:43'),
(319, 12, '{\"Full Name\": \"Rabi&#039;atu Adam abdullah\", \"Phone Number\": \"07048776950\", \"Email Address\": \"rabiatukano819@gamil.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kano\", \"What do you hope to learn from this training?\": \"Yes\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 07:08:08'),
(320, 12, '{\"Full Name\": \"Tayo Odeyemi\", \"Phone Number\": \"07039522669\", \"Email Address\": \"Tayopaul@31gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Ikorodu\", \"What do you hope to learn from this training?\": \"Gaining all necessary knowledge needed to launch into the snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 08:18:27'),
(321, 12, '{\"Full Name\": \"CHRISTIANA MIMIDOO TYOPEV\", \"Phone Number\": \"08148202857\", \"Email Address\": \"mimidootyopev1997@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"Snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 08:19:27'),
(322, 12, '{\"Full Name\": \"Mary James Gaisa\", \"Phone Number\": \"08034235940\", \"Email Address\": \"marygaisa7@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Taraba\", \"What do you hope to learn from this training?\": \"Everything about Snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 08:34:09'),
(323, 12, '{\"Full Name\": \"Abdulganiyu mujidat omolara\", \"Phone Number\": \"08065727566\", \"Email Address\": \"abdulganiyumujidat74@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ilorin,Kwara\", \"What do you hope to learn from this training?\": \"How to rear snail for profit porpose\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 09:15:52'),
(324, 12, '{\"Full Name\": \"Khadijah Yusuf\", \"Phone Number\": \"08088642811\", \"Email Address\": \"kondijot@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"Everything\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 09:25:14'),
(325, 12, '{\"Full Name\": \"DENNIS WEDE UMUKORO\", \"Phone Number\": \"07034816279\", \"Email Address\": \"umukorodennis420@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Ayeitoro\", \"What do you hope to learn from this training?\": \"Dynamics of snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 09:45:00'),
(326, 12, '{\"Full Name\": \"Onyinyechi Winner Ethelbert\", \"Phone Number\": \"07069352262\", \"Email Address\": \"genteeldeevine@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Bwari\", \"What do you hope to learn from this training?\": \"I hope to learn how to successfully run a snail farming business for financial independence and community support.\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"I require a wheelchair accessible facility.\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 09:55:02'),
(327, 12, '{\"Full Name\": \"Abdulkarim Isa\", \"Phone Number\": \"09035555656\", \"Email Address\": \"abdulkarimyareema@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kwaya central/Kwaya kusar local government\", \"What do you hope to learn from this training?\": \"Farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"No\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 12:08:59'),
(328, 12, '{\"Full Name\": \"JAMILA UGBEDE ADAM\", \"Phone Number\": \"08160141469\", \"Email Address\": \"jemilaadam2050@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"How to start a snail business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 12:48:11'),
(329, 12, '{\"Full Name\": \"Unyime Isidore Udoka\", \"Phone Number\": \"08100311049\", \"Email Address\": \"unyimeisidoreudoka1987@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Obot Akara\", \"What do you hope to learn from this training?\": \"To know how raise and keep snails that are profitable\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 12:55:15'),
(330, 12, '{\"Full Name\": \"Afolayan Hafsat Omowumi\", \"Phone Number\": \"08163015578\", \"Email Address\": \"hafsatafolayan@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Abeokuta Ogun state\", \"What do you hope to learn from this training?\": \"How to hearing sanil\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 13:54:59'),
(331, 12, '{\"Full Name\": \"Adedolapo king\", \"Phone Number\": \"08119603888\", \"Email Address\": \"adedolapokosokoking@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Lagos\", \"What do you hope to learn from this training?\": \"How to start a snail farm business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 14:14:39'),
(332, 12, '{\"Full Name\": \"Omorogieva Henry Ogbebor\", \"Phone Number\": \"08051652826\", \"Email Address\": \"obrohenry@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Benin City Edo state\", \"What do you hope to learn from this training?\": \"I hope to learn and aquire knowledge about snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 17:59:32'),
(333, 12, '{\"Full Name\": \"Salisu yahaya\", \"Phone Number\": \"08103992328\", \"Email Address\": \"yahayasalisu72@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna state\", \"What do you hope to learn from this training?\": \"I want to learn the snail farming,and how to market it,\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 18:47:06'),
(334, 12, '{\"Full Name\": \"Lawal Abbas\", \"Phone Number\": \"08034851476\", \"Email Address\": \"lawalabbas29@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"Yes\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 19:50:24'),
(335, 12, '{\"Full Name\": \"Asheta Rosemary Ayi\", \"Phone Number\": \"08059948545\", \"Email Address\": \"ashetarosemary@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Cross River State\", \"What do you hope to learn from this training?\": \"How to keep snails\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 20:46:37'),
(336, 12, '{\"Full Name\": \"Bilikis\", \"Phone Number\": \"alabibilikisopeyemi@gmail.com\", \"Email Address\": \"alabibilikisopeyemi@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kwara state\", \"What do you hope to learn from this training?\": \"Snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-02 21:49:21'),
(337, 12, '{\"Full Name\": \"Somide Oluwatoyin\", \"Phone Number\": \"07063966422\", \"Email Address\": \"toyinsomide5@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Abeokuta Ogun State\", \"What do you hope to learn from this training?\": \"Start Snail as a business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-04 02:42:21'),
(338, 12, '{\"Full Name\": \"Adekanbi Onitiri\", \"Phone Number\": \"08026694460\", \"Email Address\": \"akanboni@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Kaduna,kaduna state\", \"What do you hope to learn from this training?\": \"Production, marketing and sales and management of snail farming.\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-04 10:54:44'),
(339, 12, '{\"Full Name\": \"Peter\", \"Phone Number\": \"09073780794\", \"Email Address\": \"infodeskmeristem@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Ibadan\", \"What do you hope to learn from this training?\": \"How to sustain and scale my snail farming Business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-04 16:40:53'),
(340, 12, '{\"Full Name\": \"Mamuda Ahmad\", \"Phone Number\": \"07081722553\", \"Email Address\": \"mamudaa130@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Kano\", \"What do you hope to learn from this training?\": \"Business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Need Business\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-05 08:11:53'),
(341, 12, '{\"Full Name\": \"Aisha Aminu\", \"Phone Number\": \"07033985685\", \"Email Address\": \"aminuaisha5685@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Niger state\", \"What do you hope to learn from this training?\": \"Snail farming and how to maximize profit\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-05 11:32:21'),
(342, 12, '{\"Full Name\": \"Ahmed Idris\", \"Phone Number\": \"08067547792\", \"Email Address\": \"ahmedeedat20@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Ilorin\", \"What do you hope to learn from this training?\": \"Everything about snail value chain.\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-05 16:40:43'),
(343, 12, '{\"Full Name\": \"Taiwo Baliqees Omolayo\", \"Phone Number\": \"08148299552\", \"Email Address\": \"baliqeeshassan@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Ilorin/Kwara State\", \"What do you hope to learn from this training?\": \"Snail farming and market availability.\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-05 16:44:07'),
(344, 12, '{\"Full Name\": \"Jerushah Ketah Paul\", \"Phone Number\": \"08167825961\", \"Email Address\": \"jerushahpaul2000@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"intend to join the snail learning program because I want to build knowledge that will help me create a sustainable source of income. Snail farming is a growing business, and the training will equip me with the right techniques to succeed.\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-08 09:49:03'),
(345, 12, '{\"Full Name\": \"Anthony Chukwuma Isiekwene\", \"Phone Number\": \"07032191429\", \"Email Address\": \"isiekweneanthony@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Port Harcourt\", \"What do you hope to learn from this training?\": \"Learn to start a snail farming business.\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-08 20:02:59'),
(346, 12, '{\"Full Name\": \"Ohize Rofiat Ozohu\", \"Phone Number\": \"08062739857\", \"Email Address\": \"ohizerofiat@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"As a beginner I want to learn alot about the snail business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 08:24:11'),
(347, 12, '{\"Full Name\": \"Salisu umar Alhassan\", \"Phone Number\": \"08062419068\", \"Email Address\": \"sumaralhassan1024@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Kano State\", \"What do you hope to learn from this training?\": \"Snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"English\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 08:37:26'),
(348, 12, '{\"Full Name\": \"Dorothy.C Ayodeji\", \"Phone Number\": \"07035653994\", \"Email Address\": \"christopherdorothy6@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"To breed snails\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 08:37:37'),
(349, 12, '{\"Full Name\": \"Maryam Munir Galma\", \"Phone Number\": \"07062911373\", \"Email Address\": \"mmunirgalma@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna South\", \"What do you hope to learn from this training?\": \"I hope to gain more and more knowledge that I don&#039;t know before\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 08:41:33'),
(350, 12, '{\"Full Name\": \"Maryam Ibrahim\", \"Phone Number\": \"09069248478\", \"Email Address\": \"maryamibrahim8853@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Bauchi\", \"What do you hope to learn from this training?\": \"Henna\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 08:46:05'),
(351, 12, '{\"Full Name\": \"Maryam Ibrahim\", \"Phone Number\": \"09069248478\", \"Email Address\": \"maryamibrahim8853@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Bauchi\", \"What do you hope to learn from this training?\": \"Henna\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 08:47:44'),
(352, 12, '{\"Full Name\": \"Momoh Ifeoluwa\", \"Phone Number\": \"09020885389\", \"Email Address\": \"ifeoluwapelumi655@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Ibadan Oyo state\", \"What do you hope to learn from this training?\": \"To learn how to manage the snails, their feed formulation and how to earn from it\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 08:50:33'),
(353, 12, '{\"Full Name\": \"Timothy Ayuba\", \"Phone Number\": \"08032793967\", \"Email Address\": \"timothyayuba445@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"I hope to learn snail breeding business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 08:54:49'),
(354, 12, '{\"Full Name\": \"Hayatu Hamza\", \"Phone Number\": \"07034609869\", \"Email Address\": \"hayatuhamza24@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Katsina state, Dutsin_ma town\", \"What do you hope to learn from this training?\": \"Yes I do\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Sign language interpretation is a vital accessibility service for ensuring Deaf and hard-of-hearing people can fully participate, says\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 09:09:14'),
(355, 12, '{\"Full Name\": \"Nkeiruka Nnaji\", \"Phone Number\": \"08066172901\", \"Email Address\": \"nnajinkeruka@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"I want to learn more train\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Sign language interpter\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 10:15:07'),
(356, 12, '{\"Full Name\": \"Sarah istifanus\", \"Phone Number\": \"09149142733\", \"Email Address\": \"sarahistifanus9@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"I want to train more\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Sign language\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 10:20:18'),
(357, 12, '{\"Full Name\": \"Fatima Mohammed Tajudeen\", \"Phone Number\": \"07064745183\", \"Email Address\": \"oluwasheyi429@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"How to start my small scale business Enterprise\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 10:33:02'),
(358, 12, '{\"Full Name\": \"Musa Maxwell Kure\", \"Phone Number\": \"0803 200 2445\", \"Email Address\": \"maxwellkure@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Jos\", \"What do you hope to learn from this training?\": \"To gain knowledge on snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 10:51:08'),
(359, 12, '{\"Full Name\": \"Esther sofa\", \"Phone Number\": \"08132234342\", \"Email Address\": \"rinasofa51@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"How to start snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Non I use a wheelchair\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 10:51:52'),
(360, 12, '{\"Full Name\": \"OLUWa Aleje David\", \"Phone Number\": \"08140776731\", \"Email Address\": \"davidprosper1816@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"Am expectant to learn from your professional\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 11:34:04');
INSERT INTO `custom_form_submissions` (`id`, `form_id`, `submission_data`, `submitted_at`) VALUES
(361, 12, '{\"Full Name\": \"Agatha Paul\", \"Phone Number\": \"07063246805\", \"Email Address\": \"paulagatha45@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna city\", \"What do you hope to learn from this training?\": \"Knowledge\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Nil\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 12:06:20'),
(362, 12, '{\"Full Name\": \"Adedoyin Adewale Sijuwade\", \"Phone Number\": \"08130883340\", \"Email Address\": \"sijuadedoyin49@gmail.com\", \"Experience Level\": \"Advanced\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"AMAC Abuja FCT\", \"What do you hope to learn from this training?\": \"More wisdom and knowledge\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 13:48:17'),
(363, 12, '{\"Full Name\": \"Mary ene Godwin\", \"Phone Number\": \"09022228503\", \"Email Address\": \"maryene887@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Abuja/karu\", \"What do you hope to learn from this training?\": \"To learn and get more knowledge about snail\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 14:10:49'),
(364, 12, '{\"Full Name\": \"Obi Christiana\", \"Phone Number\": \"08133647699\", \"Email Address\": \"oc1470383@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Karu\", \"What do you hope to learn from this training?\": \"Snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 15:30:25'),
(365, 12, '{\"Full Name\": \"Oluwakemi Ogundeji\", \"Phone Number\": \"08033758491\", \"Email Address\": \"kemi.ogundeji20@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Port Harcourt\", \"What do you hope to learn from this training?\": \"How to raise snails, how to market it, how to make money out of it.\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 17:00:26'),
(366, 12, '{\"Full Name\": \"Sadiya Rabiu Dahir\", \"Phone Number\": \"08141161117\", \"Email Address\": \"sadiyarabiudahiru@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kano state\", \"What do you hope to learn from this training?\": \"I hope to learn how to start the snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 18:59:48'),
(367, 12, '{\"Full Name\": \"Sadiya Rabiu Dahir\", \"Phone Number\": \"08141161117\", \"Email Address\": \"sadiyarabiudahiru@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kano state\", \"What do you hope to learn from this training?\": \"I hope to learn how to start the snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 19:01:06'),
(368, 12, '{\"Full Name\": \"Chime Chika\", \"Phone Number\": \"08065634016\", \"Email Address\": \"chikachime37@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"Snail\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 19:31:27'),
(369, 12, '{\"Full Name\": \"Okoh Ruth Favour\", \"Phone Number\": \"08077922766\", \"Email Address\": \"ruthokoh066@gmail.com\", \"Experience Level\": \"Advanced\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Abuja NAsarawa Mararaba\", \"What do you hope to learn from this training?\": \"To be productive and be of help to myself and sosity at large\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Rap flour\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 19:32:52'),
(370, 12, '{\"Full Name\": \"Hamza Alhassan Dantata\", \"Phone Number\": \"08148783565\", \"Email Address\": \"hamzaalhassandantata@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Zaria, Kaduna State\", \"What do you hope to learn from this training?\": \"Learn how to start Snail farm my own business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 20:50:53'),
(371, 12, '{\"Full Name\": \"Dike Oluchi Christiana\", \"Phone Number\": \"08068306423\", \"Email Address\": \"dikeoluchi2019@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Asokoro, Abuja\", \"What do you hope to learn from this training?\": \"To learn snail farming to sustain livelihood and to help other disabled persons in my community.\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes!\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-09 21:41:54'),
(372, 12, '{\"Full Name\": \"VIVIAN NGOKA\", \"Phone Number\": \"08134076398\", \"Email Address\": \"ngokav@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Guri\", \"What do you hope to learn from this training?\": \"How to train life stock\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Bold prints\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-10 07:34:30'),
(373, 12, '{\"Full Name\": \"Aisha abdullahi abubakar\", \"Phone Number\": \"08069977269\", \"Email Address\": \"Aishaabdullahiabubakar40@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Sheka gidan ledan\", \"What do you hope to learn from this training?\": \"Fashion tailoring\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Deaf sign language\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-10 10:22:32'),
(374, 12, '{\"Full Name\": \"Aisha abdullahi abubakar\", \"Phone Number\": \"08069977269\", \"Email Address\": \"Aishaabdullahiabubakar40@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Sheka gidan ledan\", \"What do you hope to learn from this training?\": \"Fashion tailoring\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Deaf sign language\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-10 10:23:43'),
(375, 12, '{\"Full Name\": \"Bulus Kabu Chibok\", \"Phone Number\": \"08031150929\", \"Email Address\": \"buluskabu@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Maiduguri, Borno State.\", \"What do you hope to learn from this training?\": \"Skill\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-10 16:10:30'),
(376, 12, '{\"Full Name\": \"Shamsuddeni Dahiru Musa\", \"Phone Number\": \"09135415747\", \"Email Address\": \"dshamsuddeni1@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kano\", \"What do you hope to learn from this training?\": \"Product management and agriculture\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-10 20:53:05'),
(377, 12, '{\"Full Name\": \"Ruth Danjuma\", \"Phone Number\": \"08131692649\", \"Email Address\": \"ruthdanjuma67@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Kaduna north/kaduna state\", \"What do you hope to learn from this training?\": \"I hope to a lot\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 05:58:34'),
(378, 12, '{\"Full Name\": \"Rashidat Tauheed isah\", \"Phone Number\": \"07037864098\", \"Email Address\": \"rashidattauheedisah@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Fct/fct/ kuje area council\", \"What do you hope to learn from this training?\": \"To know more about snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 06:01:52'),
(379, 12, '{\"Full Name\": \"Grace Etukudo Wilson\", \"Phone Number\": \"09010855437\", \"Email Address\": \"farmoniqueresources@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Eket LGA Akwa Ibom State\", \"What do you hope to learn from this training?\": \"Everything about a successful snail farming business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 06:03:13'),
(380, 12, '{\"Full Name\": \"Shaapera Mercy\", \"Phone Number\": \"08165742342\", \"Email Address\": \"shaapera2022@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"I hope to learning and grow acknowledge that will gain me in future\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Sign language interpreter\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 06:15:17'),
(381, 12, '{\"Full Name\": \"Martha Unekwuojo Anthony\", \"Phone Number\": \"08104182064\", \"Email Address\": \"unekwuthony@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Sabon Gari\", \"What do you hope to learn from this training?\": \"How to rear snail to start up a snail rearing business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 06:18:39'),
(382, 12, '{\"Full Name\": \"Joy Michael uyo\", \"Phone Number\": \"08160682604\", \"Email Address\": \"joymichael247@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"I hope to learn how they are training snail\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 06:28:17'),
(383, 12, '{\"Full Name\": \"BENIBO DONALD PAUL\", \"Phone Number\": \"07064971087\", \"Email Address\": \"donatybliss@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Port Harcourt Rivers State\", \"What do you hope to learn from this training?\": \"Yes for sure\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 06:44:33'),
(384, 12, '{\"Full Name\": \"Maryam Lamido\", \"Phone Number\": \"07061319090\", \"Email Address\": \"lamidomaryam@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"How to make livelihood from the business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 06:46:55'),
(385, 12, '{\"Full Name\": \"Member Omokhafe Balogun\", \"Phone Number\": \"08139594906\", \"Email Address\": \"mrjohnbalo@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"Verbal or online\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 06:48:43'),
(386, 12, '{\"Full Name\": \"Durowade abdulraheem kehinde\", \"Phone Number\": \"09158885900\", \"Email Address\": \"durowadeabdulraheem@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Ilorin kwara\", \"What do you hope to learn from this training?\": \"To have mine\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"English\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 06:52:19'),
(387, 12, '{\"Full Name\": \"Aminat Ibrahim\", \"Phone Number\": \"09064600433\", \"Email Address\": \"aminatibrahim127@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna north\", \"What do you hope to learn from this training?\": \"Knowledge of snail farming and turning it into business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 06:56:37'),
(388, 12, '{\"Full Name\": \"Rabiu Auwal Garba\", \"Phone Number\": \"08033791147\", \"Email Address\": \"rabiug105@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kiru avenue\", \"What do you hope to learn from this training?\": \"To improve my skills\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 06:58:19'),
(389, 12, '{\"Full Name\": \"Aminat Ibrahim\", \"Phone Number\": \"09064600433\", \"Email Address\": \"aminatibrahim127@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna north\", \"What do you hope to learn from this training?\": \"Knowledge of snail farming and turning it into business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 06:59:39'),
(390, 12, '{\"Full Name\": \"HALIMA SALEH IBRAHIM\", \"Phone Number\": \"08038638974\", \"Email Address\": \"salehhalima2020@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Jos North\", \"What do you hope to learn from this training?\": \"To acquire knowledge\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 07:14:47'),
(391, 12, '{\"Full Name\": \"Isah Haruna ismaila\", \"Phone Number\": \"08037400327\", \"Email Address\": \"isahh4323@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Gwale\", \"What do you hope to learn from this training?\": \"Yes\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"See\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 07:19:46'),
(392, 12, '{\"Full Name\": \"Mujittapha Ahmed\", \"Phone Number\": \"08143279326\", \"Email Address\": \"mujittaphaahmed111@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"IKARA LGA kaduna state\", \"What do you hope to learn from this training?\": \"To have a skills\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 07:30:47'),
(393, 12, '{\"Full Name\": \"Boniface Elizabeth Ojochogwu\", \"Phone Number\": \"07032132234\", \"Email Address\": \"ojosbon2@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"More knowledge on snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 08:08:52'),
(394, 12, '{\"Full Name\": \"Adam alhaji ahmad\", \"Phone Number\": \"09025415226\", \"Email Address\": \"adamalhajiahmad@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Damaturu yobe\", \"What do you hope to learn from this training?\": \"Available\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 08:11:23'),
(395, 12, '{\"Full Name\": \"Fatima Abdulhameed\", \"Phone Number\": \"08030539655\", \"Email Address\": \"fatimaabdulhameed66@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Kanol\", \"What do you hope to learn from this training?\": \"No\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Hausa\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 08:29:06'),
(396, 12, '{\"Full Name\": \"Idowu Olugbenga Omotayo\", \"Phone Number\": \"08070551400\", \"Email Address\": \"geebenga2013@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Ibadan\", \"What do you hope to learn from this training?\": \"Skills\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 08:30:25'),
(397, 12, '{\"Full Name\": \"Queen Odede Christopher\", \"Phone Number\": \"07068185821\", \"Email Address\": \"christopherqueen095@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"Everything I need to start up the business and to be sustainable\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 08:30:42'),
(398, 12, '{\"Full Name\": \"Obi Christiana\", \"Phone Number\": \"08133647699\", \"Email Address\": \"oc1470383@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Karu\", \"What do you hope to learn from this training?\": \"I hope to gain knowledge on how to support myself in business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 09:03:19'),
(399, 12, '{\"Full Name\": \"Naansing Zailani\", \"Phone Number\": \"08037770704\", \"Email Address\": \"edifyjones@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Jos\", \"What do you hope to learn from this training?\": \"Snail business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Nope\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 09:16:52'),
(400, 12, '{\"Full Name\": \"Chidinma ezemah\", \"Phone Number\": \"08068815999\", \"Email Address\": \"chidinmaeze486@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"BWARI abuja\", \"What do you hope to learn from this training?\": \"The most important thing I learned during my training was targeting student as I am writing this I am imagining business catering and bakery moveing around the classroom\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 09:32:34'),
(401, 12, '{\"Full Name\": \"Abdulsalam Alimat Sadiat\", \"Phone Number\": \"07033669970\", \"Email Address\": \"alimatabdulsalam87@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ilorin\", \"What do you hope to learn from this training?\": \"All about snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 10:04:45'),
(402, 12, '{\"Full Name\": \"ALHASSAN RAYYANU\", \"Phone Number\": \"08036536949\", \"Email Address\": \"alhassanrayyanu63@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Kaduna  State\", \"What do you hope to learn from this training?\": \"This is a prompt asking about your personal learning goals and expectations for the training course. To answer effectively, consider what you want to gain, such as:  * Specific Skills: e.g., &quot;To master advanced Python data analysis techniques,&quot; or &quot;To learn how to use the new project management software.&quot;  * Knowledge Areas: e.g., &quot;To gain a deeper understanding of market trends,&quot; or &quot;To learn the theoretical foundations of renewable energy.&quot;  * Professional Development: e.g., &quot;To improve my leadership and team-building capabilities,&quot; or &quot;To earn a certification recognized in the industry.&quot;  * Problem-Solving: e.g., &quot;To find practical solutions for improving workflow efficiency in my department.&quot; Example Answer: &gt; &quot;I hope to learn practical strategies for implementing agile methodologies in small teams and to gain hands-on experience with the latest cloud collaboration tools.&quot; &gt;  Would you like help phrasing a specific goal?\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"This question is asking if you need Sign Language Interpretation (e.g., an ASL interpreter) to access the course or material effectively due to a hearing impairment. Your answer should be a simple Yes or No.  * Select Yes if you require Sign Language Interpretation.  * Select No if you do not require Sign Language Interpretation.\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 10:18:40'),
(403, 12, '{\"Full Name\": \"SAUDATU ISAH ABDULLAHI\", \"Phone Number\": \"09026955504\", \"Email Address\": \"saudatisah2020@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kano\", \"What do you hope to learn from this training?\": \"How can I be perfect in farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 10:23:46'),
(404, 12, '{\"Full Name\": \"Abubakar Abdulmalik\", \"Phone Number\": \"07030059022\", \"Email Address\": \"abdulmalik.aa39@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Gombe\", \"What do you hope to learn from this training?\": \"Anything that will help ourselves\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 10:53:41'),
(405, 12, '{\"Full Name\": \"Chioma Okeke\", \"Phone Number\": \"08118573017\", \"Email Address\": \"ochioma408@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Igando\", \"What do you hope to learn from this training?\": \"To learn about snails and commercial farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No thanks\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 11:14:40'),
(406, 12, '{\"Full Name\": \"Abubakar ramatu roseline\", \"Phone Number\": \"08035929588\", \"Email Address\": \"Arama256@gmali.com\", \"Experience Level\": \"Advanced\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Bwair\", \"What do you hope to learn from this training?\": \"I learn about to be attend important\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"I&#039;m DEAF\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 11:38:43'),
(407, 12, '{\"Full Name\": \"Abubakar ramatu roseline\", \"Phone Number\": \"08035929588\", \"Email Address\": \"Arama256@gmali.com\", \"Experience Level\": \"Advanced\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Bwair\", \"What do you hope to learn from this training?\": \"I learn about to be attend important\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"I&#039;m DEAF\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 11:39:01'),
(408, 12, '{\"Full Name\": \"Hauwa Uwais Abdulhameed\", \"Phone Number\": \"08161876293\", \"Email Address\": \"hauwauwais2016@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kano state\", \"What do you hope to learn from this training?\": \"I want to learn more in snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 12:28:09'),
(409, 12, '{\"Full Name\": \"Oluwakemi Ogundeji\", \"Phone Number\": \"08033758491\", \"Email Address\": \"kemi.ogundeji20@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Port Harcourt\", \"What do you hope to learn from this training?\": \"How to market snails, how to train snails and others\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 12:53:01'),
(410, 12, '{\"Full Name\": \"Tolase Kolapo\", \"Phone Number\": \"08167013428\", \"Email Address\": \"tolasekolapo@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Lagos\", \"What do you hope to learn from this training?\": \"To gain knowledge on snail farming and start my snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 15:43:44'),
(411, 12, '{\"Full Name\": \"Akinlabi Ramota Abidemi\", \"Phone Number\": \"07069794008\", \"Email Address\": \"akinlabiramota24@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Lagos\", \"What do you hope to learn from this training?\": \"to start the business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 15:49:21'),
(412, 12, '{\"Full Name\": \"Mary Abimiku\", \"Phone Number\": \"09065522098\", \"Email Address\": \"maryabimiku30@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Mararaba Nasarawa state\", \"What do you hope to learn from this training?\": \"Snail farming from scratch\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 18:27:09'),
(413, 12, '{\"Full Name\": \"Unity Elon\", \"Phone Number\": \"08129461784\", \"Email Address\": \"elonunity@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"New nyanya  Nasarawa state\", \"What do you hope to learn from this training?\": \"How to start a snail business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 18:38:31'),
(414, 12, '{\"Full Name\": \"MOJEED AKINSOLA FASANYA\", \"Phone Number\": \"08058483451\", \"Email Address\": \"akinsolafasanya@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Ibadan North\", \"What do you hope to learn from this training?\": \"To have a wide experience and knowledge in modern snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 19:22:23'),
(415, 12, '{\"Full Name\": \"Jennifer uchechi ibemma\", \"Phone Number\": \"08102951331\", \"Email Address\": \"jenniferibemma@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Surulere\", \"What do you hope to learn from this training?\": \"I need learning snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 19:50:23'),
(416, 13, '{\"Full Name\": \"SHUAIB ABDOOL\", \"Phone Number\": \"08122598372\", \"Email Address\": \"shuaibabdul192@gmail.com\", \"Expertise Sector\": \"Law\", \"Location (City/State)\": \"kANO\", \"What do you know about disability and inclusion?\": \"test\", \"What is your availability for conducting training sessions?\": \"test\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"test\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"test\"}', '2025-12-11 22:40:32'),
(417, 12, '{\"Full Name\": \"Oyiboka Monica Arinze\", \"Phone Number\": \"08036045972\", \"Email Address\": \"monikyibo@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"Every about snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-11 23:54:49'),
(418, 12, '{\"Full Name\": \"Shaapera Mercy\", \"Phone Number\": \"08165742352\", \"Email Address\": \"shaapera2022@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"I hope to learning and grow acknowledge to gain also it will help me in future\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes need interpreter\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 02:17:40'),
(419, 12, '{\"Full Name\": \"Abiodun Christianah\", \"Phone Number\": \"08037684502\", \"Email Address\": \"abiodunnohor71@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ilesa\", \"What do you hope to learn from this training?\": \"How to rear snail and sell\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 04:50:10'),
(420, 12, '{\"Full Name\": \"Okeke chiamaka\", \"Phone Number\": \"08066288318\", \"Email Address\": \"daniellachii40@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ikorodu Lagos\", \"What do you hope to learn from this training?\": \"To learn and start a business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No I am visually impaired\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 05:10:02'),
(421, 12, '{\"Full Name\": \"Agatha Paul\", \"Phone Number\": \"07063246805\", \"Email Address\": \"agathapaul760@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"Nil\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Nil\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 09:46:12'),
(422, 12, '{\"Full Name\": \"Paul kuyet\", \"Phone Number\": \"07063246805\", \"Email Address\": \"paulagatha45@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"Nil\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Nil\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 10:43:16'),
(423, 12, '{\"Full Name\": \"Sule Zainab Lydia\", \"Phone Number\": \"08068961660\", \"Email Address\": \"queenzee441@gmail.com\", \"Experience Level\": \"Intermediate\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ankpa\", \"What do you hope to learn from this training?\": \"To learn more about snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 11:04:17'),
(424, 12, '{\"Full Name\": \"SHUAIB ABDOOL\", \"Phone Number\": \"08122598372\", \"Email Address\": \"shuaibabdul192@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"kANO\", \"What do you hope to learn from this training?\": \"fgggh\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"gfdgjn\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 11:10:25'),
(425, 12, '{\"Full Name\": \"SHUAIB ABDOOL\", \"Phone Number\": \"08122598372\", \"Email Address\": \"shuaibabdul192@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"kANO\", \"What do you hope to learn from this training?\": \"test\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"test\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 11:12:08'),
(426, 12, '{\"Full Name\": \"Paul kuyet\", \"Phone Number\": \"07063246805\", \"Email Address\": \"paulagatha45@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"Nil\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Nil\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 11:15:07'),
(427, 12, '{\"Full Name\": \"Paul kuyet\", \"Phone Number\": \"07063246805\", \"Email Address\": \"paulagatha45@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"Nil\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Nil\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 11:15:20'),
(428, 12, '{\"Full Name\": \"Igwe, Victor Chinonye\", \"Phone Number\": \"08149317334\", \"Email Address\": \"igwevictor8@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Port Harcourt\", \"What do you hope to learn from this training?\": \"How to start my own snail farm\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 13:31:05'),
(429, 12, '{\"Full Name\": \"Olutayo Dorcas Dolapo\", \"Phone Number\": \"08055690392\", \"Email Address\": \"dearieolutayo1@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Ibadan /Oyo\", \"What do you hope to learn from this training?\": \"To build on the existing foundation I have.\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 14:43:55'),
(430, 12, '{\"Full Name\": \"NWOBA DAVID UGOCHUKWU\", \"Phone Number\": \"07067007714\", \"Email Address\": \"nwobadavid20@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"To learn a professional method of snails farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 15:28:31'),
(431, 12, '{\"Full Name\": \"Ikechukwu Iyke Igbokwe\", \"Phone Number\": \"08108199667\", \"Email Address\": \"ikeigbokwe70@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna Town\", \"What do you hope to learn from this training?\": \"I hope to gain profitable business opportunities, experiences and agricultural skills\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 17:36:09'),
(432, 12, '{\"Full Name\": \"Tamar Obadiah\", \"Phone Number\": \"08032850019\", \"Email Address\": \"obadiahtamar@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Kaduna state\", \"What do you hope to learn from this training?\": \"I hope to learn every about snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 17:44:29'),
(433, 12, '{\"Full Name\": \"Asheta Rosemary Ayi\", \"Phone Number\": \"08059948545\", \"Email Address\": \"ashetarosemary@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Cross River State\", \"What do you hope to learn from this training?\": \"How to train snail\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 17:52:23'),
(434, 12, '{\"Full Name\": \"Asheta Rosemary Ayi\", \"Phone Number\": \"08059948545\", \"Email Address\": \"ashetarosemary@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Cross River State\", \"What do you hope to learn from this training?\": \"How to train snail\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 17:52:41'),
(435, 12, '{\"Full Name\": \"Suleiman Aliyu\", \"Phone Number\": \"08166153815\", \"Email Address\": \"suleimanaliyu317@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Kaduna\", \"What do you hope to learn from this training?\": \"Farming Business skills\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 19:06:47'),
(436, 12, '{\"Full Name\": \"Monjok Leo\", \"Phone Number\": \"07066610724\", \"Email Address\": \"leoashaba@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Lagos\", \"What do you hope to learn from this training?\": \"I hope to have a broad knowledge about snail farming and how to excel in it\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 20:52:55'),
(437, 12, '{\"Full Name\": \"Kikelomo Florence olawuyi\", \"Phone Number\": \"08067501236\", \"Email Address\": \"kikelomogbola39@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Abuja\", \"What do you hope to learn from this training?\": \"To have knowledge on how to train a snail\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 20:56:36');
INSERT INTO `custom_form_submissions` (`id`, `form_id`, `submission_data`, `submitted_at`) VALUES
(438, 12, '{\"Full Name\": \"Hakima Junaid Muhammad\", \"Phone Number\": \"09037921852\", \"Email Address\": \"hakeemahjunaidmuhammad@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kano state\", \"What do you hope to learn from this training?\": \"I want to learn business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 21:14:11'),
(439, 12, '{\"Full Name\": \"Omotola Eniola Falodun\", \"Phone Number\": \"08119978595\", \"Email Address\": \"adebisiomotola57@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ikorodu Lagos\", \"What do you hope to learn from this training?\": \"To learn how to do snail business\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Nope\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 21:36:54'),
(440, 12, '{\"Full Name\": \"Emmanuel Awoyemi\", \"Phone Number\": \"08148154734\", \"Email Address\": \"emmanuelawoyemi917@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Abeokuta South\", \"What do you hope to learn from this training?\": \"Snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-12 22:15:48'),
(441, 12, '{\"Full Name\": \"Ubenyi Sarah Anoyoyi\", \"Phone Number\": \"08067926049\", \"Email Address\": \"ubenyisarah@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Jos\", \"What do you hope to learn from this training?\": \"Alot\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-13 00:09:51'),
(442, 12, '{\"Full Name\": \"HALIMA SALEH IBRAHIM\", \"Phone Number\": \"08038638974\", \"Email Address\": \"salehhalima2020@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Jos North\", \"What do you hope to learn from this training?\": \"Knowledge\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-13 07:21:50'),
(443, 12, '{\"Full Name\": \"Adeola Ogunleye\", \"Phone Number\": \"08054673726\", \"Email Address\": \"Adeolahealth@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Lagos\", \"What do you hope to learn from this training?\": \"How to start and scale the business of snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-13 08:51:44'),
(444, 12, '{\"Full Name\": \"OTEIKWU SAMUEL AMEDU\", \"Phone Number\": \"08036240456\", \"Email Address\": \"samuelamedu77@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Not Person With Disability\", \"Location (City/State)\": \"Benue State\", \"What do you hope to learn from this training?\": \"Alot\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-13 14:55:46'),
(445, 12, '{\"Full Name\": \"FATIMA SULEIMAN YAKUBU\", \"Phone Number\": \"08033033581\", \"Email Address\": \"fatimayakubu65@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Kaduna,kaduna state\", \"What do you hope to learn from this training?\": \"How tostart snail farming\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Maybe\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-13 15:21:14'),
(446, 12, '{\"Full Name\": \"Roseline\", \"Phone Number\": \"07033400863\", \"Email Address\": \"ap.roses90@gmail.com\", \"Experience Level\": \"Beginner\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Ilorin\", \"What do you hope to learn from this training?\": \"Yes\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"No\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-13 18:16:24'),
(447, 12, '{\"Full Name\": \"Dallah Kenneth agasi\", \"Phone Number\": \"07068945240\", \"Email Address\": \"kdallah419@gmail.com\", \"Experience Level\": \"Advanced\", \"Disability Status\": \"Person With Disability\", \"Location (City/State)\": \"Nasarawa\", \"What do you hope to learn from this training?\": \"To knowledge and benefits\", \"Accessibility Needs: Do you require Sign Language Interpretation?\": \"Yes\", \"Business Intent: Do you intend to start a snail farming business?\": \"Yes\", \"Confirmation: I would like to receive updates via email or WhatsApp\": \"Yes\"}', '2025-12-15 07:10:51'),
(448, 13, '{\"Full Name\": \"Fatimah Usman\", \"Phone Number\": \"8109571340\", \"Email Address\": \"albatulusman93@gmail.com\", \"Expertise Sector\": \"Education\", \"Location (City/State)\": \"Naibawa, Kano\", \"What do you know about disability and inclusion?\": \"I am certified disability inclusion facilitator\", \"What is your availability for conducting training sessions?\": \"Anytime\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"I believe I&#039;m an excellent disability inclusion facilitator\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"I have been training partner organisations on disability inclusion, disability etiquettes and respectful languages, types of disability, barriers and types, perspective of viewing disability, inclusion, segregation, integration and exclusion\"}', '2025-12-17 12:48:55'),
(449, 13, '{\"Full Name\": \"Bangaskiya Joshua\", \"Phone Number\": \"07015218591\", \"Email Address\": \"bangaskiya18@gmail.com\", \"Expertise Sector\": \"Education\", \"Location (City/State)\": \"FCT Abuja\", \"What do you know about disability and inclusion?\": \"Disability are people with special needs and attention.\\r\\n\\r\\nInclusion is taking everyone along.\\r\\n\\r\\nPeople with disability are special people in the society,that doesn&#039;t need to be discriminate because of their special needs but they are to be taken along.\", \"What is your availability for conducting training sessions?\": \"Virtual and physical\", \"Are you willing to commit to a minimum number of training sessions?\": \"No\", \"Is there any additional information you would like to share with us?\": \"No thanks.\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"No\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"But I&#039;m a qualify teacher.\"}', '2025-12-17 13:15:57'),
(450, 13, '{\"Full Name\": \"Rotgak Danjuma Kassam\", \"Phone Number\": \"08036586209\", \"Email Address\": \"rotgakprof@gmail.com\", \"Expertise Sector\": \"Education\", \"Location (City/State)\": \"Lafia Nasarawa state\", \"What do you know about disability and inclusion?\": \"I am knowledgeable in special needs education, community based rehabilitation and inclusive practices. I have fourteen research publications in disability, rehabilitation and inclusion\", \"What is your availability for conducting training sessions?\": \"Very available when duty calls\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"I have more ideas on disabilities and inclusion for your organization, if given the opportunity, I can partner as an advisor to the organization\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"I facilitate for Universal basic Eeducation Commission On inclusive education. I have been on this role of facilitator for the past three years.\"}', '2025-12-17 16:19:50'),
(451, 13, '{\"Full Name\": \"Aminat Ibrahim\", \"Phone Number\": \"09064600433\", \"Email Address\": \"aminatibrahim127@gmail.com\", \"Expertise Sector\": \"Others\", \"Location (City/State)\": \"Kaduna north\", \"What do you know about disability and inclusion?\": \"Disability and inclusion are about recognizing human diversity and making sure everyone can participate fully in society with dignity and respect.\", \"What is your availability for conducting training sessions?\": \"Immediately\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"Am a person with physical impairment\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"I have experience facilitating interactive training sessions on disability inclusion, focusing on barriers, respectful language, and practical engagement. I use a learner-centered approach that encourages participation, discussion, and real-life application.\\r\\n\\r\\nI have facilitated for different organizations like jobberman, IITA, SCL etc with this organization was quite a lot of experience.\"}', '2025-12-18 08:53:56'),
(452, 13, '{\"Full Name\": \"Hamidat Ajibola\", \"Phone Number\": \"09066204681\", \"Email Address\": \"hamidatajibola33@gmail.com\", \"Expertise Sector\": \"Healthcare\", \"Location (City/State)\": \"kaduna Kaduna State\", \"What do you know about disability and inclusion?\": \"Disability inclusion is about creating a society where people with disabilities have equal opportunities and are valued. It&#039;s about accessibility, accommodation, and empowering individuals to reach their full potential. In Nigeria, we can work towards breaking stigmas and promoting inclusivity in education, employment, health education, public spaces, and more. This includes accessible healthcare, inclusive curricula, and opportunities for economic empowerment.\", \"What is your availability for conducting training sessions?\": \"Am always available\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"No\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"No\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"\"}', '2025-12-18 09:23:13'),
(453, 13, '{\"Full Name\": \"Joy Samuel\", \"Phone Number\": \"+2348069317560\", \"Email Address\": \"samueljoy2206@gmail.com\", \"Expertise Sector\": \"Education\", \"Location (City/State)\": \"Kaduna, Kaduna State\", \"What do you know about disability and inclusion?\": \"Disability is a condition where an individual is not able to fully perform or carry out certain activities due to an impairment. \\r\\nEveryone deserves equal rights. This is why inclusion goes beyond accessibility, it focuses on equity and belonging; Inclusion also drives innovation, promotes creativity and participation among persons with disability.\", \"What is your availability for conducting training sessions?\": \"I am generally available on Mondays, Wednesdays and Saturdays from 11:00 AM to 2:00 PM.  But I also work on my availability outside the days and time stated if need be.\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"I have been part of team of facilitators to train and create awareness among women and young girls with disabilities on sexual rights and reproductive health.\\r\\n\\r\\nAs a facilitator, I was tasked with empowering women and girls with disabilities by providing essential education and awareness on sexual rights and reproductive health to promote self consciousness and informed decision-making.\\r\\n\\r\\nI am also leading the Gender and Social Inclusion subcommittee of the OpenGov youth innovation hub where I have organized and facilitated trainings on awareness and inclusion for persons with disability.\"}', '2025-12-18 14:08:16'),
(454, 13, '{\"Full Name\": \"Oluwatomisin Adewunmi Adeyefa\", \"Phone Number\": \"07036339628\", \"Email Address\": \"tomisin92adeyefa@gmail.com\", \"Expertise Sector\": \"Others\", \"Location (City/State)\": \"Abeokuta Ogun state\", \"What do you know about disability and inclusion?\": \"To me, disability and inclusion is the intentional practice of identifying and removing barriers—be they physical, attitudinal, or institutional—to ensure that persons with disabilities (PWDs) have equal access to opportunities and can participate with dignity.\\r\\n My understanding is built on three core pillars:\\r\\n1.  The Social Model of Disability: I believe that disability is not an individual &#039;defect&#039; to be fixed, but a result of how society is organized. My role as a facilitator is to help organizations shift their focus from the impairment to the environment, ensuring that the &#039;standard&#039; way of doing things doesn&#039;t exclude talent.\\r\\n2.  Reasonable Accommodation and Universal Design: Inclusion is most effective when it is proactive. Whether it is through Universal Design (creating systems usable by everyone from the start) or Reasonable Accommodations (specific adjustments for an individual), the goal is to create a level playing field.\\r\\n3.  Economic Empowerment and Dignity: True inclusion goes beyond charity. It is about harnessing the unique skills of PWDs for fulfilling employment. In my work with JONAPWD, I have seen how modeling inclusive practices at organizations like Terra Academy for the Arts (TAFTA) and Soiless Farm Lab transforms not just the lives of the individuals, but the productivity and culture of the partner organizations themselves.\\r\\n Ultimately, my approach is guided by the principle of &#039;Nothing About Us Without Us.&#039; As a facilitator, I don&#039;t just speak for PWDs; I work alongside them and partner organizations to ensure that inclusion is sustainable, measurable, and integrated into the very DNA of the workplace\", \"What is your availability for conducting training sessions?\": \"Very available\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"In the question about expertise sector, I chose others because I have experience and expertise across multiple sectors.\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"I am a certified Disability Inclusion Facilitator (DIF), trained and certified by the Joint National Association of Persons with Disabilities (JONAPWD) with the support of the Mastercard Foundation. My experience involves designing and delivering training sessions focused on disability-inclusive practices and workplace accessibility.\\r\\n In my current role, I support partner organizations in Ogun State, such as Terra Academy for the Arts (TAFTA), Christian Aid, IDH, and Soiless Farm Lab, as well as members in the disability community by facilitating workshops that help them harness opportunities for fulfilling and dignified employment for Persons with Disabilities. I have experience in facilitating both virtual and physical sessions. My facilitation style is participatory, ensuring that inclusion is not just a policy but a practical model integrated into organizational culture.&quot;\"}', '2025-12-19 07:57:23'),
(455, 13, '{\"Full Name\": \"Ife Sarah Olowoyeye\", \"Phone Number\": \"09139290059\", \"Email Address\": \"ifeolowoyeye22@gmail.com\", \"Expertise Sector\": \"Education\", \"Location (City/State)\": \"Kaduna\", \"What do you know about disability and inclusion?\": \"Children or adult with special needs\", \"What is your availability for conducting training sessions?\": \"I will be available\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"No\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"\"}', '2025-12-20 05:59:28'),
(456, 13, '{\"Full Name\": \"Ife Sarah Olowoyeye\", \"Phone Number\": \"09139290059\", \"Email Address\": \"ifeolowoyeye22@gmail.com\", \"Expertise Sector\": \"Education\", \"Location (City/State)\": \"Kaduna\", \"What do you know about disability and inclusion?\": \"Children or adult with special needs\", \"What is your availability for conducting training sessions?\": \"I will be available\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"No\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"\"}', '2025-12-20 05:59:28'),
(457, 13, '{\"Full Name\": \"Oluwafunbi Afeniforo\", \"Phone Number\": \"08066426043\", \"Email Address\": \"funbiafeniforo@gmail.com\", \"Expertise Sector\": \"Entrepreneur\", \"Location (City/State)\": \"Ekiti state\", \"What do you know about disability and inclusion?\": \"Disability is when the person has an impairment and experience a barrier that prevents them from performing or participating in a day to day activity like others\\r\\nInclusion is when all groups/persons, have access to the same opportunities and are able to fully participate in the society \\r\\ndisability inclusion means intentional, systematic action to ensure all persons with disabilities have equal access to opportunities to participate in the society and this can be achieved by combining two approaches which are mainstreaming and disability specific interventions which is called twin-track approach.\", \"What is your availability for conducting training sessions?\": \"I am available and flexible to conduct training sessions\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"Not for now\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"I have had an extensive experience in training and facilitation on disability inclusion, which I gained through my work as a Disability Inclusion Facilitator under the We Can Work.\\r\\n\\r\\nIn this role, I have designed and facilitated capacity-building sessions for civil society organizations, private sector actors, and development partners, including UNDP and Young Africa Works partners. My trainings focused on core themes such as the concept of disability and inclusion, models of viewing disability (the human rights–based approach) to disability, reasonable accommodation, accessibility, inclusive program design, and the twin-track approach. I ensured that sessions were practical, participatory, and grounded in real-life program challenges rather than theory alone.\\r\\n\\r\\nI have facilitated both in-person and virtual trainings, using interactive methods such as group discussions, case studies, role plays, and lived-experience storytelling to challenge stereotypes and shift attitudes. I also supported organizations to translate learning into action by providing practical guidance on mainstreaming disability inclusion into recruitment, service delivery, project implementation, and monitoring frameworks.\\r\\n\\r\\nAdditionally, I have trained and supported stakeholders to identify and remove environmental, attitudinal, and institutional barriers, while ensuring reasonable accommodation for persons with different types of impairments. I have also facilitated co-creation sessions where persons with disabilities actively contributed to program design and policy discussions.\\r\\n\\r\\nThrough these training and facilitation experiences, I have strengthened institutional capacity, improved inclusive practices, and promoted sustainable disability inclusion across programs and partnerships.\"}', '2025-12-20 07:34:57'),
(458, 13, '{\"Full Name\": \"Ruqayya Yahaya Haruna\", \"Phone Number\": \"09030546617\", \"Email Address\": \"rukayyayahaya406@gmail.com\", \"Expertise Sector\": \"Education\", \"Location (City/State)\": \"Kano State\", \"What do you know about disability and inclusion?\": \"Inclusion is a situation whereby persons with disabilities are properly, and reasonably accommodated in every aspect of life.\\r\\n\\r\\nDisability is a condition iwhereby a person a long term impairment which causes barriers that will hinder them from participating fully in every life aspect.\", \"What is your availability for conducting training sessions?\": \"I&#039;m always available whenever needed.\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"I&#039;m a person with disability and I am passionate about disability inclusion in every aspect of life.\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"I am a certified disability facilitaor and have been facilitating for the past 16 months.\"}', '2025-12-20 19:50:46'),
(459, 13, '{\"Full Name\": \"Kabiru Sani Fandubu\", \"Phone Number\": \"09034633834\", \"Email Address\": \"kabirsfandubu@gmail.com\", \"Expertise Sector\": \"Law\", \"Location (City/State)\": \"Kano\", \"What do you know about disability and inclusion?\": \"Disability and inclusion are about removing barriers so people with disabilities can participate equally in all areas of life. Inclusion means designing environments, services, and attitudes that respect diversity and ensure accessibility, dignity, and equal opportunity for everyone.\", \"What is your availability for conducting training sessions?\": \"I can adapt to the training sessions\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"No\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"\"}', '2025-12-20 19:55:49'),
(460, 13, '{\"Full Name\": \"Musa Maxwell Kure\", \"Phone Number\": \"0803 200 2445\", \"Email Address\": \"maxwellkure@gmail.com\", \"Expertise Sector\": \"Others\", \"Location (City/State)\": \"Jos\", \"What do you know about disability and inclusion?\": \"Not much but I am willing to learn and contribute to creating the needed awareness.\", \"What is your availability for conducting training sessions?\": \"1 to 2 hours,  twice weekly\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"I am  a Spinal Cord injury Sovivor.\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"I am a eCPR Practitioner and a trained digital facilitator with La Plage Meta Verse.\"}', '2025-12-20 20:26:28'),
(461, 13, '{\"Full Name\": \"John Paul\", \"Phone Number\": \"07036634326\", \"Email Address\": \"ministerjp2015@gmail.com\", \"Expertise Sector\": \"Entrepreneur\", \"Location (City/State)\": \"Gwagwalada area council FCT Abuja\", \"What do you know about disability and inclusion?\": \"Disability are person&#039;s who are in bad condition or a situation where someone is in a state of diformity.\", \"What is your availability for conducting training sessions?\": \"Time factor\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"Early awareness\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"Tutoring\"}', '2025-12-20 20:28:19'),
(462, 13, '{\"Full Name\": \"Chioma Okeke\", \"Phone Number\": \"08118573017\", \"Email Address\": \"ochioma408@gmail.com\", \"Expertise Sector\": \"Education\", \"Location (City/State)\": \"Igando\", \"What do you know about disability and inclusion?\": \"I know that disability and inclusion has affected many lives in the world today especially in Nigeria. I am very concerned about women and children in these categories and I am most willing to take opportunities to join in making life easier for them like empowering them with skills that make them earn living at the comfort of their homes.\", \"What is your availability for conducting training sessions?\": \"I am available on the afternoons\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"Nothing for now. Thanks\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"No\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"\"}', '2025-12-20 20:48:42'),
(463, 13, '{\"Full Name\": \"Hayatu Hamza\", \"Phone Number\": \"07034609869\", \"Email Address\": \"Hayatuhamza24@gmail.com\", \"Expertise Sector\": \"Healthcare\", \"Location (City/State)\": \"Katsina state, Dutsin_ma town\", \"What do you know about disability and inclusion?\": \"Understanding inclusion requires distinguishing between two primary ways of viewing disability:\\r\\nThe Social Model: Asserts that people are &quot;disabled&quot; by societal barriers (e.g., stairs, lack of sign language, prejudice) rather than by their impairments. Inclusion, therefore, focuses on removing these barriers rather than &quot;fixing&quot; the individual.\\r\\nThe Medical Model: Views disability as a &quot;defect&quot; or medical condition to be treated or cured. Inclusion advocates often argue this approach can lead to pity and exclusion. \\r\\nKey Pillars of Inclusion\\r\\nEffective disability inclusion involves several layers of action:\\r\\nAccessibility: Designing physical spaces (ramps, wide doorways) and digital environments (screen reader compatibility) to be usable by everyone.\\r\\nReasonable Accommodations: Providing specific adjustments, such as flexible work hours, assistive technology, or sign language interpreters, to enable full participation.\\r\\nUniversal Design: Creating products and environments that are usable by all people from the start, without the need for specialized adaptation.\\r\\nInclusive Language: Using respectful terminology. While &quot;person-first language&quot; (e.g., &quot;person with a disability&quot;) is standard internationally, some prefer &quot;identity-first language&quot; (e.g., &quot;disabled person&quot;) to emphasize that society disables them.\", \"What is your availability for conducting training sessions?\": \"I am available 24/7 to provide information, resources, and guidance for your training sessions in 2025. I can assist you immediately by generating training materials, drafting agendas, and explaining core concepts. Depending on which &quot;Ally&quot; program you are referring to, specific organizations provide the following training availability and options for 2025: 1. Workplace Allyship Training (DEI) Several specialized organizations offer live or on-demand sessions for diversity and inclusion:  Onvero: Offers tailor-made Allyship training for teams of all sizes, which can be customized to your organization&#039;s specific schedule. CultureAlly: Provides half-day or full-day in-person workshops, as well as 1–2 hour online training sessions suitable for hybrid teams. Inclusive Employers: Runs a 4-week &quot;Inclusion Allies&quot; program throughout the year, featuring live and recorded sessions, with a recommended commitment of 2 hours per week.\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"None\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"Training and facilitation experience at the DADE Foundation (Digitally Accessible &amp; Diverse Education) centers on empowering Persons with Disabilities (PWDs) through inclusive education and leadership development. \\r\\nBased on the foundation&#039;s established programs for 2025, specific facilitation experience includes:\\r\\nInclusive Skills &amp; Empowerment: Facilitating free inclusive digital skills bootcamps and workplace readiness training designed for the aspirations of PWDs.\\r\\nAlly and Caregiver Support: Delivering mental health and psychosocial support training for both PWDs and their allies to foster safer healthcare navigation.\\r\\nLeadership Development: Facilitating peer-to-peer mentorship programs and leadership development specifically for young PWDs to build belonging and civic engagement.\\r\\nAlly Inclusion Advocacy: Participating in and promoting high-level initiatives like the Nigerian National Disability Summit 2025, which focuses on strengthening disability rights and ally accountability across Africa. \\r\\nThe foundation&#039;s approach shifts from a content-driven model to a participant-driven model, ensuring that learning environments are adjusted for accessibility and that diverse lived experiences are central to the training.\"}', '2025-12-20 23:14:09'),
(464, 13, '{\"Full Name\": \"Esther sofa\", \"Phone Number\": \"08132234342\", \"Email Address\": \"rinasofa51@gmail.com\", \"Expertise Sector\": \"Entrepreneur\", \"Location (City/State)\": \"Kaduna\", \"What do you know about disability and inclusion?\": \"Gender violence \\r\\nFamily planning\", \"What is your availability for conducting training sessions?\": \"Available\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"I use a wheelchair\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"I was a participant on IFLAN project organized by sight savers\"}', '2025-12-21 00:46:34'),
(465, 13, '{\"Full Name\": \"Khadija Muhammad lawan\", \"Phone Number\": \"09038150716\", \"Email Address\": \"khadijamuhammed716@gmail.com\", \"Expertise Sector\": \"Agriculture\", \"Location (City/State)\": \"Kano\", \"What do you know about disability and inclusion?\": \"Hearing impaired and deaf\", \"What is your availability for conducting training sessions?\": \"To learning more about you\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"Yes more successful with God great\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"Ok\"}', '2025-12-22 07:40:57'),
(466, 13, '{\"Full Name\": \"Hamza Alhassan Dantata\", \"Phone Number\": \"08148783565\", \"Email Address\": \"hamzaalhassandantata@gmail.com\", \"Expertise Sector\": \"Others\", \"Location (City/State)\": \"Zaria\", \"What do you know about disability and inclusion?\": \"Disability and inclusion are closely connected ideas about ensuring everyone can participate fully and fairly in society, regardless of physical, sensory, cognitive, mental health, or developmental differences.\", \"What is your availability for conducting training sessions?\": \"I am available weekdays, can be flexible with timing on session\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"No\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"\"}', '2025-12-22 07:41:33'),
(467, 13, '{\"Full Name\": \"Victor Ekondu Amedu\", \"Phone Number\": \"08137685525\", \"Email Address\": \"ekonduvicamedu@gmail.com\", \"Expertise Sector\": \"Education\", \"Location (City/State)\": \"Makurdi Benue state\", \"What do you know about disability and inclusion?\": \"Disability and inclusion involve recognising the rights, dignity, and potential of persons with disabilities and ensuring their full participation in social, educational, economic, and civic life. Inclusive practice requires the removal of physical, attitudinal, communication, and institutional barriers while providing reasonable accommodations and accessible environments. My work emphasises inclusive planning, stakeholder engagement, and rights-based approaches that promote equal opportunities, empowerment, and social participation for persons with disabilities.\", \"What is your availability for conducting training sessions?\": \"I am available for both short-term and long-term training engagements, including weekday and weekend sessions. I am also available for on-site, community-based, and virtual training sessions, subject to prior scheduling.\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"I am passionate about inclusive education and disability rights and bring a strong combination of academic training, field experience, and advocacy work. I am skilled in communication, facilitation, and stakeholder engagement and am committed to delivering practical, impactful training that promotes inclusion, accessibility, and sustainable community development.\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"I hold a Master’s degree in Educational Psychology and I&#039;m a certified teacher (TRCN). I have received coordinator training and have extensive experience facilitating inclusive programmes and advocacy sessions, particularly through my volunteer role as Programme and Advocacy Officer with Dauntless Dream Initiative for Citizens with Disabilities (DDICD). In this role, I support disability-focused programme design, facilitate inclusive planning meetings, prepare training materials, and engage communities on disability awareness and inclusion. I also have experience delivering sensitisation and training sessions through NACTAL and providing psychosocial and academic counselling to vulnerable groups.\"}', '2025-12-22 07:55:12'),
(468, 13, '{\"Full Name\": \"Ngoka Ernest Chinedu\", \"Phone Number\": \"07037821050\", \"Email Address\": \"chinedungoka007@gmail.com\", \"Expertise Sector\": \"Education\", \"Location (City/State)\": \"Kaduna state, Zaria\", \"What do you know about disability and inclusion?\": \"Disability and inclusion are closely connected concepts that focus on ensuring that all people regardless of physical, sensory, intellectual, or psychosocial differences can participate fully and equally in society.\\r\\n\\r\\n\\r\\nDisability refers to long term physical, mental, intellectual, or sensory impairments which, when combined with environmental and social barriers, may hinder a person’s full participation in society.\\r\\n\\r\\nKey perspectives on disability include \\r\\n\\r\\n1.Medical model which\\r\\nViews disability as a problem of the individual that needs treatment or cure.\\r\\n\\r\\n2. Social model\\r\\nSees disability as the result of barriers in society (poor infrastructure, negative attitudes, lack of policies), not just the impairment itself.\\r\\n\\r\\n3. Human-rights model\\r\\nEmphasizes dignity, equality, non-discrimination, and participation. This is the approach adopted by the UN Convention on the Rights of Persons with Disabilities (UNCRPD).\\r\\n\\r\\nCommon types of disabilities\\r\\n1. Physical (mobility impairments)\\r\\n2. Sensory (visual or hearing impairments)\\r\\n3. Intellectual (learning difficulties)\\r\\n4. Psychosocial (mental health conditions)\\r\\n5. Multiple disabilities\\r\\n\\r\\n\\r\\nInclusion:\\r\\nInclusion means creating environments, policies, and practices that enable everyone especially marginalized groups like persons with disabilities to participate fully in social, economic, political, and cultural life.\\r\\n\\r\\nInclusion goes beyond access; it ensures belonging, respect, and meaningful participation.\\r\\n\\r\\n\\r\\nDisability inclusion involves intentionally removing barriers and providing reasonable accommodations so persons with disabilities can enjoy equal opportunities.\\r\\n\\r\\nKey elements of disability inclusion include \\r\\n\\r\\n1. Accessibility: ramps, elevators, accessible transport, braille, sign language\\r\\n\\r\\n2. Equal opportunities: Disability and inclusion are closely connected concepts that focus on ensuring that all people—regardless of physical, sensory, intellectual, or psychosocial differences—can participate fully and equally in society.\\r\\n\\r\\n3. Participation: involving persons with disabilities in decision-making (“Nothing about us without us”)\\r\\n\\r\\n4. Non-discrimination: laws and policies that protect rights\\r\\n\\r\\n5. Reasonable accommodation: adjustments that enable participation without undue burden\\r\\n\\r\\nWhy disability inclusion matters\\r\\n1. It promotes social justice and human rights\\r\\n2. It reduces poverty and exclusion\\r\\n3. It strengthens development outcomes\\r\\n4. It encourages diversity and innovation\\r\\n5. It builds more inclusive and resilient communities\", \"What is your availability for conducting training sessions?\": \"Physically and virtually available\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"No... That should be all for now\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"No\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"\"}', '2025-12-22 08:07:50'),
(469, 13, '{\"Full Name\": \"Jonah Karo Bulus\", \"Phone Number\": \"08026498085\", \"Email Address\": \"jonahbuluskaro040@gmail.com\", \"Expertise Sector\": \"Others\", \"Location (City/State)\": \"Ungwan Yelwa Television Kaduna State\", \"What do you know about disability and inclusion?\": \"Equal opportunity\", \"What is your availability for conducting training sessions?\": \"Full time\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"I advocate through drama and visual arts\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"An Inclusive champion with Sightsavers\"}', '2025-12-22 12:16:23'),
(470, 13, '{\"Full Name\": \"JAMES MERCY\", \"Phone Number\": \"09012811770\", \"Email Address\": \"james4mercy321@gmail.com\", \"Expertise Sector\": \"Education\", \"Location (City/State)\": \"KAKURI (KADUNA )\", \"What do you know about disability and inclusion?\": \"Disability is a physical, mental,cognitive or developmental condition that impairs or limits a person&#039;s ability to engage in a certain tasks or actions or participate fully in typical daily activities and interactions.Though looks disabled and are called so,but they are not disadvantaged, they are capable.\", \"What is your availability for conducting training sessions?\": \"My availability as the LORD permits is to ensure positive impact, what will be of help and life transforming towards people with disability.\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"With guidance and help towards people with disability, I have seen some of them who became great entrepreneurs, teachers, religious leaders and many others which I may not know. Like I said earlier, though, they are identified as disabled but are not disadvantaged.  When ever duty calls, I am at their service. Except otherwise. Thanks.\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"I have passed through 4years training in Guidance and Counselling with the ABU Zaria, taught and instructed on how to guide and counsel people who are faced with such conditions. And never to admit that they can do nothing, instead be positive about themselves as they can do anything they put their mind to do, and become a blessing to their lives and in the society they found themselves.\"}', '2025-12-22 16:30:49'),
(471, 13, '{\"Full Name\": \"Jesufemi Dunsin Oluajagbe\", \"Phone Number\": \"08164229604\", \"Email Address\": \"oluajagbejesufemi@gmail.com\", \"Expertise Sector\": \"Education\", \"Location (City/State)\": \"Ilorin, Kwara State\", \"What do you know about disability and inclusion?\": \"Disability and inclusion involve recognizing and addressing the barriers that prevent people with disabilities from fully participating in society. This includes; physical accessibility (e.g., infrastructure, transportation), digital accessibility (e.g., accessible websites, assistive technologies), social inclusion (e.g., equal opportunities, respect, and understanding).\\r\\nInclusion on the other hand  means; providing equal access to education, employment, and services, accommodating diverse needs, promoting inclusive attitudes and practices.\\r\\nBy fostering disability inclusion, we can create a more equitable and diverse society where everyone has the opportunity to thrive\", \"What is your availability for conducting training sessions?\": \"I am fully available\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"Being a Disability Inclusion Facilitator and a member of the Disability Inclusion Advisory team of the We Can Work program under the Joint National Association of Persons With Disabilities, I have successfully facilitated and delivered series of disability awareness trainings for Young Africa Works partners, some of which includes United Nations Development program and Young Africa Innovates program.\"}', '2025-12-22 18:26:47'),
(472, 13, '{\"Full Name\": \"Kausar Muhammad tajudeen\", \"Phone Number\": \"08107080083\", \"Email Address\": \"kausarmuhammadtajudeen@gmail.com\", \"Expertise Sector\": \"Healthcare\", \"Location (City/State)\": \"Kaduna state\", \"What do you know about disability and inclusion?\": \"Is a permanent deformity\", \"What is your availability for conducting training sessions?\": \"Yes\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"No\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"No\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"Nil\"}', '2025-12-22 18:41:30'),
(473, 13, '{\"Full Name\": \"Kausar Muhammad tajudeen\", \"Phone Number\": \"08107080083\", \"Email Address\": \"kausarmuhammadtajudeen@gmail.com\", \"Expertise Sector\": \"Healthcare\", \"Location (City/State)\": \"Kaduna state\", \"What do you know about disability and inclusion?\": \"Is a permanent deformity\", \"What is your availability for conducting training sessions?\": \"Yes\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"No\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"No\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"Nil\"}', '2025-12-22 18:41:47'),
(474, 13, '{\"Full Name\": \"Maryann ijeoma Izuegbunam\", \"Phone Number\": \"08082682851\", \"Email Address\": \"maryannizuegbunam@hotmail.com\", \"Expertise Sector\": \"Entrepreneur\", \"Location (City/State)\": \"Lagos\", \"What do you know about disability and inclusion?\": \"Making sure that people with disabilities have equal access, opportunity, train in all aspects of life, working, education. removing barriers through accessible environment, making sure no one is left behind.\", \"What is your availability for conducting training sessions?\": \"Monday - Friday\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"I want to know if the training will hold online or physical\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"Graphic design training both smartphone and computer, software coledrsw and Photoshop\"}', '2025-12-22 19:50:38');
INSERT INTO `custom_form_submissions` (`id`, `form_id`, `submission_data`, `submitted_at`) VALUES
(475, 13, '{\"Full Name\": \"Awopetu Ayodele Jummycate\", \"Phone Number\": \"07039229355\", \"Email Address\": \"jummycate03@gmail.com\", \"Expertise Sector\": \"Tourism\", \"Location (City/State)\": \"Ado ekiti\", \"What do you know about disability and inclusion?\": \"Disability is not just a Inclusion is the intentional practice of removing barriers so persons with disabilities condition.While\", \"What is your availability for conducting training sessions?\": \"Always\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"No\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"No\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"Non\"}', '2025-12-22 21:07:31'),
(476, 13, '{\"Full Name\": \"Ebere favour Wilson odinika\", \"Phone Number\": \"08129817827\", \"Email Address\": \"eberewilson11@gmail.com\", \"Expertise Sector\": \"Agriculture\", \"Location (City/State)\": \"Ajasa command Lagos\", \"What do you know about disability and inclusion?\": \"Discrimination 2&#039;\", \"What is your availability for conducting training sessions?\": \"Am available anytime\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"No\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"Yes\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"I learn  about business for the training\"}', '2025-12-23 00:11:24'),
(477, 13, '{\"Full Name\": \"Azeezat Lawal\", \"Phone Number\": \"07063854005\", \"Email Address\": \"foodheroesagro21@gmail.com\", \"Expertise Sector\": \"Agriculture\", \"Location (City/State)\": \"Abuja\", \"What do you know about disability and inclusion?\": \"Disability refers to any condition that limits a person&#039;s ability to perform everyday activities, such as seeing, hearing, walking, or communicating. Inclusion means making sure people with disabilities have the same opportunities and access as everyone else.\\r\\n\\r\\nKey points:\\r\\n\\r\\nTypes of disabilities: Physical, sensory, intellectual, mental health, and neurodevelopmental.\\r\\n- Barriers: Physical (like stairs), communication (like lack of sign language), and attitudinal (like stereotypes).\\r\\n- Inclusion strategies: Accessible buildings, inclusive education, assistive tech, and raising awareness.\\r\\n- Benefits: Diversity and inclusion boost creativity, productivity, and social cohesion.\", \"What is your availability for conducting training sessions?\": \"My availability for conducting training sessions is completely flexible — I can adapt to any schedule you need, 24/7. Just let me know the date, time, and topic, and I’ll be ready!\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"Yes, I would like to share that I can help facilitate training sessions on a wide range of topics, including disability and inclusion, and I&#039;m happy to tailor the content and delivery to meet your specific needs and goals. Additionally, I can provide supporting materials and resources to enhance the learning experience.\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"No\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"\"}', '2025-12-28 23:11:07'),
(478, 13, '{\"Full Name\": \"Samson Ayodeji\", \"Phone Number\": \"09116033935\", \"Email Address\": \"ayodejis84@gmail.com\", \"Expertise Sector\": \"Law\", \"Location (City/State)\": \"Abuja\", \"What do you know about disability and inclusion?\": \"Disability inclusion is when everyone regardless of their disability is carried along in every program or agenda - leaving no one behind.\", \"What is your availability for conducting training sessions?\": \"I&#039;m available anytime I&#039;m needed\", \"Are you willing to commit to a minimum number of training sessions?\": \"Yes\", \"Is there any additional information you would like to share with us?\": \"\", \"Do you have any certifications or qualifications related to disability awareness, inclusion, or training facilitation?\": \"No\", \"if you answered yes for the question before this please specify and  describe your experience in training/facilitation\": \"\"}', '2026-01-03 09:25:29');

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `html_template` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  `progress` int(11) DEFAULT 0,
  `completed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `enrolled_at`, `completed_at`, `progress`, `completed`) VALUES
(2, 320, 6, '2026-02-05 16:23:22', '2026-02-05 16:27:10', 100, 1),
(4, 320, 9, '2026-02-05 20:01:00', '2026-02-06 09:58:20', 100, 1),
(5, 320, 10, '2026-02-06 11:59:08', NULL, 0, 0),
(6, 34, 6, '2026-02-06 17:08:37', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `entry_test_questions`
--

CREATE TABLE `entry_test_questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` char(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `entry_test_results`
--

CREATE TABLE `entry_test_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `entry_test_results`
--

INSERT INTO `entry_test_results` (`id`, `user_id`, `score`, `total_questions`, `completed_at`) VALUES
(16, 58, 13, 15, '2025-08-21 10:36:19'),
(30, 76, 10, 10, '2025-08-28 14:01:47'),
(42, 91, 10, 10, '2025-09-04 15:29:18'),
(43, 93, 10, 10, '2025-09-05 07:36:09'),
(44, 94, 10, 10, '2025-09-06 13:20:21'),
(49, 99, 6, 15, '2025-09-17 21:03:40'),
(50, 99, 6, 15, '2025-09-17 21:03:40'),
(51, 99, 6, 15, '2025-09-17 21:03:41'),
(52, 99, 6, 15, '2025-09-17 21:03:43'),
(53, 99, 6, 15, '2025-09-17 21:03:44'),
(54, 100, 15, 15, '2025-09-18 06:31:33'),
(55, 100, 15, 15, '2025-09-18 06:31:33'),
(56, 100, 15, 15, '2025-09-18 06:31:35'),
(57, 101, 8, 15, '2025-09-18 09:51:01'),
(58, 103, 15, 15, '2025-09-22 18:35:55'),
(59, 104, 15, 15, '2025-09-24 07:02:58'),
(60, 104, 15, 15, '2025-09-24 07:03:09'),
(61, 105, 15, 15, '2025-09-24 13:55:01'),
(62, 106, 11, 15, '2025-09-24 16:07:38'),
(63, 108, 15, 15, '2025-09-29 22:12:44'),
(64, 110, 14, 15, '2025-09-30 15:22:46'),
(65, 111, 15, 15, '2025-10-01 02:26:43'),
(66, 112, 15, 15, '2025-10-01 03:13:09'),
(67, 113, 15, 15, '2025-10-01 07:39:34'),
(68, 114, 15, 15, '2025-10-01 08:27:19'),
(69, 115, 15, 15, '2025-10-01 09:25:01'),
(70, 118, 15, 15, '2025-10-01 09:42:13'),
(71, 119, 15, 15, '2025-10-01 10:02:34'),
(72, 116, 9, 15, '2025-10-01 11:16:31'),
(73, 121, 15, 15, '2025-10-01 19:07:54'),
(74, 122, 13, 15, '2025-10-01 19:26:32'),
(75, 123, 15, 15, '2025-10-01 19:28:27'),
(76, 125, 15, 15, '2025-10-01 19:37:40'),
(77, 126, 14, 15, '2025-10-01 19:41:19'),
(78, 127, 14, 15, '2025-10-01 20:10:31'),
(79, 128, 15, 15, '2025-10-01 20:32:21'),
(80, 129, 15, 15, '2025-10-01 20:46:49'),
(81, 129, 15, 15, '2025-10-01 20:46:49'),
(82, 129, 15, 15, '2025-10-01 20:46:49'),
(83, 131, 15, 15, '2025-10-01 21:52:26'),
(84, 130, 14, 15, '2025-10-01 21:54:16'),
(85, 132, 15, 15, '2025-10-01 22:39:29'),
(86, 133, 14, 15, '2025-10-01 23:36:36'),
(87, 134, 14, 15, '2025-10-01 23:54:02'),
(88, 135, 6, 15, '2025-10-01 23:58:12'),
(89, 137, 14, 15, '2025-10-02 00:51:55'),
(90, 138, 9, 15, '2025-10-02 02:35:23'),
(91, 139, 15, 15, '2025-10-02 03:28:54'),
(92, 140, 13, 15, '2025-10-02 03:34:12'),
(93, 141, 15, 15, '2025-10-02 03:42:34'),
(94, 143, 14, 15, '2025-10-02 04:03:28'),
(95, 142, 13, 15, '2025-10-02 04:05:34'),
(96, 144, 14, 15, '2025-10-02 04:26:41'),
(97, 146, 14, 15, '2025-10-02 04:26:50'),
(98, 145, 12, 15, '2025-10-02 04:28:39'),
(99, 147, 14, 15, '2025-10-02 04:37:03'),
(100, 148, 13, 15, '2025-10-02 04:50:19'),
(101, 149, 15, 15, '2025-10-02 04:57:40'),
(102, 149, 15, 15, '2025-10-02 04:57:42'),
(103, 150, 15, 15, '2025-10-02 05:06:47'),
(104, 151, 11, 15, '2025-10-02 05:30:20'),
(105, 152, 14, 15, '2025-10-02 06:24:39'),
(106, 152, 14, 15, '2025-10-02 06:24:44'),
(107, 152, 14, 15, '2025-10-02 06:24:50'),
(108, 155, 14, 15, '2025-10-02 06:25:02'),
(109, 156, 14, 15, '2025-10-02 06:30:03'),
(110, 154, 13, 15, '2025-10-02 06:44:06'),
(111, 157, 15, 15, '2025-10-02 07:24:36'),
(112, 158, 15, 15, '2025-10-02 07:33:56'),
(113, 158, 15, 15, '2025-10-02 07:33:56'),
(114, 158, 15, 15, '2025-10-02 07:33:56'),
(115, 158, 15, 15, '2025-10-02 07:33:56'),
(116, 158, 15, 15, '2025-10-02 07:33:56'),
(117, 158, 15, 15, '2025-10-02 07:33:56'),
(118, 158, 15, 15, '2025-10-02 07:33:56'),
(119, 158, 15, 15, '2025-10-02 07:33:57'),
(120, 158, 15, 15, '2025-10-02 07:34:02'),
(121, 159, 15, 15, '2025-10-02 07:58:38'),
(122, 160, 15, 15, '2025-10-02 08:22:18'),
(123, 162, 13, 15, '2025-10-02 10:14:21'),
(124, 163, 14, 15, '2025-10-02 10:41:49'),
(125, 164, 10, 15, '2025-10-02 10:49:31'),
(126, 165, 15, 15, '2025-10-02 12:26:02'),
(127, 166, 14, 15, '2025-10-02 13:10:24'),
(128, 167, 14, 15, '2025-10-02 13:32:23'),
(129, 168, 15, 15, '2025-10-02 14:07:27'),
(130, 169, 15, 15, '2025-10-02 15:11:12'),
(131, 170, 12, 15, '2025-10-02 15:27:00'),
(132, 171, 13, 15, '2025-10-02 15:38:49'),
(133, 172, 15, 15, '2025-10-02 16:02:49'),
(134, 173, 14, 15, '2025-10-02 16:27:50'),
(135, 175, 14, 15, '2025-10-02 16:42:15'),
(136, 176, 14, 15, '2025-10-02 16:57:27'),
(137, 177, 15, 15, '2025-10-02 17:13:05'),
(138, 179, 15, 15, '2025-10-02 17:22:46'),
(139, 180, 15, 15, '2025-10-02 17:26:42'),
(140, 178, 15, 15, '2025-10-02 17:38:41'),
(141, 181, 15, 15, '2025-10-02 17:43:00'),
(142, 182, 15, 15, '2025-10-02 17:54:07'),
(143, 183, 15, 15, '2025-10-02 18:04:01'),
(144, 184, 14, 15, '2025-10-02 18:10:11'),
(145, 185, 14, 15, '2025-10-02 18:25:03'),
(146, 186, 14, 15, '2025-10-02 18:50:03'),
(147, 187, 11, 15, '2025-10-02 19:05:13'),
(148, 187, 11, 15, '2025-10-02 19:05:13'),
(149, 187, 11, 15, '2025-10-02 19:05:15'),
(150, 188, 15, 15, '2025-10-02 19:09:35'),
(151, 191, 15, 15, '2025-10-02 19:15:31'),
(152, 190, 15, 15, '2025-10-02 19:15:33'),
(153, 192, 15, 15, '2025-10-02 19:22:59'),
(154, 193, 15, 15, '2025-10-02 19:38:21'),
(155, 195, 15, 15, '2025-10-02 19:41:39'),
(156, 194, 14, 15, '2025-10-02 19:44:18'),
(157, 196, 14, 15, '2025-10-02 19:56:58'),
(158, 197, 15, 15, '2025-10-02 20:02:33'),
(159, 198, 14, 15, '2025-10-02 20:27:24'),
(160, 198, 14, 15, '2025-10-02 20:27:24'),
(161, 200, 13, 15, '2025-10-02 20:46:20'),
(162, 203, 15, 15, '2025-10-02 21:31:40'),
(163, 205, 15, 15, '2025-10-02 22:33:12'),
(164, 207, 14, 15, '2025-10-02 23:41:20'),
(165, 208, 14, 15, '2025-10-02 23:48:43'),
(166, 209, 14, 15, '2025-10-03 00:27:07'),
(167, 211, 15, 15, '2025-10-03 01:59:57'),
(168, 212, 14, 15, '2025-10-03 03:43:37'),
(169, 213, 14, 15, '2025-10-03 03:49:39'),
(170, 214, 15, 15, '2025-10-03 04:10:12'),
(171, 215, 12, 15, '2025-10-03 04:34:20'),
(172, 217, 15, 15, '2025-10-03 04:35:37'),
(173, 216, 15, 15, '2025-10-03 04:39:19'),
(174, 219, 15, 15, '2025-10-03 05:15:25'),
(175, 218, 15, 15, '2025-10-03 05:21:48'),
(176, 220, 15, 15, '2025-10-03 05:41:57'),
(177, 221, 15, 15, '2025-10-03 06:05:17'),
(178, 222, 15, 15, '2025-10-03 06:45:09'),
(179, 223, 13, 15, '2025-10-03 07:19:17'),
(180, 199, 13, 15, '2025-10-03 07:40:22'),
(181, 224, 12, 15, '2025-10-03 07:44:49'),
(182, 225, 15, 15, '2025-10-03 08:01:17'),
(183, 226, 14, 15, '2025-10-03 08:15:13'),
(184, 227, 14, 15, '2025-10-03 08:45:16'),
(185, 228, 15, 15, '2025-10-03 08:51:17'),
(186, 174, 15, 15, '2025-10-03 09:07:46'),
(187, 229, 12, 15, '2025-10-03 09:38:48'),
(188, 229, 12, 15, '2025-10-03 09:38:48'),
(189, 229, 12, 15, '2025-10-03 09:38:48'),
(190, 230, 15, 15, '2025-10-03 09:54:19'),
(191, 231, 13, 15, '2025-10-03 10:52:44'),
(192, 234, 15, 15, '2025-10-03 11:01:12'),
(193, 233, 15, 15, '2025-10-03 11:24:39'),
(194, 236, 15, 15, '2025-10-03 12:21:27'),
(195, 237, 15, 15, '2025-10-03 12:38:01'),
(196, 238, 15, 15, '2025-10-03 12:48:59'),
(197, 239, 8, 15, '2025-10-03 13:23:23'),
(198, 201, 12, 15, '2025-10-03 14:01:02'),
(199, 241, 15, 15, '2025-10-03 15:21:09'),
(200, 243, 15, 15, '2025-10-03 15:51:13'),
(201, 235, 13, 15, '2025-10-03 15:54:11'),
(202, 235, 13, 15, '2025-10-03 15:54:11'),
(203, 244, 13, 15, '2025-10-03 15:59:34'),
(204, 245, 15, 15, '2025-10-03 16:10:45'),
(205, 247, 14, 15, '2025-10-03 16:39:51'),
(206, 248, 11, 15, '2025-10-03 17:40:06'),
(207, 249, 14, 15, '2025-10-03 17:55:08'),
(208, 249, 14, 15, '2025-10-03 17:55:11'),
(209, 250, 14, 15, '2025-10-03 18:12:49'),
(210, 252, 15, 15, '2025-10-03 18:29:22'),
(211, 251, 15, 15, '2025-10-03 18:39:47'),
(212, 253, 15, 15, '2025-10-03 18:41:11'),
(213, 255, 15, 15, '2025-10-03 18:44:26'),
(214, 256, 15, 15, '2025-10-03 18:47:06'),
(215, 257, 14, 15, '2025-10-03 18:47:44'),
(216, 259, 13, 15, '2025-10-03 19:05:10'),
(217, 258, 12, 15, '2025-10-03 19:06:20'),
(218, 260, 15, 15, '2025-10-03 19:13:07'),
(219, 261, 12, 15, '2025-10-03 19:14:50'),
(220, 262, 14, 15, '2025-10-03 19:43:58'),
(221, 263, 14, 15, '2025-10-03 19:45:15'),
(222, 267, 15, 15, '2025-10-03 20:43:09'),
(223, 268, 15, 15, '2025-10-03 20:50:00'),
(224, 270, 15, 15, '2025-10-03 21:30:24'),
(225, 271, 14, 15, '2025-10-03 22:58:41'),
(226, 242, 15, 15, '2025-10-03 23:44:01'),
(227, 272, 15, 15, '2025-10-04 00:28:03'),
(228, 273, 15, 15, '2025-10-04 01:48:11'),
(229, 274, 15, 15, '2025-10-04 04:25:40'),
(230, 275, 15, 15, '2025-10-04 06:25:36'),
(231, 277, 15, 15, '2025-10-04 10:02:03'),
(234, 279, 10, 15, '2025-10-04 16:25:43'),
(235, 280, 8, 15, '2025-10-04 17:01:32'),
(236, 281, 15, 15, '2025-10-04 17:05:47'),
(237, 283, 15, 15, '2025-10-04 17:51:10'),
(238, 284, 10, 15, '2025-10-04 18:59:02'),
(239, 285, 14, 15, '2025-10-04 19:43:26'),
(240, 286, 14, 15, '2025-10-04 23:09:37'),
(241, 288, 14, 15, '2025-10-05 12:42:09'),
(242, 206, 12, 15, '2025-10-05 21:27:36'),
(243, 289, 13, 15, '2025-10-06 04:04:07'),
(244, 210, 13, 15, '2025-10-06 10:05:21'),
(245, 232, 15, 15, '2025-10-07 07:01:26'),
(246, 202, 14, 15, '2025-10-09 04:14:02'),
(247, 291, 15, 15, '2025-10-11 06:33:22'),
(248, 292, 14, 15, '2025-10-11 09:30:08'),
(249, 287, 9, 15, '2025-10-11 16:04:05'),
(250, 293, 5, 15, '2025-10-12 21:39:31'),
(251, 294, 15, 15, '2025-10-14 10:18:18'),
(252, 295, 5, 15, '2025-10-14 22:32:24'),
(253, 295, 5, 15, '2025-10-14 22:32:26'),
(254, 296, 7, 15, '2025-10-16 10:16:19'),
(255, 297, 11, 15, '2025-10-16 17:42:17'),
(256, 298, 12, 15, '2025-10-16 17:47:06'),
(257, 299, 14, 15, '2025-10-16 20:14:10'),
(258, 300, 15, 15, '2025-10-17 04:36:05'),
(259, 301, 15, 15, '2025-10-17 13:39:00'),
(260, 302, 15, 15, '2025-10-17 16:10:16'),
(262, 304, 15, 15, '2025-10-22 09:46:33'),
(263, 303, 15, 15, '2025-10-22 12:17:00'),
(264, 305, 8, 15, '2025-10-22 18:55:12'),
(265, 308, 14, 15, '2025-10-23 15:14:22'),
(266, 308, 14, 15, '2025-10-23 15:14:22'),
(267, 309, 15, 15, '2025-10-23 17:36:46'),
(268, 310, 14, 15, '2025-10-23 17:43:17'),
(269, 311, 15, 15, '2025-10-23 18:20:22'),
(270, 314, 15, 15, '2025-10-27 16:28:09'),
(271, 317, 14, 15, '2025-10-28 15:30:41'),
(272, 266, 12, 15, '2025-10-29 08:04:41'),
(273, 320, 11, 15, '2026-01-27 14:44:27'),
(276, 322, 15, 15, '2026-01-30 10:33:36');

-- --------------------------------------------------------

--
-- Table structure for table `e_books`
--

CREATE TABLE `e_books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_name` varchar(255) NOT NULL COMMENT 'Stores the name of the uploaded PDF file',
  `cover_image` varchar(255) DEFAULT NULL COMMENT 'Stores the name of the uploaded cover image',
  `uploaded_by` int(11) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_replies`
--

CREATE TABLE `forum_replies` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_threads`
--

CREATE TABLE `forum_threads` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `video_path` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `lesson_order` int(11) NOT NULL DEFAULT 0,
  `lesson_type` enum('video','text','quiz') NOT NULL DEFAULT 'text',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_index` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `title`, `content`, `video_url`, `video_path`, `attachment`, `lesson_order`, `lesson_type`, `created_at`, `order_index`) VALUES
(2, 2, 'Welcome & Course Overview', 'Welcome to this course! In this lesson, we will cover what you will learn and how to get the most out of this program.', NULL, NULL, NULL, 1, 'text', '2026-02-05 07:40:41', 0),
(6, 6, 'Welcome & Course Overview', 'Welcome to this course! In this lesson, we will cover what you will learn and how to get the most out of this program.', NULL, NULL, NULL, 1, 'text', '2026-02-05 07:40:41', 0),
(7, 7, 'Welcome & Course Overview', 'Welcome to this course! In this lesson, we will cover what you will learn and how to get the most out of this program.', NULL, NULL, NULL, 1, 'text', '2026-02-05 07:40:41', 0),
(17, 2, 'Getting Started', 'Now that you understand the course structure, lets dive into the fundamentals.', NULL, NULL, NULL, 2, 'text', '2026-02-05 07:40:41', 0),
(21, 6, 'Getting Started', 'Now that you understand the course structure, lets dive into the fundamentals.', NULL, NULL, NULL, 2, 'text', '2026-02-05 07:40:41', 0),
(22, 7, 'Getting Started', 'Now that you understand the course structure, lets dive into the fundamentals.', NULL, NULL, NULL, 2, 'text', '2026-02-05 07:40:41', 0),
(32, 9, 'TEST', 'TESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTEST', '', NULL, NULL, 1, 'text', '2026-02-05 20:35:58', 1),
(33, 9, 'TESTB@@', 'TESTB@@TESTB@@TESTB@@TESTB@@TESTB@@TESTB@@TESTB@@TESTB@@TESTB@@TESTB@@TESTB@@TESTB@@TESTB@@TESTB@@', 'https://www.youtube.com/watch?v=s22BUEoDuto', NULL, 'lesson_1770323790_6984ff4e7015a.docx', 2, 'video', '2026-02-05 20:36:30', 2),
(34, 9, 'QUZ', '', '', NULL, NULL, 3, 'quiz', '2026-02-05 20:36:42', 3),
(35, 10, 'TESTS', 'TESTST CONTENT', 'https://www.youtube.com/watch?v=_kj4Kud-ia8', 'video_1770379857_6985da51c9bdc.mp4', 'lesson_1770378463_6985d4dff05f3.docx', 1, 'video', '2026-02-06 11:47:27', 1),
(36, 10, 'TESTSTSING STARTED', 'TESTSTSING STARTEDTESTSTSING STARTEDTESTSTSING STARTEDTESTSTSING STARTEDTESTSTSING STARTED', '', 'video_1770381517_6985e0cdd10ea.mp4', NULL, 2, 'video', '2026-02-06 12:21:58', 2);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_progress`
--

CREATE TABLE `lesson_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_progress`
--

INSERT INTO `lesson_progress` (`id`, `user_id`, `lesson_id`, `completed`, `completed_at`) VALUES
(1, 320, 6, 1, '2026-02-05 16:27:06'),
(2, 320, 21, 1, '2026-02-05 16:27:10'),
(3, 320, 34, 1, '2026-02-05 20:43:17'),
(4, 320, 32, 1, '2026-02-06 08:39:45'),
(5, 320, 33, 1, '2026-02-06 09:58:20');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `is_read`, `created_at`) VALUES
(3, 329, 'Instructor Application Approved', 'Congratulations! Your application to become an instructor has been approved. You can now access the Instructor Panel.', 'success', 1, '2026-02-07 11:23:54');

-- --------------------------------------------------------

--
-- Table structure for table `opportunities`
--

CREATE TABLE `opportunities` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `time_commitment` varchar(100) NOT NULL,
  `points_awarded` int(11) NOT NULL DEFAULT 15,
  `required_cadre_level_id` int(11) NOT NULL DEFAULT 1,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `opportunities`
--

INSERT INTO `opportunities` (`id`, `title`, `description`, `category`, `location`, `time_commitment`, `points_awarded`, `required_cadre_level_id`, `status`, `created_by`, `created_at`) VALUES
(5, 'Attend a Monthly Webinar', 'Participate in the monthly volunteer webinar to learn, engage, and discuss key topics on disability inclusion and community service.', 'Learning', 'Online', '1hour', 10, 1, 'open', 36, '2025-09-03 11:14:51'),
(6, 'Share Webinar Reflection', 'Submit a brief reflection or key takeaway from a monthly webinar to demonstrate learning and engagement.', 'Learning', 'Online', '30-45 minutes', 5, 1, 'open', 36, '2025-09-04 06:45:27'),
(7, 'Creating an Inclusion-Focused Social Media Post', 'Design and share a social media post highlighting disability inclusion or DADE Foundation initiatives.', 'Engagement', 'Online', '1hour', 5, 1, 'open', 36, '2025-09-04 06:48:47'),
(8, 'Organize a Local Outreach', 'Lead a community outreach event to raise awareness, educate, or provide support on disability inclusion in your local area.', 'Leadership', 'Local/Community', '3hours', 20, 1, 'open', 36, '2025-09-04 06:56:03'),
(9, 'Refer a New Volunteer', 'Invite a new volunteer to join DADE Foundation and guide them through the onboarding process.', 'Engagement', 'Online/Local', '30 minutes', 5, 1, 'open', 36, '2025-09-04 09:55:36'),
(10, 'Completing a Specialized Course', 'Finish any DADE Foundation approved course to enhance your skills in inclusion, community development, or volunteer management.', 'Learning', 'Online', '1-3 hours', 10, 1, 'open', 36, '2025-10-12 07:38:42'),
(11, 'Being Featured as Volunteer of the Month', 'Recognized for exceptional contribution, commitment, or impact within DADE Foundation volunteer activities.', 'Recognition', 'Online/Local', 'N/A', 15, 1, 'open', 36, '2025-10-12 07:48:38'),
(12, 'Proposing a Micro-Project Idea', 'Submit an idea for a small-scale project that could improve inclusion or community engagement locally.', 'Innovation', 'Online/Community', '1-2 hours', 10, 1, 'open', 36, '2025-10-12 07:54:31'),
(13, 'Submitting an Impact Story/Testimonial', 'Share a personal or observed story demonstrating the positive impact of DADE Foundation activities in your community.', 'Engagement', 'Online/Community', '1-2 hours', 10, 1, 'open', 36, '2025-10-12 08:15:57'),
(14, 'Participating in a Campaign', 'Join any DADE Foundation campaign, such as awareness drives or advocacy activities, to support community impact.', 'Engagement', 'Local/Community', '2-4 hours', 10, 1, 'open', 36, '2025-10-12 09:01:03'),
(15, 'Follow DADE Foundation on Facebook', 'Stay informed and inspired by following DADE Foundation on Facebook. You will get updates on activities, opportunities, and volunteer highlights.', 'Social Engagement', 'Online', '5 minutes', 10, 1, 'open', 36, '2025-10-12 20:39:30'),
(16, 'Follow DADE Foundation on X (Twitter)', 'Join the conversation and stay updated by following DADE Foundation on X (formerly Twitter). Engage with our posts, retweet and spread the impact.', 'Social Engagement', 'Online', '5 minutes', 10, 1, 'open', 36, '2025-10-12 20:42:47'),
(17, 'Follow DADE Foundation on Instagram', 'Be part of our visual storytelling by following DADE Foundation on Instagram. Discover inspiring volunteer moments, campaigns and impact stories.', 'Social Engagement', 'Online', '5 minutes', 10, 1, 'open', 36, '2025-10-12 20:45:22'),
(18, 'Follow DADE Foundation on LinkedIn', 'Connect professionally with DADE Foundation on LinkedIn to explore opportunities, updates and stories of impact from across our network.', 'Social Engagement', 'Online', '5 minutes', 10, 1, 'open', 36, '2025-10-12 20:48:03');

-- --------------------------------------------------------

--
-- Table structure for table `opportunity_proofs`
--

CREATE TABLE `opportunity_proofs` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `proof_text` text DEFAULT NULL,
  `proof_file` varchar(255) DEFAULT NULL,
  `status` enum('submitted','approved') NOT NULL DEFAULT 'submitted',
  `submitted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `opportunity_proofs`
--

INSERT INTO `opportunity_proofs` (`id`, `application_id`, `user_id`, `proof_text`, `proof_file`, `status`, `submitted_at`) VALUES
(1, 60, 293, 'sdbjksdjfdhjk', 'proof_68ecb3ad6522d-_.jpeg', 'approved', '2025-10-13 10:09:17');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires_at`, `used`, `created_at`) VALUES
(4, 34, '3e75a7ea1f0661eef07a3713e4718628a281c2fdad85f6c537d0ba9ed58d52bb', '2026-02-05 10:46:43', 0, '2026-02-05 08:46:43'),
(5, 280, 'cdd9974d5a17bc193023af0ade254c00471483334bc099caf39f3a45cc1349f6', '2026-02-05 10:47:09', 1, '2026-02-05 08:47:09'),
(6, 320, '7291db9d192ccb382c5f4228ce7a081ed3b6d5affc1015f4496390cd3951f133', '2026-02-05 11:44:38', 1, '2026-02-05 09:44:38');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `author_id`, `created_at`, `updated_at`) VALUES
(1, 'TESTING THE NEWS PAGE', 'Ne Money Edon Come i Buy Garri Ten Kobo Testing My News Pages is a joke', 1, '2025-08-04 07:46:19', '2025-08-04 07:46:19'),
(2, 'TEST 2', 'Am Not Joking Dont Play With Me i Can Be vEry wicked and Thesame Time Honorable', 1, '2025-08-04 07:47:42', '2025-08-04 07:47:42'),
(3, 'TEST 3', 'Am Not Joking Dont Play With Me i Can Be vEry wicked and Thesame Time Honorable', 1, '2025-08-04 07:47:52', '2025-08-04 07:47:52'),
(4, 'TEST 4', 'Am Not Joking Dont Play With Me i Can Be vEry wicked and Thesame Time Honorable', 1, '2025-08-04 07:48:01', '2025-08-04 07:48:01'),
(6, 'From Silence to Strength: The Inspiring Journey of Safiyanu Mohammed Umar', '<p class=\"MsoNormal\" style=\"text-align: justify;\">My name is Safiyanu Mohammed Umar, and I am the founder and CEO of Safyan Fashion Paradise.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">I am a Deaf, goal-driven individual with an unwavering passion for promoting inclusivity and accessibility in services, workplaces, and beyond. Originally from Bauchi and currently residing in Kano, I hold a Bachelor&rsquo;s degree in Special Education and Rehabilitation Science from the prestigious University of Jos. Alongside this, I&rsquo;ve gained certifications in network administration, data analysis, and cybersecurity through Cisco Networking. I am currently seeking an internship opportunity that will immerse me deeply in the tech space through hands-on learning and experience.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">My journey has not been typical. Between 2008 and mid-2009, I lived as a hearing person who felt aimless and lost. I had no direction, no dreams, and I couldn\'t even read in my native Hausa language. I was drifting, unaware of my potential or purpose. Then, after a brief illness, I became Deaf. This life-altering moment was my rebirth&mdash;a transformation that awakened something powerful within me.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Suddenly, I felt the urge to dream. I wanted to read, to write, to communicate. Determined to adapt and thrive, I enrolled in junior secondary school in Bauchi and later pursued a university education focused on special education in Bauchi State. One guiding light on this path was my Baffa (my father), Baffa, truly a gift from God. Whenever I made grammatical mistakes, he corrected me with care and candor: &ldquo;It should be this, not that. Otherwise, you&rsquo;ll ridicule yourself in public.&rdquo; His honest feedback fueled me. It became the energy that pushed me forward.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">From those early lessons to university graduation,and now running a fashion business while pursuing a career in tech, every step has been marked by determination and growth. I believe that when one door closes, another opens&mdash;and my journey is proof of that truth. Without a doubt, I&rsquo;ve come this far by God&rsquo;s mercy and endless love.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Becoming Deaf has shaped me into someone who dares to dream big and achieve even bigger. Yes, I&rsquo;ve faced challenges, many of which are common to us all, but the ones tied to the hearing impaired are different. There have been times I&rsquo;ve felt invisible, excluded, and underestimated. Misconceptions and prejudice have tried to shake me. But I never lost focus. Instead, they gave me strength. They inspired me. And they instilled in me a relentless refusal to be discouraged, defeated, or manipulated.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">I am determined to prove that disability is not weakness. Through hard work, perseverance, and faith, I will keep pushing boundaries, and continue to rise.</p>', 36, '2025-08-14 19:52:16', '2025-08-14 19:58:25'),
(7, 'Beyond Limits: Nasiru’s Dream to Inspire and Empower', '<p class=\"MsoNormal\" style=\"text-align: justify;\">My name is Nasiru Umar. I am from the village of Boshikiri in Guyuk Local Government Area of Adamawa State. I was affected by polio when I was three years old, which left me physically challenged.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">I attended both primary and secondary school at the Special Education Center in Jada, Adamawa State. I completed my secondary education in 2016, but due to life&rsquo;s challenges, I was unable to continue.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Being a person with special needs in our kind of environment is tough, especially when your parents struggle financially. That was my reality. I faced many obstacles just to pursue my education. I began school in my village before I was transferred to the special school. I had no mobility aid, and reaching school meant pushing through scorching sun or muddy roads after rain. But I kept going, driven by the desire to be educated like everyone else.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Today, I remain at home since completing secondary school. But I refuse to let my disability stop me. I have a skill, I repair mobile phones, something I&rsquo;ve been passionate about since childhood. Though I don&rsquo;t have a shop yet, I work from home, and Alhamdulillah, I earn a decent living through this work.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">My biggest dream is to further my education, and eventually open a large repair shop where I can also teach young people the skills I&rsquo;ve learned. I believe through this, I can create real change in my community.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">To my fellow persons with disability, I say: stop seeing yourself as worthless. You are not defined by your limitations, you are defined by your determination. Show the world the gifts that God has given you. Every time I see another person with special needs go to school or become self-reliant, I feel inspired and hopeful that someday I, too, can be a role model.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Society may sometimes see persons with disabilities as unproductive or useless, but that perception is wrong. We have unique talents, and if given the opportunity, we will amaze everyone. If I had the privilege to be a person of influence, I would ensure that every person with a disability has access to education and meaningful work. I would help create opportunities, not just sympathy.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">My personal mantra is: &ldquo;Believe in yourself even when no one else does.&rdquo; There will be times when others doubt your abilities, dismiss your dreams, or overlook your potential. In those moments, belief in yourself becomes your greatest power. Trust your journey, even when you walk it alone. Confidence isn&rsquo;t about having everyone on your side, it&rsquo;s about standing firm in who you are, no matter what others think or say. Your faith in yourself will take you farther than anyone&rsquo;s approval ever could.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">If my life were a movie, it would be titled My Tears. And if I could describe it in a hashtag, it would be #BurdenedHeart&mdash;because I&rsquo;ve faced great suffering in my educational journey.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Initiatives like the Empowerment Hub of DADE Foundation can change lives. First, they give people with disabilities the chance to speak out, share their stories, and feel seen. This builds confidence and self-worth. Second, they provide training, support, and partnerships with key stakeholders, such as employers and donors, that help people pursue their dreams. Most importantly, they bring the attention of the public at large to the truth: persons with disabilities have talent and potential like anyone else. What we need is opportunity, not pity.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">This is how we build an inclusive society&mdash;one where every person is respected, valued, and empowered to contribute.</p>', 36, '2025-08-14 20:07:02', '2025-08-14 20:07:02'),
(8, 'Hope in Motion: The Determined Journey of Kabiru Sani Fandubu', '<p class=\"MsoNormal\" style=\"text-align: justify;\">MY STORY AND MY JOURNEY By Kabiru Sani Fandubu</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">My life has been a blend of challenges, lessons, and small but powerful victories. Spend a little time with me and you\'ll see that I&rsquo;m full of energy, passionate about helping others, and always ready to lead when something needs to be done. Music is my escape, faith is my anchor, and hope is what I hold on to, no matter what.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">But it hasn&rsquo;t always been this way.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">In April 2010, everything changed for me. A spinal cord injury turned my world upside down. One moment I was moving freely, the next I couldn&rsquo;t walk, stand, or care for myself. Overnight, I went from independence to complete dependence, needing help just to get out of bed, eat, or sit up. It was a shock I never expected.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">At first, I felt lost. The nights were the hardest. I lay awake staring at the ceiling, haunted by questions (fears about the future): Will I ever walk again? Will my life ever feel normal? While my friends went out living their lives, I was stuck in bed, feeling like mine had come to a halt.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">But somewhere deep inside, I made a choice: I wouldn&rsquo;t stop there. I had to fight.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">The road to recovery was long, slow, and honestly, painful. First came the wheelchair. Then came the walking frame. Years of therapy followed, along with countless exercises and tears of frustration when my body refused to cooperate and do what i wanted it to do. There were days I felt strong, and days I nearly gave up, but I never did.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">I\'ll never forget the day I took my first steps with a walking frame, unsupported might i add. My hands were shaking, my heart racing, but I moved forward, on my own. They weren\'t perfect steps, but to me, they were a victory over everything i\'d been through. That moment gave me back a piece of my independence.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Today, I walk with forearm crutches. I\'m not where I want to be yet, but I&rsquo;m closer than I&rsquo;ve ever been. Every step reminds me: slow progress is still progress.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Getting into university is one of my proudest achievements. It wasn\'t just about education, it was proof that I could still dream and achieve, despite my physical struggles. My parents have been my pillars, supporting me through the darkest hours. Everything I do now, I do with them in mind.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">I&rsquo;ve discovered a gift for uplifting others. I thrive on honest conversations that leave people feeling seen and encouraged. I\'m also a natural problem-solver, I don&rsquo;t just see challenges, I find paths forward to get around them. My dream isn&rsquo;t just to walk unaided one day, but to use my journey to help others with disabilities believe in themselves and chase their goals.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">I wish more people understood: having a disability doesn\'t mean your life is over. It means you live it differently. Mobility issues don\'t stop you from being happy or successful, far from it, and I\'m living proof of that.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">What makes me smile most are the little victories, taking a few more steps than i could last week, laughing with loved ones, or hearing a song that lifts my spirit. My personal mantra is simple: &ldquo;Slow progress is still progress.&rdquo; I whisper it to myself on the hard days when energy runs low.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">I have learned that Life isn&rsquo;t about avoiding struggles, it&rsquo;s about overcoming them, pushing through them. My journey has taught me patience, strength, and compassion. And if just one person hears my story and finds the courage to keep going, then everything I&rsquo;ve been through will have meant something.</p>', 36, '2025-08-14 20:12:27', '2025-08-14 20:12:27');

-- --------------------------------------------------------

--
-- Table structure for table `pre_launch_registrations`
--

CREATE TABLE `pre_launch_registrations` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `areas_of_interest` text DEFAULT NULL COMMENT 'Stores comma-separated list of interests',
  `how_did_you_hear` varchar(255) DEFAULT NULL,
  `registered_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pre_launch_registrations`
--

INSERT INTO `pre_launch_registrations` (`id`, `full_name`, `email`, `phone_number`, `areas_of_interest`, `how_did_you_hear`, `registered_at`) VALUES
(6, 'Usman Adam Mikail', 'usmanadammikail@gmail.com', '09136509195', 'Community Building &amp; Mentorship, Skills &amp; Empowerment', 'Social Media, Friend/Colleague', '2025-09-18 14:56:04'),
(7, 'Kabiru Sani Fandubu', 'kabirsfandubu@gmail.com', '09034633834', 'Community Building &amp; Mentorship, Advocacy &amp; Right Support, Inclusive Education, Others', 'Social Media', '2025-09-18 15:44:35'),
(8, 'NURA KHALID', 'nurakhalid281@gmail.com', '08074110837', 'Others', 'Social Media', '2025-09-18 15:49:03'),
(9, 'Aaron Adeola', 'aaronadeola@outlook.com', '09037646755', 'Community Building &amp; Mentorship, Skills &amp; Empowerment', 'Social Media', '2025-09-18 15:54:19'),
(10, 'Jonah Karo Bulus', 'jonahbuluskaro040@gmail.com', '08026498085', 'Inclusive Education', 'Friend/Colleague', '2025-09-18 15:58:21'),
(11, 'Saudat isah Abdullahi', 'saudatisah2020@gmail.com', '09026955504', 'Community Building &amp; Mentorship, Skills &amp; Empowerment, Inclusive Education, Inclusive Health', 'Social Media', '2025-09-18 16:30:18'),
(12, 'Fatimah Usman', 'albatulusman93@gmail.com', '08109571340', 'Community Building &amp; Mentorship, Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-09-18 16:45:22'),
(13, 'Hauwau Ismail', 'hauwauismail7@gmail.com', '08142422379', 'Inclusive Education', 'Social Media', '2025-09-18 17:43:50'),
(14, 'Maris Aisosa Mustapha', 'marisaisosa1@gmail.com', '08105570834', 'Advocacy &amp; Right Support, Inclusive Education, Assistive Tech Access', 'Social Media', '2025-09-18 17:54:43'),
(15, 'Casper Okpara', 'casperjoeproduction@gmail.com', '07035323038', 'Community Building &amp; Mentorship, Skills &amp; Empowerment', 'Friend/Colleague', '2025-09-19 11:23:14'),
(16, 'John Christopher', 'j.christopher2025@gmail.com', '08038026326', 'Community Building &amp; Mentorship', 'Social Media', '2025-09-21 07:26:08'),
(17, 'Yusufa Adamu', 'yusufaadamu7@gmail.com', '07031592879', 'Skills &amp; Empowerment, Inclusive Education', 'Social Media', '2025-09-23 12:00:46'),
(18, 'Victoria Okon', 'etorobongotu@gmail.com', '08102322439', 'Community Building &amp; Mentorship, Advocacy &amp; Right Support, Inclusive Education, Others', 'Social Media', '2025-09-23 13:14:48'),
(19, 'Ibrahim Suleiman', 'ibrahimsuleiman466@gmail.com', '08039648229', 'Community Building &amp; Mentorship, Skills &amp; Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access, Others', 'Social Media', '2025-09-24 01:39:48'),
(21, 'Saidu Yakubu', 'saiduyakubu224@gmail.com', '07068064225', 'Skills Empowerment', 'Social Media', '2025-09-24 16:51:43'),
(24, 'Ephraim Elam Filibus', 'ephraimelam@gmail.com', '08143370806', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health', 'Friend', '2025-09-30 01:03:09'),
(25, 'CHRISTIANA AREMU', 'aremuchristianaebun@gmail.com', '08169093334', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Assistive Tech Access, Others', 'Social Media', '2025-09-30 16:57:21'),
(26, 'Ibrahim Umar Abdulkarim', 'ibrazain02@gmail.com', '+2348036571820', 'Inclusive Education', 'Friend', '2025-10-01 05:16:04'),
(27, 'Sarah Pam', 'sarahpam49@gmail.com', '08065687530', 'Skills Empowerment', 'Social Media', '2025-10-01 06:02:45'),
(28, 'Abdulsalam Alimat Sadiat', 'alimatabdulsalam87@gmail.com', '07033669970', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access', 'Social Media', '2025-10-01 07:32:00'),
(29, 'Charles Peletiri', 'diweni4charles@gmail.com', '07061562882', 'Skills Empowerment, Assistive Tech Access', 'Social Media', '2025-10-01 09:18:36'),
(30, 'Blessing Ese Andem', 'oshomatanblessing32@gmail.com', '09059393176', 'Inclusive Education', 'Social Media', '2025-10-01 09:32:41'),
(31, 'AKERELE ABAYOMI ABEL', 'yomiakerele16@gmail.com', '08035739277', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access', 'Friend', '2025-10-01 10:03:55'),
(32, 'AKERELE ABAYOMI ABEL', 'omosuare4world@yahoo.com', '08035739277', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access', 'Friend', '2025-10-01 10:05:55'),
(33, 'Bolaji Adeola Oluwafemi-Danie', 'bolajiobe20@gmail.com', '07039385484', 'Community Building, Skills Empowerment', 'Social Media', '2025-10-01 10:19:29'),
(34, 'Adesunloye Kayode Emmanuel', 'sunloyekayode@gmail.com', '+2347038255658', 'Community Building, Skills Empowerment, Inclusive Education', 'Social Media', '2025-10-01 10:30:42'),
(35, 'Esther Essien', 'estheressien0201@gmail.com', '', 'Skills Empowerment, Advocacy &amp; Right Support', 'Social Media', '2025-10-01 11:24:27'),
(36, 'Paul Jael', 'jaelpaul2020.jp@gmail.com', '08164835684', 'Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education', 'Friend', '2025-10-01 12:09:05'),
(37, 'Ummulkursum Aliyu', 'aliyuummulkursum0@gmail.com', '08144422202', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access, Others', 'Social Media, Friend, Others', '2025-10-01 12:10:08'),
(38, 'Tijani Azeezat Ibukunoluwa', 'tijaniazeezat134@gmail.com', '08140869515', 'Community Building, Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-01 15:40:18'),
(39, 'Hakeem Tokunbo Oyeniyi', 'akoyeniyi@gmail.com', '08075096428', 'Community Building', 'Social Media', '2025-10-01 21:54:34'),
(40, 'Emmanuel Olapeju Awoyemi', 'emmanuelawoyemi917@gmail.com', '08148154734', 'Community Building, Advocacy &amp; Right Support', 'Social Media', '2025-10-01 22:05:53'),
(41, 'Adebiyi Anuoluwapo', 'anuoluwapogail9@gmail.com', '09061405865', 'Community Building', 'Social Media', '2025-10-01 22:17:54'),
(42, 'ANAFI GRACE OLUWAYEMISI', 'o.anafigrace@gmail.com', '09068483532', 'Inclusive Education', 'Social Media', '2025-10-01 22:19:58'),
(43, 'Prince David N. Gbarato', 'bcabori@gmail.com', '+2348064763255', 'Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-01 22:27:54'),
(44, 'Adamu Muhammed Hassan', 'adamumuhammed100@yahoo.com', '08067755332', 'Community Building, Skills Empowerment, Inclusive Health', 'Social Media', '2025-10-01 22:36:30'),
(45, 'SHUAIBU MAXWELL AKPAJESHI', 'iammaxwell921@gmail.com', '07016941921', 'Community Building, Advocacy &amp; Right Support, Assistive Tech Access', 'Social Media', '2025-10-01 23:04:40'),
(46, 'Toyin Afolayan', 'sholatoyin45@gmail.com', '07039601316', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-01 23:06:37'),
(47, 'Olamide Adekola', 'adekolaolamyde@gmail.com', '08118331092', 'Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-01 23:29:23'),
(48, 'Margaret Thomas', 'margarethomas433@gmail.com', '+2349036350523', 'Community Building', 'Social Media', '2025-10-01 23:39:59'),
(49, 'mary ene Godwin', 'maryene887@gmail.com', '09022228503', 'Community Building, Others', '', '2025-10-02 00:15:59'),
(50, 'Faith Desmond', 'contact.faithdesmond@gmail.com', '09054691193', 'Community Building, Skills Empowerment', 'Friend', '2025-10-02 00:45:01'),
(51, 'Yakubu Garba', 'yakubugarba2003@gmail.com', '09030799776', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health', 'Social Media, Friend', '2025-10-02 00:46:44'),
(52, 'Enoch Yohanna', 'enochguten@yahoo.com', '07039347873', 'Inclusive Education, Assistive Tech Access', 'Social Media', '2025-10-02 01:32:18'),
(53, 'Igba Gbaingior Cynthia', 'cynthiaigba2017@gmail.com', '08168642337', 'Community Building, Skills Empowerment, Inclusive Education', 'Social Media', '2025-10-02 01:44:40'),
(54, 'Mustapha Ayuba', 'ansaar01@googlemail.com', '08022033292', 'Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-02 02:31:50'),
(55, 'Bala Iliya', 'iliyabala555@gmail.com', '09077800663', 'Community Building, Others', 'Social Media', '2025-10-02 02:47:13'),
(56, 'Torhee Tersoo Donald', 'torheetersoo@gmail.com', '07067083076', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Assistive Tech Access, Others', 'Social Media', '2025-10-02 03:28:58'),
(57, 'Abba Muktar Muhammad', 'mamuktar001@gmail.com', '09134056171', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Health', 'Social Media', '2025-10-02 03:48:20'),
(58, 'Ikhide Titilola Esther', 'titilolaomoh@gmail.com', '08030466827', 'Community Building', 'Social Media', '2025-10-02 05:08:21'),
(59, 'Hajara y', 'ummusumeey@gmail.com', '09023761483', 'Inclusive Health', 'Friend', '2025-10-02 05:26:55'),
(60, 'Chinaza Favour', 'prudenceonovo18@gmail.com', '', 'Community Building, Inclusive Education, Inclusive Health, Others', 'Social Media', '2025-10-02 06:16:16'),
(61, 'Idris salisu Idris', 'idrissalisu459@gmail.com', '+2348147118563', 'Community Building, Skills Empowerment, Inclusive Education', 'Friend', '2025-10-02 06:24:10'),
(62, 'Jeremiah tata', 'jerrydantata1@gmail.com', '07039787173', 'Community Building', 'Social Media', '2025-10-02 06:28:08'),
(63, 'Muhammad amir abubakar', 'ameeryagi@gmail.com', '08036645202', 'Community Building', 'Social Media', '2025-10-02 06:38:51'),
(64, 'Patience Moses', 'patiencemoses1144@gmail.com', '08038057967', 'Inclusive Education', 'Social Media', '2025-10-02 06:42:11'),
(65, 'Lekwot Simeon Boman', 'simeonboman.lekwot@gmail.com', '08163121391', 'Community Building, Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-02 06:50:45'),
(66, 'Umar Peter', 'umarpeter26@gmail.com', '07039397026', 'Community Building', 'Social Media', '2025-10-02 06:57:07'),
(67, 'Olaoye Adeola', 'adeolaolaoye904@gmail.com', '08143891234', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 06:59:21'),
(68, 'Precious Godwin James', 'preciousgodwin9743@gmail.com', '07018909743', 'Community Building', 'Social Media', '2025-10-02 07:00:16'),
(69, 'Ogbe Ogheneovo Magdalene', 'darlene.ogbe@gmail.com', '07056210159', 'Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-02 07:00:38'),
(70, 'Agunbiade Taiwo Ayomikun', 'ayomikun6160@gmail.com', '08109533669', 'Community Building', 'Social Media', '2025-10-02 07:02:03'),
(71, 'Hindatu Awwal', 'hindatuawwal30@gmail.com', '08053159724', 'Community Building, Skills Empowerment, Inclusive Education, Assistive Tech Access', 'Social Media', '2025-10-02 07:20:04'),
(72, 'Douglas Danladi Barde', 'douglasbarde08@yahoo.com', '07039044372', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 07:22:14'),
(73, 'Bangaskiya Joshua', 'joshuabangaskiya@gmail.com', '07015218594', 'Community Building, Inclusive Education, Others', 'Social Media', '2025-10-02 07:23:00'),
(74, 'Ruth Ojonugwa Emmanuel', 'ruthcharity2018@gmail.com', '07081439159', 'Skills Empowerment, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 07:26:17'),
(75, 'Rintep Friday Christopher', 'rintepfryec@gmail.com', '08160214585', 'Community Building, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 07:33:35'),
(76, 'Richard okoro', 'richpatus@gmail.com', '08056777396', 'Others', 'Social Media', '2025-10-02 07:43:40'),
(77, 'Josiah Albert', 'albertjosy43@gmail.com', '07066829090', 'Community Building', 'Social Media', '2025-10-02 07:53:11'),
(78, 'Dominic', 'dominicchuks947@gmail.com', '', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-02 08:04:05'),
(79, 'Esther Lawrence Mandah', 'nkesemandah9@gmail.com', '09169578100', 'Inclusive Health', 'Social Media', '2025-10-02 08:05:07'),
(80, 'Collins Obidiegwu', 'colchesky25.cu@gmail.com', '+2348039343123', 'Community Building', 'Social Media', '2025-10-02 08:18:23'),
(81, 'Nkechi mary', 'nkechim933@gmail.com', '07030759163', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-02 08:27:58'),
(82, 'Emmanuel oladeji Oladeji', 'testimonyemmanuel57@gmail.com', '08169079267', 'Community Building', 'Social Media', '2025-10-02 08:34:10'),
(83, 'Longkat Joel Jwalshik', 'longkatjoeljwashik@gmail.com', '09124370063', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 08:38:52'),
(84, 'Gimba Ezekiel Itsegok', 'ezekielgimba89@gmail.com', '08024454745', 'Skills Empowerment', 'Social Media', '2025-10-02 08:52:31'),
(85, 'Uchenna Ekwenye', 'ekwenyu@gmail.com', '08066095543', 'Community Building, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 09:00:07'),
(86, 'Johnson Bankole', 'bankyj55@gmail.com', '08144289510', 'Community Building, Skills Empowerment, Inclusive Education', 'Social Media', '2025-10-02 09:06:16'),
(87, 'Folashade C. Oseni', 'folashadeoseni9@gmail.com', '08035510383', 'Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Others', 'Social Media', '2025-10-02 09:07:57'),
(88, 'Sao Daniel Pedetin', 'saodan2004@gmail.com', '07032730052', 'Skills Empowerment, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 09:17:16'),
(89, 'Deborah Abasita', 'abasita.deborah@gmail.com', '2349067142129', 'Community Building, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Others', 'Social Media', '2025-10-02 09:20:22'),
(90, 'Nwosu Benjamin chidiebere', 'benchidinwosu75@gmail.com', '08062551085', 'Skills Empowerment', 'Social Media', '2025-10-02 09:24:27'),
(91, 'Blessing Olanrewaju', 'bomolalah@gmail.com', '08109653203', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access, Others', 'Social Media', '2025-10-02 10:20:36'),
(92, 'Edeh Chika Confidence', 'Confibless86@gmail.com', '07080305786', 'Community Building, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 10:25:22'),
(93, 'Sodeeq Ogundele Adebayo', 'hawllar101@gmail.com', '08128533958', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Others', 'Social Media', '2025-10-02 10:55:11'),
(94, 'Glory Nwachukwu', 'gnwachukwu43@gmail.com', '07032311764', 'Inclusive Education, Inclusive Health', 'Social Media', '2025-10-02 10:59:28'),
(95, 'Adebayo Ibrahim olalekan', 'adebayoibrahim984@gmail.com', '08135255948', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-02 11:12:19'),
(96, 'Ibrahim Daddy Mbaka', 'dadditic@gmail.com', '09051015411', 'Community Building, Skills Empowerment, Inclusive Health, Assistive Tech Access', 'Social Media', '2025-10-02 11:19:17'),
(97, 'Florence nwokedi', 'florencenwokedi2016@gmail.com', '08038839057', 'Community Building, Skills Empowerment', 'Social Media', '2025-10-02 11:28:39'),
(98, 'Celestina Ezekwudo', 'ezekwudocelestinachy@gmail.com', '+2347060789707', 'Community Building, Skills Empowerment, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-02 13:11:16'),
(99, 'Yusuf Alhamdu Yake', 'yusufalhamdu@gmail.com', '+2347038409216', 'Skills Empowerment', 'Social Media', '2025-10-02 13:35:01'),
(100, 'Muraina Gbenga Samuel', 'samgbenga56@gmail.com', '7033916526', 'Community Building', 'Social Media', '2025-10-02 13:47:05'),
(101, 'Abi Inalegwu', 'abiinalegwu87@gmail.com', '07067970049', 'Community Building, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-02 15:22:17'),
(102, 'AMEDU OTEIKWU SAMUEL', 'samuelamedu77@gmail.com', '08036240456', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-02 16:02:54'),
(103, 'Adayi Janet Oyije', 'adayijanet92@gmail.com', '07063192769', 'Inclusive Health', 'Social Media', '2025-10-02 16:29:06'),
(104, 'Ronke Jimoh', 'ronke953@gmail.com', '08132993056', 'Community Building, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 17:05:00'),
(105, 'Barau Zubairu', 'zubairbarau42@gmail.com', '08161117586', 'Community Building', 'Social Media', '2025-10-02 17:09:11'),
(106, 'Temitope Dorcas Moses', 'temitopedorcas596@gmail.com', '08135036095', 'Inclusive Education', 'Social Media', '2025-10-02 18:06:29'),
(107, 'Flourish', 'flourishinkspires@gmail.com', '08149832438', 'Skills Empowerment, Advocacy &amp; Right Support, Inclusive Health', 'Social Media', '2025-10-02 18:34:31'),
(108, 'Matilda Eleojo', 'eleojomatilda2022@gmail.com', '08039632265', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Others', 'Social Media', '2025-10-02 18:50:46'),
(109, 'Afolabi Olabisi', 'afolabiolabisi1@gmail.com', '07026081620', 'Others', 'Social Media', '2025-10-02 19:17:45'),
(110, 'Cyril oglegba', 'cyriloglegba@gmail.com', '07063337278', 'Community Building, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 19:23:48'),
(111, 'Rex Yakwal', 'tyakwal@gmail.com', '08083003689', 'Community Building, Advocacy &amp; Right Support, Others', 'Social Media', '2025-10-02 19:34:56'),
(112, 'Nsikanabasi Asanga', 'nsikanabasiasanga@yahoo.com', '+2348065370578', 'Community Building, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 19:41:09'),
(113, 'Sim Martins', 'simmartins45@gmail.com', '07035052139', 'Inclusive Health', 'Social Media', '2025-10-02 19:53:12'),
(114, 'Eniola Olatinwo', 'eniolatinwo@gmail.com', '09122077516', 'Community Building, Skills Empowerment, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-02 19:59:26'),
(115, 'Mordecai James Musa', 'musamordecai@gmail.com', '09036595924', 'Skills Empowerment, Assistive Tech Access, Others', 'Friend', '2025-10-02 20:07:24'),
(116, 'Glory Onu', 'onuglory1@gmail.com', '09060561534', 'Community Building, Skills Empowerment, Inclusive Education, Assistive Tech Access', 'Social Media', '2025-10-02 20:09:26'),
(117, 'Abubakar Gbagba Mohammed', 'abubakarmgbagba@gmail.com', '07025280529', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-02 20:11:28'),
(118, 'Lawal Shakirat Oreoluwa', 'lawalshakirat2018@gmail.com', '09041487707', 'Skills Empowerment, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 20:14:31'),
(119, 'Timothy John Bako', 'timothyjohnbako12@gmail.com', '07030185812', 'Community Building, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 20:16:10'),
(120, 'Deborah Olubusola Ajao', 'olubusolaajao85@gmail.com', '08168266272', 'Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Assistive Tech Access', 'Social Media', '2025-10-02 20:24:38'),
(121, 'Adesina Sherif Kayode', 'gsp4succex@gmail.com', '07036551104', 'Community Building, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 20:40:27'),
(122, 'Nnaa Lucky Maxwell', 'luckymaximum1@gmail.com', '08066283823', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-02 20:47:09'),
(123, 'Ann Tarfa', 'anntarfa@gmail.com', '8037011168', 'Community Building', 'Social Media', '2025-10-02 20:48:54'),
(124, 'Maryam Ojochenemi Abdullahi', 'abdullahimaryamojochenemi@gmail.com', '07045997336', 'Advocacy &amp; Right Support, Inclusive Health', 'Social Media', '2025-10-02 21:01:04'),
(125, 'Abubakar Danbala Gambo', 'adgambo1741@gmail.com', '08036512737', 'Community Building, Advocacy &amp; Right Support', 'Social Media', '2025-10-02 21:04:25'),
(126, 'Ufomadu ThankGod Chibueze', 'editor601@gmail.com', '08061180907', 'Community Building, Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-02 21:16:08'),
(127, 'Ozougwu Rachael', 'nellinanelly@yahoo.com', '+2347018109370', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Health, Assistive Tech Access', 'Social Media', '2025-10-02 21:18:18'),
(128, 'Okeke Oluebube Emmanuella', 'ellacruise2@gmail.com', '07068988236', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access', 'Social Media', '2025-10-02 21:24:04'),
(129, 'Nkiruka Ebere Okafor', 'nkybaks@yahoo.com', '08037995357', 'Skills Empowerment, Inclusive Education, Assistive Tech Access', 'Social Media', '2025-10-02 21:39:43'),
(130, 'Busari Mariam tosin', 'busarimariamtosin25@gmail.com', '08068824654', 'Skills Empowerment', 'Social Media', '2025-10-02 21:48:02'),
(131, 'Azeez Ganiyu', 'azeezganiuabiola55@gmail.com', '07064207713', 'Community Building', 'Social Media', '2025-10-02 22:00:30'),
(132, 'Umar Emmanuel', 'emmanuelumar001@gmail.com', '09026355245', 'Community Building, Skills Empowerment, Inclusive Education, Assistive Tech Access', 'Social Media', '2025-10-02 22:01:38'),
(133, 'John Dorcas', 'dorcaseric1990@gmail.com', '08133527004', 'Community Building, Skills Empowerment', 'Social Media', '2025-10-02 22:07:08'),
(134, 'Wukatwe Charles Wakji', 'charlzwakji@gmail.com', '08036877117', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-02 22:11:59'),
(135, 'Wisdom Chiekeziem Anucha', 'wisdomanucha4@gmail.com', '09074200370', 'Community Building, Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-02 22:13:02'),
(136, 'UBONG IFEOMA', 'ifeomablessing138@gmail.com', '08066243730', 'Others', 'Social Media', '2025-10-02 22:15:08'),
(137, 'Mohammed fatima Tajudeen', 'oluwasheyi429@gmail.com', '07064745183', 'Inclusive Health', 'Social Media', '2025-10-02 22:32:05'),
(138, 'Martins Oluwakemi Margaret', 'martinsoluwakemi1988@gmail.com', '0806 759 6163', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Health, Others', 'Social Media', '2025-10-02 22:34:14'),
(139, 'Ruth Friday Olue', 'ruth.olue@gmail.com', '08105317920', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access, Others', 'Social Media', '2025-10-02 22:38:17'),
(140, 'Gowok Golepdi Joshua', 'golepgochom@gmail.com', '07048755555', 'Inclusive Education', 'Social Media', '2025-10-02 22:48:17'),
(141, 'Dusu Hephzibah Toma', 'hephzibahalisondusu@gmail.com', '07061202624', 'Skills Empowerment', 'Social Media', '2025-10-02 22:48:48'),
(142, 'ANU-OLUWAPO PEACE MUSTAPHA', 'anumustapha4@gmail.com', '08132189530', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access, Others', 'Social Media', '2025-10-02 22:52:34'),
(143, 'Ezeugo Ijeoma', 'ezeugoijeoma109@gmail.com', '08160134635', 'Skills Empowerment, Assistive Tech Access', 'Social Media', '2025-10-02 22:55:26'),
(144, 'Idowu Abdullahi', 'idowudada1@gmail.com', '08066337066', 'Community Building, Skills Empowerment, Assistive Tech Access, Others', 'Social Media', '2025-10-02 23:02:53'),
(145, 'Zainab Abdulrahman', 'abdulrahmanzainabdikko@gmail.com', '09037470305', 'Community Building, Skills Empowerment', 'Social Media', '2025-10-02 23:05:10'),
(146, 'Abdullahi Umar', 'abdullahiumar559@gmail.com', '07034497151', 'Community Building, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-02 23:22:28'),
(147, 'Ayanyemi Barakah Adedoyin', 'ayanyemibarakah@gmail.com', '09161433204', 'Skills Empowerment, Inclusive Health, Assistive Tech Access', 'Social Media', '2025-10-02 23:25:05'),
(148, 'Oyetunde Favour Esther', 'oyetundee220@gmail.com', '0916 598 4818', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-02 23:39:35'),
(149, 'Christiana Dep Ezekiel', 'anongdepezekiel@gmail.com', '08148793623', 'Community Building', 'Social Media', '2025-10-02 23:53:28'),
(150, 'Dakup Jatau Jan', 'janjataudakup@gmail.com', '08148710476', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-03 00:08:09'),
(151, 'Abdullahi Sharhabil Lawan', 'lawansa5574@gmail.com', '07034795793', 'Skills Empowerment, Assistive Tech Access', 'Social Media', '2025-10-03 00:25:14'),
(152, 'Racheal Reuben Mashor', 'mashorracheal@gmail.com', '09034669622', 'Inclusive Health', 'Social Media', '2025-10-03 00:27:17'),
(153, 'Bimpe Ademolake', 'fathiatademolake@gmail.com', '09078952380', 'Community Building, Skills Empowerment', 'Social Media', '2025-10-03 00:40:15'),
(154, 'Ojiji Egaji Abel', 'ojijiabel@gmail.com', '08100672006', 'Community Building', '', '2025-10-03 01:15:08'),
(155, 'Akingbola Deborah', 'akingboladeborah7@gmail.com', '07056963253', 'Others', 'Social Media', '2025-10-03 01:23:51'),
(156, 'Owolaiye Amos', 'amosowolaye07@gmail.com', '07015040238', 'Community Building, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-03 01:31:28'),
(157, 'LYNDA CHIAMAKA EMENIKE', 'emenikelyndachiamaka@gmail.con', '+2347067474467', 'Community Building, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-03 01:35:50'),
(158, 'Victor Ndimele', 'ndimelevictor2393.me@gmail.com', '08023408606', 'Skills Empowerment', 'Social Media', '2025-10-03 02:02:02'),
(159, 'Pamela chidimma', 'chidimmapamela5@gmail.com', '+2349160288116', 'Inclusive Health, Assistive Tech Access', 'Social Media', '2025-10-03 02:05:00'),
(160, 'Chuku Michael nnana', 'chukumic85@gmail.com', '08064889995', 'Community Building, Skills Empowerment, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-03 02:35:18'),
(161, 'Asiyanbola Damilare Oluwaseun', 'asinyanbolar@gmail.com', '07063437138', 'Skills Empowerment', 'Social Media', '2025-10-03 02:35:40'),
(162, 'Deborah Mojisola Adili', 'debbistix@gmail.com', '07038929560', 'Community Building, Inclusive Education', 'Social Media', '2025-10-03 02:44:41'),
(163, 'Bitrus Itse Adang', 'itsebitrus94@gmail.com', '08169660013', 'Inclusive Health', 'Social Media', '2025-10-03 03:06:37'),
(164, 'Samson Adeola Adejumo', 'samsonadeolaadejumo@gmail.com', '08108448897', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Others', 'Social Media', '2025-10-03 03:23:25'),
(165, 'Emeka Emmanuel Ezeanyika', 'emekaemmanuelezeanyika@gmail.com', '07045475489', 'Assistive Tech Access', 'Social Media', '2025-10-03 03:38:55'),
(166, 'Ada Mary Anyaeji', 'irobiada55@gmail.com', '07040698366', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support', 'Social Media', '2025-10-03 04:53:42'),
(167, 'Damilola Ademoye', 'tymfoods19@gmail.com', '+2348023517044', 'Skills Empowerment, Inclusive Education, Inclusive Health, Assistive Tech Access', 'Social Media', '2025-10-03 06:20:14'),
(168, 'Naansing Jones Zailani', 'edifyjones@gmail.com', '08037770704', 'Community Building', 'Social Media', '2025-10-03 06:39:04'),
(169, 'Ahmad Abubakar', 'faazabubakartijjani@gmail.com', '09061650646', 'Community Building, Skills Empowerment', 'Social Media', '2025-10-03 06:46:28'),
(170, 'Immaculate Azuogu', 'amazingfavour2017@gmail.com', '08149256788', 'Others', 'Social Media', '2025-10-03 06:55:24'),
(171, 'Godwin Ejoga', 'godwinejoga@gmail.com', '+2348108301618', 'Community Building, Advocacy &amp; Right Support, Inclusive Education, Assistive Tech Access', 'Social Media', '2025-10-03 07:06:17'),
(172, 'Susan Dadi  Miri', 'mirisusan02@gmail.com', '07066272538', 'Advocacy &amp; Right Support, Others', 'Social Media', '2025-10-03 07:25:31'),
(173, 'ISAH ABDULLAHI', 'yareema2508@gmail.com', '08100843557', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access, Others', 'Social Media', '2025-10-03 07:28:37'),
(174, 'David Prosper', 'davidprosper1816@gmail.com', '08140776731', 'Skills Empowerment, Advocacy &amp; Right Support, Inclusive Health', 'Social Media', '2025-10-03 07:30:46'),
(175, 'Orji Henry', 'orjihenry48@gmail.com', '08130155482', 'Inclusive Health', 'Social Media', '2025-10-03 07:31:20'),
(176, 'Ayuba Timothy', 'timothyayuba445@gmail.com', '08032793967', 'Community Building, Assistive Tech Access, Others', 'Social Media', '2025-10-03 08:11:01'),
(177, 'Salvation Joseph', 'funmijoseoh8@gmail.com', '080032098549', 'Community Building, Skills Empowerment, Inclusive Health', 'Social Media', '2025-10-03 08:11:33'),
(178, 'Dalong Charity', 'charitylongs001@gmail.com', '09066669445', 'Skills Empowerment', 'Social Media', '2025-10-03 08:24:42'),
(179, 'Abayomi Titilayo', 'abayomititilayo053@gmail.com', '08125524179', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-03 08:38:11'),
(180, 'Juliana Joel Ihiabe', 'julianajoelihiabe@gmail.com', '08065308748', 'Community Building, Advocacy &amp; Right Support', 'Social Media', '2025-10-03 08:54:11'),
(181, 'Joshua Olayinka Alabi', 'ollacourse@gmail.com', '08033336643', 'Community Building, Skills Empowerment, Inclusive Education, Inclusive Health, Assistive Tech Access', 'Social Media', '2025-10-03 09:41:27'),
(182, 'Hayatu Hamza', 'hhayatuddeen1@gmail.com', '07034609869', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access', 'Social Media, Friend', '2025-10-03 09:58:48'),
(183, 'Moses Agbo', 'agbom377@gmail.com', '09067615837', 'Community Building', 'Social Media', '2025-10-03 10:39:27'),
(184, 'Abraham buba', 'abrahmsino@gmail.com', '07049033439', 'Community Building', 'Social Media', '2025-10-03 10:54:58'),
(185, 'Ogbor john chimuanya', 'johnchimuanya24@gmail.com', '08104059136', 'Skills Empowerment', 'Social Media', '2025-10-03 11:09:37'),
(186, 'GLADYS AWENTA SAMUEL', 'gladyssamuel681998@gmail.com', '07084905272', 'Skills Empowerment', 'Social Media', '2025-10-03 11:39:37'),
(187, 'Rahila Yohanna Kokong', 'rahilakokong@gmail.com', '07069755633', 'Community Building, Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-03 11:49:12'),
(188, 'Nzeadibe Nneka Precious', 'nzeadibe.6469@gmail.com', '08164917285', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education', '', '2025-10-03 12:32:54'),
(189, 'Remilekun salako', 'Remilekunsalako@gmail.com', '08084847554', 'Skills Empowerment', 'Social Media', '2025-10-03 12:37:34'),
(190, 'Tersoo Simeon Biem', 'biemtersoo1@gmail.com', '08134709987', 'Community Building, Others', 'Social Media', '2025-10-03 12:45:39'),
(191, 'Kabu bulama maina', 'kabubulama1984@gmail.com', '07084009474', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-03 13:34:53'),
(192, 'Bethel Emmanuel', 'bethellite04@gmail.com', '07031357791', 'Community Building, Skills Empowerment, Inclusive Health, Assistive Tech Access, Others', 'Social Media', '2025-10-03 13:45:16'),
(193, 'Nguhemen Queen Dzuave', 'ndzuave@gmail.com', '08064612971', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-03 13:46:07'),
(194, 'Fadeke Layade', 'layadefadeke@gmail.com', '07033092920', 'Skills Empowerment', 'Others', '2025-10-03 13:47:23'),
(195, 'Mohammed Abubakar', 'mohdabbakarr@gmail.com', '07065656558', 'Skills Empowerment, Assistive Tech Access', 'Social Media', '2025-10-03 13:54:45'),
(196, 'Eva Linda Oshaju', 'vava20042000@yahoo.com', '08033146221', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Health, Assistive Tech Access, Others', 'Social Media', '2025-10-03 14:02:13'),
(197, 'Enyinnah victor', 'enyinnahvictor38@gmail.com', '08134417327', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support', 'Social Media', '2025-10-03 15:16:01'),
(198, 'Ifada Andy Isioma', 'ifadaandy@yahoo.com', '+2347030282514', 'Community Building', 'Social Media', '2025-10-03 15:32:56'),
(199, 'Akoh Freedom', 'akohfreedom@gmail.com', '+2348051830427', 'Community Building, Skills Empowerment, Inclusive Education, Assistive Tech Access', 'Social Media', '2025-10-03 15:45:56'),
(200, 'Uche Nkem-Philips', 'uchephilips6@gmail.com', '08161236379', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access, Others', 'Social Media', '2025-10-03 15:48:36'),
(201, 'Bala negab becky', 'bbeckybala@gmail.com', '08129501187', 'Skills Empowerment', 'Social Media', '2025-10-03 16:45:14'),
(202, 'Bright Onyedikachi Samson', 'crystalspring2011@gmail.com', '08036980016', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-03 17:28:26'),
(203, 'Chinonso Onuoha', 'chinonsobuchy@outlook.com', '', 'Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-03 18:16:54'),
(204, 'Adebayo Fatima Tunrayo', 'adebayofatimot6@gmail.com', '08131663883', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-03 18:21:08'),
(205, 'Barry Paul Goni', 'barrypaul991@gmail.com', '07037399253', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education', '', '2025-10-03 18:47:42'),
(206, 'Bulus Kabu Chibok', 'buluskabu@gmail.com', '08031150929', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support', 'Social Media', '2025-10-03 18:55:14'),
(207, 'Shabanyan Samson Kantiok', 'kantiokshan55@gmail.com', '07066073717', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-03 19:01:04'),
(208, 'Henry Ernest', 'wealthhenry9@gmail.com', '09019702930', 'Skills Empowerment', 'Social Media', '2025-10-03 19:13:31'),
(209, 'Okenwa Okenwa Elvis', 'elvisfizer20@gmail.com', '07038615580', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access', 'Social Media', '2025-10-03 19:32:14'),
(210, 'Christopher olaolu Dixon', 'christopherdixon202@gmail.com', '09131361911', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support', 'Social Media', '2025-10-03 19:37:15'),
(211, 'Vivian Ngoka', 'ngokav@gmail.com', '08134076398', 'Inclusive Education, Inclusive Health', 'Social Media', '2025-10-03 20:09:42'),
(212, 'Mary Precious', 'maryereme@yahoo.com', '+2348102942041', 'Assistive Tech Access', 'Social Media', '2025-10-03 20:16:16'),
(213, 'Adegbola Samedi', 'dekunle78@gmail.com', '08033780258', 'Community Building', 'Social Media', '2025-10-03 20:26:56'),
(214, 'Harriet', 'nnodukaharrietogogo@gmail.com', '09023431200', 'Skills Empowerment', 'Social Media', '2025-10-03 20:27:51'),
(215, 'Friday Igoche', 'igocheuloko@yahoo.com', '08055129993', 'Community Building', 'Social Media', '2025-10-03 20:34:17'),
(216, 'Habila Gizo', 'gizohabila11@gmail.com', '09055037015', 'Community Building, Skills Empowerment, Inclusive Education', 'Social Media', '2025-10-03 20:46:23'),
(217, 'Kazeem Rokeeb Opeyemi', 'kazeemrokeeb9@gmail.com', '08168295729', 'Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access', 'Friend', '2025-10-03 20:48:08'),
(218, 'hafsat Ibrahim', 'hafsati378@gmail.com', '07088497046', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-03 21:00:45'),
(219, 'Chekwube Cornelius Ngwu', 'ccm.ngwu@gmail.com', '+2348065282886', 'Skills Empowerment, Inclusive Education, Assistive Tech Access, Others', 'Social Media', '2025-10-03 21:02:30'),
(220, 'Tsetimi fortune', 'tsetimifortune@gmail.com', '08066748258', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access', 'Social Media', '2025-10-03 21:13:43'),
(221, 'Taiwo Samuel Akinremi', 'delightakin97@gmail.com', '08065594578', 'Others', 'Social Media', '2025-10-03 21:24:28'),
(222, 'Cirkat Peter Rwamzhi', 'cirkat200@gmail.com', '09069918642', 'Inclusive Education', 'Social Media', '2025-10-03 21:27:28'),
(223, 'Bridget Donatus Eze', 'bridgeteze4u@gmail.com', '08162024126', 'Inclusive Health', 'Social Media', '2025-10-03 21:35:50'),
(224, 'Ajiboye Tioluwanimi', 'ajiboyetioluwanimi22@gmail.com', '07017754938', 'Community Building, Skills Empowerment', 'Social Media', '2025-10-03 21:40:57'),
(225, 'Ariyo Olugbosun', 'olugbosunariyo@yahoo.com', '+2348025392225', 'Community Building, Skills Empowerment, Inclusive Education, Inclusive Health, Assistive Tech Access', 'Social Media', '2025-10-03 21:41:02'),
(226, 'Iwuoha Samuel', 'donsamueliwuoha@gmail.com', '+2347037393971', 'Inclusive Health', 'Social Media', '2025-10-03 21:41:12'),
(227, 'Bello Idris', 'idrisbello371@gmail.com', '09026560847', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-03 21:45:45'),
(228, 'Elijah Jagbadi', 'switch4change8@gmail.com', '+2348102906692', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Assistive Tech Access', 'Social Media', '2025-10-03 21:59:49'),
(229, 'Aremu Mary Moromoke', 'aremudynamicmary@gmail.com', '07033511133', 'Community Building, Inclusive Health', 'Friend', '2025-10-03 22:00:20'),
(230, 'Sendiyol Elijah Terngu', 'sendiyolafrica@gmail.com', '07010770', '', '', '2025-10-03 22:08:01'),
(231, 'Matthew Lucy mnena', 'mnenalucy55@gmail.com', '09070911190', 'Community Building, Skills Empowerment', 'Social Media', '2025-10-03 22:12:13'),
(232, 'Anyama Esther', 'anyamaesther@gmail.com', '09085091349', 'Community Building, Skills Empowerment, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-03 22:39:06'),
(233, 'Ibrahim Sidiq', 'ibrahimsidiqolamide@gmail.com', '09067251242', 'Community Building, Inclusive Education', 'Social Media', '2025-10-03 22:40:29'),
(234, 'Grey James Jeffry', 'echetaobisike1@gmail.com', '08063521323', 'Skills Empowerment, Assistive Tech Access', 'Social Media', '2025-10-03 22:45:44'),
(235, 'Mwanret Walar', 'felixmwanret@gmail.com', '08133155924', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-03 22:52:02'),
(236, 'Favour Adimchi Elechi', 'favoursheart1991@gmail.com', '09153586598', 'Inclusive Health', 'Friend', '2025-10-03 22:55:42'),
(237, 'Joseph Emmanuel', 'emmajoe2019@gmail.com', '09020559740', 'Skills Empowerment', 'Social Media', '2025-10-03 23:10:14'),
(238, 'Naanwul Sylvester', 'sylvesternaanwul@gmail.com', '07035998713', 'Community Building', 'Social Media', '2025-10-03 23:17:55'),
(239, 'Emmanuel Linus', 'limanuel105@gmail.com', '08103896892', 'Others', 'Social Media', '2025-10-03 23:31:46'),
(240, 'Sulaiman Mobolaji', 'princemobolajis@gmail.com', '+2348088012767', 'Community Building, Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-03 23:33:19'),
(241, 'Igbo Emmanuel Gabriel', 'emmanuelgabriel326@gmail.com', '0902680361', 'Community Building, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-03 23:37:55'),
(242, 'Hannatu Mam Joshua', 'hmamjoshua@gmail.com', '08132620357', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-03 23:46:54'),
(243, 'Zainab', 'oladelezainab2019@yahoo.com', '7039047958', 'Advocacy &amp; Right Support, Inclusive Education, Inclusive Health', 'Social Media', '2025-10-04 00:11:48'),
(244, 'Godspower Blessing', 'godspoweresther335@gmail.com', '09071920801', 'Inclusive Education', 'Social Media', '2025-10-04 00:24:49'),
(245, 'Aminu Ahmad Sulaiman', 'aminuahmadzak20@gmail.com', '08039278015', 'Inclusive Education, Inclusive Health', 'Social Media', '2025-10-04 00:57:23'),
(246, 'Yosi Danjuma', 'yosidanjuma15@gmail.com', '08125973916', 'Community Building, Advocacy &amp; Right Support', 'Social Media', '2025-10-04 03:25:04'),
(247, 'Charles Charlie', 'charlie08168098351@gmail.com', '0816 809 8351', 'Inclusive Education', 'Social Media', '2025-10-04 04:40:43'),
(248, 'Jeffrey oghenebrorhie Obiowu', 'clickjeff@yahoo.co.uk', '08054242517', 'Community Building', 'Social Media', '2025-10-04 04:55:23'),
(249, 'Jacob Cleopas Dodo', 'jdcleopas@gmail.com', '08034401040', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access, Others', 'Friend', '2025-10-04 09:20:04'),
(250, 'Adeoye Abisola Saidat', 'bisolasadeoye2004@gmail.com', '+2347041518941', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-04 12:59:35'),
(251, 'SHUAIB ABDOOL', 'shuaibabdul192@gmail.com', '08122598372', 'Community Building, Advocacy &amp; Right Support', 'Friend', '2025-10-04 15:28:54'),
(252, 'Hafsat ynunsa musa', 'hafsatyunusa838@gmail.com', '07089922033', 'Community Building', 'Friend', '2025-10-04 19:08:24'),
(253, 'AbdulQuadri Issa', 'abdulquadriissa@gmail.com', '09035044154', 'Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-04 20:48:02'),
(254, 'Ajayi Omotola Jumoke', 'ajayiomotola001@gmail.com', '08160257942', 'Community Building, Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-04 22:40:04'),
(255, 'Alabi Rilwan', 'omobaadeyemi22@gmail.com', '07069621300', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education', 'Social Media', '2025-10-05 15:34:07'),
(256, 'David Uwello Joshua', 'davidjoshua1060@gmail.com', '08067617853', 'Community Building, Skills Empowerment, Assistive Tech Access', 'Social Media', '2025-10-06 07:00:14'),
(257, 'Victoria Ufomba-Chikanma', 'victoria.ufombachikanma@outlook.com', '07030019259', 'Skills Empowerment, Assistive Tech Access', 'Social Media', '2025-10-09 18:46:39'),
(258, 'AKERELE ABAYOMI ABEL', 'talentcube2@gmail.com', '08035739277', 'Community Building, Skills Empowerment, Assistive Tech Access', 'Friend', '2025-10-11 15:09:42'),
(259, 'Hope Benson', 'hopebenson76@gmail.com', '08118050427', 'Skills Empowerment, Advocacy &amp; Right Support, Inclusive Health', 'Social Media', '2025-10-16 20:37:03'),
(260, 'Abdul Kareem Adedayo', 'salisusofiyyah57@gmail.com', '08057051549', 'Community Building, Advocacy &amp; Right Support', 'Social Media', '2025-10-16 23:11:49'),
(261, 'Onyebuchi Daluchukwu Chinwe', 'daluchukwuo@gmail.com', '08101617930', 'Advocacy &amp; Right Support', 'Social Media', '2025-10-17 06:41:19'),
(262, 'Kausara Osumare', 'osumarekausara@gmail.com', '09070154824', 'Community Building', 'Social Media', '2025-10-17 16:34:15'),
(263, 'Yahaya SaniAdam', 'yahayasaniadam2020@gmail.com', '08062357650', 'Community Building, Skills Empowerment, Inclusive Education', 'Social Media', '2025-10-22 10:55:19'),
(264, 'Okeke Sunday', 'Okeke.sunday1800@gmail.com', '07031095071', 'Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access', 'Social Media', '2025-10-23 18:11:42'),
(265, 'Babawande Habibat Adebisi', 'babawandehabibat004@gmail.com', '08167624623', 'Community Building, Skills Empowerment, Advocacy &amp; Right Support, Inclusive Education, Inclusive Health, Assistive Tech Access', 'Social Media', '2025-10-23 20:17:20'),
(266, 'Ikeoluwa Abiiba', 'abiibaikeoluwa@gmail.com', '09060774707', 'Community Building, Advocacy &amp; Right Support', 'Social Media', '2025-10-23 20:25:18'),
(267, 'SALAWU FARUQ ABIMBOLA', 'flexybimbz@gmail.com', '+2349016188157', 'Advocacy &amp; Right Support, Inclusive Health, Assistive Tech Access, Others', 'Social Media', '2025-10-23 21:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `total_points` int(11) NOT NULL,
  `passed` tinyint(1) DEFAULT 0,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`id`, `user_id`, `lesson_id`, `score`, `total_points`, `passed`, `completed_at`) VALUES
(1, 320, 34, 100, 20, 1, '2026-02-06 10:06:22'),
(2, 320, 34, 100, 20, 1, '2026-02-06 10:07:10'),
(3, 320, 34, 50, 20, 0, '2026-02-06 11:43:38');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_answer` char(1) NOT NULL,
  `points` int(11) DEFAULT 1,
  `question_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `lesson_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `points`, `question_order`, `created_at`) VALUES
(1, 34, 'wowoowow', '1', '2', '3', '4', 'a', 10, 1, '2026-02-05 20:37:15'),
(2, 34, '222030303', '1', '2', '3', '4', 'a', 10, 2, '2026-02-05 20:37:33');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `rating` decimal(2,1) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `course_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 320, 9, 3.0, 'Good Course', '2026-02-06 12:33:41', '2026-02-06 12:33:41');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'NGN',
  `payment_reference` varchar(255) NOT NULL,
  `status` enum('pending','success','failed') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'paystack',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `course_id`, `amount`, `currency`, `payment_reference`, `status`, `payment_method`, `created_at`, `updated_at`) VALUES
(1, 320, 10, 30000.00, 'NGN', 'PAY_6985d512633d4_1770378514', 'pending', 'paystack', '2026-02-06 11:48:34', '2026-02-06 11:48:34'),
(2, 320, 10, 30000.00, 'NGN', 'PAY_6985d780eb36e_1770379136', 'success', 'paystack', '2026-02-06 11:58:56', '2026-02-06 11:59:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `password` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `lga` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT 'default.png',
  `role` enum('student','instructor','admin') DEFAULT NULL,
  `referred_by` int(11) DEFAULT NULL,
  `recognitions` text DEFAULT NULL,
  `cadre_level_id` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `skills` text DEFAULT NULL,
  `availability` text DEFAULT NULL,
  `has_disability` tinyint(1) DEFAULT 0,
  `disability_details` text DEFAULT NULL,
  `program_preferences` text DEFAULT NULL,
  `consent_data_processing` tinyint(1) DEFAULT 0,
  `consent_communications` tinyint(1) DEFAULT 0,
  `status` enum('active','suspended','pending') DEFAULT 'active',
  `verification_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `instructor_pending` tinyint(1) DEFAULT 0,
  `instructor_applied_at` datetime DEFAULT NULL,
  `instructor_approved_at` datetime DEFAULT NULL,
  `instructor_approved_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `google_id`, `first_name`, `last_name`, `username`, `gender`, `email`, `email_verified`, `password`, `bio`, `phone`, `address`, `state`, `lga`, `profile_picture`, `role`, `referred_by`, `recognitions`, `cadre_level_id`, `created_at`, `skills`, `availability`, `has_disability`, `disability_details`, `program_preferences`, `consent_data_processing`, `consent_communications`, `status`, `verification_token`, `reset_token`, `reset_token_expiry`, `last_login`, `instructor_pending`, `instructor_applied_at`, `instructor_approved_at`, `instructor_approved_by`) VALUES
(58, NULL, 'EMMANUEL', 'Odumusi', 'EMMANUEL Odumusi', NULL, 'emmanuelodumusi@gmail.com', 0, '$2y$10$2t2kgA1yz2PWCvfiOnIFdeO8HVkW5RdkkFFq6J8CLm77gfFRXKyfW', '', '09057299917', '27, Edda Street, Gbazango Extension, Kubwa, Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-08-21 10:34:09', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(76, '115517490492889494943', 'Aaron', 'Adeola', 'Aaron Adeola', NULL, 'livingstoneaaron41@gmail.com', 0, NULL, NULL, NULL, NULL, NULL, NULL, '68b0718878ed0-Aaron.jpg', 'student', NULL, NULL, 2, '2025-08-28 13:58:52', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(91, NULL, 'Precious', 'Asiegbu', 'Precious Asiegbu', NULL, 'preciousasiegbu92@gmail.com', 0, '$2y$10$yVCwj7SoVGchVPQtQKy8geRaj4MqcDZsf/P8xt9ItFDX3rctBg8TO', '', '08131099320', '6, Oliwo Mayan street', NULL, NULL, '68b9c0bcc3dff-Precious portrait 1.png', 'student', NULL, NULL, 1, '2025-09-04 15:11:03', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(92, '101475485872936181650', 'Precious', 'Asiegbu', 'Precious Asiegbu', NULL, 'mekasdomains@gmail.com', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocLfl83rn6yp7iP1yZ7RQxMLcP6YiaZBA3Rb-68P4u-qL7fKNA=s96-c', 'student', NULL, NULL, 1, '2025-09-04 15:49:10', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(93, NULL, 'HAUWA UWAISU', 'Abdulhameed', 'HAUWA UWAISU Abdulhameed', NULL, 'hauwauwais2016@gmail.com', 0, '$2y$10$lY8JBr0fQIFoKCmrLiGOvOLNVm4hvlluKHXNiQmRTLnYHHLObu/O.', 'Am small business owners,to let the society believe that there&#039;s ability in disability', '08161976293', 'Kano state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-09-05 07:29:00', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(94, NULL, 'Jerushah', 'Ketah Paul', 'Jerushah Ketah Paul', NULL, 'jerushahpaul2000@gmail.com', 0, '$2y$10$cP.XEbSApSv2BwxHVCrmXOAnXuXKIzNI5sCOYG.nlFB2yDMfXXc/G', 'I chose to volunteer because I believe everyone deserves support, respect, and inclusion. I enjoy working with people, I’m patient, and I’m always willing to learn. I hope I can contribute positively while also learning from the experiences of others.', '08173323337', 'Kutuku Street Janruwa Kaduna', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-09-06 13:04:16', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(99, NULL, 'Nasiba', 'Abdullahi Alhassan', 'Nasiba Abdullahi Alhassan', NULL, 'nasibaabdullahialhassan@gmail.com', 0, '$2y$10$GdRIzLCAp/c3JCH.5hCMju5Vlw/ZmVfFF2N0rL30WVCHtqn9q.7FG', 'Business of training', '08143182685', 'Daneji mandawari', NULL, NULL, 'default.png', 'student', NULL, '', 1, '2025-09-17 20:56:17', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(100, '113015221297227886338', 'Agatha', 'Paul', 'Agatha Paul', NULL, 'paulagatha45@gmail.com', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocJINsFY6HfuVcmNO88SYh6aAWaPPTMUL-VbGUH3IZDFVx9xGU8v=s96-c', 'student', NULL, NULL, 1, '2025-09-18 06:29:15', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(101, NULL, 'Sani Muhammad', 'Abdullhahi', 'Sani Muhammad Abdullhahi', NULL, 'sanimuhammadabdullahi12@gmail.com', 0, '$2y$10$35yrzvjNuZvIZNcUzAOj9eGTCJtWDR2H0WeXtI5RXjZrP4Zm0M1KW', '', '07033414945', 'No 49 sheka gabas', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-09-18 09:37:25', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(102, NULL, 'NYIZOGEMBI', 'AARON', 'NYIZOGEMBI AARON', NULL, 'nyizogembiaaron64@gmail.com', 0, '$2y$10$pVBfCb2wA0uiZ0FdiTsP/emFjQgRxD0o8DMI.MIvHKAt8pF426NRq', 'My skill is computer ICT operation, I want to voluntary', '09121257629', 'Behind Redeem Christian Church Of God Bonugo, Kwali Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-09-18 15:57:01', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(103, NULL, 'Oke', 'Ikumariegbe', 'Oke Ikumariegbe', NULL, 'ikusoke@gmail.com', 0, '$2y$10$WnMzCxuTQ2Teylce4RBJDuYM4cF73PZ5/CnsIttPDuAi.Um.jA8dK', '', '+2348108180096', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-09-22 18:29:06', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(104, NULL, 'Unyime Isidore', 'Udoka', 'Unyime Isidore Udoka', NULL, 'unyimeisidoreudoka1987@gmail.com', 0, '$2y$10$RBP/YPVyMcWYtM2gtZ0IdepvQvUWQTa5WkVeieLOa23RDy6/43Ffu', 'I can help on documentation and keep records of day to day activities', '08100311049', 'Nto Omum Nto Edino 1, Obot Akara LGA, Akwa ibom state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-09-24 06:58:07', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(105, NULL, 'Saidu', 'Yakubu', 'Saidu Yakubu', NULL, 'saiduyakubu224@gmail.com', 0, '$2y$10$b1MYpDrCL69bDf6LdqZcWutUEYmGn7B/ICXXp3lVQqJf1Hle3VtIe', 'I am deaf special educator, Disablity rights activist and social Entreprenuer with the focus on Disablity inclusion and employment accessibility', '07068064225', 'Bauchi', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-09-24 13:51:43', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(106, NULL, 'Abubakar', 'Sani', 'Abubakar Sani', NULL, 'abubakarsani1960@gmail.com', 0, '$2y$10$RPnXQM4Pz25HvamMISqUO.MVssCpMQcBQonFCSMcI.YmAhosz0W8i', 'Poultry farmer and technology computer', '08100779411', 'Kebbi state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-09-24 15:59:51', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(107, '100170793982053963673', 'Aaron', 'Livingstone', 'Aaron Livingstone', NULL, 'virtualhelp2021@gmail.com', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocJV5ii-ADB7uR6u-3pRVcMoTD8XcTD5QK8YLbLr-uZ7WjCF7_I=s96-c', 'student', NULL, NULL, 1, '2025-09-29 15:16:39', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(108, NULL, 'Ephraim', 'Elam Filibus', 'Ephraim Elam Filibus', NULL, 'ephraimelam@gmail.com', 0, '$2y$10$Ccm3MMHq6uiNyaLts8D7PeXHcoOdvu04vyL/QavbUbyI7pNFKhF5O', 'Filibus Ephraim Elam is a dedicated finance and administration professional with experience in marketing, microfinance, project management, and accounting. He has applied his expertise in various roles, including NYSC service, and is passionate about financial planning, reporting, and community development.', '08143370806', 'No. 31 Catholic Street, Upper Luggere', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-09-29 22:03:09', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(109, NULL, 'Faith', 'Oladeji', 'Faith Oladeji', NULL, 'faithdaniel960@gmail.com', 0, '$2y$10$UJAkTgO/wJFiQGAkC0msluYEoVvNJbkkuvBQR3.dnyBiCcd1LpuhW', 'I have skills in financial literacy training, project management, and monitoring and evaluation, as well as a strong background in entrepreneurship and MSME capacity building. These abilities enable me to support programs that drive empowerment and sustainable livelihoods for people with disabilities. I want to boost this foundation because I am passionate about inclusion and believe that true empowerment comes when people are equipped with both opportunities and independence. I am deeply honored to serve.', '07063013285', 'Rayfield -,,-Jos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-09-30 01:05:34', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(110, NULL, 'CHRISTIANA', 'AREMU', 'CHRISTIANA AREMU', NULL, 'aremuchristianaebun@gmail.com', 0, '$2y$10$2EL4epFGZsyYLYcVZskJ/.OV2J5rpYYlhTrEJzrPwU9MtO.HWY7sa', 'My name is Christana Aremu. As a financial analyst, researcher, and auditor, with a passion for making a positive impact. I possess skills in data analysis, financial modeling, and audit processes. \r\n\r\nI&#039;m proficient in tools like Excel, PowerPoint, Audit Pro, QuickBooks, SPSS, E-view, and Stata. I&#039;m currently expanding my expertise in financial modeling and Power BI. \r\n\r\nMy passion for making a positive impact drives me to volunteer. I&#039;ve had experiences that sparked my interest in sign language and community service, and I&#039;ve volunteered with NGOs to contribute to meaningful causes. I&#039;m excited to bring my skills and enthusiasm to this opportunity.', '08169093334', 'CRESCENT, KUBWA', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-09-30 15:12:32', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(111, NULL, 'Ibrahim', 'Umar Abdulkarim', 'Ibrahim Umar Abdulkarim', NULL, 'ibrazain02@gmail.com', 0, '$2y$10$NJs2MbkLq9LXIr1yy0.6K.hheknXQWE5P6BqCKocTDJpCbAU9it4u', '55-year old blind person. Teacher in Special Needs Education School, Tudun Maliki, Kano State, Nigeria. Does online teaching in English Language and Unified English Braille Code. Completed a Master&#039;s Degree in Inclusive Education at the University of Manchester, North-West England, in 2009. A one-time part-time lecturer with the School of Special Needs Education at the Federal College of Education (Technical), Bichi, Kano State, Nigeria.', '+2348036571820', 'OC89 Bakin Bulo Link 6, beside BUK Old Site Fence, off Jan Bulo First Gate, Kabuga Housing Estate, Gwale LGA, Kano State.', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 02:16:04', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(112, NULL, 'Sarah', 'Pam', 'Sarah Pam', NULL, 'sarahpam49@gmail.com', 0, '$2y$10$05IlMzc13P7T4CYB6HzHAO7uHC4xNoyjBNFOracQbD7kTxhbQ/MwW', 'My name is Sarah Pam Berom by tribe from Plateau State I am physically handicap, a civil servant, a caterer and an advocate to my community (persons with impairments)', '08065687530', 'Jos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 03:02:45', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(113, NULL, 'Adesunloye', 'Kayode Emmanuel', 'Adesunloye Kayode Emmanuel', NULL, 'sunloyekayode@gmail.com', 0, '$2y$10$RMq7dri0kuTbftKY5plTQe6fIQQoKrrcdEiNn7zfgc8LatKwpOy4m', 'Kayode Emmanuel loves God. He loves to add value to lives in any possible ways. He is a graduate of computer science from the prestigious federal polytechnic Nasarawa Nigeria.', '+2347038255658', 'Zone 5 Dutse Alhaji Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 07:30:43', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(114, NULL, 'Esther', 'Essien', 'Esther Essien', NULL, 'estheressien0201@gmail.com', 0, '$2y$10$0GERrqJ3UsYRNcQR3EaXq.2e9.TKPYOfDEoId2Kwi46hPfUkfPZ.S', '', '', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 08:24:27', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(115, NULL, 'Paul', 'Jael', 'Paul Jael', NULL, 'jaelpaul2020.jp@gmail.com', 0, '$2y$10$iqc0iuPV0.OFTxXBbSIwveEXajEzCOsJxLDDF0Tb3lqx.bKTrkcN.', '', '08164835684', 'No 18B Dutse road ungwan Boro Kaduna,Kaduna State.', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 09:09:05', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(116, NULL, 'Ummulkursum', 'Aliyu', 'Ummulkursum Aliyu', NULL, 'aliyuummulkursum0@gmail.com', 0, '$2y$10$38rkg./oLPJbpthHUCdBquydv/XsNQ/KFs6rDKpc4vEt5o0ynUYG.', 'Niger State minna', '08144422202', 'Flayout', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 09:10:08', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(117, NULL, 'Amina abubakar', 'Muazu', 'Amina abubakar Muazu', NULL, 'ummigarba618@gmail.com', 0, '$2y$10$Z.79nRmSEPOXluU0lrOrG.omFk17caQE.b27UGuofvOWgL5WobEy2', '', '09015841436', 'Kano state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 09:28:02', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(118, NULL, 'Olusola', 'Ojo', 'Olusola Ojo', NULL, 'Honourableolusoladavid@gmail.com', 0, '$2y$10$WdNXih1AzaJvAEINxo.O..rEpZ3eP8iv4fMIeNXty/HeEUBrHnAEy', 'I am a passionate leader with great soft skills in ICT and communication. I have believe that I am equipped to serve', '07037565236', 'Plot 118, Zone B6, House 14 ATB Ade Street, Lokotiye, Maraba Lokotiye, Along Orozo - Karshi Expressway.', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 09:38:26', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(119, NULL, 'Fatimah', 'Usman', 'Fatimah Usman', NULL, 'albatulusman93@gmail.com', 0, '$2y$10$b2Y.SGiFLHrj7vwgPSF8vOoJH7bgcB7pIdAHo3g58rLwCH6xjkoNu', 'I am a disability inclusion Facilitator, an educator, a poet, a scriptwriter who is passionate about disability inclusion', '08109571340', 'Naibawa Kano', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 09:57:35', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(120, NULL, 'Tijani', 'Azeezat Ibukunoluwa', 'Tijani Azeezat Ibukunoluwa', NULL, 'tijaniazeezat134@gmail.com', 0, '$2y$10$IcvGgkgKUOOTiZrS8Yj4s.gjoS2JylMurTzvgSYp5mHj5/6GCYDLC', 'Azeezat Tijani is a passionate and creative young professional with a background in Child Development and Family Studies. She is also a fashion designer and advocate for sustainability, with experience in waste recycling, customer service, and community development. She is committed to growth, impact, and empowering others through her work.', '08140869515', 'Abiola zone T, ogungbade road Ibadan', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 12:40:18', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(121, NULL, 'Emmanuel', 'Olapeju Awoyemi', 'Emmanuel Olapeju Awoyemi', NULL, 'emmanuelawoyemi917@gmail.com', 0, '$2y$10$wB0dDRpKfCHC/gax5bDa0eBbbdDRcQkyvohdjzLdQeJWUBbTWNo/S', 'Awoyemi Emmanuel Olapeju is a professional educator, certified fundraiser and social media manager. He is an Alumni of LEAP AFRICA where he was trained as a community leader, project manager and all-round innovator and changemaker.\r\nHe is also a certified UN SDGS Advocate.\r\nHe is interested in community advocacy and leadership', '08148154734', '2 Wale sodipo street SARAKI ADIGBE ABEOKUTA OGUN STATE', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 19:05:53', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(122, NULL, 'Adebiyi', 'Anuoluwapo', 'Adebiyi Anuoluwapo', NULL, 'anuoluwapogail9@gmail.com', 0, '$2y$10$D4ru9elgQJpgyQ9I25HKE./Rqc8cbynTji9T5M59FJLxzgSjzr4sG', 'Building bonds that make us stronger', '09061405865', 'Ibadan,Oyo state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 19:17:54', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(123, NULL, 'ANAFI', 'GRACE OLUWAYEMISI', 'ANAFI GRACE OLUWAYEMISI', NULL, 'o.anafigrace@gmail.com', 0, '$2y$10$G3.aqS9XBHYCpPNEUIqs..0om889HoVHG8juzJNjlD0jvtKoXFv42', '', '09068483532', 'No. 2 Ovie Asogie-agho lane, Olambe, Ogun State', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 19:19:58', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(124, NULL, 'Esther', 'Bankole', 'Esther Bankole', NULL, 'bankoleoluesther@gmail.com', 0, '$2y$10$n3w.oxqTUiB/2KD8a8pqS.h8KDEPLIu7qgiQu50nkEFtvajcmPQCO', 'A dedicated and results-driven professional with over 4 years of experience in customer service,\r\nproject support, and community engagement. Combines strong operational skills with a deep\r\ncommitment to social impact, demonstrated through successful project execution and community\r\nempowerment initiatives at Every Child is A Star Foundation. Proven ability to manage multiple tasks,\r\nleverage project management tools, and build strong relationships to drive program success in a\r\ndynamic NGO environment.', '08138233767', 'lugbe, Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 19:30:38', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(125, NULL, 'Prince', 'David N. Gbarato', 'Prince David N. Gbarato', NULL, 'bcabori@gmail.com', 0, '$2y$10$NSXcSlsuNgl.gIW9fqnisudjxednOMbJe/o5EDpQs3Yz7FPmIc7H6', 'As a person with special needs, I work to support PWDs gain social inclusion. I need a platform to do more.', '+2348064763255', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 19:32:29', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(126, NULL, 'Adamu', 'Muhammed Hassan', 'Adamu Muhammed Hassan', NULL, 'adamumuhammed100@yahoo.com', 0, '$2y$10$cTUj9.YdEGdvLL1PSx.VXeq6vhlaq6fBcfiSOxClVwCPqaplQnlUe', 'I&#039;m being longing for an opportunity of this nuture to join.', '08067755332', 'Kubwa/ FCT Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 19:36:30', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(127, NULL, 'Toyin', 'Afolayan', 'Toyin Afolayan', NULL, 'sholatoyin45@gmail.com', 0, '$2y$10$EaN9f9Bt/tbqj.DpCKDn7eaeRhDhZ.J7vy.dFnc3CbcQd8A6Qu8jG', '', '07039601316', 'No', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 20:06:37', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(128, NULL, 'Olamide', 'Adekola', 'Olamide Adekola', NULL, 'adekolaolamyde@gmail.com', 0, '$2y$10$bwX/s.WNsSo8aQEh4Nu.cuEJ8ozr7jjSAdgyjxYCdPAbzRyix2/Lm', 'A Serial volunteer, project manager and human resource manager', '08118331092', 'Osogbo', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 20:29:24', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(129, NULL, 'Margaret', 'Thomas', 'Margaret Thomas', NULL, 'margarethomas433@gmail.com', 0, '$2y$10$DDqy16ESjdtW7Gzk0IrWQ.v9eFPqLd9z3tgPiqvggNk6BlX5SqDZ2', 'Hello everyone, my name is Margaret Thomas. I am a Field Operations and Development Expert with strong experience in agricultural extension services, project management, and business analysis. Over the past few years, I have worked with organizations like TracTrac Mechanization Services, Farm Innovation Nigeria, and Babban Gona, where I led large-scale agricultural projects, supported smallholder farmers, and strengthened community-based initiatives.\r\n\r\nCurrently, I serve as a State Operations Manager, where I oversee project implementation, team management, and stakeholder engagement to drive sustainable impact. My expertise also extends to data collection, business intelligence tools, and Agile project management, which I use to improve efficiency and decision-making.\r\n\r\nBeyond work, I am passionate about community empowerment, gender equality, and leveraging technology for development. I also volunteer as a Data Analyst and Curriculum Developer, helping people build digital skills and fostering inclusive education.\r\n\r\nI am excited to connect, learn, and share experiences with like-minded professionals who are equally passionate about creating sustainable solutions and driving positive change.', '+2349036350523', 'Nasarawa', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 20:39:59', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(130, NULL, 'Faith', 'Desmond', 'Faith Desmond', NULL, 'contact.faithdesmond@gmail.com', 0, '$2y$10$Iqj8bMRQbKNqhAc4iqCIwe8RjARtixrhUgwZBzO6Q.1trt/SplQr6', 'Business development service provider, HR professional, Digitalisation Consultant, Trainer and Coach, Sustainability advocate', '09054691193', 'Lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 21:45:01', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(131, NULL, 'Yakubu', 'Garba', 'Yakubu Garba', NULL, 'yakubugarba2003@gmail.com', 0, '$2y$10$5xnzGfiiXcgzg/YaHGVaK.rw5dUrexF0EBTrISk1JEe81xrT8qOAa', 'As a passionate climate change advocate and community builder dedicated to creating sustainable solutions for people and the environment. My work focuses on empowering young people with skills, advancing advocacy and rights support, and promoting inclusive education and healthcare. Through leadership, activism, and community engagement, I remain committed to driving social justice, building resilience, and ensuring that no one is left behind in the journey toward a better and more inclusive society.', '09030799776', '10, Zango Street, Limawa Ward, Jimeta-Yola North, Adamawa State', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 21:46:44', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(132, NULL, 'Enoch', 'Yohanna', 'Enoch Yohanna', NULL, 'enochguten@yahoo.com', 0, '$2y$10$ft0dM55KWqEFeQAwL7TfjuxFHJFRj/dANEOQAJ4MzQEQ37kDrzeee', 'Civil Servant/ Education Officerin NationalcommissionforPWDs,office of Honourable Minister Humanitarian Affairs and Poverty Reduction, Sign Language Interpreter, M.ed Special Education, PhD Special Education.', '07039347873', 'Plot 322 FHA okay Water Lugbe', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 22:32:18', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(133, NULL, 'Mustapha', 'Ayuba', 'Mustapha Ayuba', NULL, 'ansaar01@googlemail.com', 0, '$2y$10$oreIdWVUTBANgQs0YJhC...vkWmmeur/1GboXxjp3qkQsioKCT8w.', '', '08022033292', 'Suleja Niger state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 23:31:50', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(134, NULL, 'Bala', 'Iliya', 'Bala Iliya', NULL, 'iliyabala555@gmail.com', 0, '$2y$10$QwgP1cLksHpYbMjDqDptg.fl5a2NF62RjAcbSyGxDDGKlCM.3BoFa', 'I am interested in adding value to humanity', '09077800663', 'Toge,Along Airport Road,Municipal Area Council Abuja.', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 23:47:13', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(135, NULL, 'Ibrahim', 'Hamisu abdullahi', 'Ibrahim Hamisu abdullahi', NULL, 'ibrahimhamisuhafsat@gmail.com', 0, '$2y$10$F07SPAVjRt/BujbCUzZjKuHYV2uti53c41PwrYpfsaLxqHSPesJtW', '', '', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-01 23:50:21', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(136, NULL, 'Torhee', 'Tersoo Donald', 'Torhee Tersoo Donald', NULL, 'torheetersoo@gmail.com', 0, '$2y$10$boOQlWZZ4Y/O5WvWlNEiT.4RzUl1Q/lO8hy7AYY9q93h6ntxtArF2', 'A graduate of Sociology, passionate about supporting and contributing my quota to societal wellbeing, growth and development.', '07067083076', 'Dada Estate, Osogbo, Osun State.', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 00:28:58', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(137, NULL, 'Abba', 'Muktar Muhammad', 'Abba Muktar Muhammad', NULL, 'mamuktar001@gmail.com', 0, '$2y$10$t4j0v7HKUFpvQTcH3cBPte67d64VjGHD6cb1F4UU.mkrG1SC8V592', 'A Student of college of nursing sciences Gombe, founder Leo Initiative, a youth led organization that focuses on health and wellness, educational programs, personal growth and leadership skills.', '09134056171', 'Gombe', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 00:48:20', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(138, NULL, 'Hajara', 'y', 'Hajara y', NULL, 'ummusumeey@gmail.com', 0, '$2y$10$7aL5zg1bqjd.LXyX.YYRg.5shlui9iBo.FP.XHbLYduBHe3xfZf0u', 'Nil', '09023761483', 'Kaduna', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 02:26:55', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(139, NULL, 'Idris', 'salisu Idris', 'Idris salisu Idris', NULL, 'idrissalisu459@gmail.com', 0, '$2y$10$4bqeRoRMaGSALZTQEKvRMu9E0OEIFpQxAf.RDapNE4ysvBzx2pZMi', 'A young individual passionate about driving change and making impact.', '+2348147118563', 'Number 1 A&amp;B opposite Central mosque dagiri gwagwalada, Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 03:24:10', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(140, NULL, 'Jeremiah', 'tata', 'Jeremiah tata', NULL, 'jerrydantata1@gmail.com', 0, '$2y$10$TFT27dTlkrn7QsgVIjkvQej4BG7p4HIMt8H/Pp30hIIvmSmvyzwfa', '', '07039787173', 'Adamawa state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 03:28:08', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(141, NULL, 'Muhammad', 'amir abubakar', 'Muhammad amir abubakar', NULL, 'ameeryagi@gmail.com', 0, '$2y$10$6IW3QHlB4Rv7flCcPCX2j.o5n3cOn9rmpiNINvwF02wjn51NGaTHi', 'A gentleman', '08036645202', 'Gom be', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 03:38:51', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(142, NULL, 'Olaoye', 'Adeola', 'Olaoye Adeola', NULL, 'adeolaolaoye904@gmail.com', 0, '$2y$10$ay914PhnFdRLJ9SEfLfMReHt3HC2aWHqYxjWiXEYa3fafZaTzqL9u', '', '08143891234', '1lawal street off ajayi oronti agbele ikorodu lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 03:59:21', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(143, NULL, 'Ogbe', 'Ogheneovo Magdalene', 'Ogbe Ogheneovo Magdalene', NULL, 'darlene.ogbe@gmail.com', 0, '$2y$10$9aqmlJI9o.CxxjIWu.w59.K/3HhCGEo8.7ETQ9sM80vkLzon61Qq6', 'I am a compassionate and empathetic person.', '07056210159', 'Lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 04:00:38', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(144, NULL, 'Hindatu', 'Awwal', 'Hindatu Awwal', NULL, 'hindatuawwal30@gmail.com', 0, '$2y$10$djq0vSc/FWCRkWgNr2k6puMehz4kWHp86Sr3F17EKsCYVvHL7joRG', 'Hindatu is a geospatial enthusiast that&#039;s keen in using her expertise in solving complex environmental problems.', '08053159724', 'Zone B 74 anguwan Kasuwa Gwagwa, Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 04:20:04', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(145, NULL, 'Douglas', 'Danladi Barde', 'Douglas Danladi Barde', NULL, 'douglasbarde08@yahoo.com', 0, '$2y$10$.V6pIeRm.WkcFxt8uvIhiuIAVVlitDhrEBBafjWXqhMwozmhVgsPK', 'My name is Douglas Danladi Barde from kaduna state graduate of Estate Management and Valuation from the federal polytechnic Bauchi.', '07039044372', 'No.5 Yakawada street Narayi High Cost Kaduna', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 04:22:15', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(146, NULL, 'Bangaskiya', 'Joshua', 'Bangaskiya Joshua', NULL, 'joshuabangaskiya@gmail.com', 0, '$2y$10$tNNi/GnFVLCHsI8AobZAveCd.VCIgEBxufrlu2izvEQ8zSMxf0Ilm', 'I&#039;m a passionate community advocate.', '07015218594', 'FCT Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 04:23:01', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(147, NULL, 'Rintep', 'Friday Christopher', 'Rintep Friday Christopher', NULL, 'rintepfryec@gmail.com', 0, '$2y$10$/5YEGq6nkv9k9SjDVOj81OaVS8menhTZ8PX85ROJpnnRUIZ8qbXgC', 'I&#039;m a Social Worker', '08160214585', 'Jos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 04:33:35', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(148, NULL, 'Richard', 'okoro', 'Richard okoro', NULL, 'richpatus@gmail.com', 0, '$2y$10$5pZ2OUzwvfpPHjWiwGq3fOrTstjD1148q.0oyHiincUXd4RxO5S36', '', '08056777396', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 04:43:41', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(149, NULL, 'Josiah', 'Albert', 'Josiah Albert', NULL, 'albertjosy43@gmail.com', 0, '$2y$10$b7ngbuhVQT4TP.wvBQ0H..zpvdy8Kv4qemd1lRVGgFkFtoke5uByy', 'A self motivated fellow passionate about making and impact and learning new ideas', '07066829090', '16 NYSC Road Alakahia', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 04:53:11', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(150, NULL, 'Dominic', '', 'Dominic ', NULL, 'dominicchuks947@gmail.com', 0, '$2y$10$JdbA8YYGo7BZ0RSaeopHDeZn0Wj2TYHqKCDq9PliddCtRyhc9pcim', '', '', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 05:04:05', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(151, NULL, 'HALIMA', 'IBRAHIM', 'HALIMA IBRAHIM', NULL, 'salehhalima2020@gmail.com', 0, '$2y$10$/WUA2mB7WqU5HeVgpb54nuq4vDYco08SUrwYhIph3Vs0h/y6L6FZi', 'None', '08038638974', 'Anguwar rimi opposite Sunnah hospital jos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 05:26:16', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(152, NULL, 'Johnson', 'Bankole', 'Johnson Bankole', NULL, 'bankyj55@gmail.com', 0, '$2y$10$q3EgPx7mncogkBCbg6P3oOe.gEXB8Dqi4yJs1U45na8kPdMnHmVXG', '', '08144289510', '68, Olorunsogo street 1 Off Somorin Obantoko Abeokuta, Ogun State', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 06:06:16', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(153, NULL, 'Folashade', 'C. Oseni', 'Folashade C. Oseni', NULL, 'folashadeoseni9@gmail.com', 0, '$2y$10$hYaz35dpKEa2LPZSK.NoYeBJDQNEI6xLbPMthVcnF8c/MrVP3hEJm', 'Compassionate social worker and psychologist with passion for advocacy,social justice,counseling, psycho education and empowerment of vulnerable population', '08035510383', 'LGos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 06:07:57', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(154, NULL, 'Haruna', 'Babangida iliyasu', 'Haruna Babangida iliyasu', NULL, 'mrabbey93@gmail.com', 0, '$2y$10$zmO0/0rCoHTIhPsJTruE8e3JhOh2VxFYOigD.DYiKl66Ws89djfnK', '', '08052232991', 'Kano', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 06:16:00', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(155, NULL, 'Sao', 'Daniel Pedetin', 'Sao Daniel Pedetin', NULL, 'saodan2004@gmail.com', 0, '$2y$10$fdQoDEFV2ikvnBF.4UCYPuxrwnuDzYx0PVHClMyy6.l2IIQNRGZa6', 'Sao Daniel Pedetin is a communications and administrative professional with experience in content writing, social media management, and community engagement. He has worked with organizations such as SheFoundry Ltd, JCI Lagos Metropolitan, and Christ Foundation Gospel Church, supporting digital campaigns, program coordination, and advocacy initiatives. Trained in leadership, governance, and advocacy through the Fort Institute, CLAY Fellowship, and the Electoral College Nigeria, he is passionate about storytelling, civic engagement, and using communication as a tool for social impact.', '07032730052', 'Ibadan', NULL, NULL, '68e161ad7bdf3-file_00000000c16861f8bb319053eae147fc.png', 'student', NULL, NULL, 1, '2025-10-02 06:17:16', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(156, NULL, 'Nwosu', 'Benjamin chidiebere', 'Nwosu Benjamin chidiebere', NULL, 'benchidinwosu75@gmail.com', 0, '$2y$10$2fWnYuaH8emFLFGBNJuqqOsBk9Qai2MnUqrnYSpCXiWsE1qf7zAte', 'I&#039;m native of Umumbiri Autonomous community, Ahiazu Mbaise LGA Imo State. I&#039;m 50 years old. Married with 3 children. I have first degree (B.eng) in automotive engineering from Federal University of Technology, Owerri. I also possess diploma certificate in project planning and facility management.  I am into construction/ engineering contracts, project and facility management. My company name is Nisca Ark Ventures limited.', '08062551085', '10 Umuaduru road Osisioma Industrial Area Aba Abia state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 06:24:27', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(157, NULL, 'Blessing', 'Olanrewaju', 'Blessing Olanrewaju', NULL, 'bomolalah@gmail.com', 0, '$2y$10$bcGWt699qfx4imO5rpS17OkrtSWCnDI5sN3eojszamaPPA93ezCFS', 'I am Blessing Olanrewaju, a graduate of Guidance and Counseling from Olabisi Onabanjo University (OOU). I am passionate about education, child development, and advocacy, with strong interests in mentoring, girl-child empowerment, and women’s development. I am also committed to continuous growth through professional training and digital learning programs.', '08109653203', 'Festac town Lagos', NULL, NULL, '68de37460d34e-inbound5124909553109044879.jpg', 'student', NULL, NULL, 1, '2025-10-02 07:20:36', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(158, NULL, 'Edeh', 'Chika Confidence', 'Edeh Chika Confidence', NULL, 'Confibless86@gmail.com', 0, '$2y$10$C/ymJU/7i8o79vV.cwmh0eur0m0go7cP.f5y9yc/SJjbI.kMdvyay', 'An educator who have passion for community service.', '07080305786', 'Plot D24, Zone D1 Pegi kuje Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 07:25:22', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(159, NULL, 'Sodeeq', 'Ogundele Adebayo', 'Sodeeq Ogundele Adebayo', NULL, 'hawllar101@gmail.com', 0, '$2y$10$9C9kLE.m4uxkMqy1XjeqseSnc6172SE.m1iC2MyG8Xo2MIM8.Tkmu', '', '08128533958', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 07:55:11', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(160, NULL, 'Adebayo', 'Ibrahim olalekan', 'Adebayo Ibrahim olalekan', NULL, 'adebayoibrahim984@gmail.com', 0, '$2y$10$3zwOkI0AAXab29PZYQTan.FdOtpzInr4aM4vtG4aws.I0aZqQuJb.', 'I am Adebayo Ibrahim olalekan a graduate of demography and social statistics from obafemi Awolowo University ile ife Nigeria \r\n\r\nI was a trained and certified media practitioner with specialization in radiotv presentation \r\n\r\nI possessed proficiency certificate in management from Nigeria institute of Management.', '08135255948', 'Ibadan', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 08:12:20', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(161, NULL, 'Florence', 'nwokedi', 'Florence nwokedi', NULL, 'florencenwokedi2016@gmail.com', 0, '$2y$10$ScGAfQHW0CekBhuIoUlXjeUEAzFf3EYiryifdaaf/gnYznfO5dvJC', 'I’m a primary school teacher, a mother and a result-oriented person that has passion for humanitarian services', '08038839057', 'Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 08:28:39', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(162, NULL, 'Celestina', 'Ezekwudo', 'Celestina Ezekwudo', NULL, 'ezekwudocelestinachy@gmail.com', 0, '$2y$10$p1ITwl0PzPIRa88Iyq5hW.YlJe6WrA5gXJgw9BiwXK/NdnG1ALrYm', 'A self disciplined and hardworking Educator. Ready to give back to the society and humanity', '+2347060789707', 'Gwarinpa Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 10:11:17', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(163, NULL, 'Yusuf', 'Alhamdu Yake', 'Yusuf Alhamdu Yake', NULL, 'yusufalhamdu@gmail.com', 0, '$2y$10$lBrwzOLEsb85w8F/Ue.JW.04diUE6gBM.GFkG9qg20y4V7j/5Y2Du', 'My Name is Alhamdu Yusuf Yake, am a HND holder from the College of Agriculture and Animal Scoence Mando Road kaduna.\r\nI am a practical and fast learners and i am well trained to train others in various Agricultural skills.', '+2347038409216', 'Mando Kaduna', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 10:35:01', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(164, NULL, 'Muraina', 'Gbenga Samuel', 'Muraina Gbenga Samuel', NULL, 'samgbenga56@gmail.com', 0, '$2y$10$9E4rRwX2EChUE9N5VtQpgeNfnLMJYoN7cWc.JElVdiokWcOi6pXe.', 'Volunteers \r\nChef\r\nEvent planner \r\nTeens coach', '7033916526', 'Ibadan', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 10:47:05', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(165, NULL, 'Love', 'Oginni', 'Love Oginni', NULL, 'oluwakemisolalove64@gmail.com', 0, '$2y$10$2yQw2Ysvp8I2U2TIxous1.durkl/a.0xEckx.QAsPZgvOSzdwN9Ka', 'I am passionate about creating spaces where everyone feels seen, valued, and included. Over time, I have learned the importance of love, resilience, and service in uplifting others, and I believe these values are needed in working with persons with disabilities. I enjoy engaging people, encouraging growth, and supporting initiatives that make a lasting difference. Volunteering with your foundation gives me an opportunity to contribute meaningfully, learn, and be part of a vision that promotes inclusion, dignity, and empowerment for all.', '07063358833', '3 Bode Ajayi street, Olonde ologuneru -iddo road', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 12:10:22', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(166, NULL, 'AMEDU', 'OTEIKWU SAMUEL', 'AMEDU OTEIKWU SAMUEL', NULL, 'samuelamedu77@gmail.com', 0, '$2y$10$crBjLqE0BdjDHTXkkBepqubvp/8Ufgdtd5nhrskiSbEdmNIOU5C1O', '', '08036240456', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 13:02:54', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(167, NULL, 'Adayi', 'Janet Oyije', 'Adayi Janet Oyije', NULL, 'adayijanet92@gmail.com', 0, '$2y$10$5O5AjLdkkTmARIDeHLfgCejSRGPb1G/0.F2ZvN/3qMQuexhOA5H7G', 'Am Adayi Janet Oyije, female of 28 years. Studied Nutrition and Dietetics. Am also an Ex corp member.\r\n\r\nIt would be an honor to be part of volunteers in this organization.', '07063192769', 'Ugbowo, Benin City', NULL, NULL, '68df9f873ec94-IMG_20250925_130426_755.webp', 'student', NULL, NULL, 1, '2025-10-02 13:29:06', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(168, NULL, 'Ronke', 'Jimoh', 'Ronke Jimoh', NULL, 'ronke953@gmail.com', 0, '$2y$10$MxXeDU1.faLz3i/LXUE3PuZx5kW4YPTCFrAZdQlPgyXwPy784CM6K', 'A lawyer passionate about humanity and respect for human rights.', '08132993056', 'Kado,Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 14:05:00', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(169, NULL, 'Temitope', 'Dorcas Moses', 'Temitope Dorcas Moses', NULL, 'temitopedorcas596@gmail.com', 0, '$2y$10$zS65G8wUfpgEH0MdijPc7.EXE7deBu8Qq1Q7TYJ15MKia6D8QKuPy', '', '08135036095', 'Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 15:06:29', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(170, NULL, 'MOHAMMED', 'ABUBAKAR', 'MOHAMMED ABUBAKAR', NULL, 'abubakarmgbagba@gmail.com', 0, '$2y$10$uNHlGDo6gR/hJPU0KCyJYepdody7PstXatDsV15/BJsSjpFpwAI9C', 'I want to volunteer because I’m passionate about making a positive impact in my community. I have strong communication skills, which help me connect with people easily, and I enjoy advocating for meaningful causes. Volunteering also allows me to grow, contribute my time and energy, and work with others toward a shared goal. I believe my experience and dedication make me a valuable part of any team.', '07025280529', 'FCT ABUJA', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 15:24:04', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(171, NULL, 'Flourish', '', 'Flourish ', NULL, 'flourishinkspires@gmail.com', 0, '$2y$10$lsP2XDk/.Ni2I8K/pn7Vz.Tz8FV/bjZ9K/lko0F09u.iCYctVW86y', 'I&#039;m a social health work student', '08149832438', 'Ibadan', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 15:34:31', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(172, NULL, 'Matilda', 'Eleojo', 'Matilda Eleojo', NULL, 'eleojomatilda2022@gmail.com', 0, '$2y$10$d/F3c33GeqppHWdICZo.FubnMlGfVTnykDN4NB3ws/n6ds6/SU4Z.', 'I&#039;m Matilda Eleojo Adah, a graduate of Kogi State University Anyigba, I study banking and finance but currently I&#039;m not working I&#039;m married with 4kids I stays in Abuja with my family I love children orphans widows and the less privileged ones I have passion for them and I always like children orphans widows and the less privileged ones around me. My love for children orphans widows and the less privileged ones is not just a passion but a spiritual calling.', '08039632265', 'Abuja Fct', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 15:50:46', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(173, NULL, 'Afolabi', 'Olabisi', 'Afolabi Olabisi', NULL, 'afolabiolabisi1@gmail.com', 0, '$2y$10$VaTHcFqwvmrZegv7eylyYO9A6e/IxGAi0s51xs9llFn/YT61nt75y', '', '07026081620', 'Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 16:17:46', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(174, NULL, 'Cyril', 'oglegba', 'Cyril oglegba', NULL, 'cyriloglegba@gmail.com', 0, '$2y$10$1qRQngjKJagLW5pspnqAZui37WSYT4Q8m9nZYVmeHJFxVTZqno62q', 'I am passionate about solving problems and facing challenges', '07063337278', 'No.2 Gwari rd Kaduna Nigeria', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 16:23:48', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(175, NULL, 'Rex', 'Yakwal', 'Rex Yakwal', NULL, 'tyakwal@gmail.com', 0, '$2y$10$nvqqPL4PSer2Zdr3MmkMge3xHgBoGtSliBQIXkuGMBCfnyq2or6XC', '', '08083003689', 'Jos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 16:34:56', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(176, NULL, 'Sim', 'Martins', 'Sim Martins', NULL, 'simmartins45@gmail.com', 0, '$2y$10$QVKJh9eMAcqFD2hM/fgcfO6.4PWLpPm/bdTuFqqvcbdwM64xBivt6', 'Passionate about community building, healthy leaving and helping those on need', '07035052139', 'No.8 Maijamia street television Kaduna State', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 16:53:12', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(177, NULL, 'Mordecai', 'James Musa', 'Mordecai James Musa', NULL, 'musamordecai@gmail.com', 0, '$2y$10$XmiXrQhQ6j6FQk7XEvcpYuizC1jOuoAPGGIL1MuKe6vUVr.WFn9v6', '', '09036595924', 'No. 17 Livinus Road, Kaduna state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 17:07:25', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(178, NULL, 'Glory', 'Onu', 'Glory Onu', NULL, 'onuglory1@gmail.com', 0, '$2y$10$DBbdWi9I509GIMeuErb49e99bNm0FuquIh2HHK.tndSkBh56mQVtC', '', '09060561534', 'Ikeja/ lagis', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 17:09:26', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(179, NULL, 'Lawal', 'Shakirat Oreoluwa', 'Lawal Shakirat Oreoluwa', NULL, 'lawalshakirat2018@gmail.com', 0, '$2y$10$D2yvpMQPwNgOjSCl36W7p.SnBOGVJppp2JW9c3EVgeT5g0.xzTP8e', 'When it comes to impacting positively in the lives of others I am passionate about it.', '09041487707', 'Okemoro street isashi Lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 17:14:31', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(180, NULL, 'Deborah', 'Olubusola Ajao', 'Deborah Olubusola Ajao', NULL, 'olubusolaajao85@gmail.com', 0, '$2y$10$KZ4x8unh30ipoP92mWSr2OBrugPwthp/Iiogqf4GzN0KGvmNyaj7q', '', '08168266272', 'Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 17:24:38', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(181, NULL, 'Adesina', 'Sherif Kayode', 'Adesina Sherif Kayode', NULL, 'gsp4succex@gmail.com', 0, '$2y$10$oG7ePh2DvdihltS9F.5xG.QgwUMEVd6JtoI/vHVmR3fsuWCetLQr.', 'I am Adesina Sherif Kayode and I am passionate about giving back to the society in my little way', '07036551104', '3, Ayinke Williams, wawa, Ogun State', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 17:40:27', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(182, NULL, 'Ann', 'Tarfa', 'Ann Tarfa', NULL, 'anntarfa@gmail.com', 0, '$2y$10$IBefctr59ooPPUHhuqF8G.IZDlnG7/aqNRjHqfAOD0wOrggVTfcXK', 'Ann Tarfa. A graduate from ABU Zaria, Masters Degree from university of keffi, I live in Abuja Fct.', '8037011168', 'Abuja Fct', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 17:48:54', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(183, NULL, 'Maryam', 'Ojochenemi Abdullahi', 'Maryam Ojochenemi Abdullahi', NULL, 'abdullahimaryamojochenemi@gmail.com', 0, '$2y$10$WpvG4VIlP4oenoFxF.C1p.ij9gMcIcSpBoNOKtg51dShgvl.CA/Ri', 'I believe I have a “Voice” that is needed for positive change in the society.', '07045997336', 'FCT ABUJA', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 18:01:04', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(184, NULL, 'Abubakar', 'Danbala Gambo', 'Abubakar Danbala Gambo', NULL, 'adgambo1741@gmail.com', 0, '$2y$10$bTOwP5bMBSNgB4RZtRiRd.vnja3Z3xHil0UYqiidGCFC4jdhD/9n.', 'Right Advocate', '08036512737', 'Abuja, FCT', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 18:04:25', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(185, NULL, 'Ozougwu', 'Rachael', 'Ozougwu Rachael', NULL, 'nellinanelly@yahoo.com', 0, '$2y$10$hjKei4pC3JAfj9i3Vv06tOtJaX7Q248g8p2MeukjBjeD3X/UpXVNS', 'I enjoy alot of things, inclusive shopping, travelling,surfing,making researches, trying new foods, and practicing yoga. I&#039;m also passionate about environmentalism and social are what brings me joy!', '+2347018109370', '2a ayodele odubiyi street off petrocam filling station oriwu road elf lekki lagos nigeria', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 18:18:18', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(186, NULL, 'Nkiruka', 'Ebere Okafor', 'Nkiruka Ebere Okafor', NULL, 'nkybaks@yahoo.com', 0, '$2y$10$WHGal0IGa9xoMQ7a37e0gOz8xZFbBed6XrNOSu9qBFUmTDgkYD08q', 'Am passionate about self and youth development, for a better nation building.', '08037995357', '55 Unity street Trans Ekulu Enugu', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 18:39:43', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(187, NULL, 'Azeez', 'Ganiyu', 'Azeez Ganiyu', NULL, 'azeezganiuabiola55@gmail.com', 0, '$2y$10$MXW6Hz/pfiUdpMq4q95Ftu4yXjljGnVGBqIt14Qo1kzvtJW6jMpl2', 'very ambitious and a goal getter.', '07064207713', 'Ado-odo', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 19:00:30', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(188, NULL, 'Umar', 'Emmanuel', 'Umar Emmanuel', NULL, 'emmanuelumar001@gmail.com', 0, '$2y$10$jG94UXU4xMrMdjXtRIDNQ.wxhsGdV5ScnDoIhqGJLiXvfQGVEVLt6', 'Visionary and ambitious defines me', '09026355245', 'Azare', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 19:01:38', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(189, NULL, 'John', 'Dorcas', 'John Dorcas', NULL, 'dorcaseric1990@gmail.com', 0, '$2y$10$hH4A.b4QOqXTNI4VO6SFWeUVxaIsIvb3YS3jD5KM2Pw3Qq7CmNE4S', 'Passionate about helping and supporting people find life worth living n living life to the fullest.', '08133527004', 'Abuja Nigeria', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 19:07:09', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(190, NULL, 'Wukatwe', 'Charles Wakji', 'Wukatwe Charles Wakji', NULL, 'charlzwakji@gmail.com', 0, '$2y$10$Hl81iQgWEBWYK34ABJYyAuhoW84kq4RTMelrSxeTeJ7L2yHDbIZde', 'Lawyer', '08036877117', 'Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 19:11:59', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(191, NULL, 'Wisdom', 'Chiekeziem Anucha', 'Wisdom Chiekeziem Anucha', NULL, 'wisdomanucha4@gmail.com', 0, '$2y$10$IhXtpu/axXxyqeZTxB0UFeeI68giMjnjaiOOUdUnvRd4/YmMVN9sq', 'A young lad who&#039;s got great innovation for youth development', '09074200370', '37, Adeleye Street, Ifako - Gbagada, Lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 19:13:02', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL);
INSERT INTO `users` (`id`, `google_id`, `first_name`, `last_name`, `username`, `gender`, `email`, `email_verified`, `password`, `bio`, `phone`, `address`, `state`, `lga`, `profile_picture`, `role`, `referred_by`, `recognitions`, `cadre_level_id`, `created_at`, `skills`, `availability`, `has_disability`, `disability_details`, `program_preferences`, `consent_data_processing`, `consent_communications`, `status`, `verification_token`, `reset_token`, `reset_token_expiry`, `last_login`, `instructor_pending`, `instructor_applied_at`, `instructor_approved_at`, `instructor_approved_by`) VALUES
(192, NULL, 'UBONG', 'IFEOMA', 'UBONG IFEOMA', NULL, 'ifeomablessing138@gmail.com', 0, '$2y$10$Bu8qT4KL/xTZ9ZJCsd6D4u5I7wuH0gwy3aIKd.fQu6.6GRY0MvO6O', 'I&#039;m a young talented woman who is teachable and ready to learn.', '08066243730', 'ASABA', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 19:15:08', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(193, NULL, 'Mohammed', 'fatima Tajudeen', 'Mohammed fatima Tajudeen', NULL, 'oluwasheyi429@gmail.com', 0, '$2y$10$y7OjwFwbChhFUN8RDJdd3evq3KvpCwtmydPx3iF/UXDIV06vzorX6', 'An enthusiastic healthcare giver and an advocate', '07064745183', 'Phase 3 gwagwalada. No 9 Kashikoko crescent', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 19:32:05', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(194, NULL, 'Martins', 'Oluwakemi Margaret', 'Martins Oluwakemi Margaret', NULL, 'martinsoluwakemi1988@gmail.com', 0, '$2y$10$bu1EfqF2hLc3Q8VrN/rXmujQQncwIMFIhkP7nt5DvxwYxVOExlODq', 'My goal is to be able to reach out to people that needs my capabilities', '0806 759 6163', 'Ogun State', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 19:34:15', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(195, NULL, 'Ruth', 'Friday Olue', 'Ruth Friday Olue', NULL, 'ruth.olue@gmail.com', 0, '$2y$10$wOuqTRtilVqHAWme2SUjo.cNnghY8R4FZVb2cXopoArjPBPoq6UOi', '', '08105317920', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 19:38:17', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(196, NULL, 'ANU-OLUWAPO', 'PEACE MUSTAPHA', 'ANU-OLUWAPO PEACE MUSTAPHA', NULL, 'anumustapha4@gmail.com', 0, '$2y$10$NBqe5ngfwYSVs41w5PXxiugGQDtFctldcgSBFcn2t8MujYtgs28C6', 'My name is Anu, and I hold a degree in English Language. I am passionate about helping others, building meaningful connections, and contributing positively to my community. I bring strong communication, teamwork, and organizational skills, along with a willingness to learn and adapt. Volunteering gives me the opportunity to share my skills while also gaining valuable experiences that help me grow personally and professionally.', '08132189530', 'Gwagwalada, Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 19:52:34', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(197, NULL, 'Ezeugo', 'Ijeoma', 'Ezeugo Ijeoma', NULL, 'ezeugoijeoma109@gmail.com', 0, '$2y$10$qhvyouzJzJu7nm1B1yaRK.90Y2VrZ7.eLFSzVLc0uRnM3odkqhZey', 'Ezeugo Ijeoma. A data scientist.', '08160134635', 'Area 1, Garki Abuja.', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 19:55:26', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(198, NULL, 'Abdullahi', 'Umar', 'Abdullahi Umar', NULL, 'abdullahiumar559@gmail.com', 0, '$2y$10$N5Maj4AGI5sIdsli5ahxFeYhZsAgfss.vEE/kwVD1emBltfiraw3W', '', '07034497151', 'No.23. layin yamma kwai, Faskari LGA, Katsina State.', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 20:22:28', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(199, NULL, 'Ayanyemi', 'Barakah Adedoyin', 'Ayanyemi Barakah Adedoyin', NULL, 'ayanyemibarakah@gmail.com', 0, '$2y$10$g5W980E6a7OVJALRuQFENuw9Ykta6G9amE.GWwCz4bpDjxaq97ikm', 'I&#039;m a student of Information Technology and Health Informatics at federal University of Health Sciences Ila Orangun, Osun State. I love to advocate for health and youth development.', '09161433204', 'Osogbo, Osun State.', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 20:25:05', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(200, NULL, 'Oyetunde', 'Favour Esther', 'Oyetunde Favour Esther', NULL, 'oyetundee220@gmail.com', 0, '$2y$10$CzhfQiu/ZIpek8KnREu1PuwrqZCqst2/ZoS8pn4vpVdpC6YYnZrwi', 'Law student,daughter of Christ', '0916 598 4818', 'Mararaba Aso pada', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 20:39:35', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(201, NULL, 'Dakup', 'Jatau Jan', 'Dakup Jatau Jan', NULL, 'janjataudakup@gmail.com', 0, '$2y$10$904pgQJ9WONXf4hIKFYLbeX6OyQ1WpF9u6nij7xDlk.9fRmq424JO', '', '08148710476', 'Plateau state jos north rukuba road no 17 avan street', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 21:08:09', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(202, NULL, 'Abdullahi', 'Sharhabil Lawan', 'Abdullahi Sharhabil Lawan', NULL, 'lawansa5574@gmail.com', 0, '$2y$10$qNcKPtilCErq.qTAuzzGPeR6XEw3sK92olapC/U3Zyj28.hiLHSmC', 'Simple', '07034795793', 'Jos plateau/Bukuru', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 21:25:14', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(203, NULL, 'Racheal', 'Reuben Mashor', 'Racheal Reuben Mashor', NULL, 'mashorracheal@gmail.com', 0, '$2y$10$LWE/VG..e.g6wJEaoS7R7uQFKqwBKEjALwW5ehXybA6K8YUGRMnMu', 'My name is Racheal Reuben Mashor a graduate of science laboratory technology.', '09034669622', 'Ecwa church kvom/ Jos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 21:27:17', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(204, NULL, 'Akingbola', 'Deborah', 'Akingbola Deborah', NULL, 'akingboladeborah7@gmail.com', 0, '$2y$10$2BDyaaObTzC4.aDOHjJzEeokZP5ac8Yqn3Hi5bIrcDksAslxt5iS6', 'Hi, l’m Debby, a student of the university of Abuja studying Accounting, ready to learn and know more about Dade Foundation', '07056963253', 'Abuja Municipal', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 22:23:51', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(205, NULL, 'Owolaiye', 'Amos', 'Owolaiye Amos', NULL, 'amosowolaye07@gmail.com', 0, '$2y$10$pU5WAfFO6GzHAgEos5sdOeUOjlQMWDjX0XCcDPiRsHj.kUclxcdia', '.', '07015040238', '6 osanyin Street Alagomeji yaba/ Lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 22:31:28', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(206, NULL, 'Chuku', 'Michael nnana', 'Chuku Michael nnana', NULL, 'chukumic85@gmail.com', 0, '$2y$10$Lhnqeodd52dtYSkCFgaHc.w46VkcMJ4sXu/x.A3cIz2kO9V9MnUiS', 'Passionate about life impact', '08064889995', 'Port Harcourt', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 23:35:18', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(207, NULL, 'Asiyanbola', 'Damilare Oluwaseun', 'Asiyanbola Damilare Oluwaseun', NULL, 'asinyanbolar@gmail.com', 0, '$2y$10$o3lmUQM/ZzbQ5Rw5XnO.rezH5Iazp57525vYyFuaVcTEmfqYrNc9W', 'A Microbiologist with other 5 years working experience across different humanitarian organisations', '07063437138', 'Lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 23:35:40', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(208, NULL, 'Deborah', 'Mojisola Adili', 'Deborah Mojisola Adili', NULL, 'debbistix@gmail.com', 0, '$2y$10$zy2E64yF.cFtcf6ZTbC1D.HNrtdpe56bu3mmhCU5I5lZDw7VIILI2', 'I love volunteering because I believe giving back to the community is essential, and I&#039;m passionate about making a positive impact in the lives of others. I&#039;m an educator.', '07038929560', 'Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-02 23:44:41', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(209, NULL, 'Samson', 'Adeola Adejumo', 'Samson Adeola Adejumo', NULL, 'samsonadeolaadejumo@gmail.com', 0, '$2y$10$ta14QBO/.BBSzv5iFhwtQ.1qFI88ZtgjMf7DZfoqjM56U/zjcgqDS', 'Am a food and beverage specialist and also a social worker I love to impact the community with my passion for building a better society', '08108448897', 'Lagos/Nigeria', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 00:23:25', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(210, NULL, 'Emeka', 'Emmanuel Ezeanyika', 'Emeka Emmanuel Ezeanyika', NULL, 'emekaemmanuelezeanyika@gmail.com', 0, '$2y$10$y6UFIEkiN6xzLNmL4VkBleX1wS3r2/TkZtqZWK5gFPWf7WA5pUIxe', '', '07045475489', 'WUSE 2 ABUJA', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 00:38:55', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(211, NULL, 'Ada', 'Mary Anyaeji', 'Ada Mary Anyaeji', NULL, 'irobiada55@gmail.com', 0, '$2y$10$HOu19QXACREkhLnmL8Jb7ux.caobrxpwVXvs7OjVoBq671eiaeAya', 'My name is Ada Mary Anyaeji, my state of origin is Imo state. An accountant.', '07040698366', 'No 3 Ujam Street Achara Layout Enugu State,Nigeria.', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 01:53:42', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(212, NULL, 'Naansing', 'Jones Zailani', 'Naansing Jones Zailani', NULL, 'edifyjones@gmail.com', 0, '$2y$10$k2YDrgPHwoBzLwoPn3iuBOyzKxqyaHEmxWOzsvzxQyOXff0fd7P1m', 'Am young energetic and have passion for reaching out to both needy', '08037770704', '6 panyam street Jos plateau', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 03:39:04', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(213, NULL, 'Ahmad', 'Abubakar', 'Ahmad Abubakar', NULL, 'faazabubakartijjani@gmail.com', 0, '$2y$10$jZTc72rloEC7fr712XuayOcrq0IYSB0qgVXe0/U5vzC.T26fYfOX2', '', '09061650646', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 03:46:29', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(214, NULL, 'Godwin', 'Ejoga', 'Godwin Ejoga', NULL, 'godwinejoga@gmail.com', 0, '$2y$10$o3RDmUh4rshUfdfC6gbmbOB8Op/0s9T99Mi.jGn4u0OoVVO9uVhSW', '', '+2348108301618', 'Yaba, Lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 04:06:17', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(215, NULL, 'ISAH', 'ABDULLAHI', 'ISAH ABDULLAHI', NULL, 'yareema2508@gmail.com', 0, '$2y$10$Hhbh3D0hdbJtUmhMhk1yUOo4HUpM/OY6i74jLWGjueZQVK8FIZVnW', '', '08100843557', 'No 49 kauna avenue Abakpa new extension kaduana', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 04:28:37', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(216, NULL, 'David', 'Prosper', 'David Prosper', NULL, 'davidprosper1816@gmail.com', 0, '$2y$10$aleIn0wJrgOgEScswM44AuSccj.M05NxoliBqBFaR.nKnHhl8KCHG', '', '08140776731', 'Abuja Nigeria', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 04:30:46', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(217, NULL, 'Orji', 'Henry', 'Orji Henry', NULL, 'orjihenry48@gmail.com', 0, '$2y$10$FFtd7NExuju9ZR6kc90kN.Bz3F11JmJPeT1sqbMe15Q5yDCH9Fegi', '', '08130155482', 'Port Harcourt, Rivers State', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 04:31:20', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(218, NULL, 'Ayuba', 'Timothy', 'Ayuba Timothy', NULL, 'timothyayuba445@gmail.com', 0, '$2y$10$e96iFvFCLAunseDKIoZJEeBAbLT4aI1Mfjz57sl1f938ABiWifdca', 'I&#039;m an orphan from a family 6.I grew up in the Army barrack,where I attended my primary and secondary education,Army children school and Army day secondary school.After my father retire,we move to Kaduna where I obtain my National diploma in Economic and Management studies from Federal Cooperative College Kaduna.', '08032793967', 'NO 27 Redemption Street Karatudu Gonin Hora,Kaduna City', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 05:11:01', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(219, NULL, 'Salvation', 'Joseph', 'Salvation Joseph', NULL, 'funmijoseoh8@gmail.com', 0, '$2y$10$9Bc/Hss5xa442PGdE9zBqO5oGJwLqCvBp6ykGVO7DUejUg9t3Fc7u', 'Salvation is a lover of God and a lover of people', '080032098549', 'Kaduna', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 05:11:33', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(220, NULL, 'Abayomi', 'Titilayo', 'Abayomi Titilayo', NULL, 'abayomititilayo053@gmail.com', 0, '$2y$10$71oqCc2haAFxkB4zPyTK3e/S9sRU/.mI9foIdEgYf6YfhSepnpp0O', 'I am Abayomi Titilayo\r\nWriter, spoken word artist, public speaker and child advocate.', '08125524179', '9, Adeoye Fafore Street ongorge Bus Stop, Lagos state', NULL, NULL, 'default.png', 'student', NULL, '', 1, '2025-10-03 05:38:12', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(221, NULL, 'Juliana', 'Joel Ihiabe', 'Juliana Joel Ihiabe', NULL, 'julianajoelihiabe@gmail.com', 0, '$2y$10$ZRUe36s.V62o1gQmjZjWrOHhcYCy8Ve0kvL.squANtZMuKxSFTTT6', 'BSc public health,\r\nPublic health advocate', '08065308748', 'Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 05:54:11', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(222, NULL, 'Joshua', 'Olayinka Alabi', 'Joshua Olayinka Alabi', NULL, 'ollacourse@gmail.com', 0, '$2y$10$5tFHchGeyC3bVKCYeDpwk.Yt/X2Rbsk5HtYuSwPwTrHuldL9e8uRi', 'Createive Technologist', '08033336643', '4, Rev Ilesanmi Close Abesan Estate Opeki Ipaja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 06:41:27', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(223, NULL, 'Hayatu', 'Hamza', 'Hayatu Hamza', NULL, 'hhayatuddeen1@gmail.com', 0, '$2y$10$SvCKqbI447Xyw.mnRrZ/3egSMqMBEXXh8XJbQaPZyLJ2lBhPBDZuC', 'I&#039;m hayatu hamza from nigeria. \r\nI was born in 1989 while I started my primary school in Dutsin_ma at yandaka D`model primary school Dutsin-ma.\r\nI have been secured secondary school in Dutsin_ma which as government pilot secondary school in dutsin_ma town. \r\nI have been go to my tertiary school which has affiliated ABU zaria in Dutsin_ma,Nigeria. \r\nI have got opportunity from google African scholarship to be professional based on software developer over 7 years experience.', '07034609869', 'Dutsin_ma, katsina state, Nigeria', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 06:58:48', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(224, NULL, 'Moses', 'Agbo', 'Moses Agbo', NULL, 'agbom377@gmail.com', 0, '$2y$10$K4VRkRE3whXJ3d8toqsTvugtiXOClFxzfy0CiN4.3ukaf47n9nyq2', 'I am a team player', '09067615837', 'Karu Nasarawa', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 07:39:28', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(225, NULL, 'Abraham', 'buba', 'Abraham buba', NULL, 'abrahmsino@gmail.com', 0, '$2y$10$I1gqx50sHg3hjrZLV6BG3.7bBGeUrXGppbmdt7EjjFI/LLXTb2ehG', 'Nice and humble', '07049033439', 'Kaduna', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 07:54:58', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(226, NULL, 'Ogbor', 'john chimuanya', 'Ogbor john chimuanya', NULL, 'johnchimuanya24@gmail.com', 0, '$2y$10$5yCB70vaeZnP1y3gw1uQ0.I3mTNQNlkwjkIrvZMV2x87E7TOo5mmu', '', '08104059136', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 08:09:37', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(227, NULL, 'GLADYS', 'AWENTA SAMUEL', 'GLADYS AWENTA SAMUEL', NULL, 'gladyssamuel681998@gmail.com', 0, '$2y$10$bWCbimf66TYxWEw7X67CPeXFVybLHzg8zzhthR8YiAr6IZPNiKUhe', 'I&#039;m passionate about humanity', '07084905272', 'Kaduna', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 08:39:37', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(228, NULL, 'Rahila', 'Yohanna Kokong', 'Rahila Yohanna Kokong', NULL, 'rahilakokong@gmail.com', 0, '$2y$10$Nfm06qVJn0nb2JTduh/w6OXofGF23hL2endx8YH6aHDaRFgR3UzSm', 'Nil', '07069755633', 'Jos, Nigeria', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 08:49:12', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(229, NULL, 'Nzeadibe', 'Nneka Precious', 'Nzeadibe Nneka Precious', NULL, 'nzeadibe.6469@gmail.com', 0, '$2y$10$t9m5HWHHS8D.S2UR1F7OPOPlNUgAaMqcw9yKcZlgcvM5c/my6U/sS', 'Very Articulative, meticulous and God feaeing', '08164917285', 'Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 09:32:54', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(230, NULL, 'Tersoo', 'Simeon Biem', 'Tersoo Simeon Biem', NULL, 'biemtersoo1@gmail.com', 0, '$2y$10$ug/UC66ZMJWN.yh3p8ePG.pxBVI9SmdK9bo.lsB5tvWl3t8K6x1z6', 'A trained journalist but with high interest in human and community capacity development. Believer and upholder of equality irrespective of gender and background.', '08134709987', 'ECWA Church Lane Durumi 3 Abuja', NULL, NULL, '68dfac54ea7d4-sim.jpg', 'student', NULL, NULL, 1, '2025-10-03 09:45:39', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(231, NULL, 'Kabu', 'bulama maina', 'Kabu bulama maina', NULL, 'kabubulama1984@gmail.com', 0, '$2y$10$Jz2UvIreWg.qjm.PEtxuC.oxqO5tSdz8XrzZJcJLEVkh9FPb306bS', 'A village farmer', '07084009474', 'Borno State chibok LGA', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 10:34:53', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(232, NULL, 'Bethel', 'Emmanuel', 'Bethel Emmanuel', NULL, 'bethellite04@gmail.com', 0, '$2y$10$vW9rVS3Lq5tZ5BzDSbCN6eylcA3z/bVKwN2N8Y5n9JaV85R8ifQQO', '', '07031357791', 'Ikeja, Lagos', NULL, NULL, '68e4d993e93f4-Untitled.png', 'student', NULL, NULL, 1, '2025-10-03 10:45:16', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(233, NULL, 'Fadeke', 'Layade', 'Fadeke Layade', NULL, 'layadefadeke@gmail.com', 0, '$2y$10$mZMZSvKZ3WCkKDChy9nJoe3ay22IMqRBmlrwAp1O5tO9HukhZNkUy', 'Fadeke Layade is an entrepreneur, fashion designer, and leather bag manufacturer with over 7 years of experience in producing quality leather and fabric products. She is the founder of Fadek-set Concept, a business that specializes in handbags, school bags, lunch boxes, laptop bags, and accessories, while also training youths and women in leather works. As a community-based training service provider in Ogun State, she is passionate about skills development, creativity, and empowering others to build sustainable livelihoods.', '07033092920', '44, idishin ijeja, Abeokuta', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 10:47:23', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(234, NULL, 'Mohammed', 'Abubakar', 'Mohammed Abubakar', NULL, 'mohdabbakarr@gmail.com', 0, '$2y$10$S07Rbw4ZVIYJCH4bwsS9gesvmX8XF84hemhioCN/ROhpxo3FfyyFa', '', '07065656558', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 10:54:45', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(235, NULL, 'Eva', 'Linda Oshaju', 'Eva Linda Oshaju', NULL, 'vava20042000@yahoo.com', 0, '$2y$10$PjqfaPO2a9.vWAjmkW/Awuc3ON6pa6i92OZdotyaLCHlK8W1Z1sHa', 'I am a very smart woman', '08033146221', 'Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 11:02:13', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(236, NULL, 'Enyinnah', 'victor', 'Enyinnah victor', NULL, 'enyinnahvictor38@gmail.com', 0, '$2y$10$gKJ.9rAKcgTCufG8BOOs6ur0QPCvEf7yUHg3WaMoJ5yVi8gw7VoR2', 'I am Enyinnah victor.Am from Abia state.I studied mechanical engineering Technology.I have passion to help people who are in need.I am friendly and I love to work in a team.', '08134417327', 'No 50 umudo Road Abayi', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 12:16:01', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(237, NULL, 'Ifada', 'Andy Isioma', 'Ifada Andy Isioma', NULL, 'ifadaandy@yahoo.com', 0, '$2y$10$mT8Wm5L54fWiyeJ/35mfZ.SIX6CcuYOmpCOVNHwh2fqs4kwp8pHMO', '', '+2347030282514', '7 Peace Crescent Area 1 Estate Salolo Lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 12:32:56', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(238, NULL, 'Akoh', 'Freedom', 'Akoh Freedom', NULL, 'akohfreedom@gmail.com', 0, '$2y$10$67iDVenr51H1ifEfb/Rq2ukzFDvAcV5QVNSovvGXUgCz.ToEQbYuG', 'I&#039;m ma data professional with interest in developmental projects such as this and I love to volunteer my skills for the betterment of the society', '+2348051830427', 'Phase 4,Kubwa. FCT, Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 12:45:56', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(239, NULL, 'Hussaini', 'Aminu', 'Hussaini Aminu', NULL, 'hussainiaminu406@gmail.com', 0, '$2y$10$mJeHiN5dlXlc9hBLXuKj1eBrnEV8lQpczKMe7iKnbTKuXlCVPCZKK', '', '09038098449', 'No. 1802 bachirawa. Ungogo L.G Kano state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 13:13:50', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(240, NULL, 'Bala', 'negab becky', 'Bala negab becky', NULL, 'bbeckybala@gmail.com', 0, '$2y$10$PayvReOAOkJ1aDzM/4HCKeGILQu.9baqmJBjOoJgfLq4cKi1Zn.Bm', '', '08129501187', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 13:45:14', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(241, NULL, 'Chinonso', 'Onuoha', 'Chinonso Onuoha', NULL, 'chinonsobuchy@outlook.com', 0, '$2y$10$fGZXhmtxnBR9l5m8kyct/Oi1XKIxe2Jmqa1FzjegbEKYF7bKahgRO', '', '', 'Federal housing,Airport road, Lugbe /Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 15:16:55', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(242, NULL, 'Adebayo', 'Fatima Tunrayo', 'Adebayo Fatima Tunrayo', NULL, 'adebayofatimot6@gmail.com', 0, '$2y$10$m/vlEZ4rBBuqqH5BkBZuhectXh8DY3vZBLHLxvUSBAiknuz726f.q', 'A 5th year law student, that is passionate about adding value to the society in anyway she can.', '08131663883', 'Oshogbo, Osun state.', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 15:21:08', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(243, NULL, 'Barry', 'Paul Goni', 'Barry Paul Goni', NULL, 'barrypaul991@gmail.com', 0, '$2y$10$5qHl0kkxE80.sKcYnSeX.u0LwdUGPwVm/Hf3f1FOTmcjQ28BXHebW', '', '07037399253', 'Jos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 15:47:42', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(244, NULL, 'Bulus', 'Kabu Chibok', 'Bulus Kabu Chibok', NULL, 'buluskabu@gmail.com', 0, '$2y$10$h5ur7vmgaqkVoyueDdSL7OgGBtivz6AlQwgxOrJwAiSTXyHaXd8vi', 'Simple and very social to everyone.', '08031150929', 'Maiduguri, Borno State. Nigerian', NULL, NULL, '68e00405e218f-IMG_20250814_161305.jpg', 'student', NULL, NULL, 1, '2025-10-03 15:55:14', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(245, NULL, 'Shabanyan', 'Samson Kantiok', 'Shabanyan Samson Kantiok', NULL, 'kantiokshan55@gmail.com', 0, '$2y$10$WaTwSkKOBiQ7AISubqjCyuyta162q.jVMv2wGL4QzqcQDWm4AzxHe', 'I hold B.Sc in Mass Communication from Ahmadu Bello University,Zaria.\r\nI also hold a Professional Diploma in Education with 5 years working experience as a freelance journalist.', '07066073717', 'No 2 More Road Ungwan Romi Kaduna', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 16:01:04', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(246, NULL, 'Okenwa', 'Okenwa Elvis', 'Okenwa Okenwa Elvis', NULL, 'elvisfizer20@gmail.com', 0, '$2y$10$mUv8oh2bV30PEiALnq2R8.0mMATcd4h8MIwbCCSxsTGKebfOw5NXO', 'I am child of God, and a lover of God to shine the light of God everywhere i go.', '07038615580', 'Ikorodu lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 16:32:14', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(247, NULL, 'Christopher', 'O Dixon', 'Christopher O Dixon', NULL, 'christopherdixon202@gmail.com', 0, '$2y$10$iK6B/dhlHoV0hC706V6tV.NvZxP8ai4yAvhjV2oVnvV/BKyWvcoLu', '', '09131361911', 'Lagos', NULL, NULL, '68e00ac56e6eb-inbound6201075948779923022.jpg', 'student', NULL, NULL, 1, '2025-10-03 16:37:15', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(248, NULL, 'Friday', 'Igoche', 'Friday Igoche', NULL, 'igocheuloko@yahoo.com', 0, '$2y$10$5hjWselK2YsMDS4W0pxPw.b5N3zkzPTXdyuYWvwvvFARXXJEf/DLm', 'Male', '08055129993', 'Lafiia nasarawa state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 17:34:17', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(249, NULL, 'Habila', 'Gizo', 'Habila Gizo', NULL, 'gizohabila11@gmail.com', 0, '$2y$10$dtMCbh99ivINtoE6IQRfUukE7OewleHvpSCEpDugGM5l0jg7XJaeS', 'Kind and pragmatic', '09055037015', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 17:46:23', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(250, NULL, 'Chekwube', 'Cornelius Ngwu', 'Chekwube Cornelius Ngwu', NULL, 'ccm.ngwu@gmail.com', 0, '$2y$10$LL74YaT33QLekqWe9GVMgeNMghg2bCkLt9zj4FfGae0Nz2cb9njHC', 'Am just a simple guy, trying to make life simple for others', '+2348065282886', 'Enugu Nigeria', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 18:02:30', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(251, NULL, 'Taiwo', 'Samuel Akinremi', 'Taiwo Samuel Akinremi', NULL, 'delightakin97@gmail.com', 0, '$2y$10$wwIUa5PVQHqphwJZi0J9EOcf6s4aI5gQnhspMCQFBLCAsshmbYelS', 'I&#039;m Akinremi Taiwo Samuel, a native of Abeokuta ogun state study mass communication from The Federal Polytechnic, Ilaro ogun state currently serving in the city of Jos plateau state', '08065594578', 'Jos plateau State', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 18:24:29', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(252, NULL, 'Cirkat', 'Peter Rwamzhi', 'Cirkat Peter Rwamzhi', NULL, 'cirkat200@gmail.com', 0, '$2y$10$fHg/xzH4f88kC/6WRYbLz.eCFPPcL83TJ9M9jEghEXpddmSkU6hEm', '', '09069918642', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 18:27:28', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(253, NULL, 'Bridget', 'Donatus Eze', 'Bridget Donatus Eze', NULL, 'bridgeteze4u@gmail.com', 0, '$2y$10$ql.BMKuutOvM.AZ7M5B3ROit3LEVTAOUoHJTU2.bSKWLZV/7.M/si', 'Dedicated health advocate passionate about empowering communities through education, support, and advocacy. Committed to promoting health equity, wellness, and social justice. Let&#039;s work together to create a healthier, more compassionate world', '08162024126', 'IBAFO OGUN', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 18:35:50', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(254, NULL, 'Ajiboye', 'Tioluwanimi', 'Ajiboye Tioluwanimi', NULL, 'ajiboyetioluwanimi22@gmail.com', 0, '$2y$10$7J1iPXYw4RBw4hrLGAXbR.AOyFJr7QpAYVw3qfEzeVaocbTCYz4fi', '', '07017754938', 'Ibadan', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 18:40:58', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(255, NULL, 'Ariyo', 'Olugbosun', 'Ariyo Olugbosun', NULL, 'olugbosunariyo@yahoo.com', 0, '$2y$10$Zko9tv6yuWAm2S8M2S8yx.GE./.0zYP0BYxs0MEKRasRNi5IxSl7q', '', '+2348025392225', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 18:41:03', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(256, NULL, 'Iwuoha', 'Samuel', 'Iwuoha Samuel', NULL, 'donsamueliwuoha@gmail.com', 0, '$2y$10$bhEHfn/v41qOfdQ4eJHx7uxpKXMzCODmuzKhIOhxd2wBezYprlNTq', 'I am registered nurse who is willing to help humanity with my knowledge and skill. Empathetic, compassion, ethics and professionalism.', '+2347037393971', 'Success Hospital and Maternity Gwagwa', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 18:41:12', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(257, NULL, 'Bello', 'Idris', 'Bello Idris', NULL, 'idrisbello371@gmail.com', 0, '$2y$10$MsVqbkBGd4roqh3xa4yC9eyh/6RU55JUcgq.ccG2ZeqHFLDSvYtjW', 'I am an individual who is passionate about people&#039;s growth and peaceful cohabiting', '09026560847', 'Ikorodu, Lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 18:45:45', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(258, NULL, 'Elijah', 'Jagbadi', 'Elijah Jagbadi', NULL, 'switch4change8@gmail.com', 0, '$2y$10$STILBy9UVf9tjqd2wAG0Eek2AXP6OARy9xjalUcWGdSc1nesWkdPW', 'Influencer,Youth Leader,Leadership Coach Building Leaders,Empowering Youth,Shaping Nations', '+2348102906692', 'Lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 18:59:49', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(259, NULL, 'Aremu', 'Mary Moromoke', 'Aremu Mary Moromoke', NULL, 'aremudynamicmary@gmail.com', 0, '$2y$10$f6gNtl1MxyTRiwTCI1lp0Oud4n48aOzC4bp7SA.J6fxRK6JhJRNnG', 'Nutritionist-Dietitian dedicated to promoting healthy living through balanced nutrition, preventive care, and patient education.', '07033511133', 'Kaduna State, Nigeria', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 19:00:20', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(260, NULL, 'Wisdom', 'Sossou Peter', 'Wisdom Sossou Peter', NULL, 'mrwisdomp@gmail.com', 0, '$2y$10$N.Vua0i/GKgRK2swdCP/juNoDomu2UDeEAxcqsSbDP.cSlCgd1kXC', 'I am Amb. Wisdom Sossou Peter, a peace and humanitarian ambassador passionate about youth empowerment, social development, and global peacebuilding. Over the years, I have actively engaged in community outreach programs, advocacy campaigns, and initiatives that align with the United Nations Sustainable Development Goals, especially in the areas of education, child rights, and poverty alleviation.\r\n\r\nMy interest in joining DADE Foundations stems from my belief in collective impact. I am inspired by the Foundation’s commitment to building sustainable solutions and empowering vulnerable communities. I see this as a platform to contribute my skills, experience, and vision, while also learning and collaborating with like-minded leaders.\r\n\r\nJoining DADE Foundations will not only help me expand my reach but also strengthen my capacity to create meaningful change, particularly in promoting peace, education, and humanitarian support in Africa and beyond.', '07067610926', 'Gombe state', NULL, NULL, '68e02f836ea16-inbound1663134408.jpg', 'student', NULL, NULL, 1, '2025-10-03 19:05:38', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(261, NULL, 'Sendiyol', 'Elijah Terngu', 'Sendiyol Elijah Terngu', NULL, 'sendiyolafrica@gmail.com', 0, '$2y$10$RvtsNSTm59eVpiBploa8MOIqvUodApTevpBt1Z4DhQZnHhBF6mHOe', 'Public speaker and Counselor', '07010757022', 'Kwali abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 19:11:05', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(262, NULL, 'Anyama', 'Esther', 'Anyama Esther', NULL, 'anyamaesther@gmail.com', 0, '$2y$10$vbvnbJyGiPx/zmXgu/gNPeLsu/eRvGgF6smqGoCNVvtGQwrkkMWXS', '', '09085091349', 'Lagos Nigeria', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 19:39:06', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(263, NULL, 'Ibrahim', 'Sidiq', 'Ibrahim Sidiq', NULL, 'ibrahimsidiqolamide@gmail.com', 0, '$2y$10$oCOnN4PjTTkdPtA3vGw1eekiP0IjOJ78dBexgyU6H8Ca5.5WNl3a2', '', '09067251242', 'Ilorin', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 19:40:29', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(264, NULL, 'Grey', 'James Jeffry', 'Grey James Jeffry', NULL, 'echetaobisike1@gmail.com', 0, '$2y$10$rY5QSuBA.uw2CglyYlEI1.87jHGITm82cnrkBwTziTo7Mqp1lwoaW', 'Am just Jeff simple and easy going.', '08063521323', 'Port Harcourt', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 19:45:45', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(265, NULL, 'Mwanret', 'Walar', 'Mwanret Walar', NULL, 'felixmwanret@gmail.com', 0, '$2y$10$FBbFzRqQ9zyd.y07LQKSQuj.umdDh3gmF2ZGzJm685Fz8DmtyOuMi', 'I am a lawyer, passionate about making a difference in my society.', '08133155924', 'Jos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 19:52:03', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(266, NULL, 'Sulaiman', 'Mobolaji', 'Sulaiman Mobolaji', NULL, 'princemobolajis@gmail.com', 0, '$2y$10$HgeCxPE23KdgMaHaadqse.OiD92ndD4wLYSWd8uFuv7iDAW4YsErS', 'A graduate of Political Science and Masters in Election and Party Politics.  Leadership in development and Digra Ambassador', '+2348088012767', 'Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 20:33:19', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(267, NULL, 'Igbo', 'Emmanuel Gabriel', 'Igbo Emmanuel Gabriel', NULL, 'emmanuelgabriel326@gmail.com', 0, '$2y$10$s67nZTv9V4tUSdFZHn27keiP1LRz1M/.RufdU/kQNcz5AnfGXa.D.', '', '0902680361', 'Zenith street, Karu', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 20:37:55', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(268, NULL, 'Hannatu', 'Mam Joshua', 'Hannatu Mam Joshua', NULL, 'hmamjoshua@gmail.com', 0, '$2y$10$84IfLt4JPlh6wcdYNX3xvOIS1MSZ0zj3vzdzgslApeFwJWIFt4Xf.', 'A God fearing northen Christian', '08132620357', 'No 17 Ibrahim Alkali avenue potiskum yobe state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 20:46:54', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(269, NULL, 'Oladele', 'Zainab Ayomide', 'Oladele Zainab Ayomide', NULL, 'oladelezainab2019@yahoo.com', 0, '$2y$10$I8De1QEbg4dJEu01YHB9zuu95eDTX8.B9ZakqGMWLPGb8oKQFRhbm', 'A recent graduate of the university of Ibadan, Studied English education, and currently a peer educator at Onelife initiative for SRHR', '7039047958', 'Oyo', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 21:13:52', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(270, NULL, 'Godspower', 'Blessing', 'Godspower Blessing', NULL, 'godspoweresther335@gmail.com', 0, '$2y$10$WVFd/3HfBPeerUrgfHPW8uemqUDjWtHoBF.BmLx/sVc9MmyPG1dqS', '', '09071920801', '', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 21:24:49', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(271, NULL, 'Kennedy', 'Okpala', 'Kennedy Okpala', NULL, 'kennedyokpala@gmail.com', 0, '$2y$10$pJA2JrHqiYMq05ReUk3HM.mi4jYCQucrp1rOOepEbbTjKwvzaLIJy', '', '08064143620', 'Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-03 22:54:16', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(272, NULL, 'Yosi', 'Danjuma', 'Yosi Danjuma', NULL, 'yosidanjuma15@gmail.com', 0, '$2y$10$XElYnSnNLQe.35LMj/InbepAekbJJ/ymNwRB8XGBra1o6MRNSOMca', '', '08125973916', '', NULL, NULL, '68ffe0045e32f-1667702585028.jpg', 'student', NULL, NULL, 1, '2025-10-04 00:25:05', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(273, NULL, 'Charles', 'Charlie', 'Charles Charlie', NULL, 'charlie08168098351@gmail.com', 0, '$2y$10$nb8LMUcqd7v5UHerfG7z5.s2bG/H6XVXV0eeET0tSgCQbsm1JGGKi', '', '0816 809 8351', 'Iwoye Area Community High School', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-04 01:40:43', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(274, NULL, 'Naanwul', 'Sylvester', 'Naanwul Sylvester', NULL, 'sylvesternaanwul@gmail.com', 0, '$2y$10$G4qAGOsvqiNCX9YIqayJL.xr9h.vqiD80WA21Coa31n.VocEZrXGa', 'Naanwul Sylvester, a passionate Public Health student from Plateau State, Nigeria. With a heart for community development, Naanwul is dedicated to promoting health and wellbeing through community engagement. When not hitting the books, Naanwul loves creating unforgettable memories through event planning and contributing to community initiatives.', '07035998713', 'No 2 Tudun Wada street Jos plateau state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-04 04:20:26', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(275, NULL, 'Jacob', 'Cleopas Dodo', 'Jacob Cleopas Dodo', NULL, 'jdcleopas@gmail.com', 0, '$2y$10$2ZwrKzmP9khEfBtKyn.wG.iX2U7KciVROwEHuB4xa6ShiKX8lBGMa', 'I am Jacob Cleopas Dodo, a microbiologist with a professional diploma in education, passionate about creating opportunities, connecting people, and inspiring change. My interests span inclusive education, public health, empowerment, and advocacy, with a strong focus on promoting rights and access for marginalized groups. I bring experience in mentorship, communication, and community engagement, and I am particularly motivated by initiatives that expand inclusive health, education, and assistive technologies, believing that true development begins when everyone is equipped and empowered to thrive.', '08034401040', 'Kaduna', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-04 06:20:04', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(276, NULL, 'Abbas Sulaiman', 'Nuhu', 'Abbas Sulaiman Nuhu', NULL, 'abbasus502@gmail.com', 0, '$2y$10$E0.B4yFSdPcMbWk1HoRbBuwg4Pi/fQ86aTPI92lI2JBDngWmBGTM2', 'I was a disability advocating for devotional, I was professional in in photography Microsoft word and power point, as well as graphics design.\r\nI would like to join to promote inclusive democrats and governance.', '08025207927', 'Rano', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-04 08:50:09', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(277, NULL, 'Adeoye', 'Abisola Saidat', 'Adeoye Abisola Saidat', NULL, 'bisolasadeoye2004@gmail.com', 0, '$2y$10$fi35ucIwmua1LYb6V7q8r.sfiqjmToPkrn5uWFmYsqQ6dsrW4mf2G', 'Abisola Adeoye is a public health advocate and spoken word artist from Nigeria who uses storytelling to promote awareness and change. She is passionate about women’s rights, youth empowerment, and health equity, and hopes to work with global NGOs to create lasting impact in underserved communities.', '+2347041518941', 'Ikangba-imodi Road. Odogbolu', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-04 09:59:35', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(279, NULL, 'Hassan', 'Aminu Zubairu', 'Hassan Aminu Zubairu', NULL, 'hassanwaire1998@gmail.com', 0, '$2y$10$fG4iP1f6fIbvu89iSvH3ne.sFSKKqfBVEwPJ0X1Z9YzGVt7eQtak2', '', '07037335288', 'No 1802 Bachirawa qts, Kano State', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-04 16:13:21', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(280, '113607444519860104124', 'shuaib', 'abdul', 'shuaib abdul', NULL, 'shuaibabdul192@gmail.com', 0, '$2y$10$2r9ipsb8N9llxmwW6QxgEO6wlsNofZYAWV9RPJcaViOHETWwruVYy', '', '', '', NULL, NULL, '68e16247ab36c-_.jpeg', 'student', NULL, '', 1, '2025-10-04 16:59:37', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(281, '110371933788932278567', 'ABI', 'INALEGWU', 'ABI INALEGWU', NULL, 'abiinalegwu87@gmail.com', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocJgrS4rMnfJeOrTdpw6pdv24Gz83ShXhjummUeGMb5QzSATzJlt=s96-c', 'student', NULL, NULL, 1, '2025-10-04 16:59:43', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(282, NULL, 'Victoria', 'Sani', 'Victoria Sani', NULL, 'vicksamsanik@gmail.com', 0, '$2y$10$CKwqCXOpKHibpS/ABn1vvu894zr/kBlti9fxwyIWTwUGxyoG3p85W', '', '08135509968', 'Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-04 17:16:17', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(283, NULL, 'AbdulQuadri', 'Issa', 'AbdulQuadri Issa', NULL, 'abdulquadriissa@gmail.com', 0, '$2y$10$IntqmW2Gr7qKTNsVEKY5fuNdekWHftrPsew4EThQQwRKh20YXsT5S', 'Disability Inclusion Advocate', '09035044154', 'TOS Benson Cresent Utako Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-04 17:48:02', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(284, NULL, 'Orgu Ezichi', 'Glory', 'Orgu Ezichi Glory', NULL, 'oorguezichi@gmail.com', 0, '$2y$10$nCc29d1o3WQ0fmUbp6jDtO4N6Nev92cwat3gclMBnbxmujRnBzCH.', 'I don&#039;t know I am learning', '08105442093', '1, jinadu shotomwa road', NULL, NULL, '68e17d877a5f3-images.jpeg', 'student', NULL, NULL, 1, '2025-10-04 18:54:38', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(285, NULL, 'Ajayi', 'Omotola Jumoke', 'Ajayi Omotola Jumoke', NULL, 'ajayiomotola001@gmail.com', 0, '$2y$10$A.JVXRAudezr7FRfRrTgveQAFU9FQyPCsV5LRfECKLTOq/eRdR8JC', 'A goal oriented fellow with a passion for impacting young minds', '08160257942', 'Ibadan', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-04 19:40:05', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(286, NULL, 'Kabiru', 'Fandubu', 'Kabiru Fandubu', NULL, 'kabirsfandubu@gmail.com', 0, '$2y$10$QuRENpitd.pHLhqm.4HkyO919nzwoUNYmqcFMSMW2D3yZQqNPzqs2', '‎I have good communication and teamwork skills, and I enjoy working with people to achieve a common goal. I’m also organized, creative, and willing to learn quickly.\r\n‎\r\n‎I want to volunteer because I belixdeve in giving back to my community and making a positive impact where I can. Volunteering allows me to share my skills, gain new experiences, and grow while contributing to something meaningful.', '09034633834', 'Kano', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-04 23:05:18', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(287, NULL, 'Aisha', 'Usman', 'Aisha Usman', NULL, 'aishawalida62@gmail.com', 0, '$2y$10$KdjoGfhTebi5XYS6REndhueOsFO9AY7ECUPDftRoIEmJrQbGK6IaC', 'I am working as a designer fashion and I am studying of the department of Islamic studies', '+2349137807580', '4, olatunde street amuwo odofin/Lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-05 08:19:45', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(288, NULL, 'Alabi', 'Rilwan', 'Alabi Rilwan', NULL, 'omobaadeyemi22@gmail.com', 0, '$2y$10$AxdJyrUXo4HssjpD.K.IxOmf6W2bigZDDjytZGWpA8KnunLVwCWUK', '', '07069621300', '22,Oladele street', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-05 12:34:07', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(289, NULL, 'David', 'Uwello Joshua', 'David Uwello Joshua', NULL, 'davidjoshua1060@gmail.com', 0, '$2y$10$Bs2mA7oF56uS4tKYpbubXe/vaXV2nxq7z8wqo.vwx.goSJXb.ilFq', '', '08067617853', 'Nasarawa state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-06 04:00:14', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(290, NULL, 'Ahmad', 'Sambo', 'Ahmad Sambo', NULL, 'uxuhsermbo@gmail.com', 0, '$2y$10$J9pz1Hn2Kz5d5V2RG0AuieaULPXAv6S97mNRYJolmFy3wdpFin3bS', 'My name is Ahmad Sambo. I am physically challenged with spine injury due to road traffic accident. I aman entrepreneur and I am sound with good academic background as an Educationist I am highly motivated disability advocate as well.', '08063418554', 'No.2 Rima radio road. Sokoto', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-08 07:09:29', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(291, NULL, 'Victoria', 'Ufomba-Chikanma', 'Victoria Ufomba-Chikanma', NULL, 'victoria.ufombachikanma@outlook.com', 0, '$2y$10$jTb2ZxDHdjwdwPidiEwGFuZP99k2kZdzd0NRPQZs5ruxhDF8uKQKe', 'I am a motivated professional accountant who is passionate about making people smile', '07030019259', '7 Onyebuchi close behind Starline house off opobo road Ovom/ Aba', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-09 15:46:39', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(292, NULL, 'Onyinyechi', 'Ethelbert', 'Onyinyechi Ethelbert', NULL, 'genteeldeevine@gmail.com', 0, '$2y$10$k47.UZMMzS60UDAqxkWnw.mOzHoQXdau0cYQ2bV92s105qg.LLt8W', 'I have a good writing and communication skills, as well as experience in graphic design using some AI tools. I’m seeking a volunteer opportunity to further develop these abilities, build my confidence, enhance my job readiness, and gain valuable hands-on experience.', '07069352262', 'Gbazango Extension, Kubwa. Abuja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-11 09:00:08', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(293, NULL, 'SHUAIB', 'ABDOOL', 'SHUAIB ABDOOL', NULL, 'bigdaddymultimedia002@gmail.com', 0, '$2y$10$4CdFt3k5Kl8qBMHxl5uD8ONhwK1HPUYD99o4oE0qWloP4Vo.s79j.', 'sjkdjkds', '08122598372', 'bOSSO NGIGERIA NIGER STATE', NULL, NULL, '6904f659cadac-pngwing.com (1).png', 'student', NULL, '', 1, '2025-10-12 21:38:58', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(294, NULL, 'Abdussalam', 'Usman Ali', 'Abdussalam Usman Ali', NULL, 'abdussalamusmanali@gmail.com', 0, '$2y$10$3FcQSTlbVU3A6cOPxQNdu.6oZE9CHRMW04aGpAW0ATh3oGSGSLW2m', 'I am a dedicated and hardworking individual with a passion for learning and self-development. I enjoy gaining new skills, working with others, and applying my knowledge to achieve positive results in any task I take on.', '08035292970', 'Kofar ruwa', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-14 10:07:50', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(295, NULL, 'Zuwaira Tijjani hassan', 'Zuwaira', 'Zuwaira Tijjani hassan Zuwaira', NULL, 'zuwairatijjanih@gmail.com', 0, '$2y$10$o8pBz2MSTb2l7OYctFvlAuvX21iwsw81LrJ96TSqWM6SHpgnHA81a', '', '09064682781', 'Tamburawa', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-14 22:24:53', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(296, NULL, 'Isah Haruna', 'Ismaila', 'Isah Haruna Ismaila', NULL, 'isahh4323@gmail.com', 0, '$2y$10$MViBWIFqGn5N5ZLT3fzCi.thVuxreKlyUzUOCCxMHeYMofE1uL0Lm', 'We want to experience with positive for training', '08037400327', '89 Gariyo avenue, Gadon kaya', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-16 10:12:09', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(297, NULL, 'Hope', 'Benson', 'Hope Benson', NULL, 'hopebenson76@gmail.com', 0, '$2y$10$xH4iY/BEblAIAbH40iLcveJDdpS8Bat9WqckqqscGi/i1JvTD7982', 'I always to impact and put smiles on people faces', '08118050427', 'Lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-16 17:37:03', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(298, NULL, 'Rabiu', 'Garba', 'Rabiu Garba', NULL, 'rabiugarba90@gmail.com', 0, '$2y$10$hdCAcB7DRTPjfp3mdtGa9uvExeoLl3GI7V.lcbJMrWWYPZPXbtGEi', 'I want to improve my skills', '08033791147', '19 barau inuwa street', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-16 17:39:41', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(299, NULL, 'Abdul', 'Kareem Adedayo', 'Abdul Kareem Adedayo', NULL, 'salisusofiyyah57@gmail.com', 0, '$2y$10$i16JXojnJtUulpjuamSbNOKHZa.Ap3OrpxtPz8etHrNPFh46LlAYW', 'I am a dedicated and value driven individual committed to contributing positively to any organization', '08057051549', 'Ikorodu Lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-16 20:11:49', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(300, NULL, 'Pearl', 'Akpaka', 'Pearl Akpaka', NULL, 'akpakapearl800@gmail.com', 0, '$2y$10$XNwONngL1D.9PB0Rg7IyHearLOIq.AHjwjhLiHLfqtPapUwkQgBYm', 'I love helping people in whatever way I can. I could have been a doctor or a nurse but since I&#039;m not, I volunteer. And volunteering helps me remember that I can still help in so many ways.', '08143433016', '10, Aso Rock Estate, Bucknor, Lagos state', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-17 04:33:11', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL);
INSERT INTO `users` (`id`, `google_id`, `first_name`, `last_name`, `username`, `gender`, `email`, `email_verified`, `password`, `bio`, `phone`, `address`, `state`, `lga`, `profile_picture`, `role`, `referred_by`, `recognitions`, `cadre_level_id`, `created_at`, `skills`, `availability`, `has_disability`, `disability_details`, `program_preferences`, `consent_data_processing`, `consent_communications`, `status`, `verification_token`, `reset_token`, `reset_token_expiry`, `last_login`, `instructor_pending`, `instructor_applied_at`, `instructor_approved_at`, `instructor_approved_by`) VALUES
(301, NULL, 'Kausara', 'Osumare', 'Kausara Osumare', NULL, 'osumarekausara@gmail.com', 0, '$2y$10$698orfarvEicOdw09hW/5ejkIeDEwjFc.dhr.SsI4.Je6jiBzGuQq', 'I&#039;m Osumare kausara Kofoworola, a youth who is passionate about making impact, making changes in my community and in the lives of people', '09070154824', 'Ojo, Lagos', NULL, NULL, '69050bca9d74d-IMG-20251013-WA0143.jpg', 'student', NULL, NULL, 2, '2025-10-17 13:34:15', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(302, NULL, 'Mildred', 'Ibiso Williams', 'Mildred Ibiso Williams', NULL, 'mildredcookey9@gmail.com', 0, '$2y$10$WFs8JRYm.bMLcsL4Be8JZO/9RBYLXkP0rjpUz/whcBtb7Mt4fVD.m', 'An analytical and compassionate in nature, I pay attention to detail, a good communicator with administrative and leadership skills.', '806-788-7810', '6 Fuselage Street, Samonda Ibadan', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-17 16:04:14', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(303, NULL, 'Yahaya', 'SaniAdam', 'Yahaya SaniAdam', NULL, 'yahayasaniadam2020@gmail.com', 0, '$2y$10$P7lq/.ynVZf/ytSUXjxddeT.fEzLfLY0EHRR78o6inO1HT/dAzn0m', 'I&#039;m Yahaya Sani Adam', '08062357650', 'Abujan Mai Mala Gujba road Damaturu Yobe State', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-22 07:55:19', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(304, NULL, 'Tahir', 'Aminu Tahir', 'Tahir Aminu Tahir', NULL, 'tahiraminu100@gmail.com', 0, '$2y$10$19AeIkTsVyWogmM8YxFHK.ie2ZHD0dxdQB/i8w27fQZ048gM2vDCq', 'I am passionate and dynamic professional with a background in Mechanical Engineering and a B.Sc. in Statistics from Modibbo Adama University, Yola. Highly skilled in media and journalism, photography, and videography, with hands-on experience in youth mobilization, community advocacy, and peacebuilding initiatives. Recognized for promoting social cohesion and unity among diverse communities through communication and data-driven approaches.', '08068059058', 'No 14 Lamdo katsina street yola south local government adamawa state', NULL, NULL, '68f8b6cb1306b-inbound1123797838123139184.jpg', 'student', NULL, NULL, 1, '2025-10-22 09:40:28', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(305, NULL, 'Bilkisu', 'Salisu', 'Bilkisu Salisu', NULL, 'bilkisusaliumusa@gmail.com', 0, '$2y$10$uM/mOz2jOkDohc2.XseDQ.V.uLMTfs/ARKMDlNGfj2SGrJOtgulNm', '', '07035927251', 'Gwammaja', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-22 18:49:37', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(307, NULL, 'SHUAIB', 'ABDOOL', 'SHUAIB ABDOOL', 'Male', 'bigdaddymultimedia1@gmail.com', 0, '$2y$10$57Hh/7UVPIUf75d78uR/0O4kiv4hTeGBEf/EZTITcy3kHVIKuaJsC', '', '08122598372', 'bOSSO NGIGERIA NIGER STATE', '', '', 'default.png', 'student', NULL, NULL, 1, '2025-10-23 12:44:07', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(308, NULL, 'Okeke', 'Sunday', 'Okeke Sunday', NULL, 'Okeke.sunday1800@gmail.com', 0, '$2y$10$wolTpJPA9b6jlcrqK3HWPej9kNdN6nWPA6ciGIF9/QKtYBgvbUcTK', 'I’m passionate and dedicated to bring change and imprint happiness into people face.', '07031095071', 'Surulere, lagos', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-23 15:11:42', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(309, NULL, 'Babawande', 'Habibat Adebisi', 'Babawande Habibat Adebisi', NULL, 'babawandehabibat004@gmail.com', 0, '$2y$10$Tw529ImnnUJMadeBQeAy0eJU0/o8WT7ixS6J8ypX91zpoJsxAG3E2', 'Habibat Babawande is a student nurse and youth advocate passionate about inclusive healthcare and social impact. As a young leader, she promotes compassion, advocacy, and empowerment, which are values she hopes to extend through service to persons with disabilities.', '08167624623', '1-5, Oba Akinjobi Way, Ikeja, Lagos State.', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-23 17:17:20', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(310, NULL, 'Abiiba', 'Ikeoluwa Lydia', 'Abiiba Ikeoluwa Lydia', NULL, 'abiibaikeoluwa@gmail.com', 0, '$2y$10$P1PJIjuk8NbwzH.uhL/Hp.DX/4M.C1uwO7VS2ffpDcTKOUnDopkRm', 'Passionate about creating positive change, I love contributing and supporting to community development. I strive to make a meaningful impact to lives of others. I&#039;m driven by a desire to create lasting change.', '09060774707', 'Ibadan', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-23 17:38:17', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(311, NULL, 'SALAWU', 'FARUQ ABIMBOLA', 'SALAWU FARUQ ABIMBOLA', NULL, 'flexybimbz@gmail.com', 0, '$2y$10$s3wTPMeG23oXqHCfKXYAVeXO11YJ3R89Fqy5KbYVJARIWnNZkm5Ti', 'I’m a Radiography student who’s passionate about helping others and creating positive change. I love being part of projects that promote health, education, and community development', '+2349016188157', 'School road Oke baale,Osogbo', NULL, NULL, 'default.png', 'student', NULL, NULL, 1, '2025-10-23 18:12:33', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(312, '115575782155914830013', 'Azeezat', 'TIJANI', 'Azeezat TIJANI', NULL, 'tijaniai.20@student.funaab.edu.ng', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocLI_aJ_WypKc525gbKecNEXoM-yIdv5cySHJzy_Sq83TKiafQ=s96-c', 'student', NULL, NULL, 1, '2025-10-27 13:51:33', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(314, '111096996143337537736', 'Omar', 'Emmanuel', 'Omar Emmanuel', NULL, 'emmyomar01@gmail.com', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocKM_BpTtetywlX2TBXO-aPOmUBkq10i1YxD-kL63ZTU1s58CqHa=s96-c', 'student', NULL, NULL, 1, '2025-10-27 16:20:39', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(317, NULL, 'Esther Apeh', 'Ene', 'Esther Apeh Ene', 'Female', 'estherela4@gmail.com', 0, '$2y$10$DmrdQz7e8FTFeowM.8cxh.8SN/.b16Gx5aANQkG6F6SRrFVm63ZVC', 'I want to volunteer because i want to give more meaning to people&#039;s lives align their actions with their values', '08161550259', '15 soba road barnawa Kaduna phase 2', 'Kaduna', 'Chikun', 'default.png', 'student', NULL, NULL, 1, '2025-10-28 15:13:48', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(318, '113604691834320030198', 'Mujittapha', 'AHMED', 'Mujittapha AHMED', NULL, 'mujittaphaahmed111@gmail.com', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocKSf9ZLWON-3N9iNR3H4dd0djQsWcGHF4NiMtdrUm3lLonrWkX4=s96-c', 'student', NULL, NULL, 1, '2025-12-11 05:32:24', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(320, NULL, 'Shuaibu', 'Abdools', 'Shuaibu Abdools', 'Male', 'p3hub2@gmail.com', 1, '$2y$10$bOowDACHWU5I8jXLDCPdruVnCfZLcHiumOIb2RDKrMy.43GhbasgW', 'A bit about my self is me', '08122598372', 'Bosso', 'Niger', 'Chanchaga', 'avatar_320_1770372901.jpg', 'admin', NULL, NULL, 1, '2026-01-27 14:15:30', 'Community Outreach, Social Media', 'Weekends, Evenings', 1, 'Test', 'Community Building, Skills Empowerment', 1, 1, 'active', NULL, NULL, NULL, '2026-02-07 12:23:44', 0, NULL, NULL, NULL),
(322, NULL, 'Victoria', 'Bawa', 'Victoria Bawa', 'Female', 'enevickie@gmail.com', 0, '$2y$10$Cla1S0Svm5/ZrfzFekIQruZitaFuOtxOXC6ND7oQ7cibTladZ/NFq', 'A 500LVL statistics student passionate about human stability , love to read and create. A lover of Christ', '08071567954', 'no 10 joseph wayas close zone A Abuja', 'FCT', 'AMAC', 'default.png', 'student', NULL, '', 1, '2026-01-30 10:29:17', 'Community Outreach, Teaching/Training, Event Planning, Social Media, Writing/Content Creation', 'Weekdays, Flexible', 0, '', 'Skills Empowerment, Advocacy &amp; Rights, Inclusive Education, Inclusive Health', 1, 1, 'active', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(323, NULL, 'SHUAIB', 'User', 'SHUAIB', NULL, 'p3hub5@gmail.com', 0, '$2y$10$cXmHbVThtdcCQBOMPaq8leQ1C/BwC/Im1MjDXTcNNykkhnMtSmHEa', NULL, NULL, NULL, NULL, NULL, 'default.png', 'instructor', NULL, NULL, 1, '2026-02-06 18:09:36', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', '9e981f43d9afaf9e4a731bf71d991e8e5a1e7758b5628ea885959156f67091e6', NULL, NULL, '2026-02-06 19:09:38', 0, NULL, NULL, NULL),
(329, NULL, 'shuaib', 'User', 'shuaib', NULL, 'bigdaddymultimedia001@gmail.com', 1, '$2y$10$qmYS2Y0vS9mNWazpVTi0su3pN1XatjeDJtCkUg5xo15AWAvQsGqzu', '', NULL, NULL, NULL, NULL, 'avatar_329_1770463393.png', 'instructor', NULL, NULL, 1, '2026-02-07 11:22:35', NULL, NULL, 0, NULL, NULL, 0, 0, 'active', NULL, NULL, NULL, '2026-02-07 12:24:04', 0, '2026-02-07 12:22:35', '2026-02-07 12:23:52', 320);

-- --------------------------------------------------------

--
-- Table structure for table `user_badges`
--

CREATE TABLE `user_badges` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL,
  `earned_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_badges`
--

INSERT INTO `user_badges` (`id`, `user_id`, `badge_id`, `earned_at`) VALUES
(1, 320, 1, '2026-02-06 11:06:22'),
(2, 320, 4, '2026-02-06 11:06:22');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_applications`
--

CREATE TABLE `volunteer_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `opportunity_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `proof_text` text DEFAULT NULL,
  `proof_file` varchar(255) DEFAULT NULL,
  `completion_status` enum('pending','submitted','approved') NOT NULL DEFAULT 'pending',
  `application_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer_applications`
--

INSERT INTO `volunteer_applications` (`id`, `user_id`, `opportunity_id`, `status`, `proof_text`, `proof_file`, `completion_status`, `application_date`) VALUES
(4, 1, 1, 'pending', NULL, NULL, 'pending', '2025-08-05 22:04:48'),
(5, 36, 7, 'approved', NULL, NULL, 'pending', '2025-09-04 09:28:16'),
(6, 91, 9, 'approved', NULL, NULL, 'pending', '2025-09-04 15:59:10'),
(7, 122, 7, 'approved', NULL, NULL, 'pending', '2025-10-01 19:34:06'),
(8, 129, 5, 'pending', NULL, NULL, 'pending', '2025-10-01 20:49:06'),
(9, 129, 9, 'approved', NULL, NULL, 'pending', '2025-10-01 20:49:59'),
(10, 129, 7, 'approved', NULL, NULL, 'pending', '2025-10-01 20:50:35'),
(11, 131, 9, 'approved', NULL, NULL, 'pending', '2025-10-01 21:54:27'),
(12, 131, 5, 'pending', NULL, NULL, 'pending', '2025-10-01 21:55:10'),
(13, 131, 8, 'approved', NULL, NULL, 'pending', '2025-10-01 21:55:22'),
(14, 131, 7, 'approved', NULL, NULL, 'pending', '2025-10-01 21:55:33'),
(15, 131, 6, 'pending', NULL, NULL, 'pending', '2025-10-01 21:55:44'),
(16, 132, 8, 'approved', NULL, NULL, 'pending', '2025-10-01 22:42:03'),
(17, 144, 5, 'pending', NULL, NULL, 'pending', '2025-10-02 04:28:54'),
(18, 162, 9, 'approved', NULL, NULL, 'pending', '2025-10-02 10:17:27'),
(19, 163, 9, 'approved', NULL, NULL, 'pending', '2025-10-02 10:43:39'),
(20, 170, 8, 'approved', NULL, NULL, 'pending', '2025-10-02 15:28:23'),
(21, 170, 7, 'approved', NULL, NULL, 'pending', '2025-10-02 15:29:02'),
(22, 170, 6, 'pending', NULL, NULL, 'pending', '2025-10-02 15:29:14'),
(23, 170, 5, 'pending', NULL, NULL, 'pending', '2025-10-02 15:29:22'),
(24, 181, 5, 'pending', NULL, NULL, 'pending', '2025-10-02 17:44:30'),
(25, 139, 9, 'approved', NULL, NULL, 'pending', '2025-10-02 18:04:17'),
(26, 183, 7, 'approved', NULL, NULL, 'pending', '2025-10-02 18:08:31'),
(27, 185, 5, 'pending', NULL, NULL, 'pending', '2025-10-02 18:26:49'),
(28, 186, 7, 'approved', NULL, NULL, 'pending', '2025-10-02 18:55:21'),
(29, 188, 9, 'approved', NULL, NULL, 'pending', '2025-10-02 19:11:32'),
(30, 188, 7, 'approved', NULL, NULL, 'pending', '2025-10-02 19:12:03'),
(31, 191, 8, 'approved', NULL, NULL, 'pending', '2025-10-02 19:17:25'),
(32, 190, 5, 'pending', NULL, NULL, 'pending', '2025-10-02 19:36:26'),
(33, 194, 5, 'pending', NULL, NULL, 'pending', '2025-10-02 19:46:53'),
(34, 194, 9, 'approved', NULL, NULL, 'pending', '2025-10-02 19:47:23'),
(35, 196, 5, 'pending', NULL, NULL, 'pending', '2025-10-02 20:07:57'),
(36, 208, 9, 'approved', NULL, NULL, 'pending', '2025-10-02 23:51:10'),
(37, 214, 5, 'pending', NULL, NULL, 'pending', '2025-10-03 04:32:15'),
(38, 218, 9, 'approved', NULL, NULL, 'pending', '2025-10-03 05:23:37'),
(39, 167, 7, 'approved', NULL, NULL, 'pending', '2025-10-03 09:07:06'),
(40, 167, 5, 'pending', NULL, NULL, 'pending', '2025-10-03 09:08:10'),
(41, 234, 8, 'approved', NULL, NULL, 'pending', '2025-10-03 11:02:56'),
(42, 259, 5, 'pending', NULL, NULL, 'pending', '2025-10-03 19:06:22'),
(43, 263, 8, 'approved', NULL, NULL, 'pending', '2025-10-03 19:46:32'),
(44, 260, 5, 'pending', NULL, NULL, 'pending', '2025-10-03 20:50:11'),
(45, 268, 8, 'approved', NULL, NULL, 'pending', '2025-10-03 20:52:36'),
(46, 272, 8, 'approved', NULL, NULL, 'pending', '2025-10-04 00:30:28'),
(47, 274, 5, 'pending', NULL, NULL, 'pending', '2025-10-04 04:28:11'),
(48, 275, 7, 'approved', NULL, NULL, 'pending', '2025-10-04 06:28:32'),
(49, 277, 7, 'approved', NULL, NULL, 'pending', '2025-10-04 10:05:13'),
(50, 277, 9, 'approved', NULL, NULL, 'pending', '2025-10-04 10:05:43'),
(51, 90, 9, 'approved', NULL, NULL, 'pending', '2025-10-04 14:13:19'),
(52, 288, 9, 'approved', NULL, NULL, 'pending', '2025-10-05 12:44:25'),
(53, 272, 9, 'approved', NULL, NULL, 'pending', '2025-10-06 08:19:16'),
(54, 272, 7, 'approved', NULL, NULL, 'pending', '2025-10-07 07:49:11'),
(55, 58, 9, 'approved', NULL, NULL, 'pending', '2025-10-07 14:45:32'),
(56, 58, 8, 'approved', NULL, NULL, 'pending', '2025-10-07 14:46:13'),
(57, 90, 7, 'approved', NULL, NULL, 'pending', '2025-10-08 09:41:30'),
(58, 90, 8, 'approved', NULL, NULL, 'pending', '2025-10-08 09:49:07'),
(59, 36, 17, 'approved', NULL, NULL, 'pending', '2025-10-12 20:51:05'),
(60, 293, 18, 'approved', 'iodisoidfiofdos', 'proof_68ec2eb77aeda-_.jpeg', 'submitted', '2025-10-12 21:39:51'),
(61, 34, 18, 'rejected', NULL, NULL, 'pending', '2025-10-12 22:33:12'),
(62, 36, 18, 'approved', NULL, NULL, 'pending', '2025-10-13 16:11:50'),
(63, 293, 15, 'approved', NULL, NULL, 'pending', '2025-10-13 16:39:30'),
(64, 293, 16, 'approved', NULL, NULL, 'pending', '2025-10-13 16:39:54'),
(65, 293, 17, 'approved', NULL, NULL, 'pending', '2025-10-13 16:40:26'),
(66, 90, 18, 'approved', NULL, NULL, 'pending', '2025-10-14 03:19:51'),
(67, 294, 8, 'approved', NULL, NULL, 'pending', '2025-10-14 10:40:56');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_opportunities`
--

CREATE TABLE `volunteer_opportunities` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `opportunity_id` int(11) NOT NULL,
  `status` enum('applied','submitted','approved') NOT NULL DEFAULT 'applied',
  `proof_text` text DEFAULT NULL,
  `proof_file` varchar(255) DEFAULT NULL,
  `applied_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer_opportunities`
--

INSERT INTO `volunteer_opportunities` (`id`, `user_id`, `opportunity_id`, `status`, `proof_text`, `proof_file`, `applied_at`) VALUES
(1, 293, 17, 'approved', 'ldsksfkfdskafld', 'proof_68eed3a188f60-book.png', '2025-10-15 00:30:37'),
(2, 293, 16, 'approved', 'kjk', 'proof_68eedd8c073f6-_.jpeg', '2025-10-15 00:54:52'),
(3, 293, 18, 'applied', NULL, NULL, '2025-10-15 01:34:03'),
(4, 293, 15, 'approved', 'hjsdhjdhfghgshjda', 'proof_68ef6b05ebf85-_.jpeg', '2025-10-15 11:33:09'),
(5, 293, 14, 'applied', NULL, NULL, '2025-10-15 11:47:01'),
(6, 293, 13, 'approved', 'jfhkjhg', 'proof_68ef6dd301c4e-_.jpeg', '2025-10-15 11:47:53'),
(7, 271, 18, 'applied', NULL, NULL, '2025-10-16 12:46:12'),
(8, 271, 17, 'applied', NULL, NULL, '2025-10-16 12:47:07'),
(9, 271, 16, 'applied', NULL, NULL, '2025-10-16 12:47:14'),
(10, 271, 15, 'applied', NULL, NULL, '2025-10-16 12:47:20'),
(11, 271, 14, 'applied', NULL, NULL, '2025-10-16 12:47:24'),
(12, 271, 13, 'applied', NULL, NULL, '2025-10-16 12:47:28'),
(13, 271, 12, 'applied', NULL, NULL, '2025-10-16 12:47:43'),
(14, 271, 11, 'applied', NULL, NULL, '2025-10-16 12:47:47'),
(15, 271, 10, 'applied', NULL, NULL, '2025-10-16 12:47:51'),
(16, 271, 9, 'applied', NULL, NULL, '2025-10-16 12:47:59'),
(17, 271, 8, 'applied', NULL, NULL, '2025-10-16 12:48:02'),
(18, 271, 7, 'applied', NULL, NULL, '2025-10-16 12:48:05'),
(19, 271, 6, 'applied', NULL, NULL, '2025-10-16 12:48:10'),
(20, 271, 5, 'applied', NULL, NULL, '2025-10-16 12:48:12'),
(21, 296, 18, 'applied', NULL, NULL, '2025-10-16 13:19:17'),
(22, 296, 15, 'applied', NULL, NULL, '2025-10-16 13:19:37'),
(23, 299, 17, 'applied', NULL, NULL, '2025-10-16 23:15:55'),
(24, 300, 14, 'applied', NULL, NULL, '2025-10-17 07:46:05'),
(25, 251, 15, 'applied', NULL, NULL, '2025-10-17 12:41:36'),
(26, 302, 18, 'applied', NULL, NULL, '2025-10-17 19:12:02'),
(27, 159, 10, 'applied', NULL, NULL, '2025-10-17 23:46:50'),
(28, 36, 18, 'applied', NULL, NULL, '2025-10-19 09:24:51'),
(29, 286, 11, 'applied', NULL, NULL, '2025-10-20 08:52:13'),
(30, 197, 10, 'applied', NULL, NULL, '2025-10-23 18:29:40'),
(31, 311, 17, 'approved', 'Instagram follow', 'proof_68fa8d5b121ad-Screenshot_2025-10-23-21-15-56-986_com.instagram.android.jpg', '2025-10-23 22:14:55'),
(32, 311, 16, 'approved', 'Twitter follow', 'proof_68fa8e78ba78e-Screenshot_2025-10-23-21-21-20-923_com.twitter.android.jpg', '2025-10-23 22:17:49'),
(33, 311, 14, 'applied', NULL, NULL, '2025-10-23 22:23:22'),
(34, 311, 10, 'applied', NULL, NULL, '2025-10-23 22:23:38'),
(35, 76, 18, 'approved', 'LinkedIn', 'proof_68fe51cb31be4-1I9A1590.JPG', '2025-10-26 17:52:06'),
(36, 76, 17, 'approved', 'IG', 'proof_68fe51efdb846-1I9A1590.JPG', '2025-10-26 17:52:48'),
(37, 76, 16, 'approved', 'Twitter', 'proof_68fe5216d76fb-1I9A1590.JPG', '2025-10-26 17:53:22'),
(38, 76, 15, 'approved', 'SM', 'proof_68fe5236df21d-1I9A1590.JPG', '2025-10-26 17:53:59'),
(39, 76, 10, 'approved', 'Volunteer Onboarding series', 'proof_68fe55184cc5e-DADE_Certificate.pdf', '2025-10-26 17:57:29'),
(40, 76, 6, 'applied', NULL, NULL, '2025-10-26 18:23:05'),
(41, 76, 12, 'applied', NULL, NULL, '2025-10-26 18:29:25'),
(42, 36, 17, 'applied', NULL, NULL, '2025-10-27 06:05:39'),
(43, 36, 16, 'applied', NULL, NULL, '2025-10-27 06:06:16'),
(44, 90, 18, 'applied', NULL, NULL, '2025-10-27 07:47:21'),
(45, 114, 16, 'applied', NULL, NULL, '2025-10-27 08:03:08'),
(46, 114, 14, 'applied', NULL, NULL, '2025-10-27 08:03:26'),
(47, 114, 8, 'applied', NULL, NULL, '2025-10-27 08:05:22'),
(48, 90, 17, 'submitted', 'Following', 'proof_690067f7e34d6-Screenshot_20251027-182501.jpg', '2025-10-27 08:11:48'),
(49, 223, 11, 'approved', 'Who I am and my motivation for volunteering\r\n&quot;I&#039;m a dedicated and compassionate individual with a strong commitment to [mention the cause—for example, environmental conservation, social justice, or youth empowerment]. I was drawn to your organization&#039;s mission to [mention a specific mission or value] because it aligns directly with my personal values. \r\n&quot;For example, I&#039;ve been following your work on [mention a specific project or initiative] for some time now, and I&#039;ve been so inspired by the positive change you&#039;re making in the community. I want to move beyond simply admiring the work and contribute my time and energy directly to a cause that I care so deeply about.&quot; \r\nMy skills and how they would benefit your organization\r\n&quot;In my professional and personal life, I have developed several skills that I believe would be valuable to this volunteer role. I have strong communication and interpersonal skills, which I honed during [provide a brief example, e.g., my previous volunteer role at a food bank, where I interacted with people from many different backgrounds]. My ability to listen actively and connect with people helps me work effectively within a team and build strong relationships. \r\n&quot;I also have excellent organizational and time-management skills, which are essential for coordinating tasks and meeting deadlines. During [mention another example, e.g., my work managing projects], I learned how to prioritize tasks and stay focused in a dynamic environment, and I am confident I can apply this same reliability to my volunteer duties. \r\n&quot;Finally, I am a resourceful and empathetic problem-solver. I am always looking for ways to contribute proactively and take initiative. If I see an opportunity for improvement, I will propose a solution to ensure our collective goals are met.&quot; \r\nMy commitment to being an ally\r\n&quot;I am dedicated to being an active and supportive ally to marginalized and underrepresented groups. I understand that truly impactful work requires a diverse and inclusive environment where everyone feels valued and respected. \r\n&quot;For me, being an ally means more than just acknowledging differences; it&#039;s about taking concrete action. I am committed to:\r\nListening to learn: I believe that my role as an ally is to amplify the voices of those with lived experience, not to speak for them. I am committed to active listening and learning from different perspectives.\r\nFostering an inclusive environment: I will actively promote mutual respect and open communication, ensuring all volunteers and community members feel safe and included.\r\nSupporting the cause: My purpose is to use my skills to help further the organization&#039;s mission and support those it serves, rather than seeking recognition for my own contributions. \r\n&quot;I believe that by combining my skills with my genuine commitment to allyship, I can contribute positively and effectively to your organization&#039;s work.&quot;', 'proof_68ff2994087e2-Screenshot_20250816-003251_Adobe_Acrobat.jpg', '2025-10-27 09:10:26'),
(50, 293, 11, 'submitted', 'hjvhgc', 'proof_68ff714f3b2b7-NANISS_LETTER_HEADED.jpg', '2025-10-27 14:18:51'),
(51, 293, 10, 'applied', NULL, NULL, '2025-10-27 14:19:51'),
(52, 293, 7, 'submitted', 'bncvnbv', 'proof_68ffce8e975e1-Screen_Shot_2025-10-27_at_18.57.28.png', '2025-10-27 14:20:02'),
(53, 94, 17, 'applied', NULL, NULL, '2025-10-27 15:34:37'),
(54, 94, 15, 'applied', NULL, NULL, '2025-10-27 15:35:24'),
(55, 94, 12, 'applied', NULL, NULL, '2025-10-27 15:35:51'),
(56, 94, 10, 'applied', NULL, NULL, '2025-10-27 15:36:49'),
(57, 94, 7, 'applied', NULL, NULL, '2025-10-27 15:37:12'),
(58, 94, 5, 'applied', NULL, NULL, '2025-10-27 15:37:46'),
(59, 152, 18, 'applied', NULL, NULL, '2025-10-27 15:41:29'),
(60, 193, 8, 'applied', NULL, NULL, '2025-10-27 15:44:02'),
(61, 290, 18, 'applied', NULL, NULL, '2025-10-27 15:45:56'),
(62, 164, 15, 'applied', NULL, NULL, '2025-10-27 15:46:58'),
(63, 290, 17, 'applied', NULL, NULL, '2025-10-27 15:48:22'),
(64, 290, 16, 'applied', NULL, NULL, '2025-10-27 15:48:35'),
(65, 290, 15, 'applied', NULL, NULL, '2025-10-27 15:48:51'),
(66, 290, 14, 'applied', NULL, NULL, '2025-10-27 15:49:03'),
(67, 272, 18, 'approved', 'I followed DADE on LinkedIn', 'proof_68ff86ef5095c-Screenshot_20251027-155044.png', '2025-10-27 15:49:22'),
(68, 290, 10, 'applied', NULL, NULL, '2025-10-27 15:49:30'),
(69, 290, 9, 'applied', NULL, NULL, '2025-10-27 15:49:54'),
(70, 193, 18, 'applied', NULL, NULL, '2025-10-27 15:51:55'),
(71, 272, 17, 'approved', 'I followed DADE on Instagram', 'proof_68ff877ac270b-Screenshot_20251027-155309.png', '2025-10-27 15:53:21'),
(72, 272, 16, 'approved', 'I followed on X', 'proof_68ff87e6dd93b-Screenshot_20251027-155511.png', '2025-10-27 15:54:11'),
(73, 272, 15, 'approved', 'Followed on Facebook', 'proof_68ffe3dc16d13-Screenshot_20251027-222717.png', '2025-10-27 15:55:43'),
(74, 272, 14, 'applied', NULL, NULL, '2025-10-27 15:57:11'),
(75, 272, 9, 'approved', 'Referred a Friend', 'proof_68ffe371a5fdd-Screenshot_20251027-222509.png', '2025-10-27 15:57:38'),
(76, 272, 5, 'submitted', 'I attended a webinar', 'proof_68ffe308efcaa-Screenshot_20251004-185353.png', '2025-10-27 15:57:53'),
(77, 272, 10, 'approved', 'Completed a course', 'proof_68ff88eeb059a-Screenshot_20251027-155912.png', '2025-10-27 15:58:23'),
(78, 272, 11, 'approved', 'I was featured as the volunteer of the month November 2025', 'proof_69143abea1890-Screenshot_20251105-113516_1.png', '2025-10-27 16:02:59'),
(79, 120, 18, 'applied', NULL, NULL, '2025-10-27 16:03:25'),
(80, 120, 17, 'applied', NULL, NULL, '2025-10-27 16:03:41'),
(81, 120, 15, 'applied', NULL, NULL, '2025-10-27 16:03:50'),
(82, 212, 18, 'applied', NULL, NULL, '2025-10-27 16:37:49'),
(83, 180, 15, 'approved', 'I followed the foundation on Facebook.', 'proof_68ff93017570d-Screenshot_20251027_164204.jpg', '2025-10-27 16:40:17'),
(84, 212, 17, 'applied', NULL, NULL, '2025-10-27 16:42:13'),
(85, 212, 16, 'applied', NULL, NULL, '2025-10-27 16:42:31'),
(86, 212, 15, 'applied', NULL, NULL, '2025-10-27 16:42:42'),
(87, 212, 14, 'applied', NULL, NULL, '2025-10-27 16:42:53'),
(88, 212, 13, 'applied', NULL, NULL, '2025-10-27 16:43:06'),
(89, 212, 12, 'applied', NULL, NULL, '2025-10-27 16:43:19'),
(90, 212, 11, 'applied', NULL, NULL, '2025-10-27 16:43:28'),
(91, 180, 14, 'applied', NULL, NULL, '2025-10-27 16:43:31'),
(92, 212, 10, 'applied', NULL, NULL, '2025-10-27 16:43:42'),
(93, 180, 11, 'applied', NULL, NULL, '2025-10-27 16:43:43'),
(94, 212, 9, 'applied', NULL, NULL, '2025-10-27 16:43:55'),
(95, 212, 8, 'applied', NULL, NULL, '2025-10-27 16:44:09'),
(96, 212, 7, 'applied', NULL, NULL, '2025-10-27 16:44:21'),
(97, 212, 6, 'applied', NULL, NULL, '2025-10-27 16:44:34'),
(98, 212, 5, 'applied', NULL, NULL, '2025-10-27 16:44:44'),
(99, 223, 18, 'approved', 'am eager to contribute to an organization that centers the needs and voices of the communities it serves. My goal is to use my skills to support your initiatives, rather than to lead them. For instance, I have experience working within diverse teams and actively practice listening to and learning from different perspectives. I believe this collaborative approach is essential to truly create an inclusive and supportive environment.&quot;\r\nPutting it all together: A sample response\r\nYou can combine these sections into a cohesive and compelling narrative.\r\n&quot;In my professional background, I have developed strong skills in [mention 1–2 key skills, e.g., project coordination and communication]. For example, in my previous role, I was responsible for [brief, relevant example]. I am confident I can bring these skills to your team.\r\nWhat draws me to your organization specifically is [mention a specific mission or program]. Your commitment to [specific goal] aligns perfectly with my personal values. I have been inspired by your work to create [positive change] and am motivated by the prospect of contributing to that meaningful impact.\r\nIn terms of my role as an ally, I am dedicated to working collaboratively and intentionally. I understand that my role is to support the mission by uplifting the voices and experiences of the people you serve. I am committed to continuously learning how I can be the most effective supporter. I am ready to jump in and assist wherever needed to help your team achieve its goals.&quot;', 'proof_68ffa54d5a65f-Screenshot_20251027-102400_Bing.jpg', '2025-10-27 17:56:32'),
(100, 119, 7, 'applied', NULL, NULL, '2025-10-27 17:56:39'),
(101, 119, 5, 'applied', NULL, NULL, '2025-10-27 17:57:20'),
(102, 119, 10, 'applied', NULL, NULL, '2025-10-27 17:58:08'),
(103, 119, 18, 'applied', NULL, NULL, '2025-10-27 17:58:36'),
(104, 119, 17, 'applied', NULL, NULL, '2025-10-27 17:58:40'),
(105, 119, 16, 'applied', NULL, NULL, '2025-10-27 17:58:46'),
(106, 119, 15, 'applied', NULL, NULL, '2025-10-27 17:58:49'),
(107, 119, 13, 'applied', NULL, NULL, '2025-10-27 17:58:59'),
(108, 223, 17, 'approved', 'am eager to contribute to an organization that centers the needs and voices of the communities it serves. My goal is to use my skills to support your initiatives, rather than to lead them. For instance, I have experience working within diverse teams and actively practice listening to and learning from different perspectives. I believe this collaborative approach is essential to truly create an inclusive and supportive environment.&quot;\r\nPutting it all together: A sample response\r\nYou can combine these sections into a cohesive and compelling narrative.\r\n&quot;In my professional background, I have developed strong skills in [mention 1–2 key skills, e.g., project coordination and communication]. For example, in my previous role, I was responsible for [brief, relevant example]. I am confident I can bring these skills to your team.\r\nWhat draws me to your organization specifically is [mention a specific mission or program]. Your commitment to [specific goal] aligns perfectly with my personal values. I have been inspired by your work to create [positive change] and am motivated by the prospect of contributing to that meaningful impact.\r\nIn terms of my role as an ally, I am dedicated to working collaboratively and intentionally. I understand that my role is to support the mission by uplifting the voices and experiences of the people you serve. I am committed to continuously learning how I can be the most effective supporter. I am ready to jump in and assist wherever needed to help your team achieve its goals.&quot;', 'proof_68ffa5c5ac9c5-Screenshot_20251027-102400_Bing.jpg', '2025-10-27 18:01:50'),
(109, 36, 15, 'approved', 'facebook', 'proof_68ffb94783f15-Capture.PNG', '2025-10-27 18:09:27'),
(110, 223, 16, 'approved', 'Select an activity, provide a brief reflection or link, and upload proof (e.g., a screenshot, document, photo).', 'proof_68ffad3fe07ae-Screenshot_20251027-183344_Bing.jpg', '2025-10-27 18:34:11'),
(111, 223, 15, 'approved', 'Select an activity, provide a brief reflection or link, and upload proof (e.g., a screenshot, document, photo).', 'proof_68ffad72b88f9-Screenshot_20251027-183328_Bing.jpg', '2025-10-27 18:35:17'),
(112, 223, 14, 'applied', NULL, NULL, '2025-10-27 18:36:10'),
(113, 223, 13, 'submitted', 'Who you are and your skills\r\nStart by introducing yourself and highlighting relevant skills that demonstrate your readiness to contribute. Consider both your professional background and transferable &quot;soft skills&quot; that are valuable for any volunteer role. \r\nExample: &quot;I have a background in marketing and communications, which has given me strong skills in organizing projects and creating compelling content. I am also an attentive listener and an empathetic person, which I believe are vital for connecting with the community and working well within a team.&quot; \r\nCommon volunteer skills to highlight:\r\nCommunication and listening\r\nReliability and commitment\r\nTeamwork\r\nProblem-solving\r\nEmpathy and understanding\r\nFlexibility and adaptability \r\nWhy you want to volunteer (including as an ally)\r\nProvide specific, personal reasons for wanting to join this particular organization. Organizations want to see that you have done your research and are genuinely invested in their mission. As an ally, your motivation should be rooted in a genuine desire to support the community, not just a generic need to &quot;help&quot;. \r\nExample: &quot;I have been following your organization&#039;s work for some time, particularly your efforts to support [the community or cause you want to ally with]. As an ally, I believe that showing up and offering concrete support is essential. I want to volunteer to not only help you advance your mission but also to learn more, listen, and better understand the needs of the community from within. This feels like the most effective and respectful way to support a cause I care about deeply.&quot; \r\nPoints to remember as an ally:\r\nAcknowledge and uplift: Your motivation should be about the community you are supporting, not about your own heroism or desire for recognition.\r\nFocus on action, not just words: Emphasize how you want to contribute in practical ways and use your skills to support the cause.\r\nPractice humility and curiosity: Frame your involvement as an opportunity to learn and help, rather than as an attempt to lead or speak for the community. \r\nHow you will contribute\r\nConnect your skills and motivations to the specific tasks and goals of the volunteer position. Show that you are a reliable, flexible, and valuable asset to their team. \r\nExample: &quot;Based on the role description, I believe my skills in [your skill] will be a good fit. For instance, I can use my communication experience to help with your community outreach efforts. I am also a quick learner and am ready to assist wherever there is a need, whether it&#039;s an administrative task or an event. I am confident that my passion for your mission, coupled with my work ethic, will allow me to make a meaningful and reliable contribution.&quot; \r\nOverall tips for your answer\r\nBe authentic: Don&#039;t use canned phrases like &quot;I just love helping people.&quot; Share a real connection to the cause, even if it&#039;s a simple personal story.\r\nBe specific: Refer to a specific initiative or project the organization is involved in to show you&#039;ve done your research.\r\nBe concise: Keep your response focused and to the point. A concise, structured answer is more impactful than a rambling one.', 'proof_68ffb0623cae5-Screenshot_20251027-183336_Bing.jpg', '2025-10-27 18:46:27'),
(114, 223, 12, 'submitted', 'Who you are and your skills\r\nStart by introducing yourself and highlighting relevant skills that demonstrate your readiness to contribute. Consider both your professional background and transferable &quot;soft skills&quot; that are valuable for any volunteer role. \r\nExample: &quot;I have a background in marketing and communications, which has given me strong skills in organizing projects and creating compelling content. I am also an attentive listener and an empathetic person, which I believe are vital for connecting with the community and working well within a team.&quot; \r\nCommon volunteer skills to highlight:\r\nCommunication and listening\r\nReliability and commitment\r\nTeamwork\r\nProblem-solving\r\nEmpathy and understanding\r\nFlexibility and adaptability \r\nWhy you want to volunteer (including as an ally)\r\nProvide specific, personal reasons for wanting to join this particular organization. Organizations want to see that you have done your research and are genuinely invested in their mission. As an ally, your motivation should be rooted in a genuine desire to support the community, not just a generic need to &quot;help&quot;. \r\nExample: &quot;I have been following your organization&#039;s work for some time, particularly your efforts to support [the community or cause you want to ally with]. As an ally, I believe that showing up and offering concrete support is essential. I want to volunteer to not only help you advance your mission but also to learn more, listen, and better understand the needs of the community from within. This feels like the most effective and respectful way to support a cause I care about deeply.&quot; \r\nPoints to remember as an ally:\r\nAcknowledge and uplift: Your motivation should be about the community you are supporting, not about your own heroism or desire for recognition.\r\nFocus on action, not just words: Emphasize how you want to contribute in practical ways and use your skills to support the cause.\r\nPractice humility and curiosity: Frame your involvement as an opportunity to learn and help, rather than as an attempt to lead or speak for the community. \r\nHow you will contribute\r\nConnect your skills and motivations to the specific tasks and goals of the volunteer position. Show that you are a reliable, flexible, and valuable asset to their team. \r\nExample: &quot;Based on the role description, I believe my skills in [your skill] will be a good fit. For instance, I can use my communication experience to help with your community outreach efforts. I am also a quick learner and am ready to assist wherever there is a need, whether it&#039;s an administrative task or an event. I am confident that my passion for your mission, coupled with my work ethic, will allow me to make a meaningful and reliable contribution.&quot; \r\nOverall tips for your answer\r\nBe authentic: Don&#039;t use canned phrases like &quot;I just love helping people.&quot; Share a real connection to the cause, even if it&#039;s a simple personal story.\r\nBe specific: Refer to a specific initiative or project the organization is involved in to show you&#039;ve done your research.\r\nBe concise: Keep your response focused and to the point. A concise, structured answer is more impactful than a rambling one.', 'proof_68ffb0e56d1e1-Screenshot_20251027-184830_Bing.jpg', '2025-10-27 18:49:52'),
(115, 223, 10, 'submitted', 'Who you are and your skills\r\nStart by introducing yourself and highlighting relevant skills that demonstrate your readiness to contribute. Consider both your professional background and transferable &quot;soft skills&quot; that are valuable for any volunteer role. \r\nExample: &quot;I have a background in marketing and communications, which has given me strong skills in organizing projects and creating compelling content. I am also an attentive listener and an empathetic person, which I believe are vital for connecting with the community and working well within a team.&quot; \r\nCommon volunteer skills to highlight:\r\nCommunication and listening\r\nReliability and commitment\r\nTeamwork\r\nProblem-solving\r\nEmpathy and understanding\r\nFlexibility and adaptability \r\nWhy you want to volunteer (including as an ally)\r\nProvide specific, personal reasons for wanting to join this particular organization. Organizations want to see that you have done your research and are genuinely invested in their mission. As an ally, your motivation should be rooted in a genuine desire to support the community, not just a generic need to &quot;help&quot;. \r\nExample: &quot;I have been following your organization&#039;s work for some time, particularly your efforts to support [the community or cause you want to ally with]. As an ally, I believe that showing up and offering concrete support is essential. I want to volunteer to not only help you advance your mission but also to learn more, listen, and better understand the needs of the community from within. This feels like the most effective and respectful way to support a cause I care about deeply.&quot; \r\nPoints to remember as an ally:\r\nAcknowledge and uplift: Your motivation should be about the community you are supporting, not about your own heroism or desire for recognition.\r\nFocus on action, not just words: Emphasize how you want to contribute in practical ways and use your skills to support the cause.\r\nPractice humility and curiosity: Frame your involvement as an opportunity to learn and help, rather than as an attempt to lead or speak for the community. \r\nHow you will contribute\r\nConnect your skills and motivations to the specific tasks and goals of the volunteer position. Show that you are a reliable, flexible, and valuable asset to their team. \r\nExample: &quot;Based on the role description, I believe my skills in [your skill] will be a good fit. For instance, I can use my communication experience to help with your community outreach efforts. I am also a quick learner and am ready to assist wherever there is a need, whether it&#039;s an administrative task or an event. I am confident that my passion for your mission, coupled with my work ethic, will allow me to make a meaningful and reliable contribution.&quot; \r\nOverall tips for your answer\r\nBe authentic: Don&#039;t use canned phrases like &quot;I just love helping people.&quot; Share a real connection to the cause, even if it&#039;s a simple personal story.\r\nBe specific: Refer to a specific initiative or project the organization is involved in to show you&#039;ve done your research.\r\nBe concise: Keep your response focused and to the point. A concise, structured answer is more impactful than a rambling one.', 'proof_68ffb11ad1ef4-Screenshot_20251027-183328_Bing.jpg', '2025-10-27 18:50:59'),
(116, 223, 8, 'submitted', 'Who you are and your skills\r\nStart by introducing yourself and highlighting relevant skills that demonstrate your readiness to contribute. Consider both your professional background and transferable &quot;soft skills&quot; that are valuable for any volunteer role. \r\nExample: &quot;I have a background in marketing and communications, which has given me strong skills in organizing projects and creating compelling content. I am also an attentive listener and an empathetic person, which I believe are vital for connecting with the community and working well within a team.&quot; \r\nCommon volunteer skills to highlight:\r\nCommunication and listening\r\nReliability and commitment\r\nTeamwork\r\nProblem-solving\r\nEmpathy and understanding\r\nFlexibility and adaptability \r\nWhy you want to volunteer (including as an ally)\r\nProvide specific, personal reasons for wanting to join this particular organization. Organizations want to see that you have done your research and are genuinely invested in their mission. As an ally, your motivation should be rooted in a genuine desire to support the community, not just a generic need to &quot;help&quot;. \r\nExample: &quot;I have been following your organization&#039;s work for some time, particularly your efforts to support [the community or cause you want to ally with]. As an ally, I believe that showing up and offering concrete support is essential. I want to volunteer to not only help you advance your mission but also to learn more, listen, and better understand the needs of the community from within. This feels like the most effective and respectful way to support a cause I care about deeply.&quot; \r\nPoints to remember as an ally:\r\nAcknowledge and uplift: Your motivation should be about the community you are supporting, not about your own heroism or desire for recognition.\r\nFocus on action, not just words: Emphasize how you want to contribute in practical ways and use your skills to support the cause.\r\nPractice humility and curiosity: Frame your involvement as an opportunity to learn and help, rather than as an attempt to lead or speak for the community. \r\nHow you will contribute\r\nConnect your skills and motivations to the specific tasks and goals of the volunteer position. Show that you are a reliable, flexible, and valuable asset to their team. \r\nExample: &quot;Based on the role description, I believe my skills in [your skill] will be a good fit. For instance, I can use my communication experience to help with your community outreach efforts. I am also a quick learner and am ready to assist wherever there is a need, whether it&#039;s an administrative task or an event. I am confident that my passion for your mission, coupled with my work ethic, will allow me to make a meaningful and reliable contribution.&quot; \r\nOverall tips for your answer\r\nBe authentic: Don&#039;t use canned phrases like &quot;I just love helping people.&quot; Share a real connection to the cause, even if it&#039;s a simple personal story.\r\nBe specific: Refer to a specific initiative or project the organization is involved in to show you&#039;ve done your research.\r\nBe concise: Keep your response focused and to the point. A concise, structured answer is more impactful than a rambling one.', 'proof_68ffb16d3b608-Screenshot_20251027-183336_Bing.jpg', '2025-10-27 18:52:22'),
(117, 223, 7, 'submitted', 'Who you are and your skills\r\nStart by introducing yourself and highlighting relevant skills that demonstrate your readiness to contribute. Consider both your professional background and transferable &quot;soft skills&quot; that are valuable for any volunteer role. \r\nExample: &quot;I have a background in marketing and communications, which has given me strong skills in organizing projects and creating compelling content. I am also an attentive listener and an empathetic person, which I believe are vital for connecting with the community and working well within a team.&quot; \r\nCommon volunteer skills to highlight:\r\nCommunication and listening\r\nReliability and commitment\r\nTeamwork\r\nProblem-solving\r\nEmpathy and understanding\r\nFlexibility and adaptability \r\nWhy you want to volunteer (including as an ally)\r\nProvide specific, personal reasons for wanting to join this particular organization. Organizations want to see that you have done your research and are genuinely invested in their mission. As an ally, your motivation should be rooted in a genuine desire to support the community, not just a generic need to &quot;help&quot;. \r\nExample: &quot;I have been following your organization&#039;s work for some time, particularly your efforts to support [the community or cause you want to ally with]. As an ally, I believe that showing up and offering concrete support is essential. I want to volunteer to not only help you advance your mission but also to learn more, listen, and better understand the needs of the community from within. This feels like the most effective and respectful way to support a cause I care about deeply.&quot; \r\nPoints to remember as an ally:\r\nAcknowledge and uplift: Your motivation should be about the community you are supporting, not about your own heroism or desire for recognition.\r\nFocus on action, not just words: Emphasize how you want to contribute in practical ways and use your skills to support the cause.\r\nPractice humility and curiosity: Frame your involvement as an opportunity to learn and help, rather than as an attempt to lead or speak for the community. \r\nHow you will contribute\r\nConnect your skills and motivations to the specific tasks and goals of the volunteer position. Show that you are a reliable, flexible, and valuable asset to their team. \r\nExample: &quot;Based on the role description, I believe my skills in [your skill] will be a good fit. For instance, I can use my communication experience to help with your community outreach efforts. I am also a quick learner and am ready to assist wherever there is a need, whether it&#039;s an administrative task or an event. I am confident that my passion for your mission, coupled with my work ethic, will allow me to make a meaningful and reliable contribution.&quot; \r\nOverall tips for your answer\r\nBe authentic: Don&#039;t use canned phrases like &quot;I just love helping people.&quot; Share a real connection to the cause, even if it&#039;s a simple personal story.\r\nBe specific: Refer to a specific initiative or project the organization is involved in to show you&#039;ve done your research.\r\nBe concise: Keep your response focused and to the point. A concise, structured answer is more impactful than a rambling one.', 'proof_68ffb1b8c72f9-Screenshot_20251027-183328_Bing.jpg', '2025-10-27 18:53:32'),
(118, 223, 6, 'submitted', 'Who you are and your skills\r\nStart by introducing yourself and highlighting relevant skills that demonstrate your readiness to contribute. Consider both your professional background and transferable &quot;soft skills&quot; that are valuable for any volunteer role. \r\nExample: &quot;I have a background in marketing and communications, which has given me strong skills in organizing projects and creating compelling content. I am also an attentive listener and an empathetic person, which I believe are vital for connecting with the community and working well within a team.&quot; \r\nCommon volunteer skills to highlight:\r\nCommunication and listening\r\nReliability and commitment\r\nTeamwork\r\nProblem-solving\r\nEmpathy and understanding\r\nFlexibility and adaptability \r\nWhy you want to volunteer (including as an ally)\r\nProvide specific, personal reasons for wanting to join this particular organization. Organizations want to see that you have done your research and are genuinely invested in their mission. As an ally, your motivation should be rooted in a genuine desire to support the community, not just a generic need to &quot;help&quot;. \r\nExample: &quot;I have been following your organization&#039;s work for some time, particularly your efforts to support [the community or cause you want to ally with]. As an ally, I believe that showing up and offering concrete support is essential. I want to volunteer to not only help you advance your mission but also to learn more, listen, and better understand the needs of the community from within. This feels like the most effective and respectful way to support a cause I care about deeply.&quot; \r\nPoints to remember as an ally:\r\nAcknowledge and uplift: Your motivation should be about the community you are supporting, not about your own heroism or desire for recognition.\r\nFocus on action, not just words: Emphasize how you want to contribute in practical ways and use your skills to support the cause.\r\nPractice humility and curiosity: Frame your involvement as an opportunity to learn and help, rather than as an attempt to lead or speak for the community. \r\nHow you will contribute\r\nConnect your skills and motivations to the specific tasks and goals of the volunteer position. Show that you are a reliable, flexible, and valuable asset to their team. \r\nExample: &quot;Based on the role description, I believe my skills in [your skill] will be a good fit. For instance, I can use my communication experience to help with your community outreach efforts. I am also a quick learner and am ready to assist wherever there is a need, whether it&#039;s an administrative task or an event. I am confident that my passion for your mission, coupled with my work ethic, will allow me to make a meaningful and reliable contribution.&quot; \r\nOverall tips for your answer\r\nBe authentic: Don&#039;t use canned phrases like &quot;I just love helping people.&quot; Share a real connection to the cause, even if it&#039;s a simple personal story.\r\nBe specific: Refer to a specific initiative or project the organization is involved in to show you&#039;ve done your research.\r\nBe concise: Keep your response focused and to the point. A concise, structured answer is more impactful than a rambling one.', 'proof_68ffb1e602d95-Screenshot_20251027-102339_Bing.jpg', '2025-10-27 18:54:25'),
(119, 223, 5, 'approved', 'Who you are and your skills\r\nStart by introducing yourself and highlighting relevant skills that demonstrate your readiness to contribute. Consider both your professional background and transferable &quot;soft skills&quot; that are valuable for any volunteer role. \r\nExample: &quot;I have a background in marketing and communications, which has given me strong skills in organizing projects and creating compelling content. I am also an attentive listener and an empathetic person, which I believe are vital for connecting with the community and working well within a team.&quot; \r\nCommon volunteer skills to highlight:\r\nCommunication and listening\r\nReliability and commitment\r\nTeamwork\r\nProblem-solving\r\nEmpathy and understanding\r\nFlexibility and adaptability \r\nWhy you want to volunteer (including as an ally)\r\nProvide specific, personal reasons for wanting to join this particular organization. Organizations want to see that you have done your research and are genuinely invested in their mission. As an ally, your motivation should be rooted in a genuine desire to support the community, not just a generic need to &quot;help&quot;. \r\nExample: &quot;I have been following your organization&#039;s work for some time, particularly your efforts to support [the community or cause you want to ally with]. As an ally, I believe that showing up and offering concrete support is essential. I want to volunteer to not only help you advance your mission but also to learn more, listen, and better understand the needs of the community from within. This feels like the most effective and respectful way to support a cause I care about deeply.&quot; \r\nPoints to remember as an ally:\r\nAcknowledge and uplift: Your motivation should be about the community you are supporting, not about your own heroism or desire for recognition.\r\nFocus on action, not just words: Emphasize how you want to contribute in practical ways and use your skills to support the cause.\r\nPractice humility and curiosity: Frame your involvement as an opportunity to learn and help, rather than as an attempt to lead or speak for the community. \r\nHow you will contribute\r\nConnect your skills and motivations to the specific tasks and goals of the volunteer position. Show that you are a reliable, flexible, and valuable asset to their team. \r\nExample: &quot;Based on the role description, I believe my skills in [your skill] will be a good fit. For instance, I can use my communication experience to help with your community outreach efforts. I am also a quick learner and am ready to assist wherever there is a need, whether it&#039;s an administrative task or an event. I am confident that my passion for your mission, coupled with my work ethic, will allow me to make a meaningful and reliable contribution.&quot; \r\nOverall tips for your answer\r\nBe authentic: Don&#039;t use canned phrases like &quot;I just love helping people.&quot; Share a real connection to the cause, even if it&#039;s a simple personal story.\r\nBe specific: Refer to a specific initiative or project the organization is involved in to show you&#039;ve done your research.\r\nBe concise: Keep your response focused and to the point. A concise, structured answer is more impactful than a rambling one.', 'proof_68ffb24195500-Screenshot_20251027-183328_Bing.jpg', '2025-10-27 18:55:53'),
(120, 223, 9, 'submitted', 'Who you are and your skills\r\nStart by introducing yourself and highlighting relevant skills that demonstrate your readiness to contribute. Consider both your professional background and transferable &quot;soft skills&quot; that are valuable for any volunteer role. \r\nExample: &quot;I have a background in marketing and communications, which has given me strong skills in organizing projects and creating compelling content. I am also an attentive listener and an empathetic person, which I believe are vital for connecting with the community and working well within a team.&quot; \r\nCommon volunteer skills to highlight:\r\nCommunication and listening\r\nReliability and commitment\r\nTeamwork\r\nProblem-solving\r\nEmpathy and understanding\r\nFlexibility and adaptability \r\nWhy you want to volunteer (including as an ally)\r\nProvide specific, personal reasons for wanting to join this particular organization. Organizations want to see that you have done your research and are genuinely invested in their mission. As an ally, your motivation should be rooted in a genuine desire to support the community, not just a generic need to &quot;help&quot;. \r\nExample: &quot;I have been following your organization&#039;s work for some time, particularly your efforts to support [the community or cause you want to ally with]. As an ally, I believe that showing up and offering concrete support is essential. I want to volunteer to not only help you advance your mission but also to learn more, listen, and better understand the needs of the community from within. This feels like the most effective and respectful way to support a cause I care about deeply.&quot; \r\nPoints to remember as an ally:\r\nAcknowledge and uplift: Your motivation should be about the community you are supporting, not about your own heroism or desire for recognition.\r\nFocus on action, not just words: Emphasize how you want to contribute in practical ways and use your skills to support the cause.\r\nPractice humility and curiosity: Frame your involvement as an opportunity to learn and help, rather than as an attempt to lead or speak for the community. \r\nHow you will contribute\r\nConnect your skills and motivations to the specific tasks and goals of the volunteer position. Show that you are a reliable, flexible, and valuable asset to their team. \r\nExample: &quot;Based on the role description, I believe my skills in [your skill] will be a good fit. For instance, I can use my communication experience to help with your community outreach efforts. I am also a quick learner and am ready to assist wherever there is a need, whether it&#039;s an administrative task or an event. I am confident that my passion for your mission, coupled with my work ethic, will allow me to make a meaningful and reliable contribution.&quot; \r\nOverall tips for your answer\r\nBe authentic: Don&#039;t use canned phrases like &quot;I just love helping people.&quot; Share a real connection to the cause, even if it&#039;s a simple personal story.\r\nBe specific: Refer to a specific initiative or project the organization is involved in to show you&#039;ve done your research.\r\nBe concise: Keep your response focused and to the point. A concise, structured answer is more impactful than a rambling one.', 'proof_68ffb33bf2d79-Screenshot_20251027-183344_Bing.jpg', '2025-10-27 18:59:55'),
(121, 36, 14, 'applied', NULL, NULL, '2025-10-27 19:41:42'),
(122, 131, 18, 'approved', 'My username for verification: Comrade_Yakz', 'proof_68ffbe14a5790-Screenshot_20251027-194507.png', '2025-10-27 19:44:04'),
(123, 314, 18, 'applied', NULL, NULL, '2025-10-27 19:51:21'),
(124, 314, 17, 'applied', NULL, NULL, '2025-10-27 19:52:25'),
(125, 314, 16, 'applied', NULL, NULL, '2025-10-27 19:53:28'),
(126, 314, 15, 'applied', NULL, NULL, '2025-10-27 19:53:42'),
(127, 314, 14, 'applied', NULL, NULL, '2025-10-27 19:53:54'),
(128, 314, 13, 'applied', NULL, NULL, '2025-10-27 19:54:02'),
(129, 314, 12, 'applied', NULL, NULL, '2025-10-27 19:54:13'),
(130, 314, 11, 'applied', NULL, NULL, '2025-10-27 19:54:28'),
(131, 314, 10, 'applied', NULL, NULL, '2025-10-27 19:54:41'),
(132, 314, 9, 'applied', NULL, NULL, '2025-10-27 19:54:54'),
(133, 314, 8, 'applied', NULL, NULL, '2025-10-27 19:55:08'),
(134, 314, 7, 'applied', NULL, NULL, '2025-10-27 19:55:16'),
(135, 314, 6, 'applied', NULL, NULL, '2025-10-27 19:55:29'),
(136, 314, 5, 'applied', NULL, NULL, '2025-10-27 19:55:56'),
(137, 167, 15, 'applied', NULL, NULL, '2025-10-27 20:24:10'),
(138, 167, 17, 'applied', NULL, NULL, '2025-10-27 20:25:16'),
(139, 167, 18, 'applied', NULL, NULL, '2025-10-27 20:25:57'),
(140, 167, 11, 'applied', NULL, NULL, '2025-10-27 20:27:14'),
(141, 301, 17, 'approved', 'I followed Dade foundation on Instagram', 'proof_68ffcc0eda4c4-Screenshot_20251027-204512.png', '2025-10-27 20:44:23'),
(142, 301, 18, 'approved', 'I followed Dade foundation on LinkedIn', 'proof_69007e962bfd8-Screenshot_20251027-204838.png', '2025-10-27 20:48:09'),
(143, 301, 15, 'approved', 'I followed Dade foundation on Facebook', 'proof_69007ec78d3ee-Screenshot_20251027-204512.png', '2025-10-27 20:49:06'),
(144, 293, 12, 'submitted', 'efdfd', 'proof_68ffce6c4bee2-Screen_Shot_2025-10-27_at_18.57.28.png', '2025-10-27 20:56:10'),
(145, 272, 7, 'approved', 'I made a post on my X account on why inclusion is important', 'proof_68ffe286b46cc-Screenshot_20251027-222036.png', '2025-10-27 22:21:07'),
(146, 218, 15, 'applied', NULL, NULL, '2025-10-27 22:26:45'),
(147, 218, 17, 'applied', NULL, NULL, '2025-10-27 22:28:33'),
(148, 218, 16, 'applied', NULL, NULL, '2025-10-27 22:29:16'),
(149, 301, 16, 'approved', 'I followed Dade foundation on Twitter', 'proof_690081043f2d1-Screenshot_20251028-093627.png', '2025-10-28 09:26:46'),
(150, 301, 14, 'applied', NULL, NULL, '2025-10-28 09:39:40'),
(151, 166, 10, 'approved', 'Great and educative', 'proof_6900964401efb-Screenshot_20251028-110639.jpg', '2025-10-28 11:06:17'),
(152, 289, 18, 'approved', 'i followed the linkedin page of DADE foundation', 'proof_6901602831a48-Screenshot_2025-10-29_1.29.27_AM.png', '2025-10-29 01:27:36'),
(153, 289, 10, 'applied', NULL, NULL, '2025-10-29 01:31:40'),
(154, 297, 18, 'applied', NULL, NULL, '2025-10-29 07:30:02'),
(155, 218, 18, 'applied', NULL, NULL, '2025-10-29 07:47:59'),
(156, 243, 18, 'applied', NULL, NULL, '2025-10-29 12:18:11'),
(157, 284, 9, 'submitted', 'I don&#039;t want my parent thinks about me I will talk to my parent I want to find to d work I check it when I was a kid then I am grow up I&#039;m still practicing for drawing, crochet knitting and design canvas my parent make proud of me I&#039;m a big adult I say thank God I appreciate it I&#039;m not lazy I&#039;m strong 🙏🙏 I said thank you so much', 'proof_690219b5c7eda-17617452685986254320456428701756.jpg', '2025-10-29 14:36:29'),
(158, 293, 9, 'applied', NULL, NULL, '2025-10-30 20:17:16'),
(159, 301, 11, 'applied', NULL, NULL, '2025-10-31 20:08:59'),
(160, 301, 10, 'approved', 'I completed a course', 'proof_691c7762c2f7f-Screenshot_20251118-143940.png', '2025-10-31 20:10:37'),
(161, 266, 16, 'applied', NULL, NULL, '2025-11-01 00:40:01'),
(162, 320, 18, 'applied', NULL, NULL, '2026-01-27 15:45:09'),
(163, 320, 17, 'applied', NULL, NULL, '2026-01-27 16:10:22'),
(164, 320, 16, 'applied', NULL, NULL, '2026-01-27 17:17:52');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_points`
--

CREATE TABLE `volunteer_points` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `points_earned` int(11) NOT NULL,
  `submission_text` text DEFAULT NULL,
  `submission_file` varchar(255) DEFAULT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_by_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer_points`
--

INSERT INTO `volunteer_points` (`id`, `user_id`, `activity_id`, `points_earned`, `submission_text`, `submission_file`, `submission_date`, `approved_by_admin`) VALUES
(7, 10, 1, 10, NULL, NULL, '2025-08-07 18:44:26', 1),
(8, 25, 10, 10, 'njhhjv', '6895f19e7bc3b-dangote-logo-png_seeklogo-314430.png', '2025-08-08 11:46:22', 0),
(9, 28, 1, 10, NULL, NULL, '2025-08-08 12:53:03', 1),
(10, 29, 1, 10, NULL, NULL, '2025-08-08 13:55:35', 1),
(11, 41, 1, 10, NULL, NULL, '2025-08-13 12:41:29', 1),
(12, 39, 1, 10, NULL, NULL, '2025-08-13 12:53:41', 1),
(13, 42, 1, 10, NULL, NULL, '2025-08-13 13:56:14', 1),
(14, 56, 1, 10, NULL, NULL, '2025-08-18 05:30:49', 1),
(15, 57, 1, 10, NULL, NULL, '2025-08-21 08:03:40', 1),
(16, 58, 1, 10, NULL, NULL, '2025-08-21 10:36:21', 1),
(17, 67, 1, 10, NULL, NULL, '2025-08-24 18:30:01', 1),
(18, 36, 1, 10, NULL, NULL, '2025-08-24 22:32:47', 1),
(19, 68, 1, 10, NULL, NULL, '2025-08-26 14:23:39', 1),
(20, 69, 1, 10, NULL, NULL, '2025-08-26 15:15:27', 1),
(21, 34, 1, 10, NULL, NULL, '2025-08-26 16:52:18', 1),
(22, 71, 1, 10, NULL, NULL, '2025-08-26 17:05:50', 1),
(23, 72, 1, 10, NULL, NULL, '2025-08-26 17:09:02', 1),
(24, 73, 1, 10, NULL, NULL, '2025-08-26 17:20:12', 1),
(25, 74, 1, 10, NULL, NULL, '2025-08-27 15:24:12', 1),
(26, 76, 1, 10, NULL, NULL, '2025-08-28 14:01:47', 1),
(27, 77, 1, 10, NULL, NULL, '2025-08-30 07:33:04', 1),
(28, 75, 1, 10, NULL, NULL, '2025-08-30 12:23:09', 1),
(29, 78, 1, 10, NULL, NULL, '2025-08-30 13:43:00', 1),
(30, 79, 1, 10, NULL, NULL, '2025-08-30 13:47:54', 1),
(31, 80, 1, 10, NULL, NULL, '2025-08-30 14:01:21', 1),
(32, 81, 1, 10, NULL, NULL, '2025-08-30 14:33:10', 1),
(33, 82, 1, 10, NULL, NULL, '2025-08-30 14:50:42', 1),
(34, 86, 1, 10, NULL, NULL, '2025-08-30 15:48:19', 1),
(35, 87, 1, 10, NULL, NULL, '2025-08-30 15:54:41', 1),
(36, 88, 1, 10, NULL, NULL, '2025-08-30 15:56:40', 1),
(37, 89, 1, 10, NULL, NULL, '2025-08-30 16:07:58', 1),
(38, 36, 1, 5, NULL, NULL, '2025-09-04 09:43:19', 1),
(39, 91, 1, 10, NULL, NULL, '2025-09-04 15:29:18', 1),
(40, 93, 1, 10, NULL, NULL, '2025-09-05 07:36:09', 1),
(41, 94, 1, 10, NULL, NULL, '2025-09-06 13:20:21', 1),
(42, 95, 1, 10, NULL, NULL, '2025-09-09 12:53:32', 1),
(43, 96, 1, 10, NULL, NULL, '2025-09-10 13:08:32', 1),
(44, 97, 1, 10, NULL, NULL, '2025-09-10 13:48:18', 1),
(45, 98, 1, 10, NULL, NULL, '2025-09-10 13:55:51', 1),
(46, 99, 1, 10, NULL, NULL, '2025-09-17 21:03:40', 1),
(47, 100, 1, 10, NULL, NULL, '2025-09-18 06:31:33', 1),
(48, 101, 1, 10, NULL, NULL, '2025-09-18 09:51:01', 1),
(49, 91, 1, 5, NULL, NULL, '2025-09-18 13:31:38', 1),
(50, 103, 1, 10, NULL, NULL, '2025-09-22 18:35:55', 1),
(51, 104, 1, 10, NULL, NULL, '2025-09-24 07:02:58', 1),
(52, 105, 1, 10, NULL, NULL, '2025-09-24 13:55:01', 1),
(53, 106, 1, 10, NULL, NULL, '2025-09-24 16:07:38', 1),
(54, 108, 1, 10, NULL, NULL, '2025-09-29 22:12:44', 1),
(55, 110, 1, 10, NULL, NULL, '2025-09-30 15:22:46', 1),
(56, 111, 1, 10, NULL, NULL, '2025-10-01 02:26:43', 1),
(57, 112, 1, 10, NULL, NULL, '2025-10-01 03:13:09', 1),
(58, 113, 1, 10, NULL, NULL, '2025-10-01 07:39:34', 1),
(59, 114, 1, 10, NULL, NULL, '2025-10-01 08:27:19', 1),
(60, 115, 1, 10, NULL, NULL, '2025-10-01 09:25:01', 1),
(61, 118, 1, 10, NULL, NULL, '2025-10-01 09:42:13', 1),
(62, 119, 1, 10, NULL, NULL, '2025-10-01 10:02:34', 1),
(63, 116, 1, 10, NULL, NULL, '2025-10-01 11:16:31', 1),
(64, 116, 11, 25, 'Flayout Niger State minna', '68dd1cea0c14a-8819ae2ddd0c42f2a86d24650eecda29.jpg', '2025-10-01 11:22:02', 0),
(65, 121, 1, 10, NULL, NULL, '2025-10-01 19:07:54', 1),
(66, 122, 1, 10, NULL, NULL, '2025-10-01 19:26:32', 1),
(67, 123, 1, 10, NULL, NULL, '2025-10-01 19:28:27', 1),
(68, 125, 1, 10, NULL, NULL, '2025-10-01 19:37:40', 1),
(69, 126, 1, 10, NULL, NULL, '2025-10-01 19:41:19', 1),
(70, 127, 1, 10, NULL, NULL, '2025-10-01 20:10:31', 1),
(71, 128, 1, 10, NULL, NULL, '2025-10-01 20:32:21', 1),
(72, 129, 1, 10, NULL, NULL, '2025-10-01 20:46:49', 1),
(73, 131, 1, 10, NULL, NULL, '2025-10-01 21:52:26', 1),
(74, 130, 1, 10, NULL, NULL, '2025-10-01 21:54:16', 1),
(75, 132, 1, 10, NULL, NULL, '2025-10-01 22:39:29', 1),
(76, 133, 1, 10, NULL, NULL, '2025-10-01 23:36:36', 1),
(77, 134, 1, 10, NULL, NULL, '2025-10-01 23:54:02', 1),
(78, 135, 1, 10, NULL, NULL, '2025-10-01 23:58:12', 1),
(79, 137, 1, 10, NULL, NULL, '2025-10-02 00:51:55', 1),
(80, 138, 1, 10, NULL, NULL, '2025-10-02 02:35:23', 1),
(81, 139, 1, 10, NULL, NULL, '2025-10-02 03:28:54', 1),
(82, 140, 1, 10, NULL, NULL, '2025-10-02 03:34:12', 1),
(83, 141, 1, 10, NULL, NULL, '2025-10-02 03:42:34', 1),
(84, 143, 1, 10, NULL, NULL, '2025-10-02 04:03:28', 1),
(85, 142, 1, 10, NULL, NULL, '2025-10-02 04:05:34', 1),
(86, 142, 6, 20, '', '68de094a96cd3-IMG_4166.jpeg', '2025-10-02 04:10:34', 0),
(87, 144, 1, 10, NULL, NULL, '2025-10-02 04:26:41', 1),
(88, 146, 1, 10, NULL, NULL, '2025-10-02 04:26:50', 1),
(89, 145, 1, 10, NULL, NULL, '2025-10-02 04:28:39', 1),
(90, 147, 1, 10, NULL, NULL, '2025-10-02 04:37:03', 1),
(91, 148, 1, 10, NULL, NULL, '2025-10-02 04:50:19', 1),
(92, 149, 1, 10, NULL, NULL, '2025-10-02 04:57:40', 1),
(93, 150, 1, 10, NULL, NULL, '2025-10-02 05:06:47', 1),
(94, 151, 1, 10, NULL, NULL, '2025-10-02 05:30:20', 1),
(95, 152, 1, 10, NULL, NULL, '2025-10-02 06:24:39', 1),
(96, 155, 1, 10, NULL, NULL, '2025-10-02 06:25:02', 1),
(97, 156, 1, 10, NULL, NULL, '2025-10-02 06:30:03', 1),
(98, 154, 1, 10, NULL, NULL, '2025-10-02 06:44:06', 1),
(99, 157, 1, 10, NULL, NULL, '2025-10-02 07:24:36', 1),
(100, 158, 1, 10, NULL, NULL, '2025-10-02 07:33:56', 1),
(101, 159, 1, 10, NULL, NULL, '2025-10-02 07:58:38', 1),
(102, 160, 1, 10, NULL, NULL, '2025-10-02 08:22:18', 1),
(103, 162, 1, 10, NULL, NULL, '2025-10-02 10:14:21', 1),
(104, 163, 1, 10, NULL, NULL, '2025-10-02 10:41:49', 1),
(105, 164, 1, 10, NULL, NULL, '2025-10-02 10:49:31', 1),
(106, 165, 1, 10, NULL, NULL, '2025-10-02 12:26:02', 1),
(107, 166, 1, 10, NULL, NULL, '2025-10-02 13:10:24', 1),
(108, 167, 1, 10, NULL, NULL, '2025-10-02 13:32:23', 1),
(109, 168, 1, 10, NULL, NULL, '2025-10-02 14:07:27', 1),
(110, 169, 1, 10, NULL, NULL, '2025-10-02 15:11:12', 1),
(111, 170, 1, 10, NULL, NULL, '2025-10-02 15:27:00', 1),
(112, 171, 1, 10, NULL, NULL, '2025-10-02 15:38:49', 1),
(113, 172, 1, 10, NULL, NULL, '2025-10-02 16:02:49', 1),
(114, 173, 1, 10, NULL, NULL, '2025-10-02 16:27:50', 1),
(115, 175, 1, 10, NULL, NULL, '2025-10-02 16:42:15', 1),
(116, 176, 1, 10, NULL, NULL, '2025-10-02 16:57:27', 1),
(117, 177, 1, 10, NULL, NULL, '2025-10-02 17:13:05', 1),
(118, 179, 1, 10, NULL, NULL, '2025-10-02 17:22:46', 1),
(119, 180, 1, 10, NULL, NULL, '2025-10-02 17:26:42', 1),
(120, 178, 1, 10, NULL, NULL, '2025-10-02 17:38:41', 1),
(121, 181, 1, 10, NULL, NULL, '2025-10-02 17:43:00', 1),
(122, 182, 1, 10, NULL, NULL, '2025-10-02 17:54:07', 1),
(123, 183, 1, 10, NULL, NULL, '2025-10-02 18:04:01', 1),
(124, 184, 1, 10, NULL, NULL, '2025-10-02 18:10:11', 1),
(125, 185, 1, 10, NULL, NULL, '2025-10-02 18:25:03', 1),
(126, 186, 1, 10, NULL, NULL, '2025-10-02 18:50:03', 1),
(127, 187, 1, 10, NULL, NULL, '2025-10-02 19:05:13', 1),
(128, 188, 1, 10, NULL, NULL, '2025-10-02 19:09:35', 1),
(129, 191, 1, 10, NULL, NULL, '2025-10-02 19:15:31', 1),
(130, 190, 1, 10, NULL, NULL, '2025-10-02 19:15:33', 1),
(131, 192, 1, 10, NULL, NULL, '2025-10-02 19:22:59', 1),
(132, 193, 1, 10, NULL, NULL, '2025-10-02 19:38:21', 1),
(133, 195, 1, 10, NULL, NULL, '2025-10-02 19:41:39', 1),
(134, 194, 1, 10, NULL, NULL, '2025-10-02 19:44:18', 1),
(135, 196, 1, 10, NULL, NULL, '2025-10-02 19:56:58', 1),
(136, 197, 1, 10, NULL, NULL, '2025-10-02 20:02:33', 1),
(137, 198, 1, 10, NULL, NULL, '2025-10-02 20:27:24', 1),
(138, 200, 1, 10, NULL, NULL, '2025-10-02 20:46:20', 1),
(139, 203, 1, 10, NULL, NULL, '2025-10-02 21:31:40', 1),
(140, 205, 1, 10, NULL, NULL, '2025-10-02 22:33:12', 1),
(141, 207, 1, 10, NULL, NULL, '2025-10-02 23:41:20', 1),
(142, 208, 1, 10, NULL, NULL, '2025-10-02 23:48:43', 1),
(143, 209, 1, 10, NULL, NULL, '2025-10-03 00:27:07', 1),
(144, 211, 1, 10, NULL, NULL, '2025-10-03 01:59:57', 1),
(145, 212, 1, 10, NULL, NULL, '2025-10-03 03:43:37', 1),
(146, 213, 1, 10, NULL, NULL, '2025-10-03 03:49:39', 1),
(147, 214, 1, 10, NULL, NULL, '2025-10-03 04:10:12', 1),
(148, 215, 1, 10, NULL, NULL, '2025-10-03 04:34:20', 1),
(149, 217, 1, 10, NULL, NULL, '2025-10-03 04:35:37', 1),
(150, 216, 1, 10, NULL, NULL, '2025-10-03 04:39:19', 1),
(151, 219, 1, 10, NULL, NULL, '2025-10-03 05:15:25', 1),
(152, 218, 1, 10, NULL, NULL, '2025-10-03 05:21:48', 1),
(153, 220, 1, 10, NULL, NULL, '2025-10-03 05:41:57', 1),
(154, 221, 1, 10, NULL, NULL, '2025-10-03 06:05:17', 1),
(155, 222, 1, 10, NULL, NULL, '2025-10-03 06:45:09', 1),
(156, 218, 1, 5, NULL, NULL, '2025-10-03 06:45:29', 0),
(157, 208, 1, 5, NULL, NULL, '2025-10-03 06:45:37', 0),
(158, 194, 1, 5, NULL, NULL, '2025-10-03 06:45:43', 0),
(159, 188, 1, 5, NULL, NULL, '2025-10-03 06:45:52', 0),
(160, 163, 1, 5, NULL, NULL, '2025-10-03 06:46:01', 0),
(161, 139, 1, 5, NULL, NULL, '2025-10-03 06:46:19', 0),
(162, 162, 1, 5, NULL, NULL, '2025-10-03 06:46:29', 0),
(163, 131, 1, 5, NULL, NULL, '2025-10-03 06:46:39', 0),
(164, 129, 1, 5, NULL, NULL, '2025-10-03 06:46:46', 0),
(165, 223, 1, 10, NULL, NULL, '2025-10-03 07:19:17', 1),
(166, 223, 11, 25, 'Sorting donations made me realize the high demand for food in my community and how crucial such efforts are for families in need. I also developed better organization skills.&quot;', '68df894d586b9-inbound9139709576950687569.pdf', '2025-10-03 07:29:01', 0),
(167, 199, 1, 10, NULL, NULL, '2025-10-03 07:40:22', 1),
(168, 224, 1, 10, NULL, NULL, '2025-10-03 07:44:49', 1),
(169, 225, 1, 10, NULL, NULL, '2025-10-03 08:01:17', 1),
(170, 226, 1, 10, NULL, NULL, '2025-10-03 08:15:13', 1),
(171, 227, 1, 10, NULL, NULL, '2025-10-03 08:45:16', 1),
(172, 228, 1, 10, NULL, NULL, '2025-10-03 08:51:17', 1),
(173, 174, 1, 10, NULL, NULL, '2025-10-03 09:07:46', 1),
(174, 229, 1, 10, NULL, NULL, '2025-10-03 09:38:48', 1),
(175, 230, 1, 10, NULL, NULL, '2025-10-03 09:54:19', 1),
(176, 231, 1, 10, NULL, NULL, '2025-10-03 10:52:44', 1),
(177, 234, 1, 10, NULL, NULL, '2025-10-03 11:01:12', 1),
(178, 233, 1, 10, NULL, NULL, '2025-10-03 11:24:39', 1),
(179, 236, 1, 10, NULL, NULL, '2025-10-03 12:21:27', 1),
(180, 237, 1, 10, NULL, NULL, '2025-10-03 12:38:01', 1),
(181, 238, 1, 10, NULL, NULL, '2025-10-03 12:48:59', 1),
(182, 239, 1, 10, NULL, NULL, '2025-10-03 13:23:23', 1),
(183, 201, 1, 10, NULL, NULL, '2025-10-03 14:01:02', 1),
(184, 241, 1, 10, NULL, NULL, '2025-10-03 15:21:09', 1),
(185, 243, 1, 10, NULL, NULL, '2025-10-03 15:51:13', 1),
(186, 235, 1, 10, NULL, NULL, '2025-10-03 15:54:11', 1),
(187, 244, 1, 10, NULL, NULL, '2025-10-03 15:59:34', 1),
(188, 245, 1, 10, NULL, NULL, '2025-10-03 16:10:45', 1),
(189, 247, 1, 10, NULL, NULL, '2025-10-03 16:39:51', 1),
(190, 248, 1, 10, NULL, NULL, '2025-10-03 17:40:06', 1),
(191, 249, 1, 10, NULL, NULL, '2025-10-03 17:55:08', 1),
(192, 250, 1, 10, NULL, NULL, '2025-10-03 18:12:49', 1),
(193, 252, 1, 10, NULL, NULL, '2025-10-03 18:29:22', 1),
(194, 251, 1, 10, NULL, NULL, '2025-10-03 18:39:47', 1),
(195, 253, 1, 10, NULL, NULL, '2025-10-03 18:41:11', 1),
(196, 255, 1, 10, NULL, NULL, '2025-10-03 18:44:26', 1),
(197, 256, 1, 10, NULL, NULL, '2025-10-03 18:47:06', 1),
(198, 257, 1, 10, NULL, NULL, '2025-10-03 18:47:44', 1),
(199, 259, 1, 10, NULL, NULL, '2025-10-03 19:05:10', 1),
(200, 258, 1, 10, NULL, NULL, '2025-10-03 19:06:20', 1),
(201, 260, 1, 10, NULL, NULL, '2025-10-03 19:13:07', 1),
(202, 261, 1, 10, NULL, NULL, '2025-10-03 19:14:50', 1),
(203, 262, 1, 10, NULL, NULL, '2025-10-03 19:43:58', 1),
(204, 263, 1, 10, NULL, NULL, '2025-10-03 19:45:15', 1),
(205, 267, 1, 10, NULL, NULL, '2025-10-03 20:43:09', 1),
(206, 268, 1, 10, NULL, NULL, '2025-10-03 20:50:00', 1),
(207, 270, 1, 10, NULL, NULL, '2025-10-03 21:30:24', 1),
(208, 271, 1, 10, NULL, NULL, '2025-10-03 22:58:41', 1),
(209, 242, 1, 10, NULL, NULL, '2025-10-03 23:44:01', 1),
(210, 272, 1, 10, NULL, NULL, '2025-10-04 00:28:03', 1),
(211, 273, 1, 10, NULL, NULL, '2025-10-04 01:48:11', 1),
(212, 274, 1, 10, NULL, NULL, '2025-10-04 04:25:40', 1),
(213, 275, 1, 10, NULL, NULL, '2025-10-04 06:25:36', 1),
(214, 277, 1, 10, NULL, NULL, '2025-10-04 10:02:03', 1),
(215, 90, 1, 10, NULL, NULL, '2025-10-04 14:12:07', 1),
(216, 278, 1, 10, NULL, NULL, '2025-10-04 14:33:56', 1),
(217, 279, 1, 10, NULL, NULL, '2025-10-04 16:25:43', 1),
(218, 280, 1, 10, NULL, NULL, '2025-10-04 17:01:32', 1),
(219, 281, 1, 10, NULL, NULL, '2025-10-04 17:05:47', 1),
(220, 155, 15, 5, 'https://www.facebook.com/share/17TRCiTZPZ/', '68e164147eb99-Screenshot_20251004-191402.png', '2025-10-04 17:14:44', 1),
(221, 155, 15, 5, 'https://www.facebook.com/share/17TRCiTZPZ/', '68e1642bed2c1-Screenshot_20251004-191402.png', '2025-10-04 17:15:07', 1),
(222, 283, 1, 10, NULL, NULL, '2025-10-04 17:51:10', 1),
(223, 284, 1, 10, NULL, NULL, '2025-10-04 18:59:02', 1),
(224, 285, 1, 10, NULL, NULL, '2025-10-04 19:43:26', 1),
(225, 285, 11, 25, 'I searved as a project lead for the Teachers Hub project at MAYEIN NGO', '68e188873b04d-IMG_2017.jpeg', '2025-10-04 19:50:15', 0),
(226, 285, 11, 25, 'I searved as a project lead for the Teachers Hub project at MAYEIN NGO', '68e18895a787a-IMG_2017.jpeg', '2025-10-04 19:50:29', 0),
(227, 286, 1, 10, NULL, NULL, '2025-10-04 23:09:37', 1),
(228, 288, 1, 10, NULL, NULL, '2025-10-05 12:42:09', 1),
(229, 206, 1, 10, NULL, NULL, '2025-10-05 21:27:36', 1),
(230, 289, 1, 10, NULL, NULL, '2025-10-06 04:04:07', 1),
(231, 210, 1, 10, NULL, NULL, '2025-10-06 10:05:21', 1),
(232, 232, 1, 10, NULL, NULL, '2025-10-07 07:01:26', 1),
(233, 58, 1, 5, NULL, NULL, '2025-10-08 09:30:02', 0),
(234, 58, 1, 20, NULL, NULL, '2025-10-08 09:30:06', 0),
(235, 272, 1, 5, NULL, NULL, '2025-10-08 09:30:18', 0),
(236, 272, 1, 5, NULL, NULL, '2025-10-08 09:30:23', 0),
(237, 288, 1, 5, NULL, NULL, '2025-10-08 09:30:27', 0),
(238, 90, 1, 5, NULL, NULL, '2025-10-08 09:30:30', 1),
(239, 277, 1, 5, NULL, NULL, '2025-10-08 09:30:33', 0),
(240, 277, 1, 5, NULL, NULL, '2025-10-08 09:30:37', 0),
(241, 275, 1, 5, NULL, NULL, '2025-10-08 09:30:40', 1),
(242, 234, 1, 20, NULL, NULL, '2025-10-08 09:31:37', 0),
(243, 272, 1, 20, NULL, NULL, '2025-10-08 09:31:42', 0),
(244, 268, 1, 20, NULL, NULL, '2025-10-08 09:31:45', 0),
(245, 263, 1, 20, NULL, NULL, '2025-10-08 09:31:48', 0),
(246, 167, 1, 5, NULL, NULL, '2025-10-08 09:31:53', 0),
(247, 191, 1, 20, NULL, NULL, '2025-10-08 09:32:02', 0),
(248, 188, 1, 5, NULL, NULL, '2025-10-08 09:32:09', 0),
(249, 186, 1, 5, NULL, NULL, '2025-10-08 09:32:15', 0),
(250, 170, 1, 5, NULL, NULL, '2025-10-08 09:32:24', 0),
(251, 131, 1, 5, NULL, NULL, '2025-10-08 09:32:31', 0),
(252, 122, 1, 5, NULL, NULL, '2025-10-08 09:32:37', 0),
(253, 183, 1, 5, NULL, NULL, '2025-10-08 09:32:49', 0),
(254, 90, 1, 5, NULL, NULL, '2025-10-08 09:42:18', 1),
(255, 90, 1, 20, NULL, NULL, '2025-10-08 11:59:50', 0),
(256, 90, 14, 5, 'I have invited someone to join as a volunteer', '68e6620a59b79-17599287998458495571725877862652.jpg', '2025-10-08 12:07:22', 1),
(257, 170, 1, 20, NULL, NULL, '2025-10-08 12:31:35', 0),
(258, 132, 1, 20, NULL, NULL, '2025-10-08 12:31:43', 0),
(259, 131, 1, 20, NULL, NULL, '2025-10-08 12:31:50', 0),
(260, 129, 1, 5, NULL, NULL, '2025-10-08 12:32:01', 0),
(261, 202, 1, 10, NULL, NULL, '2025-10-09 04:14:02', 1),
(262, 291, 1, 10, NULL, NULL, '2025-10-11 06:33:22', 1),
(263, 292, 1, 10, NULL, NULL, '2025-10-11 09:30:08', 1),
(264, 287, 1, 10, NULL, NULL, '2025-10-11 16:04:05', 1),
(265, 293, 1, 10, NULL, NULL, '2025-10-12 21:39:31', 1),
(266, 293, 1, 10, NULL, NULL, '2025-10-12 21:40:40', 0),
(267, 36, 1, 10, NULL, NULL, '2025-10-13 08:03:12', 0),
(268, 293, 1060, 10, NULL, NULL, '2025-10-13 08:28:55', 1),
(269, 36, 1, 10, NULL, NULL, '2025-10-13 16:12:26', 0),
(270, 294, 1, 10, NULL, NULL, '2025-10-14 10:18:18', 1),
(271, 294, 11, 25, 'Activity: Teaching inclusion at a local school\r\n\r\nReflection: “I shared with students how everyone can contribute to society regardless of their abilities. They were attentive and asked many questions. photos of students', '68ee36553c191-1760441712130.jpg', '2025-10-14 10:39:01', 1),
(272, 294, 9, 10, 'Activity: Teaching inclusion at a local school\r\n\r\nReflection: “I shared with students how everyone can contribute to society regardless of their abilities. They were attentive and asked many questions. photos of students', '68ee367e9deb9-1760441712130.jpg', '2025-10-14 10:39:42', 1),
(273, 293, 1, 10, NULL, NULL, '2025-10-14 21:53:54', 1),
(274, 295, 1, 10, NULL, NULL, '2025-10-14 22:32:24', 1),
(275, 293, 1, 10, NULL, NULL, '2025-10-14 22:32:54', 1),
(276, 90, 1, 10, NULL, NULL, '2025-10-15 07:43:02', 0),
(277, 293, 1, 10, NULL, NULL, '2025-10-15 08:36:42', 1),
(278, 296, 1, 10, NULL, NULL, '2025-10-16 10:16:19', 1),
(279, 296, 8, 10, 'I saw through that social media to listen', '68f0d49aa2dcf-Screenshot_20251006-183509.jpg', '2025-10-16 10:18:50', 0),
(280, 294, 1, 20, NULL, NULL, '2025-10-16 15:02:50', 0),
(281, 293, 1, 10, NULL, NULL, '2025-10-16 15:02:54', 0),
(282, 293, 1, 10, NULL, NULL, '2025-10-16 15:02:55', 0),
(283, 293, 1, 10, NULL, NULL, '2025-10-16 15:03:00', 0),
(284, 297, 1, 10, NULL, NULL, '2025-10-16 17:42:17', 1),
(285, 298, 1, 10, NULL, NULL, '2025-10-16 17:47:06', 1),
(286, 299, 1, 10, NULL, NULL, '2025-10-16 20:14:10', 1),
(287, 300, 1, 10, NULL, NULL, '2025-10-17 04:36:05', 1),
(288, 301, 1, 10, NULL, NULL, '2025-10-17 13:39:00', 1),
(289, 302, 1, 10, NULL, NULL, '2025-10-17 16:10:16', 1),
(290, 304, 1, 10, NULL, NULL, '2025-10-22 09:46:33', 1),
(291, 303, 1, 10, NULL, NULL, '2025-10-22 12:17:00', 1),
(292, 305, 1, 10, NULL, NULL, '2025-10-22 18:55:12', 1),
(293, 308, 1, 10, NULL, NULL, '2025-10-23 15:14:22', 1),
(294, 309, 1, 10, NULL, NULL, '2025-10-23 17:36:46', 1),
(295, 310, 1, 10, NULL, NULL, '2025-10-23 17:43:17', 1),
(296, 311, 1, 10, NULL, NULL, '2025-10-23 18:20:22', 1),
(297, 311, 1, 10, NULL, NULL, '2025-10-26 15:48:15', 1),
(298, 311, 1, 10, NULL, NULL, '2025-10-26 15:50:14', 1),
(299, 293, 1, 10, NULL, NULL, '2025-10-26 15:50:28', 1),
(300, 76, 1, 10, NULL, NULL, '2025-10-26 15:56:24', 1),
(301, 76, 1, 10, NULL, NULL, '2025-10-26 15:56:27', 1),
(302, 76, 1, 10, NULL, NULL, '2025-10-26 15:56:30', 1),
(303, 76, 1, 10, NULL, NULL, '2025-10-26 15:56:33', 1),
(304, 76, 1, 10, NULL, NULL, '2025-10-26 16:13:50', 1),
(305, 272, 1, 10, NULL, NULL, '2025-10-27 15:15:31', 1),
(306, 272, 1, 10, NULL, NULL, '2025-10-27 15:15:59', 1),
(307, 272, 1, 10, NULL, NULL, '2025-10-27 15:16:43', 1),
(308, 272, 1, 10, NULL, NULL, '2025-10-27 15:17:04', 1),
(309, 180, 1, 10, NULL, NULL, '2025-10-27 15:21:25', 1),
(310, 314, 1, 10, NULL, NULL, '2025-10-27 16:28:09', 1),
(311, 131, 1, 10, NULL, NULL, '2025-10-27 18:05:35', 1),
(312, 207, 10, 10, 'This is a great course that has shaped my knowledge on the said course', '68ffcbcebb617-IMG_20251014_120123.jpg', '2025-10-27 18:45:18', 0),
(313, 207, 10, 10, 'This is a great course that has shaped my knowledge on the said course', '68ffcc2181b24-IMG_20251014_120123.jpg', '2025-10-27 18:46:41', 0),
(314, 207, 15, 5, 'Here is the link to my LinkedIn page https://www.linkedin.com/posts/damilare-asiyanbola-203644120_last-week-i-joined-the-dade-foundation-as-activity-7383819343689609216-_62c?utm_source=share&amp;utm_medium=member_android&amp;rcm=ACoAAB3-qCMB8HhndPGMrTsTSwK3QwFLPTFsZEI', '68ffcd841722f-IMG_20251027_205215.jpg', '2025-10-27 18:52:36', 0),
(315, 207, 10, 10, 'This is a great course that has shaped my knowledge on the subject matter', '68ffd4f2b2110-IMG_20251014_120123.jpg', '2025-10-27 19:24:18', 0),
(316, 301, 1, 10, NULL, NULL, '2025-10-28 00:11:55', 1),
(317, 272, 1, 5, NULL, NULL, '2025-10-28 00:29:41', 1),
(318, 272, 1, 10, NULL, NULL, '2025-10-28 02:03:12', 1),
(319, 36, 1, 10, NULL, NULL, '2025-10-28 07:29:21', 1),
(320, 301, 1, 10, NULL, NULL, '2025-10-28 07:30:16', 1),
(321, 301, 1, 10, NULL, NULL, '2025-10-28 07:30:59', 1),
(322, 301, 1, 10, NULL, NULL, '2025-10-28 07:50:39', 1),
(323, 317, 1, 10, NULL, NULL, '2025-10-28 15:30:41', 1),
(324, 272, 1, 5, NULL, NULL, '2025-10-28 18:41:12', 1),
(325, 266, 1, 10, NULL, NULL, '2025-10-29 08:04:41', 1),
(326, 166, 1, 10, NULL, NULL, '2025-10-30 03:44:50', 1),
(327, 272, 1, 15, NULL, NULL, '2025-11-13 05:32:18', 1),
(328, 223, 1, 10, NULL, NULL, '2025-11-22 04:24:21', 1),
(329, 223, 1, 15, NULL, NULL, '2025-11-22 04:24:31', 1),
(330, 289, 1, 10, NULL, NULL, '2025-11-26 15:19:14', 1),
(331, 223, 1, 10, NULL, NULL, '2025-11-26 15:32:05', 1),
(332, 223, 1, 10, NULL, NULL, '2025-11-26 15:32:14', 1),
(333, 223, 1, 10, NULL, NULL, '2025-11-26 15:32:39', 1),
(334, 223, 1, 10, NULL, NULL, '2025-12-02 08:21:42', 1),
(335, 296, 8, 10, 'Here is the link for social media to listen', '693a63847948e-IMG-20250827-WA0059.jpg', '2025-12-11 05:24:04', 0),
(336, 320, 1, 10, NULL, NULL, '2026-01-27 14:44:27', 1),
(337, 321, 1, 10, NULL, NULL, '2026-01-28 11:07:32', 1),
(338, 301, 1, 10, NULL, NULL, '2026-01-30 07:29:51', 1),
(339, 322, 1, 10, NULL, NULL, '2026-01-30 10:33:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `webinar_registrations`
--

CREATE TABLE `webinar_registrations` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `is_pwd` tinyint(1) NOT NULL,
  `pwd_details` text DEFAULT NULL,
  `accessibility_requirements` text DEFAULT NULL,
  `how_did_you_hear` text DEFAULT NULL,
  `consent_for_updates` tinyint(1) NOT NULL,
  `registered_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `webinar_registrations`
--

INSERT INTO `webinar_registrations` (`id`, `full_name`, `email`, `phone_number`, `gender`, `is_pwd`, `pwd_details`, `accessibility_requirements`, `how_did_you_hear`, `consent_for_updates`, `registered_at`) VALUES
(1, 'SHUAIB ABDOOL', 'shuaibabdul192@gmail.com', '08122598372', 'Male', 0, '', 'noti', 'Social Media, Website', 1, '2025-09-17 14:57:18'),
(2, 'Agatha Paul', 'paulagatha45@gmail.com', '07063246805', 'Female', 1, 'physical', 'nil', 'Social Media', 1, '2025-09-17 15:32:46'),
(3, 'Agatha Kuyet Paul', 'agathapaul760@gmail.com', '07063246805', 'Female', 1, 'Physical', 'Nil', 'Social Media', 0, '2025-09-17 19:17:02'),
(4, 'Abigail  Micheal', 'abgmicheal3@gmail.com', '07086154073', 'Female', 1, 'Spinal cord injury', '', 'Website', 1, '2025-09-17 21:19:05'),
(5, 'Mubarak Isa', 'mubarakisa04@gmail.com', '08033966319', 'Male', 0, '', '', 'Website', 1, '2025-09-17 21:32:50'),
(6, 'Anigor mercy chikodili', 'chikodilimercy2@gmail.com', '08069768059', 'Female', 1, 'Blind', '', 'Friend', 1, '2025-09-17 23:03:45'),
(7, 'Nasiba Alhassan Abdullahi', 'nasibaabdullahialhassan@gmail.com', '08143182685', 'Female', 1, 'I&#039;m deaf', '', 'Friend', 1, '2025-09-17 23:54:22'),
(8, 'Umaru Eggah Wilson', 'djtestimonywilson@gmail.com', '08161895680', 'Male', 1, 'Mobility', 'Scholarship', 'Website', 1, '2025-09-18 07:10:16'),
(9, 'Hauwa Uwais Abdulhameed', 'hauwauwais2016@gmail.com', '08161876293', 'Female', 1, 'Physical challenge', 'Cerlifer', 'Website', 1, '2025-09-18 11:24:04'),
(10, 'Timjul Samson', 'samsintimju@gmail.com', '08031952651', 'Male', 1, 'Blind', 'Yes', 'Social Media', 1, '2025-09-18 11:31:18'),
(11, 'Maryam jamilu Suleiman', 'Maryamjamilsuleiman@gmail.com', '09033539933', 'Female', 1, '', 'Yes', 'Social Media', 1, '2025-09-18 11:34:06'),
(12, 'Filivus Abimiku', 'filibusabimiku41@gmail.com', '09030474073', 'Male', 1, '', 'Though iam a blind person so it has not been easy for me to access it', 'Social Media', 1, '2025-09-18 11:38:11'),
(13, 'Sani Muhammad Abdullahi', 'sanimuhammadabdullahi12@gmail.com', '07033414945', 'Male', 1, 'Physical challenge', 'Physical', 'Website', 1, '2025-09-18 12:08:27'),
(14, 'Sani Isyaku', 'ishaqsani367@gmail.com', '07060894156', 'Male', 1, 'Hearing Impaired', 'Sign language interpreter', 'Social Media', 1, '2025-09-18 13:02:12'),
(15, 'Hadiza Mukhtar', 'hamandijo21@gmail.com', '08105472212', 'Female', 1, 'Deaf', 'Hearing aid', 'Social Media', 1, '2025-09-18 16:45:58'),
(16, 'Jamilu Abubakar', 'abubakarjamilu70@gmail.com', '08079525997', 'Male', 1, 'Spinal cord injury', 'Data', 'Friend', 1, '2025-09-18 21:10:30'),
(17, 'Fatimah Usman', 'albatulusman93@gmail.com', '08109571340', 'Female', 1, 'Albinism', 'No', 'Social Media', 1, '2025-09-18 21:13:18'),
(18, 'Salisu Sani Aliyu', 'salisusanialiyu807@gmail.com', '09137733080', 'Male', 1, 'Deaf', '', 'Website', 0, '2025-09-19 09:12:44'),
(19, 'Manbyen Daki', 'Manbyendaki@gmail.com', '08136064433', 'Female', 0, '', '', 'Social Media', 1, '2025-09-19 14:08:51'),
(20, 'Abdulrasaq Aisha', 'opeyemi24747@gmail.com', '+12348105657340', 'Female', 1, 'Physical challenge', 'Physical challenge', 'Friend', 1, '2025-09-21 00:03:10'),
(21, 'David felix', 'hassandavid554@gmaol.com', '08141312708', 'Male', 1, 'Blind', 'Education', 'Friend', 1, '2025-09-21 16:28:23'),
(22, 'suleiman abubakar saidu', 'suleimern236@gmail.com', '08105669685', 'Male', 0, '', '', 'Social Media', 0, '2025-09-23 11:44:53'),
(23, 'Unyime Isidore', 'unyimeisidoreudoka1987@gmail.com', '08100311049', 'Male', 1, 'Amputee ( physically challenged)', 'No', 'Social Media', 1, '2025-09-24 09:55:47'),
(24, 'Abdulazeez Muideen', 'suborlar@gmail.com', '08092653039', 'Male', 1, 'Spinal Cord Injuries', 'Wheelchair', 'Social Media', 1, '2025-09-24 10:10:28'),
(25, 'Esther Abimbola Ogunrotimi', 'bimbolami23@gmail.com', '08103235871', 'Female', 1, 'Deaf', 'Sign language interpreter', 'Social Media', 1, '2025-09-24 10:15:05'),
(26, 'Hussaini Alhassan', 'attahirualhassan2015@gmail.com', '08109545496', 'Male', 1, 'Physical disability', 'Left leg', 'Website', 1, '2025-09-24 10:29:44'),
(27, 'VIVIAN NGOKA', 'ngokav@gmail.com', '08134076398', 'Female', 1, 'Albinism', 'Bold prints', 'Friend', 1, '2025-09-24 10:32:53'),
(28, 'Ogunshina Deborah Oluwatosin', 'demiladeayo1@gmail.com', '07012992201', 'Female', 1, 'Blind', '', 'Friend', 1, '2025-09-24 10:33:02'),
(29, 'Perpetua Ada Anyanwu', 'adaperpetua33@gmail.com', '08097593528', 'Female', 1, 'Physically challenged', 'Ramp', 'Website', 1, '2025-09-24 10:35:01'),
(30, 'Jatula Temidayo ayodele', 'jatulatemidayo936@gmail.com', '+2348037557412', 'Female', 1, 'Blind', 'Braille and mobility aid.', 'Social Media', 1, '2025-09-24 10:37:33'),
(31, 'Ibrahim umar', 'iu777570@gmail.com', '08109174107', 'Male', 1, 'Yes', 'Yes', 'Others', 1, '2025-09-24 10:49:52'),
(32, 'Damilare Aduralere Olatunji', 'aduralereoluwadamilare@gmail.com', '09030667240', 'Male', 1, 'Visually impaired', 'Total blind', 'Social Media', 1, '2025-09-24 11:16:17'),
(33, 'Florence Chinonyerem Udeh', 'udefloxxynonyerem1@gmail.com', '09132669627', 'Female', 1, 'Physical disability', '', 'Social Media', 1, '2025-09-24 11:18:27'),
(34, 'Olapade  olorunwa teminijesu', 'olapadeolorunwa@gmail.com', '08140649262', 'Female', 1, 'Visually impaired', 'Mobility cane', 'Friend', 1, '2025-09-24 11:43:39'),
(35, 'Habiba dauda fosa', 'habibafosa19922@gmail.com', '08166800290', 'Female', 1, 'I am deaf', '', 'Social Media', 1, '2025-09-24 11:48:27'),
(36, 'AbdulAzeez Asadullah Ahmed', 'azeezahmedasad@gmail.com', '08134625039', 'Male', 1, 'Blind', 'Screenreader enabled platform as well as audio description where necessary.', 'Social Media', 1, '2025-09-24 11:52:37'),
(37, 'Deborah Ayomide shaba', 'ayomideshaba1999@gmail.com', '09063980442', 'Female', 1, 'Person with Albinism', '', 'Website', 0, '2025-09-24 12:15:08'),
(38, 'Emmanuel Olajuwon Olarewaju', 'Pureboy048@gmail.com', '08135177554', 'Male', 1, 'I am deaf', 'I&#039;m good, thanks. I was just looking at the information about accessibility requirements. No specific project requirements for just understanding the concept.', 'Social Media', 0, '2025-09-24 12:27:09'),
(39, 'Ajayi Ayomide Bose', 'ayomidebosede3@gmail.com', '08063597369', 'Female', 1, 'Physically challenge', '', 'Website', 1, '2025-09-24 13:01:48'),
(40, 'Ayuba Florence Azewanre', 'florenceayuba96@gmail.com', '09035248495', 'Female', 1, 'Physically challenged', '', 'Social Media', 1, '2025-09-24 13:03:15'),
(41, 'Jibrin Umaru', 'umarujibrin33@gmail.com', '08130631349', 'Male', 1, 'Hearing impaired', 'Look for scholarship schools fees', 'Friend', 1, '2025-09-24 13:40:25'),
(42, 'Caleb Fwanglong Bulus', 'buluscaleb8@gmail.com', '09033965132', 'Male', 1, 'Limb mutation', '', 'Social Media', 1, '2025-09-24 13:44:37'),
(43, 'adelani okanlawon', 'okanlawonidris11@gmail.com', '09022424602', 'Male', 1, 'blind', '', 'Social Media', 1, '2025-09-24 13:46:06'),
(44, 'Kadir Abdulfatai', 'fatai.double@gmail.com', '08091158098', 'Male', 1, 'Deaf', 'Interpretation', 'Friend', 1, '2025-09-24 13:58:04'),
(45, 'Adekanbi Muhammad Sodiq.', 'adekanbimuhammadsodiq@gmail.com', '08137381491', 'Male', 1, 'Blind', '', 'Social Media', 1, '2025-09-24 14:06:23'),
(46, 'ROSE DANIEL', 'roselynyoms@gmail.com', '08061504534', 'Female', 1, 'Physical Impairment', '', 'Social Media, Website', 1, '2025-09-24 14:13:10'),
(47, 'Suleiman Muhammed saidu', 'saidusuleiman715@gmail.com', '08038166031', 'Male', 1, 'Hearing impaired', 'Request for scholarship school fees payment MED.', 'Friend', 1, '2025-09-24 14:35:54'),
(48, 'Aminu Abdullahi', 'aminuabdullahi925@gmail.com', '08144274572', 'Male', 1, 'Deaf', 'I need support to pay my sch fee', 'Friend', 1, '2025-09-24 14:38:37'),
(49, 'Abubakar Sadiq salisu', 'bosssadiq79@gmail.com', '07085590620', 'Male', 1, 'Blind', 'Communication accessibility', 'Friend', 1, '2025-09-24 14:38:40'),
(50, 'Ameh Emmanuel Ojodomo', 'emmanuelameh636@gmail.com', '09069528531', 'Male', 1, 'Low vision', 'Supporting technologies for low vision', 'Social Media', 1, '2025-09-24 15:13:45'),
(51, 'Agbejo Dorcas Oluwapamilerin', 'dorcasoluwapamilerin9@gmail.com', '07060520805', 'Female', 1, 'Visually impair', 'Education', 'Social Media', 1, '2025-09-24 17:40:42'),
(52, 'Samson Okuribido', 'Samsonokuri@gmail.com', '07062376497', 'Male', 1, 'Deaf', '', 'Social Media, Website, Friend', 1, '2025-09-24 18:28:38'),
(53, 'Abubakar Sani', 'abubakarsani1960@gmail.com', '08100779411', 'Male', 1, 'Am hearing impairment', 'Technology computer and poultry farmer', 'Social Media', 1, '2025-09-24 18:58:05'),
(54, 'Ojo Olawale Daniel', 'ojoolawaledaniel4@gmail.com', '09132734774', 'Male', 1, 'Albinism', 'Yes', 'Others', 1, '2025-09-24 20:02:32'),
(55, 'Uzoamaka Stella Ike', 'ikestella19@gmail.com', '07060632819', 'Female', 1, 'Physical impairment', 'None', 'Friend', 1, '2025-09-24 20:23:29'),
(56, 'Ogunkanmi michael', 'ogunkanmimichael29@gmail.com', '08062209611', 'Male', 1, 'Visually impaired', '', 'Social Media, Friend', 0, '2025-09-24 21:20:30'),
(57, 'KOHOL TERKURA', 'koholterkura611@gmail.com', '08161511675', 'Male', 1, 'Hard of hearing', 'No', 'Social Media', 1, '2025-09-24 21:29:42'),
(58, 'Opeola Oluwasegun Rotimi', 'opeolaoluwasegun@gmail.com', '07062646212', 'Male', 1, 'Blind', 'Access to scholarship', 'Friend', 1, '2025-09-24 21:39:35'),
(59, 'Osaosemwen egharevba', 'osaseghareba@gmail.com', '09150784450', 'Male', 1, 'Visually impaired', 'The accessibility requirements are one electronic documents such as Microsoft word documents PDF format in any form of the appreciated to make me have access to everything that&#039;s been done and the materials in this lecture', 'Social Media', 1, '2025-09-24 22:39:53'),
(60, 'Iliya Ismail', 'scianbauchi@gmail.com', '08137740440', 'Male', 1, 'Spinal Cord Injury', 'Wheelchair', 'Social Media', 1, '2025-09-24 23:04:07'),
(61, 'Salamatu', 'salamatujiya2025@gmail.com', '0812 194 0536', 'Female', 1, 'I am deaf', 'Clear language', 'Social Media', 1, '2025-09-24 23:54:28'),
(62, 'Ojeaga richard awekpenloje', 'ojeagarichard45@gmail.com', '09136489003', 'Male', 1, 'Blindness', '', 'Social Media', 1, '2025-09-25 00:11:29'),
(63, 'Abdullahi Abdulkadir', 'abthoul24@gmail.com', '07083853192', 'Male', 1, '', 'Hearing aid', 'Social Media', 1, '2025-09-25 07:25:04'),
(64, 'Nasir Dalha', 'nasirshagzi01@gmail.com', '08037916699', 'Male', 1, 'Deaf', 'Sign Language Interpreter', 'Website', 1, '2025-09-25 11:11:37'),
(65, 'ekiuwa Obanor joy', 'obasyiekiuwa@gmail.com', '08034261649', 'Female', 1, 'visualimpa', '', 'Social Media', 1, '2025-09-25 11:31:15'),
(66, 'Abdulsalam Alimat Sadiat', 'alimatabdulsalam87@gmail.com', '07033669970', 'Female', 1, 'Albinism', 'Bold fonts/ magnifying glass', 'Social Media', 1, '2025-09-25 15:21:17'),
(67, 'BAILEY SEGUN', 'baileysegun148@yahoo.com', '07089207407', 'Male', 1, 'Physically challenge', 'Wheelchair', 'Social Media', 1, '2025-09-25 15:41:57'),
(68, 'Ogweda Emmanuel Binainu', 'uniquefarmproducts@gmail.comcom', '08134359945', 'Male', 1, 'Hearing impaired', 'Eyeglasses and Hearing Aids', 'Social Media', 1, '2025-09-25 15:47:02'),
(69, 'Adisa Abefe Luqman', 'abefeoluwaseun07@gmail.com', '07030281908', 'Male', 1, 'Physical disability', 'Walking stick', 'Social Media', 1, '2025-09-25 16:25:38'),
(70, 'Akinjo Joy', 'missakinjojoy@yahoo.com', '08103712566', 'Female', 1, 'Polio', '', 'Social Media, Friend', 1, '2025-09-25 17:27:06'),
(71, 'Onyinyechi Winner Ethelbert', 'genteeldeevine@gmail.com', '07069352262', 'Female', 1, 'Wheelchair User', 'Accessible accommodation', 'Others', 1, '2025-09-25 20:07:09'),
(72, 'Gboh-igbara karabari Charles', 'gbohigbarakarabaricharles@gmail.com', '08113285896', 'Female', 1, 'Physically challenge', 'How to access scholarship as disability person', 'Social Media', 1, '2025-09-25 22:38:41'),
(73, 'Peter', 'gwerpeter@gmail.com', '07080859350', 'Male', 1, 'Physical and mobility disability', 'Physical wheelchair accessibility', 'Friend', 0, '2025-09-25 22:58:23'),
(74, 'ANAFI GRACE OLUWAYEMISI', 'graciousgrace6824@gmail.com', '09068483532', 'Female', 0, '', '', 'Friend', 1, '2025-09-26 09:07:54'),
(75, 'Charles Okoro', 'charlesemeka147@gmail.com', '09061554156', 'Male', 1, 'Paraplegic', 'Wheel Chair Accessibility', 'Social Media', 1, '2025-09-26 11:23:34'),
(76, 'Akolade Ruth Titilayomi', 'E010116.akolade@dlc.ui.edu.ng', '07087562296', 'Female', 1, 'Physical challenge', 'None', 'Social Media', 1, '2025-09-26 17:21:18'),
(77, 'Nuhu Barnabas KADANGA', 'nuhubarnabaskadanga@gmail.com', '07032727966', 'Male', 0, '', 'When the need arise', 'Social Media', 1, '2025-09-26 19:16:04'),
(78, 'Raphael Martha', 'martharaphael05@gmail.com', '08105005880', 'Female', 1, 'Spinal cord injury survival', 'Good road that would be accessible for wheelchair', 'Social Media', 1, '2025-09-26 19:47:30'),
(79, 'Gbebikan olayinka samson', 'olayinkasam259@gmail.com', '07054510828', 'Male', 1, 'Blind', '', 'Social Media', 1, '2025-09-26 19:47:58'),
(80, 'Abubakar sharif saleh', 'abubakarsharifsaleh890@gmail.com', '09161512465', 'Male', 1, 'I&#039;m hearing impaired', 'Deaf', 'Social Media', 1, '2025-09-26 20:55:46'),
(81, 'Oluwatomisin Olatayo', 'tomisinolatayo29@gmail.com', '07036339628', 'Female', 1, 'blind', 'Image distribution', 'Social Media', 1, '2025-09-26 21:47:33'),
(82, 'Madu Chinedu Charles', 'chinedumchukwuemeka@gmail.com', '08132429260', 'Male', 1, 'Physically challenged', '', 'Friend', 1, '2025-09-27 00:54:18'),
(83, 'Halima Ahmad Hassan', 'halimaahassan689@gmail.com', '07063395720', 'Female', 1, 'Cripple', 'Welcher', 'Social Media', 1, '2025-09-27 06:31:29'),
(84, 'Aaron Ayuba', 'ayubasamailajnr@gmail.com', '+234 806 034 3446', 'Male', 1, 'Left Leg Amputation', 'Prosthetic Device/Crouches', 'Friend', 1, '2025-09-27 06:51:54'),
(85, 'Auwalu Ismaila Salisu', 'muhammadawwalismail3@gmail.com', '+234 916 631 5182', 'Male', 1, 'Deafness', 'Sign language interpretation', 'Website', 1, '2025-09-27 07:51:06'),
(86, 'Paul Jael', 'jaelpaul2020.jp@gmail.com', '08164835684', 'Female', 1, 'Sickle cell', '', 'Others', 1, '2025-09-27 09:04:00'),
(87, 'Paul Jerushah Ketah', 'jerushahpaul2000@gmail.com', '08173323337', 'Female', 0, '', '', 'Social Media, Friend', 1, '2025-09-27 09:06:49'),
(88, 'Victoria moses', 'vickyimmamoses@gmail.com', '07033409604', 'Female', 1, 'Physical mobility challenge', '', 'Friend', 1, '2025-09-27 13:14:48'),
(89, 'Timothy Audu Whyegon', 'timwhyegon19@gmail.com', '08134545153', 'Male', 1, 'Quadriplegic. Cervical injury from a car accident which affected my fore limps, I am currently using a wheelchair for mobility', 'Yes,', 'Social Media', 1, '2025-09-27 15:11:36'),
(90, 'Michael Arigbede', 'temidayomichael@gmail.com', '08133807192', 'Male', 1, 'The right leg', '', 'Website', 1, '2025-09-27 15:50:17'),
(91, 'Emmanuel Sunday Gabriel', 'emmanuelgabriel7030@gmail.com', '08126096593', 'Male', 1, 'Visually impaired', '', 'Friend', 1, '2025-09-27 17:15:31'),
(92, 'Sarah pam', 'sarahpam49@gmail.com', '08065687530', 'Female', 1, 'Physically handicap', 'Wheel chair', 'Website', 1, '2025-09-27 17:53:01'),
(93, 'Serina Ifeoma Whyte', 'serinawhyte90@gmail.com', '08109502097', 'Female', 1, 'Visual impairment', 'Accessible formats of documents', 'Social Media', 1, '2025-09-29 00:06:38'),
(94, 'Omolade samuel olami', 'officialbigsammy@gmail.com', '09034417380', 'Male', 1, 'Blind', 'Screen reader', 'Social Media', 0, '2025-09-29 17:24:05'),
(95, 'Ekevere irikefe Stephen', 'ekevereirikefestephen@gmail.com', '09035714285', 'Male', 1, 'Visually impaired', '', 'Social Media', 1, '2025-09-30 15:46:05'),
(96, 'Olajide Ibrahim abiodun', 'olajideibrahim76@gmail.com', '08075660209', 'Male', 1, 'Visually impaired', 'Guide', 'Friend', 1, '2025-10-01 11:08:16'),
(97, 'Emem Umoetuk', 'Ememumoetuk15@gmail.com', '08064957232', 'Female', 1, 'Deaf', 'Accessibility requirements are standards that ensure digital products and services usable by everyone including people with disabilities', 'Social Media', 1, '2025-10-02 07:47:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `analytics_daily`
--
ALTER TABLE `analytics_daily`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_bookmark` (`user_id`,`course_id`),
  ADD KEY `idx_user_bookmarks` (`user_id`),
  ADD KEY `idx_course_bookmarks` (`course_id`);

--
-- Indexes for table `cadre_levels`
--
ALTER TABLE `cadre_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificate_number` (`certificate_number`),
  ADD UNIQUE KEY `unique_certificate` (`user_id`,`course_id`),
  ADD UNIQUE KEY `certificate_code` (`certificate_code`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `course_ratings`
--
ALTER TABLE `course_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `custom_forms`
--
ALTER TABLE `custom_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_form_fields`
--
ALTER TABLE `custom_form_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `custom_form_submissions`
--
ALTER TABLE `custom_form_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enrollment` (`user_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `entry_test_questions`
--
ALTER TABLE `entry_test_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `entry_test_results`
--
ALTER TABLE `entry_test_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `e_books`
--
ALTER TABLE `e_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thread_id` (`thread_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `forum_threads`
--
ALTER TABLE `forum_threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_progress` (`user_id`,`lesson_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `opportunities`
--
ALTER TABLE `opportunities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `required_cadre_level_id` (`required_cadre_level_id`);

--
-- Indexes for table `opportunity_proofs`
--
ALTER TABLE `opportunity_proofs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `application_id` (`application_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `pre_launch_registrations`
--
ALTER TABLE `pre_launch_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_review` (`user_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_reference` (`payment_reference`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_instructor_pending` (`instructor_pending`);

--
-- Indexes for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_badge` (`user_id`,`badge_id`),
  ADD KEY `idx_user_badges` (`user_id`),
  ADD KEY `idx_badge_users` (`badge_id`);

--
-- Indexes for table `volunteer_applications`
--
ALTER TABLE `volunteer_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `opportunity_id` (`opportunity_id`);

--
-- Indexes for table `volunteer_opportunities`
--
ALTER TABLE `volunteer_opportunities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_opportunity` (`user_id`,`opportunity_id`);

--
-- Indexes for table `volunteer_points`
--
ALTER TABLE `volunteer_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `webinar_registrations`
--
ALTER TABLE `webinar_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `analytics_daily`
--
ALTER TABLE `analytics_daily`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cadre_levels`
--
ALTER TABLE `cadre_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `course_ratings`
--
ALTER TABLE `course_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_forms`
--
ALTER TABLE `custom_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `custom_form_fields`
--
ALTER TABLE `custom_form_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `custom_form_submissions`
--
ALTER TABLE `custom_form_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=479;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `entry_test_questions`
--
ALTER TABLE `entry_test_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `entry_test_results`
--
ALTER TABLE `entry_test_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=277;

--
-- AUTO_INCREMENT for table `e_books`
--
ALTER TABLE `e_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `forum_replies`
--
ALTER TABLE `forum_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_threads`
--
ALTER TABLE `forum_threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `opportunities`
--
ALTER TABLE `opportunities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `opportunity_proofs`
--
ALTER TABLE `opportunity_proofs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pre_launch_registrations`
--
ALTER TABLE `pre_launch_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=330;

--
-- AUTO_INCREMENT for table `user_badges`
--
ALTER TABLE `user_badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `volunteer_applications`
--
ALTER TABLE `volunteer_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `volunteer_opportunities`
--
ALTER TABLE `volunteer_opportunities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `volunteer_points`
--
ALTER TABLE `volunteer_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=340;

--
-- AUTO_INCREMENT for table `webinar_registrations`
--
ALTER TABLE `webinar_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificates_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `custom_form_fields`
--
ALTER TABLE `custom_form_fields`
  ADD CONSTRAINT `custom_form_fields_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `custom_forms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `custom_form_submissions`
--
ALTER TABLE `custom_form_submissions`
  ADD CONSTRAINT `custom_form_submissions_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `custom_forms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `entry_test_questions`
--
ALTER TABLE `entry_test_questions`
  ADD CONSTRAINT `entry_test_questions_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `entry_test_results`
--
ALTER TABLE `entry_test_results`
  ADD CONSTRAINT `entry_test_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `e_books`
--
ALTER TABLE `e_books`
  ADD CONSTRAINT `e_books_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_attempts_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
