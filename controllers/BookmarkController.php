<?php
/**
 * DADE Learn - Bookmark Controller
 * Handles course bookmarking functionality
 */

class BookmarkController {
    
    /**
     * Toggle bookmark status (add/remove)
     * Called via AJAX
     */
    public static function toggle() {
        header('Content-Type: application/json');
        
        if (!Auth::check()) {
            echo json_encode(['success' => false, 'message' => 'Please login to bookmark courses']);
            exit;
        }
        
        $userId = Auth::user()['id'];
        $courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
        
        if (!$courseId) {
            echo json_encode(['success' => false, 'message' => 'Invalid course']);
            exit;
        }
        
        $db = getDB();
        
        // Check if already bookmarked
        $stmt = $db->prepare("SELECT id FROM bookmarks WHERE user_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $userId, $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Remove bookmark
            $stmt = $db->prepare("DELETE FROM bookmarks WHERE user_id = ? AND course_id = ?");
            $stmt->bind_param("ii", $userId, $courseId);
            $stmt->execute();
            
            echo json_encode([
                'success' => true, 
                'bookmarked' => false, 
                'message' => 'Bookmark removed'
            ]);
        } else {
            // Add bookmark
            $stmt = $db->prepare("INSERT INTO bookmarks (user_id, course_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $userId, $courseId);
            $stmt->execute();
            
            // Check for bookmark badge
            self::checkBookmarkBadge($db, $userId);
            
            echo json_encode([
                'success' => true, 
                'bookmarked' => true, 
                'message' => 'Course bookmarked'
            ]);
        }
        exit;
    }
    
    /**
     * Check if user qualifies for bookmark badge
     */
    private static function checkBookmarkBadge($db, $userId) {
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM bookmarks WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $count = $stmt->get_result()->fetch_assoc()['count'];
        
        if ($count >= 10) {
            // Award Bookworm badge
            $badgeStmt = $db->prepare("SELECT id FROM badges WHERE criteria = 'bookmarks_created' AND criteria_value <= ?");
            $badgeStmt->bind_param("i", $count);
            $badgeStmt->execute();
            $badges = $badgeStmt->get_result();
            
            while ($badge = $badges->fetch_assoc()) {
                $insertStmt = $db->prepare("INSERT IGNORE INTO user_badges (user_id, badge_id) VALUES (?, ?)");
                $insertStmt->bind_param("ii", $userId, $badge['id']);
                $insertStmt->execute();
            }
        }
    }
    
    /**
     * Check if a course is bookmarked by user
     */
    public static function isBookmarked($courseId) {
        if (!Auth::check()) return false;
        
        $db = getDB();
        $userId = Auth::user()['id'];
        $stmt = $db->prepare("SELECT id FROM bookmarks WHERE user_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $userId, $courseId);
        $stmt->execute();
        
        return $stmt->get_result()->num_rows > 0;
    }
    
    /**
     * Get user's bookmark count
     */
    public static function getCount() {
        if (!Auth::check()) return 0;
        
        $db = getDB();
        $userId = Auth::user()['id'];
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM bookmarks WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc()['count'];
    }
    
    /**
     * Get user's bookmarked courses
     */
    public static function getUserBookmarks() {
        if (!Auth::check()) return [];
        
        $db = getDB();
        $userId = Auth::user()['id'];
        
        $stmt = $db->prepare("
            SELECT c.*, 
                   cat.name as category_name,
                   u.username as instructor_name,
                   (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as enrolled_count,
                   (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count,
                   b.created_at as bookmarked_at
            FROM bookmarks b
            JOIN courses c ON b.course_id = c.id
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN users u ON c.instructor_id = u.id
            WHERE b.user_id = ? AND c.status = 'published'
            ORDER BY b.created_at DESC
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $courses = [];
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
        
        return $courses;
    }
}
