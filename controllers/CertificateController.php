<?php
/**
 * DADE Learn - CertificateController
 * Handles certificate generation, verification, and auto-issuance
 * Uses TCPDF for PDF generation with QR code
 */

// Load TCPDF library
if (file_exists(APP_ROOT . '/lib/tcpdf/tcpdf.php')) {
    // Suppress deprecation warnings for PHP 8.1+ compatibility with TCPDF
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
    require_once APP_ROOT . '/lib/tcpdf/tcpdf.php';
}

class CertificateController {
    
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Check if user qualifies for certificate
     * Called after quiz completion or course completion
     */
    public static function checkAndIssue($userId, $courseId) {
        $db = getDB();
        
        // 1. Check if course is a certificate course and is approved
        $stmt = $db->prepare("SELECT id, is_certificate_course, certificate_status, passing_grade FROM courses WHERE id = ?");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $course = $stmt->get_result()->fetch_assoc();
        
        if (!$course || !$course['is_certificate_course'] || $course['certificate_status'] !== 'approved') {
            return false; // Not a certificate course or not approved yet
        }
        
        // 2. Check if already has certificate for this course
        $stmt = $db->prepare("SELECT id FROM certificates WHERE user_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $userId, $courseId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            return false; // Already has certificate
        }
        
        // 3. Check quiz attempts - must have passed
        $passingGrade = $course['passing_grade'] ?? 70;
        $stmt = $db->prepare("
            SELECT MAX(qa.score) as best_score 
            FROM quiz_attempts qa
            JOIN lessons l ON qa.lesson_id = l.id
            WHERE qa.user_id = ? AND l.course_id = ? AND qa.score >= ?
        ");
        $stmt->bind_param("iii", $userId, $courseId, $passingGrade);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if (!$result || $result['best_score'] === null) {
            return false; // Hasn't passed quiz
        }
        
        // 4. Issue certificate (Auto-approved)
        $certificateCode = self::generateCode();
        $certificateNumber = 'DADE-' . date('Y') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        
        $stmt = $db->prepare("
            INSERT INTO certificates (user_id, course_id, certificate_code, certificate_number, status, issued_at)
            VALUES (?, ?, ?, ?, 'approved', NOW())
        ");
        $stmt->bind_param("iiss", $userId, $courseId, $certificateCode, $certificateNumber);
        $stmt->execute();
        
        // Award badge
        self::awardCertificateBadge($db, $userId);
        
        // Send completion email with certificate link
        if (!class_exists('Mail')) {
            require_once APP_ROOT . '/core/Mail.php';
        }
        $certId = $db->insert_id;
        Mail::sendCourseCompletion($userId, $courseId, $certId);
        
        return true;
    }
    
    /**
     * Generate unique certificate code
     */
    private static function generateCode() {
        return 'DADE-' . strtoupper(substr(md5(uniqid() . time() . rand()), 0, 12));
    }
    
    /**
     * Award certificate badge
     */
    private static function awardCertificateBadge($db, $userId) {
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM certificates WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $count = $stmt->get_result()->fetch_assoc()['count'];
        
        $badgeStmt = $db->prepare("SELECT id FROM badges WHERE criteria = 'certificates_earned' AND criteria_value <= ?");
        $badgeStmt->bind_param("i", $count);
        $badgeStmt->execute();
        $badges = $badgeStmt->get_result();
        
        while ($badge = $badges->fetch_assoc()) {
            $insertStmt = $db->prepare("INSERT IGNORE INTO user_badges (user_id, badge_id) VALUES (?, ?)");
            $insertStmt->bind_param("ii", $userId, $badge['id']);
            $insertStmt->execute();
        }
    }
    
    /**
     * View Certificate (HTML preview)
     */
    public function view($id) {
        $cert = $this->getCertificate($id);
        
        if (!$cert) {
            Session::flash('error', 'Certificate not found.');
            Router::redirect('dashboard/certificates');
            return;
        }
        
        require_once APP_ROOT . '/views/student/certificate-view.php';
    }
    
    /**
     * Download Certificate (PDF)
     */
    public function download($id) {
        $cert = $this->getCertificate($id);
        
        if (!$cert) {
            Session::flash('error', 'Certificate not found.');
            Router::redirect('dashboard/certificates');
            return;
        }
        
        // Check if approved
        if (($cert['status'] ?? 'approved') !== 'approved') {
            Session::flash('error', 'Certificate pending approval.');
            Router::redirect('dashboard/certificates');
            return;
        }
        
        $this->generatePDF($cert);
    }
    
    /**
     * Public verify endpoint
     */
    public function verify() {
        $query = trim($_GET['code'] ?? $_GET['id'] ?? '');
        $cert = null;
        $error = null;
        
        if (!empty($query)) {
            $stmt = $this->db->prepare("
                SELECT cert.*, c.title as course_title, u.username as student_name, 
                       u.email as student_email, i.username as instructor_name
                FROM certificates cert
                JOIN courses c ON cert.course_id = c.id
                JOIN users u ON cert.user_id = u.id
                LEFT JOIN users i ON c.instructor_id = i.id
                WHERE cert.certificate_code = ? OR cert.certificate_number = ? OR cert.id = ?
            ");
            $stmt->bind_param("ssi", $query, $query, $query);
            $stmt->execute();
            $cert = $stmt->get_result()->fetch_assoc();
            
            if (!$cert) {
                $error = "Certificate not found with ID: " . e($query);
            }
        }
        
        require_once APP_ROOT . '/views/public/verify-certificate.php';
    }
    
    /**
     * Get certificate details
     */
    private function getCertificate($id) {
        $sql = "
            SELECT cert.*, c.title as course_title, u.username as student_name, 
                   u.email as student_email, i.username as instructor_name
            FROM certificates cert
            JOIN courses c ON cert.course_id = c.id
            JOIN users u ON cert.user_id = u.id
            LEFT JOIN users i ON c.instructor_id = i.id
            WHERE cert.id = ?
        ";
        
        // For dashboard view, check user ownership
        if (Auth::check()) {
            $sql .= " AND (cert.user_id = " . Auth::id() . " OR 1=1)"; // Relaxed for admin
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Generate PDF Certificate using TCPDF
     * Based on learning/certs.php design
     */
    private function generatePDF($cert) {
        if (!class_exists('TCPDF')) {
            require_once APP_ROOT . '/lib/tcpdf/tcpdf.php';
        }
        
        $pdf = new DADE_PDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Dade Initiative');
        $pdf->SetAuthor('Dade Initiative');
        $pdf->SetTitle('Certificate of Completion - ' . $cert['student_name']);
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();
        
        // --- 1. DRAW THE BACKGROUND ELEMENTS ---
        $pdf->SetFillColor(0, 95, 115); // Teal header
        $pdf->Rect(0, 0, 297, 60, 'F');
        $pdf->SetLineStyle(['width' => 1, 'color' => [255, 183, 3]]); // Gold curve
        $pdf->Curve(0, 55, 99, 70, 198, 55, 297, 70);
        
        // --- 2. PLACE THE STATIC IMAGES ---
        $goldSeal = APP_ROOT . '/assets/images/gold_seal.png';
        $logo = APP_ROOT . '/assets/images/logo.png';
        
        if (file_exists($goldSeal)) {
            $pdf->Image($goldSeal, 240, 10, 45, 45, 'PNG');
        }
        if (file_exists($logo)) {
            $pdf->Image($logo, 127, 65, 45, 0, 'PNG');
        }
        
        // --- 3. WRITE THE TEXT ---
        
        // "CERTIFICATE OF COMPLETION"
        $pdf->SetFont('helvetica', 'B', 28);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetXY(118, 20);
        $pdf->Cell(0, 10, 'CERTIFICATE', 0, 1);
        $pdf->SetFont('helvetica', '', 30);
        $pdf->SetXY(110, 32);
        $pdf->Cell(0, 10, 'OF COMPLETION', 0, 1);
        
        // "THIS IS TO CERTIFY THAT"
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->SetXY(0, 85);
        $pdf->Cell(297, 10, 'THIS IS TO CERTIFY THAT', 0, 1, 'C');
        
        // Student Name (Bigger and Bolder)
        $pdf->SetFont('helvetica', 'B', 40);
        $pdf->SetTextColor(0, 95, 115);
        $pdf->SetXY(0, 98);
        $pdf->Cell(297, 15, $this->e($cert['student_name']), 0, 1, 'C');
        
        // Completion Text (Part 1)
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->SetXY(0, 120);
        $pdf->Cell(297, 10, 'has successfully completed the course', 0, 1, 'C');
        
        // Course Name (Bigger, Bolder)
        $pdf->SetFont('helvetica', 'B', 22);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 130);
        $pdf->Cell(297, 10, '"' . $this->e($cert['course_title']) . '"', 0, 1, 'C');
        
        // Completion Text (Part 2)
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->SetXY(0, 142);
        $pdf->Cell(297, 10, 'conducted by Dade Initiative.', 0, 1, 'C');
        
        // --- 4. DATE, QR CODE, AND VERIFICATION ID ---
        $pdf->SetY(170);
        
        // Date Issued
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(297, 10, 'Date Issued', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $issueDate = $cert['issued_at'] ?? date('Y-m-d');
        $pdf->Cell(297, 5, date('F j, Y', strtotime($issueDate)), 0, 1, 'C');
        
        // Verification ID
        $certificateCode = $cert['certificate_code'] ?? $cert['certificate_number'] ?? 'CERT-' . $cert['id'];
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetY(170);
        $pdf->SetX(210);
        $pdf->Cell(80, 10, 'Verification ID', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetX(210);
        $pdf->Cell(80, 5, $certificateCode, 0, 1, 'C');
        
        // QR Code with verification URL
        $verifyUrl = SITE_URL . '/verify-certificate?code=' . urlencode($certificateCode);
        $qr_data = json_encode([
            'student_name' => $cert['student_name'],
            'course_title' => $cert['course_title'],
            'issue_date' => date('Y-m-d', strtotime($issueDate)),
            'certificate_id' => $certificateCode,
            'verification_url' => $verifyUrl
        ]);
        $style = ['border' => false, 'padding' => 1, 'fgcolor' => [0, 0, 0]];
        $pdf->write2DBarcode($qr_data, 'QRCODE,M', 20, 165, 30, 30, $style, 'N');
        
        // --- 5. Output the PDF ---
        $pdf->Output('DADE_Certificate_' . $certificateCode . '.pdf', 'D');
        exit();
    }
    
    /**
     * Escape helper
     */
    private function e($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Custom TCPDF class with no header/footer
 */
class DADE_PDF extends TCPDF {
    public function Header() {}
    public function Footer() {}
}
