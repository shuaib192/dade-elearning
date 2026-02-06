<?php
/**
 * PaymentController - Handles Paystack payments
 */

class PaymentController {
    
    private $db;
    private $paystack_secret;

    public function __construct() {
        $this->db = getDB();
        $this->paystack_secret = defined('PAYSTACK_SECRET_KEY') ? PAYSTACK_SECRET_KEY : '';
    }

    /**
     * Initialize payment
     */
    public function initialize() {
        if (!Auth::check()) {
            Session::flash('error', 'Please login to purchase courses.');
            Router::url('login');
            return;
        }

        $courseId = intval($_POST['course_id'] ?? 0);
        $user = Auth::user();

        // Get course details
        $stmt = $this->db->prepare("SELECT id, title, price FROM courses WHERE id = ? AND status = 'published'");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $course = $stmt->get_result()->fetch_assoc();

        if (!$course || $course['price'] <= 0) {
            Session::flash('error', 'Invalid course or course is free.');
            Router::redirect('courses');
            return;
        }

        // Check if already enrolled
        $stmt = $this->db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $user['id'], $courseId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            Session::flash('info', 'You are already enrolled in this course.');
            Router::redirect('learn/' . $courseId);
            return;
        }

        $reference = 'PAY_' . uniqid() . '_' . time();
        $amount = $course['price'] * 100; // Paystack takes amount in kobo/cents

        // Create pending transaction
        $stmt = $this->db->prepare("INSERT INTO transactions (user_id, course_id, amount, payment_reference, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("iids", $user['id'], $courseId, $course['price'], $reference);
        $stmt->execute();

        // Initialize Paystack
        $url = "https://api.paystack.co/transaction/initialize";
        $fields = [
            'email' => $user['email'],
            'amount' => $amount,
            'reference' => $reference,
            'callback_url' => SITE_URL . '/payment/callback',
            'metadata' => [
                'course_id' => $courseId,
                'user_id' => $user['id']
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $this->paystack_secret,
            "Cache-Control: no-cache",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $result = json_decode($response, true);
        curl_close($ch);

        if ($result && $result['status']) {
            header('Location: ' . $result['data']['authorization_url']);
            exit;
        } else {
            Session::flash('error', 'Failed to initialize payment: ' . ($result['message'] ?? 'Unknown error'));
            Router::redirect('course/' . $courseId);
        }
    }

    /**
     * Handle payment callback
     */
    public function callback() {
        $reference = $_GET['reference'] ?? '';
        if (!$reference) {
            Session::flash('error', 'Invalid payment reference.');
            Router::redirect('courses');
            return;
        }

        // Verify with Paystack
        $url = "https://api.paystack.co/transaction/verify/" . rawurlencode($reference);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $this->paystack_secret,
            "Cache-Control: no-cache",
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $result = json_decode($response, true);
        curl_close($ch);

        if ($result && $result['status'] && $result['data']['status'] === 'success') {
            $metadata = $result['data']['metadata'];
            $courseId = $metadata['course_id'];
            $userId = $metadata['user_id'];
            $amount = $result['data']['amount'] / 100;

            // Update transaction
            $stmt = $this->db->prepare("UPDATE transactions SET status = 'success' WHERE payment_reference = ?");
            $stmt->bind_param("s", $reference);
            $stmt->execute();

            // Enroll user
            $stmt = $this->db->prepare("INSERT IGNORE INTO enrollments (user_id, course_id, enrolled_at) VALUES (?, ?, NOW())");
            $stmt->bind_param("ii", $userId, $courseId);
            if ($stmt->execute() && $this->db->affected_rows > 0) {
                // Update enrolled_count
                $this->db->query("UPDATE courses SET enrolled_count = enrolled_count + 1 WHERE id = $courseId");
            }

            // Get course slug for redirect
            $slugStmt = $this->db->prepare("SELECT slug FROM courses WHERE id = ?");
            $slugStmt->bind_param("i", $courseId);
            $slugStmt->execute();
            $courseSlug = $slugStmt->get_result()->fetch_assoc()['slug'] ?? '';

            Session::flash('success', 'Payment successful! You are now enrolled.');
            Router::redirect('learn/' . $courseSlug);
        } else {
            // Update transaction to failed
            $stmt = $this->db->prepare("UPDATE transactions SET status = 'failed' WHERE payment_reference = ?");
            $stmt->bind_param("s", $reference);
            $stmt->execute();

            Session::flash('error', 'Payment failed or was cancelled.');
            Router::redirect('courses');
        }
    }
}
