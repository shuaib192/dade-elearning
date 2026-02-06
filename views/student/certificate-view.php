<?php
/**
 * DADE Learn - Certificate View
 * High-quality HTML preview of the certificate
 */

$pageTitle = 'View Certificate';
require_once APP_ROOT . '/views/layouts/header.php';

// Data is passed from CertificateController as $cert
?>

<div class="cert-view-container">
    <div class="cert-actions-top">
        <a href="<?php echo url('dashboard/certificates'); ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Certificates
        </a>
        <a href="<?php echo url('certificate/' . $cert['id'] . '/download'); ?>" class="btn btn-primary shadow-lg">
            <i class="fas fa-download"></i> Download PDF
        </a>
    </div>

    <div class="certificate-paper shadow-2xl">
        <div class="cert-inner-overlay">
            <!-- Header Section -->
            <div class="cert-header-teal">
                <div class="cert-header-content">
                    <div class="cert-title-group">
                        <h1 class="cert-main-title">CERTIFICATE</h1>
                        <h2 class="cert-sub-title">OF COMPLETION</h2>
                    </div>
                    <div class="cert-seal-box">
                        <img src="<?php echo url('assets/images/gold_seal.png'); ?>" alt="Gold Seal" class="gold-seal-img">
                    </div>
                </div>
                <!-- Decorative Curve -->
                <svg class="cert-curve" viewBox="0 0 1000 100" preserveAspectRatio="none">
                    <path d="M0,80 C250,110 750,50 1000,80 L1000,100 L0,100 Z" fill="white"></path>
                    <path d="M0,80 C250,110 750,50 1000,80" fill="none" stroke="#FFB703" stroke-width="2"></path>
                </svg>
            </div>

            <!-- Body Section -->
            <div class="cert-body-main">
                <div class="cert-logo-container">
                    <img src="<?php echo url('assets/images/logo.png'); ?>" alt="DADE Logo" class="cert-logo-img">
                </div>

                <p class="cert-certify-text">THIS IS TO CERTIFY THAT</p>
                <h2 class="cert-student-name"><?php echo e($cert['student_name']); ?></h2>
                <p class="cert-course-text">has successfully completed the course</p>
                <h3 class="cert-course-title">"<?php echo e($cert['course_title']); ?>"</h3>
                <p class="cert-foundation-text">conducted by DADE Foundation.</p>
            </div>

            <!-- Footer Section -->
            <div class="cert-footer-content">
                <div class="cert-footer-item">
                    <div class="qr-code-box">
                         <!-- Dynamically generate verification code or QR placeholder -->
                         <div id="qrcode-placeholder"></div>
                    </div>
                </div>
                
                <div class="cert-footer-item center">
                    <div class="cert-sign-line"></div>
                    <p class="cert-meta-label">Date Issued</p>
                    <p class="cert-meta-value"><?php echo date('F j, Y', strtotime($cert['issued_at'])); ?></p>
                </div>

                <div class="cert-footer-item right">
                    <div class="cert-sign-line"></div>
                    <p class="cert-meta-label">Verification ID</p>
                    <p class="cert-meta-value"><?php echo e($cert['certificate_code'] ?? $cert['certificate_number']); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --cert-teal: #005F73;
    --cert-gold: #FFB703;
    --cert-text: #505050;
}

.cert-view-container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px;
}

.cert-actions-top {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
    align-items: center;
}

.certificate-paper {
    background: #fff;
    width: 100%;
    aspect-ratio: 1.414/1; /* A4 Landscape aspect ratio */
    position: relative;
    overflow: hidden;
    color: var(--cert-text);
}

.cert-inner-overlay {
    height: 100%;
    display: flex;
    flex-direction: column;
}

/* Header */
.cert-header-teal {
    background: var(--cert-teal);
    height: 30%;
    position: relative;
    color: white;
    padding: 40px 60px;
}

.cert-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 2;
}

.cert-main-title {
    font-size: 42px;
    font-weight: 800;
    margin: 0;
    letter-spacing: 4px;
}

.cert-sub-title {
    font-size: 48px;
    font-weight: 300;
    margin: 0;
    margin-top: -5px;
}

.gold-seal-img {
    width: 130px;
}

.cert-curve {
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 60px;
    z-index: 1;
}

/* Body */
.cert-body-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 0 60px;
    text-align: center;
}

.cert-logo-container {
    margin-bottom: 20px;
}

.cert-logo-img {
    height: 60px;
}

.cert-certify-text {
    font-size: 16px;
    letter-spacing: 1px;
    margin-bottom: 15px;
}

.cert-student-name {
    font-size: 56px;
    font-weight: 800;
    color: var(--cert-teal);
    margin: 0;
    margin-bottom: 15px;
    text-transform: uppercase;
}

.cert-course-text {
    font-size: 16px;
}

.cert-course-title {
    font-size: 32px;
    font-weight: 700;
    color: #000;
    margin: 10px 0;
}

.cert-foundation-text {
    font-size: 16px;
}

/* Footer */
.cert-footer-content {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    padding: 0 60px 40px;
    align-items: flex-end;
}

.cert-footer-item.center { text-align: center; }
.cert-footer-item.right { text-align: center; }

.cert-sign-line {
    border-top: 1px solid var(--cert-text);
    width: 80%;
    margin: 0 auto 10px;
}

.cert-meta-label {
    font-weight: 700;
    font-size: 14px;
    color: #000;
    margin: 0;
}

.cert-meta-value {
    font-size: 14px;
    margin: 0;
}

.qr-code-box {
    width: 100px;
    height: 100px;
    background: #f5f5f5;
    border: 1px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    color: #999;
}

#qrcode-placeholder::after {
    content: "QR Code for Verification";
    text-align: center;
    display: block;
}

</style>

<!-- QRCode.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
window.addEventListener('load', function() {
    const verifyUrl = "<?php echo SITE_URL . '/verify-certificate?code=' . urlencode($cert['certificate_code'] ?? $cert['certificate_number']); ?>";
    
    // Clear placeholder and generate QR code
    const qrcodeContainer = document.getElementById("qrcode-placeholder");
    qrcodeContainer.innerHTML = "";
    
    new QRCode(qrcodeContainer, {
        text: verifyUrl,
        width: 100,
        height: 100,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
