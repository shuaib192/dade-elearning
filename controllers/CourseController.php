<?php
/**
 * CourseController - Handles course-related actions (Dade Initiative)
 */

class CourseController {
    
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Enroll user in a course
     */
    public function enroll($courseId) {
        if (!validateCsrf()) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('courses');
            return;
        }
        
        $userId = Auth::id();
        
        // Check if course exists
        $stmt = $this->db->prepare("SELECT id, title, slug FROM courses WHERE id = ? AND status = 'published'");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $course = $stmt->get_result()->fetch_assoc();
        
        if (!$course) {
            Session::flash('error', 'Course not found.');
            Router::redirect('courses');
            return;
        }

        // Check if course is paid
        $stmt = $this->db->prepare("SELECT price FROM courses WHERE id = ?");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $coursePrice = $stmt->get_result()->fetch_assoc()['price'] ?? 0;

        if ($coursePrice > 0) {
            // Redirect to payment initialization
            // This ensures users can't bypass payment by hitting /enroll/X directly
            $_POST['course_id'] = $courseId; // Set expected POST var for PaymentController
            require_once APP_ROOT . '/controllers/PaymentController.php';
            $payment = new PaymentController();
            return $payment->initialize();
        }
        
        // Check if already enrolled
        $stmt = $this->db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $userId, $courseId);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            Session::flash('info', 'You are already enrolled in this course.');
            Router::redirect('learn/' . ($course['slug'] ?? $course['id']));
            return;
        }
        
        // Create enrollment
        $stmt = $this->db->prepare("INSERT INTO enrollments (user_id, course_id, enrolled_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $userId, $courseId);
        
        if ($stmt->execute()) {
            // Update enrolled_count
            $this->db->query("UPDATE courses SET enrolled_count = enrolled_count + 1 WHERE id = $courseId");
            
            Session::flash('success', 'Successfully enrolled in "' . $course['title'] . '"!');
            Router::redirect('learn/' . ($course['slug'] ?? $course['id']));
        } else {
            Session::flash('error', 'Failed to enroll. Please try again.');
            Router::redirect('course/' . ($course['slug'] ?? $course['id']));
        }
    }
    
    /**
     * Mark lesson as complete
     */
    public function completeLesson($lessonId) {
        if (!validateCsrf() && !isAjax()) {
            return jsonResponse(['error' => 'Invalid request'], 400);
        }
        
        $userId = Auth::id();
        
        // Check if lesson exists and user is enrolled
        $stmt = $this->db->prepare("
            SELECT l.id, l.course_id 
            FROM lessons l
            JOIN enrollments e ON l.course_id = e.course_id
            WHERE l.id = ? AND e.user_id = ?
        ");
        $stmt->bind_param("ii", $lessonId, $userId);
        $stmt->execute();
        $lesson = $stmt->get_result()->fetch_assoc();
        
        if (!$lesson) {
            return jsonResponse(['error' => 'Lesson not found or not enrolled'], 404);
        }
        
        // Mark as complete (upsert)
        $stmt = $this->db->prepare("
            INSERT INTO lesson_progress (user_id, lesson_id, completed, completed_at)
            VALUES (?, ?, 1, NOW())
            ON DUPLICATE KEY UPDATE completed = 1, completed_at = NOW()
        ");
        $stmt->bind_param("ii", $userId, $lessonId);
        $stmt->execute();
        
        // Check if all lessons in course are complete
        $courseId = $lesson['course_id'];
        $stmt = $this->db->prepare("
            SELECT 
                (SELECT COUNT(*) FROM lessons WHERE course_id = ?) as total,
                (SELECT COUNT(*) FROM lesson_progress lp 
                 JOIN lessons l ON lp.lesson_id = l.id 
                 WHERE l.course_id = ? AND lp.user_id = ? AND lp.completed = 1) as completed
        ");
        $stmt->bind_param("iii", $courseId, $courseId, $userId);
        $stmt->execute();
        $progress = $stmt->get_result()->fetch_assoc();
        
        $percentComplete = $progress['total'] > 0 
            ? round(($progress['completed'] / $progress['total']) * 100) 
            : 0;
        
        // If 100% complete, mark course as completed
        if ($percentComplete >= 100) {
            $stmt = $this->db->prepare("UPDATE enrollments SET completed = 1, completed_at = NOW() WHERE user_id = ? AND course_id = ?");
            $stmt->bind_param("ii", $userId, $courseId);
            $stmt->execute();
            
            // Generate certificate if not exists
            $this->generateCertificate($userId, $courseId);
        }
        
        return jsonResponse([
            'success' => true,
            'progress' => $percentComplete,
            'completed' => $percentComplete >= 100
        ]);
    }
    
    /**
     * Generate certificate for completed course
     */
    private function generateCertificate($userId, $courseId) {
        // Check if certificate already exists
        $stmt = $this->db->prepare("SELECT id FROM certificates WHERE user_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $userId, $courseId);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            return; // Certificate already exists
        }
        
        // Generate certificate number
        $certNumber = 'DL-' . strtoupper(substr(md5($userId . $courseId . time()), 0, 8));
        
        // Create certificate record
        $stmt = $this->db->prepare("
            INSERT INTO certificates (user_id, course_id, certificate_number, issued_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("iis", $userId, $courseId, $certNumber);
        $stmt->execute();
    }
}
