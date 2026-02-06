<?php
/**
 * DADE Learn - Verify Certificate (Public)
 * Supports manual input and QR code scanning
 */
$pageTitle = 'Verify Certificate';
require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="verify-page">
    <div class="container">
        <div class="verify-hero">
            <div class="verify-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1>Certificate Verification</h1>
            <p>Verify the authenticity of Dade Initiative certificates</p>
        </div>
        
        <div class="verify-container">
            <div class="verify-methods">
                <!-- Manual Input -->
                <div class="verify-method active" id="manualMethod">
                    <h3><i class="fas fa-keyboard"></i> Enter Certificate Code</h3>
                    <form action="<?php echo url('verify-certificate'); ?>" method="GET">
                        <div class="input-group">
                            <input type="text" name="code" class="form-control form-control-lg" 
                                   placeholder="e.g., DADE-2026-ABC123" 
                                   value="<?php echo e($_GET['code'] ?? $_GET['id'] ?? ''); ?>" required>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search"></i> Verify
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- QR Scanner -->
                <div class="verify-method" id="qrMethod">
                    <h3><i class="fas fa-qrcode"></i> Scan QR Code</h3>
                    <div class="qr-scanner-container">
                        <div id="qr-scanner" style="width: 100%; max-width: 400px; margin: 0 auto;"></div>
                        <p class="scanner-status"><i class="fas fa-camera"></i> Point camera at certificate QR code</p>
                    </div>
                </div>
                
                <!-- Method Toggle -->
                <div class="method-toggle">
                    <button class="toggle-btn active" data-method="manual">
                        <i class="fas fa-keyboard"></i> Type Code
                    </button>
                    <button class="toggle-btn" data-method="qr">
                        <i class="fas fa-qrcode"></i> Scan QR
                    </button>
                </div>
            </div>
            
            <!-- Result Display -->
            <?php if (isset($cert)): ?>
            <div class="verify-result valid">
                <div class="result-header">
                    <div class="result-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2>Certificate Verified</h2>
                    <span class="status-badge success">Authentic</span>
                </div>
                <div class="result-details">
                    <div class="detail-row">
                        <span class="label">Certificate Holder</span>
                        <span class="value"><?php echo e($cert['student_name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Course Completed</span>
                        <span class="value"><?php echo e($cert['course_title']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Issue Date</span>
                        <span class="value"><?php echo date('F j, Y', strtotime($cert['issued_at'])); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Certificate ID</span>
                        <span class="value cert-id"><?php echo e($cert['certificate_code'] ?? $cert['certificate_number'] ?? $cert['id']); ?></span>
                    </div>
                    <?php if (!empty($cert['instructor_name'])): ?>
                    <div class="detail-row">
                        <span class="label">Instructor</span>
                        <span class="value"><?php echo e($cert['instructor_name']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php elseif (isset($error)): ?>
            <div class="verify-result invalid">
                <div class="result-header">
                    <div class="result-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <h2>Verification Failed</h2>
                    <span class="status-badge error">Not Found</span>
                </div>
                <p class="error-message"><?php echo e($error); ?></p>
                <p class="error-hint">Please check the certificate code and try again.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.verify-page {
    min-height: calc(100vh - var(--header-height));
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
    padding: var(--space-16) 0;
}

.verify-hero {
    text-align: center;
    margin-bottom: var(--space-10);
    color: white;
}

.verify-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--primary), #3b82f6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-6);
    font-size: 48px;
    color: white;
    box-shadow: 0 20px 60px rgba(59, 130, 246, 0.4);
}

.verify-hero h1 {
    font-size: var(--text-4xl);
    margin-bottom: var(--space-3);
}

.verify-hero p {
    color: rgba(255,255,255,0.7);
    font-size: var(--text-lg);
}

.verify-container {
    max-width: 700px;
    margin: 0 auto;
}

.verify-methods {
    background: var(--white);
    border-radius: var(--radius-2xl);
    padding: var(--space-8);
    box-shadow: 0 25px 60px rgba(0,0,0,0.3);
    margin-bottom: var(--space-6);
}

.verify-method {
    display: none;
}

.verify-method.active {
    display: block;
}

.verify-method h3 {
    text-align: center;
    margin-bottom: var(--space-6);
    color: var(--text-secondary);
    font-size: var(--text-lg);
}

.verify-method h3 i {
    margin-right: var(--space-2);
    color: var(--primary);
}

.input-group {
    display: flex;
    gap: var(--space-3);
}

.input-group .form-control {
    flex: 1;
    font-size: var(--text-lg);
    padding: var(--space-4) var(--space-5);
    text-align: center;
    letter-spacing: 1px;
    font-weight: 600;
}

.qr-scanner-container {
    text-align: center;
}

#qr-scanner {
    border-radius: var(--radius-lg);
    overflow: hidden;
    background: var(--gray-100);
    min-height: 300px;
}

.scanner-status {
    margin-top: var(--space-4);
    color: var(--text-muted);
}

.method-toggle {
    display: flex;
    justify-content: center;
    gap: var(--space-3);
    margin-top: var(--space-6);
    padding-top: var(--space-6);
    border-top: 1px solid var(--gray-100);
}

.toggle-btn {
    padding: var(--space-3) var(--space-5);
    border: 2px solid var(--gray-200);
    background: var(--white);
    border-radius: var(--radius-lg);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all var(--transition);
    font-weight: 500;
}

.toggle-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
}

.toggle-btn.active {
    border-color: var(--primary);
    background: var(--primary-50);
    color: var(--primary);
}

.toggle-btn i {
    margin-right: var(--space-2);
}

/* Result Styles */
.verify-result {
    background: var(--white);
    border-radius: var(--radius-2xl);
    padding: var(--space-8);
    box-shadow: 0 25px 60px rgba(0,0,0,0.3);
    text-align: center;
}

.verify-result.valid {
    border-top: 4px solid #10b981;
}

.verify-result.invalid {
    border-top: 4px solid #ef4444;
}

.result-header {
    margin-bottom: var(--space-8);
}

.result-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-4);
    font-size: 40px;
}

.verify-result.valid .result-icon {
    background: #ecfdf5;
    color: #10b981;
}

.verify-result.invalid .result-icon {
    background: #fef2f2;
    color: #ef4444;
}

.result-header h2 {
    margin-bottom: var(--space-3);
}

.status-badge {
    display: inline-block;
    padding: var(--space-2) var(--space-4);
    border-radius: 20px;
    font-size: var(--text-sm);
    font-weight: 600;
}

.status-badge.success {
    background: #d1fae5;
    color: #059669;
}

.status-badge.error {
    background: #fee2e2;
    color: #dc2626;
}

.result-details {
    background: var(--gray-50);
    border-radius: var(--radius-lg);
    padding: var(--space-5);
    text-align: left;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: var(--space-3) 0;
    border-bottom: 1px solid var(--gray-200);
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row .label {
    color: var(--text-muted);
    font-size: var(--text-sm);
}

.detail-row .value {
    font-weight: 600;
    color: var(--text-primary);
}

.detail-row .cert-id {
    font-family: monospace;
    background: var(--gray-100);
    padding: 2px 8px;
    border-radius: 4px;
    font-size: var(--text-sm);
}

.error-message {
    color: var(--text-secondary);
    margin-bottom: var(--space-2);
}

.error-hint {
    color: var(--text-muted);
    font-size: var(--text-sm);
}

@media (max-width: 600px) {
    .input-group {
        flex-direction: column;
    }
    
    .method-toggle {
        flex-direction: column;
    }
}
@media (max-width: 768px) {
    .verify-page {
        padding: var(--space-8) 0;
    }
    .verify-hero h1 {
        font-size: var(--text-2xl);
    }
    .verify-icon {
        width: 80px;
        height: 80px;
        font-size: 32px;
    }
    .verify-methods, .verify-result {
        padding: var(--space-5);
    }
}
</style>

<!-- QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Method toggle
    const toggleBtns = document.querySelectorAll('.toggle-btn');
    const methods = document.querySelectorAll('.verify-method');
    let html5QrCode = null;
    
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const method = this.dataset.method;
            
            // Update buttons
            toggleBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update methods
            methods.forEach(m => m.classList.remove('active'));
            document.getElementById(method + 'Method').classList.add('active');
            
            // Handle QR scanner
            if (method === 'qr') {
                startScanner();
            } else {
                stopScanner();
            }
        });
    });
    
    function startScanner() {
        if (html5QrCode) return;
        
        html5QrCode = new Html5Qrcode("qr-scanner");
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            function(decodedText) {
                // Parse QR data
                try {
                    const data = JSON.parse(decodedText);
                    if (data.verification_url) {
                        window.location.href = data.verification_url;
                    } else if (data.certificate_id) {
                        window.location.href = '<?php echo url("verify-certificate"); ?>?code=' + encodeURIComponent(data.certificate_id);
                    }
                } catch (e) {
                    // Plain text code
                    window.location.href = '<?php echo url("verify-certificate"); ?>?code=' + encodeURIComponent(decodedText);
                }
                stopScanner();
            },
            function(error) {
                // Ignore scan errors
            }
        ).catch(function(err) {
            document.querySelector('.scanner-status').innerHTML = 
                '<i class="fas fa-exclamation-triangle"></i> Camera access denied or unavailable';
        });
    }
    
    function stopScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode = null;
            }).catch(err => console.log(err));
        }
    }
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
