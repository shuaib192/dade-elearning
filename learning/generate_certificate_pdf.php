<?php
// generate_certificate_pdf.php
// --- SETUP ---
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/tcpdf/tcpdf.php';

// --- VALIDATE & FETCH DATA ---
if (!isset($_GET['code']) || empty($_GET['code'])) { die("Invalid code."); }
$certificate_code = $_GET['code'];

$sql = "SELECT u.username as student_name, c.title as course_title, cert.issue_date, c.instructor_id
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

// --- PREPARE DATA FOR QR CODE ---
$qr_data = [
    'student_name' => $cert_data['student_name'],
    'course_title' => $cert_data['course_title'],
    'issue_date' => date('Y-m-d', strtotime($cert_data['issue_date'])),
    'certificate_id' => $certificate_code,
    'verification_url' => 'http://learning.dadefoundation.com/verify.php?code=' . urlencode($certificate_code)
];
// Convert the array to a JSON string to embed in the QR code
$qr_code_string = json_encode($qr_data);


// ==================================================================
//               START PDF GENERATION WITH TCPDF
// ==================================================================

class DADE_PDF extends TCPDF {
    public function Header() {}
    public function Footer() {}
}

$pdf = new DADE_PDF('L', 'px', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('DADE Foundation');
$pdf->SetAuthor('DADE Foundation');
$pdf->SetTitle('Certificate - ' . $cert_data['student_name']);
$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();

// --- Background Image ---
// YOU MUST CREATE a 'certificate_template.png' image and place it in '/assets/images/'
// This template should have everything EXCEPT the student name, course name, and QR code.
$pdf->Image(__DIR__ . '/assets/images/certificate_template.png', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), 'PNG', '', '', false, 300, '', false, false, 0);

// --- Dynamic Text ---
// Student Name
$pdf->SetFont('helvetica', 'B', 40);
$pdf->SetTextColor(0, 95, 115);
$pdf->SetXY(0, 270);
$pdf->Cell(842, 0, e($cert_data['student_name']), 0, 1, 'C');

// Course Name
$pdf->SetFont('helvetica', '', 20);
$pdf->SetTextColor(51, 51, 51);
$pdf->SetXY(0, 365);
$pdf->Cell(842, 0, e($cert_data['course_title']), 0, 1, 'C');

// --- QR Code ---
$style = ['border' => false, 'padding' => 0, 'fgcolor' => [0,0,0], 'bgcolor' => false];
// Write the JSON string into the QR Code
$pdf->write2DBarcode($qr_code_string, 'QRCODE,M', 740, 470, 60, 60, $style, 'N');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetXY(740, 532);
$pdf->Cell(60, 0, 'Scan to Verify', 0, 0, 'C');


// --- Output the PDF ---
$pdf->Output('DADE_Certificate.pdf', 'I');
exit();
?>