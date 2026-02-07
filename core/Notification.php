<?php
/**
 * DADE Learn - Notification Helper
 * Handles in-app notifications
 */

class Notification {
    
    /**
     * Create a new notification
     * 
     * @param int $userId User ID
     * @param string $title Notification title
     * @param string $message Notification message body
     * @param string $type Notification type (info, success, warning, error)
     * @return bool True on success
     */
    public static function create($userId, $title, $message, $type = 'info') {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $title, $message, $type);
        return $stmt->execute();
    }
    
    /**
     * Get unread notifications for a user
     * 
     * @param int $userId User ID
     * @param int $limit Max number of notifications to return
     * @return array Array of notifications
     */
    public static function getUnread($userId, $limit = 5) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC LIMIT ?");
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        return $notifications;
    }
    
    /**
     * Get all notifications (paginated)
     */
    public static function getAll($userId, $limit = 20, $offset = 0) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->bind_param("iii", $userId, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        return $notifications;
    }
    
    /**
     * Count unread notifications
     */
    public static function countUnread($userId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['count'];
    }
    
    /**
     * Mark notification as read
     */
    public static function markAsRead($id, $userId) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $userId);
        return $stmt->execute();
    }
    
    /**
     * Mark all as read for a user
     */
    public static function markAllAsRead($userId) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
}
