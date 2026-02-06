<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../phpmailer/Exception.php';
require_once __DIR__ . '/../phpmailer/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/SMTP.php';

/**
 * Sends an email using PHPMailer or falls back to PHP mail().
 * 
 * @param string $to Recipient email address.
 * @param string $subject Email subject.
 * @param string $body Email content (HTML).
 * @param bool $debug Set to true to return the error message on failure.
 * @return bool|string True on success, error message string on failure if $debug is true.
 */
function send_email($to, $subject, $body, $debug = false) {
    $mail = new PHPMailer(true);
    $last_error = "";

    // 1. TRY SMTP FIRST
    try {
        // Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
        $mail->isSMTP();
        $mail->Host       = 'mail.dadefoundation.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'noreply@dadefoundation.com'; 
        $mail->Password   = 'Impact2025@!';    
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SSL for port 465
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('noreply@dadefoundation.com', 'DADE Foundation');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        $mail->send();
        return true;
    } catch (Exception $e) {
        $last_error = $mail->ErrorInfo ?: $e->getMessage();
        
        // 2. FALLBACK TO NATIVE PHP MAIL()
        try {
            $mail->isMail(); // Set to use PHP's mail() function
            $mail->send();
            return true;
        } catch (Exception $e2) {
            $last_error .= " | Fallback failed: " . $mail->ErrorInfo;
        }
    }

    return $debug ? $last_error : false;
}
