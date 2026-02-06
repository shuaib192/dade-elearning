<?php
/**
 * DADE Learn - AI Study Assistant Controller
 * Handles chat requests using Groq API with context-aware learning
 */

class AiController {
    
    private $db;
    private $user;
    
    public function __construct() {
        $this->db = getDB();
        $this->user = Auth::user();
    }
    
    /**
     * Handle chat message
     */
    public function chat() {
        header('Content-Type: application/json');
        
        // Require authentication
        if (!$this->user) {
            echo json_encode(['error' => 'Please log in to use the AI assistant']);
            return;
        }
        
        // Get message from request
        $input = json_decode(file_get_contents('php://input'), true);
        $message = trim($input['message'] ?? '');
        $courseId = intval($input['course_id'] ?? 0);
        $lessonId = intval($input['lesson_id'] ?? 0);
        
        if (empty($message)) {
            echo json_encode(['error' => 'Please enter a message']);
            return;
        }
        
        // Build context
        $context = $this->buildContext($courseId, $lessonId);
        
        // Create prompt
        $systemPrompt = $this->getSystemPrompt($context);
        
        // Call Groq API
        $response = $this->callGroq($systemPrompt, $message);
        
        if (isset($response['error'])) {
            echo json_encode(['error' => $response['error']]);
            return;
        }
        
        echo json_encode([
            'success' => true,
            'response' => $response['content']
        ]);
    }
    
    /**
     * Build context from user's current course and lesson
     */
    private function buildContext($courseId, $lessonId) {
        $context = [
            'user_name' => $this->user['username'] ?? 'Student',
            'course' => null,
            'lesson' => null,
            'enrolled_courses' => [],
            'completed_courses' => 0
        ];
        
        // Get user's enrolled courses
        $stmt = $this->db->prepare("
            SELECT c.id, c.title, c.description, e.completed
            FROM enrollments e
            JOIN courses c ON e.course_id = c.id
            WHERE e.user_id = ?
            ORDER BY e.enrolled_at DESC
            LIMIT 10
        ");
        $stmt->bind_param("i", $this->user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $context['enrolled_courses'][] = $row['title'];
            if ($row['completed']) $context['completed_courses']++;
        }
        
        // Get current course details
        if ($courseId) {
            $stmt = $this->db->prepare("
                SELECT id, title, description
                FROM courses WHERE id = ?
            ");
            $stmt->bind_param("i", $courseId);
            $stmt->execute();
            $context['course'] = $stmt->get_result()->fetch_assoc();
        }
        
        // Get current lesson details
        if ($lessonId) {
            $stmt = $this->db->prepare("
                SELECT id, title, content
                FROM lessons WHERE id = ?
            ");
            $stmt->bind_param("i", $lessonId);
            $stmt->execute();
            $lesson = $stmt->get_result()->fetch_assoc();
            if ($lesson) {
                // Truncate content to avoid token limits
                $lesson['content'] = substr(strip_tags($lesson['content'] ?? ''), 0, 2000);
                $context['lesson'] = $lesson;
            }
        }
        
        return $context;
    }
    
    /**
     * Create system prompt with context
     */
    private function getSystemPrompt($context) {
        $prompt = "You are DADE AI, a friendly and knowledgeable study assistant for the DADE Learn e-learning platform. 
Your role is to help students understand their course material, answer questions, and provide guidance.

IMPORTANT GUIDELINES:
- Be encouraging and supportive
- Give clear, concise explanations
- Use examples when helpful
- If you don't know something, say so honestly
- Keep responses focused and not too long
- Use markdown formatting for better readability

STUDENT CONTEXT:
- Name: {$context['user_name']}
- Enrolled in: " . (count($context['enrolled_courses']) ? implode(', ', $context['enrolled_courses']) : 'No courses yet') . "
- Completed courses: {$context['completed_courses']}";

        if ($context['course']) {
            $prompt .= "\n\nCURRENT COURSE: {$context['course']['title']}
Description: " . substr($context['course']['description'] ?? '', 0, 500);
        }
        
        if ($context['lesson']) {
            $prompt .= "\n\nCURRENT LESSON: {$context['lesson']['title']}
Content Summary: {$context['lesson']['content']}";
        }
        
        $prompt .= "\n\nAnswer the student's question based on this context. If they ask about something outside their current course, help them but gently guide them back to their studies.";
        
        return $prompt;
    }
    
    /**
     * Call Groq API
     */
    private function callGroq($systemPrompt, $userMessage) {
        $url = 'https://api.groq.com/openai/v1/chat/completions';
        
        $data = [
            'model' => GROQ_MODEL,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userMessage]
            ],
            'temperature' => 0.7,
            'max_tokens' => 1024
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . GROQ_API_KEY
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return ['error' => 'Connection error. Please try again.'];
        }
        
        $result = json_decode($response, true);
        
        if ($httpCode !== 200) {
            $errorMsg = $result['error']['message'] ?? 'API error occurred';
            return ['error' => $errorMsg];
        }
        
        $content = $result['choices'][0]['message']['content'] ?? '';
        
        if (empty($content)) {
            return ['error' => 'No response received'];
        }
        
        return ['content' => $content];
    }
}
