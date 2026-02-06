<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | <?php echo SITE_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-container {
            text-align: center;
            max-width: 500px;
        }
        .error-code {
            font-family: 'Poppins', sans-serif;
            font-size: 120px;
            font-weight: 800;
            background: linear-gradient(135deg, #005f73 0%, #0a9396 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 16px;
        }
        .error-title {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            color: #1e293b;
            margin-bottom: 12px;
        }
        .error-message {
            color: #64748b;
            font-size: 16px;
            margin-bottom: 32px;
            line-height: 1.6;
        }
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            background: linear-gradient(135deg, #005f73 0%, #0a9396 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 95, 115, 0.3);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-message">
            Oops! The page you're looking for doesn't exist or has been moved. 
            Let's get you back on track.
        </p>
        <a href="<?php echo SITE_URL; ?>" class="btn-home">
            <i class="fas fa-home"></i>
            Back to Home
        </a>
    </div>
</body>
</html>
