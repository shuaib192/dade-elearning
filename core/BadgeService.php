<?php
/**
 * DADE Learn - BadgeService
 * Handles badge checking and auto-awarding
 */

class BadgeService {
    
    /**
     * Check and award all applicable badges for a user
     */
    public static function checkAllBadges($userId) {
        $db = getDB();
        
        // Get user stats
        $stats = self::getUserStats($db, $userId);
        
        // Get all badges
        $badges = $db->query("SELECT * FROM badges")->fetch_all(MYSQLI_ASSOC);
        
        foreach ($badges as $badge) {
            self::checkAndAwardBadge($db, $userId, $badge, $stats);
        }
    }
    
    /**
     * Get user achievement stats
     */
    private static function getUserStats($db, $userId) {
        $stats = [];
        
        // Courses completed
        $stmt = $db->prepare("SELECT COUNT(*) as c FROM enrollments WHERE user_id = ? AND completed = 1");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stats['courses_completed'] = $stmt->get_result()->fetch_assoc()['c'];
        
        // Quizzes passed
        $stmt = $db->prepare("SELECT COUNT(*) as c FROM quiz_attempts WHERE user_id = ? AND passed = 1");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stats['quizzes_passed'] = $stmt->get_result()->fetch_assoc()['c'];
        
        // Perfect quizzes (100%)
        $stmt = $db->prepare("SELECT COUNT(*) as c FROM quiz_attempts WHERE user_id = ? AND score = 100");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stats['perfect_quiz'] = $stmt->get_result()->fetch_assoc()['c'];
        
        // Certificates earned
        $stmt = $db->prepare("SELECT COUNT(*) as c FROM certificates WHERE user_id = ? AND status = 'approved'");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stats['certificates_earned'] = $stmt->get_result()->fetch_assoc()['c'];
        
        // Reviews written
        $stmt = $db->prepare("SELECT COUNT(*) as c FROM reviews WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stats['reviews_written'] = $stmt->get_result()->fetch_assoc()['c'];
        
        // Bookmarks created
        $stmt = $db->prepare("SELECT COUNT(*) as c FROM bookmarks WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stats['bookmarks_created'] = $stmt->get_result()->fetch_assoc()['c'];
        
        // Login streak (simplified - count consecutive days)
        $stats['login_streak'] = self::getLoginStreak($db, $userId);
        
        return $stats;
    }
    
    /**
     * Get user login streak
     */
    private static function getLoginStreak($db, $userId) {
        $stmt = $db->prepare("SELECT last_login FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $lastLogin = $stmt->get_result()->fetch_assoc()['last_login'] ?? null;
        
        // Simplified: return 7 if logged in today (would need login history table for accurate tracking)
        if ($lastLogin && date('Y-m-d', strtotime($lastLogin)) === date('Y-m-d')) {
            return 7; // Placeholder
        }
        return 1;
    }
    
    /**
     * Check and award a specific badge
     */
    private static function checkAndAwardBadge($db, $userId, $badge, $stats) {
        $criteria = $badge['criteria'];
        $requiredValue = $badge['criteria_value'];
        
        // Check if user meets criteria
        $userValue = $stats[$criteria] ?? 0;
        
        if ($userValue >= $requiredValue) {
            // Check if already awarded
            $stmt = $db->prepare("SELECT id FROM user_badges WHERE user_id = ? AND badge_id = ?");
            $stmt->bind_param("ii", $userId, $badge['id']);
            $stmt->execute();
            
            if ($stmt->get_result()->num_rows === 0) {
                // Award badge
                $stmt = $db->prepare("INSERT INTO user_badges (user_id, badge_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $userId, $badge['id']);
                $stmt->execute();
                
                return true; // New badge awarded
            }
        }
        
        return false;
    }
    
    /**
     * Get user's earned badges
     */
    public static function getUserBadges($userId) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT b.*, ub.earned_at 
            FROM badges b
            JOIN user_badges ub ON b.id = ub.badge_id
            WHERE ub.user_id = ?
            ORDER BY ub.earned_at DESC
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $badges = [];
        while ($row = $result->fetch_assoc()) {
            $badges[] = $row;
        }
        
        return $badges;
    }
    
    /**
     * Get total badge count for user
     */
    public static function getBadgeCount($userId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) as c FROM user_badges WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['c'];
    }
    
    /**
     * Get total points for user
     */
    public static function getTotalPoints($userId) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT COALESCE(SUM(b.points), 0) as total 
            FROM user_badges ub
            JOIN badges b ON ub.badge_id = b.id
            WHERE ub.user_id = ?
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }
}
