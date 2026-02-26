<?php
session_start();
require_once '../includes/functions.php';

if (isLoggedIn()) {
    $user = getCurrentUser();
    switch ($user['role']) {
        case 'admin':
            redirect('/tecketing/parking_ticketing_sys/views/admin/dashboard.php');
            break;
        case 'guard':
            redirect('/tecketing/parking_ticketing_sys/views/guard/dashboard.php');
            break;
        case 'employee':
            redirect('/tecketing/parking_ticketing_sys/views/employee/dashboard.php');
            break;
    }
}

$flash = getFlashMessage();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ParkEase</title>
    <link rel="icon" type="image/png" href="/tecketing/parking_ticketing_sys/public/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            position: relative;
            overflow: hidden;
        }

        /* Clean background — no gradient decorations */
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, -30px); }
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 1rem;
        }

        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-brand-icon {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .login-brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 16px;
        }

        .login-brand h1 {
            color: #1a1d23;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0;
        }

        .login-brand p {
            color: #6b7280;
            font-size: 0.85rem;
            margin: 0.35rem 0 0;
        }

        .login-card {
            background: #ffffff;
            backdrop-filter: none;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            color: #6b7280;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i.input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.75rem;
            background: #f8f9fa;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            color: #1a1d23;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .input-wrapper input::placeholder {
            color: #9ca3af;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #4f6ef7;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(79,110,247,0.15);
        }

        .input-wrapper input:focus + i.input-icon,
        .input-wrapper input:focus ~ i.input-icon {
            color: #4f6ef7;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .toggle-password:hover {
            color: #4f6ef7;
        }

        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background: #4f6ef7;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79,110,247,0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-alert {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideDown 0.3s ease;
        }

        .login-alert.error {
            background: rgba(239,68,68,0.1);
            color: #ef4444;
            border: 1px solid rgba(239,68,68,0.2);
        }

        .login-alert.success {
            background: rgba(34,197,94,0.1);
            color: #22c55e;
            border: 1px solid rgba(34,197,94,0.2);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #9ca3af;
            font-size: 0.75rem;
        }

        .login-footer i {
            color: #d1d5db;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-brand">
            <div class="login-brand-icon"><img src="/tecketing/parking_ticketing_sys/public/images/logo.png" alt="Parking Logo"></div>
            <h1>ParkEase</h1>
            <p>Sign in to your account</p>
        </div>

        <div class="login-card">
            <?php if ($flash): ?>
                <div class="login-alert <?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                    <i class="fas fa-<?php echo $flash['type'] == 'error' ? 'exclamation-circle' : 'check-circle'; ?>"></i>
                    <?php echo $flash['message']; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'access_denied'): ?>
                <div class="login-alert error">
                    <i class="fas fa-exclamation-circle"></i>
                    Access denied. Please log in with proper credentials.
                </div>
            <?php endif; ?>

            <form action="../controllers/AuthController.php?action=login" method="post">
                <div class="form-group">
                    <label>Email</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" placeholder="Enter your email" required autofocus>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>
        </div>

        <div class="login-footer">
            <i class="fas fa-shield-alt me-1"></i> Secure Parking Management System
        </div>
    </div>

    <script>
        // Password toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword?.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeIcon.classList.toggle('fa-eye', !isPassword);
            eyeIcon.classList.toggle('fa-eye-slash', isPassword);
        });
    </script>
</body>

</html>