<?php
// --- SETUP ---
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/tcpdf/tcpdf.php';

// --- VALIDATE & FETCH DATA ---
if (!isset($_GET['code']) || empty($_GET['code'])) { die("Invalid certificate code."); }
$certificate_code = $_GET['code'];

$sql = "SELECT u.username as student_name, c.title as course_title, cert.issue_date
        FROM certificates cert
        JOIN users u ON cert.student_id = u.id
        JOIN courses c ON cert.course_id = c.id
        WHERE cert.certificate_code = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $certificate_code);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) { die("Certificate not found."); }
$cert_data = $result->fetch_assoc();
$stmt->close();

// ==================================================================
//               START PDF GENERATION WITH TCPDF
// ==================================================================

class DADE_PDF extends TCPDF {
    public function Header() {}
    public function Footer() {}
}

$pdf = new DADE_PDF('L', 'mm', 'A4', true, 'UTF-8', false);

$pdf->SetCreator('DADE Foundation');
$pdf->SetAuthor('DADE Foundation');
$pdf->SetTitle('Certificate of Completion - ' . $cert_data['student_name']);
$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();

// --- 1. DRAW THE BACKGROUND ELEMENTS ---
$pdf->SetFillColor(0, 95, 115);
$pdf->Rect(0, 0, 297, 60, 'F');
$pdf->SetLineStyle(['width' => 1, 'color' => [255, 183, 3]]);
$pdf->Curve(0, 55, 99, 70, 198, 55, 297, 70);

// --- 2. PLACE THE STATIC IMAGES ---
$pdf->Image(__DIR__ . '/assets/images/gold_seal.png', 240, 10, 45, 45, 'PNG');

// THIS IS THE NEWLY ADDED LOGO
// IMPORTANT: You MUST have your logo saved as 'dade_logo.png' in '/assets/images/'
$pdf->Image(__DIR__ . '/assets/images/logo.png', 127, 65, 45, 0, 'PNG');


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
$pdf->Cell(297, 15, e($cert_data['student_name']), 0, 1, 'C');

// Completion Text (Part 1)
$pdf->SetFont('helvetica', '', 11);
$pdf->SetTextColor(80, 80, 80);
$pdf->SetXY(0, 120);
$pdf->Cell(297, 10, 'has successfully completed the course', 0, 1, 'C');

// Course Name (Bigger, Bolder, and on its own line)
$pdf->SetFont('helvetica', 'B', 22);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(0, 130);
$pdf->Cell(297, 10, '"' . e($cert_data['course_title']) . '"', 0, 1, 'C');

// Completion Text (Part 2)
$pdf->SetFont('helvetica', '', 11);
$pdf->SetTextColor(80, 80, 80);
$pdf->SetXY(0, 142);
$pdf->Cell(297, 10, 'conducted by DADE Foundation.', 0, 1, 'C');


// --- 4. DATE, QR CODE, AND VERIFICATION ID ---
$pdf->SetY(170);

// Date Issued
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(297, 10, 'Date Issued', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(297, 5, date('F j, Y', strtotime($cert_data['issue_date'])), 0, 1, 'C');

// Verification ID
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetY(170);
$pdf->SetX(210);
$pdf->Cell(80, 10, 'Verification ID', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetX(210);
$pdf->Cell(80, 5, e($certificate_code), 0, 1, 'C');


// QR Code
$qr_data = "Student: " . e($cert_data['student_name']) . "\n" .
           "Course: " . e($cert_data['course_title']) . "\n" .
           "ID: " . e($certificate_code);
$style = ['border' => false, 'padding' => 1, 'fgcolor' => [0,0,0]];
$pdf->write2DBarcode($qr_data, 'QRCODE,M', 20, 165, 30, 30, $style, 'N');


// --- 5. Output the PDF to the browser ---
$pdf->Output('DADE_Certificate.pdf', 'I');
exit();
?>