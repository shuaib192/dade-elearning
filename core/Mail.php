<?php
/**
 * DADE Learn - Mail Helper
 * Sends emails using PHPMailer
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Include PHPMailer files
require_once APP_ROOT . '/lib/phpmailer/Exception.php';
require_once APP_ROOT . '/lib/phpmailer/PHPMailer.php';
require_once APP_ROOT . '/lib/phpmailer/SMTP.php';

class Mail {
    
    /**
     * Send an email
     * 
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $body HTML body content
     * @param array $options Additional options (cc, bcc, attachments)
     * @return bool|string True on success, error message on failure
     */
    public static function send($to, $subject, $body, $options = []) {
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = SMTP_PORT;

            // Extra robust settings for local/shared servers
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->Timeout = 10;
            
            // Recipients
            $mail->setFrom(SMTP_USER, SMTP_FROM_NAME);
            $mail->addAddress($to);
            
            // CC/BCC
            if (isset($options['cc'])) {
                foreach ((array)$options['cc'] as $cc) $mail->addCC($cc);
            }
            if (isset($options['bcc'])) {
                foreach ((array)$options['bcc'] as $bcc) $mail->addBCC($bcc);
            }
            if (isset($options['attachments'])) {
                foreach ((array)$options['attachments'] as $attachment) $mail->addAttachment($attachment);
            }
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = self::wrapInTemplate($body, $subject);
            $mail->AltBody = strip_tags($body);
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("SMTP Error: " . $mail->ErrorInfo);
            
            // Fallback to native PHP mail()
            try {
                $mail->isMail();
                $mail->send();
                return true;
            } catch (Exception $e2) {
                error_log("Mail Fallback Error: " . $mail->ErrorInfo);
                return $mail->ErrorInfo;
            }
        }
    }
    
    /**
     * Wrap email content in a branded template
     */
    private static function wrapInTemplate($content, $title) {
        return '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . htmlspecialchars($title) . '</title>
        </head>
        <body style="margin:0;padding:0;background-color:#f4f6f8;font-family:Arial,sans-serif;">
            <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center" style="padding:40px 20px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width:600px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1);">
                            
                            <!-- Header -->
                            <tr>
                                <td style="padding:30px 40px;background:linear-gradient(135deg, #005f73 0%, #0a9396 100%);text-align:center;">
                                    <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:700;">' . SITE_NAME . '</h1>
                                    <p style="margin:8px 0 0;color:#ffd166;font-size:14px;">' . SITE_TAGLINE . '</p>
                                </td>
                            </tr>
                            
                            <!-- Content -->
                            <tr>
                                <td style="padding:40px;font-size:16px;line-height:1.6;color:#333333;">
                                    ' . $content . '
                                </td>
                            </tr>
                            
                            <!-- Footer -->
                            <tr>
                                <td style="padding:30px 40px;background:#f8fafc;text-align:center;font-size:13px;color:#666666;">
                                    <p style="margin:0 0 10px;">© ' . date('Y') . ' ' . SITE_NAME . '. All rights reserved.</p>
                                    <p style="margin:0;">
                                        <a href="' . SITE_URL . '" style="color:#005f73;text-decoration:none;">Visit our website</a>
                                    </p>
                                </td>
                            </tr>
                            
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>';
    }
    
    /**
     * Send verification email
     */
    public static function sendVerification($email, $name, $token) {
        $verifyUrl = SITE_URL . '/verify?token=' . $token;
        
        $body = '
            <h2 style="color:#005f73;margin:0 0 20px;">Welcome, ' . htmlspecialchars($name) . '!</h2>
            <p>Thank you for joining ' . SITE_NAME . '. Please verify your email address to get started.</p>
            <p style="text-align:center;margin:30px 0;">
                <a href="' . $verifyUrl . '" style="display:inline-block;padding:14px 32px;background:#ffb703;color:#1e293b;text-decoration:none;border-radius:8px;font-weight:600;">Verify Email Address</a>
            </p>
            <p style="font-size:14px;color:#666;">If you didn\'t create an account, please ignore this email.</p>
        ';
        
        return self::send($email, 'Verify your email - ' . SITE_NAME, $body);
    }
    
    /**
     * Send password reset email
     */
    /**
     * Send password reset code
     */
    public static function sendPasswordReset($email, $name, $code) {
        $body = '
            <h2 style="color:#005f73;margin:0 0 20px;">Password Reset Code</h2>
            <p>Hi ' . htmlspecialchars($name) . ',</p>
            <p>We received a request to reset your password. Use the code below to reset it:</p>
            <div style="text-align:center;margin:30px 0;">
                <span style="display:inline-block;padding:15px 30px;background:#f0f4f8;color:#005f73;font-size:24px;letter-spacing:5px;font-weight:700;border-radius:8px;border:2px dashed #005f73;">' . $code . '</span>
            </div>
            <p style="text-align:center;font-size:14px;color:#666;">This code will expire in 1 hour.</p>
            <p style="font-size:14px;color:#666;margin-top:30px;">If you didn\'t request this, please ignore this email.</p>
        ';
        
        return self::send($email, 'Your Password Reset Code - ' . SITE_NAME, $body);
    }

    /**
     * Send course completion email
     */
    public static function sendCourseCompletion($userId, $courseId, $certificateId = null) {
        $db = getDB();
        
        // Fetch user info
        $stmt = $db->prepare("SELECT username, email FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        // Fetch course info
        $stmt = $db->prepare("SELECT title FROM courses WHERE id = ?");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $courseTitle = $stmt->get_result()->fetch_assoc()['title'];
        
        if (!$user) return false;

        $subject = "Congratulations on completing " . $courseTitle . "!";
        
        $certificateSection = "";
        if ($certificateId) {
            $viewUrl = SITE_URL . "/certificate/" . $certificateId;
            $downloadUrl = SITE_URL . "/certificate/" . $certificateId . "/download";
            
            $certificateSection = '
                <div style="background:#f0f9fa; border: 1px solid #0a9396; border-radius: 12px; padding: 25px; margin: 30px 0; text-align: center;">
                    <img src="' . SITE_URL . '/assets/images/gold_seal.png" alt="Gold Seal" style="width: 80px; margin-bottom: 15px;">
                    <h3 style="color:#005f73; margin: 0 0 10px;">Your Certificate is Ready!</h3>
                    <p style="margin: 0 0 20px; font-size: 14px; color: #555;">You have successfully earned a professional certificate for this course.</p>
                    <div style="display: flex; justify-content: center; gap: 15px;">
                        <a href="' . $viewUrl . '" style="display:inline-block; padding:12px 24px; background:#005f73; color:#ffffff; text-decoration:none; border-radius:8px; font-weight:600; font-size: 14px;">View Online</a>
                        <a href="' . $downloadUrl . '" style="display:inline-block; padding:12px 24px; background:#ffb703; color:#1e293b; text-decoration:none; border-radius:8px; font-weight:600; font-size: 14px; margin-left: 10px;">Download PDF</a>
                    </div>
                </div>
            ';
        }

        $body = '
            <h2 style="color:#005f73; margin: 0 0 20px;">You Did It, ' . htmlspecialchars($user['username']) . '!</h2>
            <p>Congratulations! You have successfully completed the course <strong>' . htmlspecialchars($courseTitle) . '</strong> on ' . SITE_NAME . '.</p>
            <p>This is a significant achievement and shows your commitment to continuous learning and growth.</p>
            
            ' . $certificateSection . '
            
            <p>We hope you found the course valuable and that you can apply your new skills effectively. Keep up the momentum by exploring our other courses!</p>
            
            <p style="text-align:center; margin: 30px 0;">
                <a href="' . SITE_URL . '/dashboard/courses" style="display:inline-block; padding:14px 32px; background:#0a9396; color:#ffffff; text-decoration:none; border-radius:8px; font-weight:600;">Explore More Courses</a>
            </p>
        ';
        
        return self::send($user['email'], $subject, $body);
    }
}
