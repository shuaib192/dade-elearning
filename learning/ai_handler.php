<?php
// --- SETUP AND SECURITY ---
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Ensure user is authenticated for all AI actions
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Authentication required. Please log in to use the AI Assistant.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['action'])) {
    echo json_encode(['error' => 'Invalid action provided.']);
    exit();
}

// --- ACTION ROUTER ---
switch ($input['action']) {
    case 'ask_question':
        handle_ask_question($input, $db);
        break;
    case 'generate_quiz':
        handle_generate_quiz($input, $db);
        break;
    case 'summarize_content':
        handle_summarize_content($input, $db);
        break;
    case 'generate_subtitles':
        handle_generate_subtitles_assemblyai($input, $db);
        break;
    default:
        echo json_encode(['error' => 'Unknown action.']);
}

// ==================================================================
//               HELPER FUNCTION FOR CALLING GEMINI API
// ==================================================================
function call_gemini_api($prompt) {
    // ===============================================================
    //      IMPORTANT: PASTE YOUR GOOGLE (GEMINI) API KEY HERE
    // ===============================================================
    $apiKey = "AIzaSyDpZGCFhVqjtP4OO5Soet1KZXxUo126nCQ"; 
    
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;
    $data = ['contents' => [['parts' => [['text' => $prompt]]]]];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    if (curl_errno($ch)) { $error_msg = curl_error($ch); curl_close($ch); return ['error' => 'cURL error: ' . $error_msg]; }
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpcode != 200) { return ['error' => 'Gemini API returned status code: ' . $httpcode]; }
    $result = json_decode($response, true);
    $text_response = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
    if ($text_response === null) { return ['error' => 'Unexpected response format from Gemini API.']; }
    return ['text' => $text_response];
}

// ==================================================================
//               HELPER FUNCTION FOR CALLING ASSEMBLYAI API
// ==================================================================
function call_assemblyai_api($endpoint, $method = 'POST', $data = null) {
    // ===============================================================
    //      IMPORTANT: PASTE YOUR ASSEMBLYAI API KEY HERE
    // ===============================================================
    $apiKey = "9e7d493d407547669e6114013376974c";
    
    $url = 'https://api.assemblyai.com/v2' . $endpoint;
    $headers = ['Authorization: ' . $apiKey, 'Content-Type: application/json'];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) { curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); }
    } else { curl_setopt($ch, CURLOPT_POST, false); }
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if (strpos($endpoint, '/vtt') !== false) { return ['status' => $httpcode, 'response' => $response]; }
    return ['status' => $httpcode, 'response' => json_decode($response, true)];
}

// ==================================================================
//               FUNCTION TO HANDLE STUDENT QUESTIONS
// ==================================================================
function handle_ask_question($input, $db) {
    $user_question = trim($input['question']);
    $lesson_id = filter_var($input['lesson_id'] ?? null, FILTER_VALIDATE_INT);
    $lesson_context = "General knowledge question.";
    if ($lesson_id) {
        $stmt = $db->prepare("SELECT content_text FROM lessons WHERE id = ?");
        $stmt->bind_param("i", $lesson_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) { $fetched_context = $result->fetch_assoc()['content_text']; if (!empty(trim($fetched_context))) { $lesson_context = $fetched_context; } }
        $stmt->close();
    }
    if(empty($user_question)) { echo json_encode(['answer' => 'Hello! How can I help?']); exit(); }
    $prompt = "You are a friendly AI tutor for DADE Foundation.\n\n--- CONTEXT ---\n" . $lesson_context . "\n\n--- QUESTION ---\n" . $user_question . "\n\n--- ANSWER ---";
    $api_response = call_gemini_api($prompt);
    echo json_encode(isset($api_response['error']) ? $api_response : ['answer' => $api_response['text']]);
    exit();
}

// ==================================================================
//                FUNCTION TO HANDLE AI QUIZ GENERATION
// ==================================================================
function handle_generate_quiz($input, $db) {
    if (!in_array($_SESSION['role_id'], [1, 2])) { echo json_encode(['error' => 'Permission denied.']); exit(); }
    $course_id = filter_var($input['course_id'], FILTER_VALIDATE_INT);
    $quiz_id = filter_var($input['quiz_id'], FILTER_VALIDATE_INT);
    if (!$course_id || !$quiz_id) { echo json_encode(['error' => 'Missing course or quiz ID.']); exit(); }
    $context = "";
    $stmt = $db->prepare("SELECT title, content_text FROM lessons WHERE course_id = ? AND content_type = 'text' AND content_text IS NOT NULL AND content_text != ''");
    $stmt->bind_param("i", $course_id); $stmt->execute(); $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) { $context .= "Lesson: " . $row['title'] . "\nContent: " . $row['content_text'] . "\n\n---\n\n"; }
    $stmt->close();
    if (empty($context)) { echo json_encode(['error' => 'No text lessons found to generate questions from.']); exit(); }
    $prompt = "Generate exactly 3 multiple-choice questions from the course material. Format the response as a single, valid JSON array of objects. Each object must have keys 'question' (string) and 'options' (array of 4 strings). The VERY FIRST option MUST be the correct answer. Do not include markdown.\n\n--- MATERIAL ---\n" . $context;
    $api_response = call_gemini_api($prompt);
    if (isset($api_response['error'])) { echo json_encode($api_response); exit(); }
    $generated_questions = json_decode($api_response['text'], true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($generated_questions)) { echo json_encode(['error' => 'AI returned an invalid format.']); exit(); }
    $db->begin_transaction();
    try {
        foreach ($generated_questions as $q_data) {
            if (!isset($q_data['question'], $q_data['options']) || !is_array($q_data['options'])) continue;
            $q_stmt = $db->prepare("INSERT INTO quiz_questions (quiz_id, question_text, question_type) VALUES (?, ?, 'mcq')");
            $q_stmt->bind_param("is", $quiz_id, $q_data['question']);
            $q_stmt->execute();
            $question_id = $q_stmt->insert_id; $q_stmt->close();
            $options = $q_data['options'];
            $correct_option_text = $options;
            shuffle($options);
            $opt_stmt = $db->prepare("INSERT INTO question_options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
            foreach ($options as $option_text) {
                if (!empty(trim($option_text))) {
                    $is_correct = ($option_text == $correct_option_text) ? 1 : 0;
                    $opt_stmt->bind_param("isi", $question_id, $option_text, $is_correct);
                    $opt_stmt->execute();
                }
            }
            $opt_stmt->close();
        }
        $db->commit();
        echo json_encode(['success' => true, 'message' => count($generated_questions) . ' questions generated!']);
    } catch (Exception $e) { $db->rollback(); echo json_encode(['error' => 'Database error.']); }
    exit();
}

// ==================================================================
//                FUNCTION TO HANDLE LESSON SUMMARIZATION
// ==================================================================
function handle_summarize_content($input, $db) {
    $lesson_id = filter_var($input['lesson_id'], FILTER_VALIDATE_INT);
    if (!$lesson_id) { echo json_encode(['error' => 'Invalid lesson ID.']); exit(); }
    $stmt = $db->prepare("SELECT title, content_text FROM lessons WHERE id = ? AND content_type IN ('text', 'assignment')");
    $stmt->bind_param("i", $lesson_id); $stmt->execute(); $result = $stmt->get_result();
    if ($result->num_rows === 0) { echo json_encode(['error' => 'No summarizable content found.']); exit(); }
    $lesson = $result->fetch_assoc(); $stmt->close();
    if (empty(trim($lesson['content_text']))) { echo json_encode(['error' => 'This lesson is empty.']); exit(); }
    $prompt = "Summarize the following lesson content into 3-5 key bullet points.\n\n--- CONTENT ---\n" . $lesson['content_text'] . "\n\n--- SUMMARY ---";
    $api_response = call_gemini_api($prompt);
    echo json_encode(isset($api_response['error']) ? $api_response : ['summary' => $api_response['text']]);
    exit();
}

// ==================================================================
//             FUNCTION TO HANDLE ACCURATE SUBTITLE GENERATION
// ==================================================================
function handle_generate_subtitles_assemblyai($input, $db) {
    if (!in_array($_SESSION['role_id'], [1, 2])) { echo json_encode(['error' => 'Permission denied.']); exit(); }
    $lesson_id = filter_var($input['lesson_id'], FILTER_VALIDATE_INT);
    $transcript_id = $input['transcript_id'] ?? null;
    if (!$lesson_id) { echo json_encode(['error' => 'Invalid lesson ID.']); exit(); }
    
    if (!$transcript_id) {
        $stmt = $db->prepare("SELECT file_path FROM lesson_media WHERE lesson_id = ?");
        $stmt->bind_param("i", $lesson_id); $stmt->execute(); $media = $stmt->get_result()->fetch_assoc();
        if (!$media || empty($media['file_path'])) { echo json_encode(['error' => 'No video file found for this lesson.']); exit(); }
        
        // FINAL FIX: Hard-code the protocol to http://
        $protocol = "http://";
        $domain = $_SERVER['SERVER_NAME'];
        $video_url = $protocol . $domain . $media['file_path'];

        $api_response = call_assemblyai_api('/transcript', 'POST', ['audio_url' => $video_url]);
        if ($api_response['status'] == 200 && isset($api_response['response']['id'])) {
            echo json_encode(['status' => 'submitted', 'transcript_id' => $api_response['response']['id']]);
        } else { $error_details = $api_response['response']['error'] ?? 'Unknown API error.'; echo json_encode(['error' => 'Failed to submit video to AI service. Reason: ' . $error_details, 'url_tried' => $video_url]); }
        exit();
    } else {
        $api_response = call_assemblyai_api('/transcript/' . $transcript_id, 'GET');
        if ($api_response['status'] != 200) { echo json_encode(['error' => 'Could not get status.', 'details' => $api_response['response']]); exit(); }
        $response_data = $api_response['response'];
        if ($response_data['status'] === 'completed') {
            $vtt_response_raw = call_assemblyai_api('/transcript/' . $transcript_id . '/vtt', 'GET');
            $vtt_content = $vtt_response_raw['response'];
            if (empty($vtt_content) || $vtt_response_raw['status'] != 200) { echo json_encode(['error' => 'Failed to retrieve VTT data.']); exit(); }
            $vtt_dir_path = '/content/subtitles/';
            $vtt_filename = 'lesson_' . $lesson_id . '.vtt';
            $vtt_target_path = __DIR__ . '/..' . $vtt_dir_path . $vtt_filename;
            if (file_put_contents($vtt_target_path, $vtt_content)) {
                $db_path = $vtt_dir_path . $vtt_filename;
                $update_stmt = $db->prepare("UPDATE lesson_media SET subtitle_path = ? WHERE lesson_id = ?");
                $update_stmt->bind_param("si", $db_path, $lesson_id);
                $update_stmt->execute(); $update_stmt->close();
                echo json_encode(['status' => 'completed', 'message' => 'Subtitles generated successfully!']);
            } else { echo json_encode(['error' => 'Failed to save subtitle file. Check permissions for /content/subtitles/.']); }
        } else if ($response_data['status'] === 'error') {
            echo json_encode(['status' => 'error', 'error' => $response_data['error']]);
        } else { echo json_encode(['status' => $response_data['status']]); }
        exit();
    }
}
?>