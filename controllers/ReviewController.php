<?php
/**
 * ReviewController - Handles course reviews and comments
 */

class ReviewController {
    
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Submit a new review
     */
    public function store() {
        if (!Auth::check()) {
            Session::flash('error', 'You must be logged in to leave a review.');
            Router::back();
            return;
        }
        
        if (!validateCsrf()) {
            Session::flash('error', 'Invalid request.');
            Router::back();
            return;
        }
        
        $userId = Auth::id();
        $courseId = intval($_POST['course_id'] ?? 0);
        $rating = floatval($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');
        
        if ($rating < 1 || $rating > 5) {
            Session::flash('error', 'Please provide a valid rating between 1 and 5.');
            Router::back();
            return;
        }
        
        // Check if user is enrolled
        $stmt = $this->db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $userId, $courseId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) {
            Session::flash('error', 'You must be enrolled in this course to leave a review.');
            Router::back();
            return;
        }
        
        // Check if already reviewed
        $stmt = $this->db->prepare("SELECT id FROM reviews WHERE user_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $userId, $courseId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            Session::flash('error', 'You have already reviewed this course.');
            Router::back();
            return;
        }
        
        // Insert review
        $stmt = $this->db->prepare("INSERT INTO reviews (user_id, course_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iids", $userId, $courseId, $rating, $comment);
        
        if ($stmt->execute()) {
            // Update course average rating (optional but recommended for performance)
            $this->updateCourseRating($courseId);
            Session::flash('success', 'Thank you for your review!');
        } else {
            Session::flash('error', 'Failed to submit review. Please try again.');
        }
        
        Router::back();
    }
    
    /**
     * Update the average rating for a course
     */
    private function updateCourseRating($courseId) {
        $stmt = $this->db->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE course_id = ?");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $avg = $stmt->get_result()->fetch_assoc()['avg_rating'] ?? 0;
        
        $stmt = $this->db->prepare("UPDATE courses SET avg_rating = ? WHERE id = ?");
        $stmt->bind_param("di", $avg, $courseId);
        $stmt->execute();
    }
}
