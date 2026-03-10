<?php
session_start();
require_once '../includes/functions.php';

if (isLoggedIn()) {
    $user = getCurrentUser();
    switch ($user['role']) {
        case 'admin':
            redirect('/parking_ticketing_system_v2/parking_ticketing_sys/views/admin/dashboard.php');
            break;
        case 'guard':
            redirect('/parking_ticketing_system_v2/parking_ticketing_sys/views/guard/dashboard.php');
            break;
        case 'employee':
            redirect('/parking_ticketing_system_v2/parking_ticketing_sys/views/employee/dashboard.php');
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
    <link rel="icon" type="image/png" href="/parking_ticketing_system_v2/parking_ticketing_sys/public/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fc;
            position: relative;
            overflow: hidden;
        }

        /* Subtle background pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(79, 110, 247, 0.05) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(99, 102, 241, 0.04) 0%, transparent 50%),
                radial-gradient(ellipse at 60% 80%, rgba(14, 165, 233, 0.03) 0%, transparent 50%);
            z-index: 0;
        }

        /* Login container */
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 1.25rem;
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-brand-icon {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(79, 110, 247, 0.18);
            border: 2px solid rgba(79, 110, 247, 0.1);
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .login-brand-icon:hover {
            transform: scale(1.08) rotate(-3deg);
        }

        .login-brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 16px;
        }

        .login-brand h1 {
            color: #1a1d23;
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin: 0;
        }

        .login-brand p {
            color: #9ca3af;
            font-size: 0.85rem;
            font-weight: 400;
            margin: 0.4rem 0 0;
        }

        /* Clean white card */
        .login-card {
            background: #ffffff;
            border: 1px solid #e8eaef;
            border-radius: 24px;
            padding: 2.25rem;
            box-shadow:
                0 1px 3px rgba(0, 0, 0, 0.04),
                0 6px 24px rgba(0, 0, 0, 0.06);
            transition: box-shadow 0.4s ease;
        }

        .login-card:hover {
            box-shadow:
                0 1px 3px rgba(0, 0, 0, 0.04),
                0 12px 40px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 1.35rem;
        }

        .form-group label {
            display: block;
            color: #64748b;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
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
            color: #c0c5ce;
            font-size: 0.88rem;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .input-wrapper input {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 2.85rem;
            background: #f5f6f8;
            border: 1.5px solid #e8eaef;
            border-radius: 14px;
            color: #1a1d23;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-wrapper input::placeholder {
            color: #b0b7c3;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #4f6ef7;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(79, 110, 247, 0.1);
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
            color: #c0c5ce;
            cursor: pointer;
            padding: 0.25rem;
            font-size: 0.88rem;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .toggle-password:hover {
            color: #4f6ef7;
        }

        /* Remember me */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border: 1.5px solid #d1d5db;
            border-radius: 5px;
            background: #f5f6f8;
            cursor: pointer;
            transition: all 0.25s ease;
            position: relative;
        }

        .remember-me input[type="checkbox"]:checked {
            background: #4f6ef7;
            border-color: #4f6ef7;
        }

        .remember-me input[type="checkbox"]:checked::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 0.55rem;
            color: #fff;
        }

        .remember-me span {
            color: #9ca3af;
            font-size: 0.8rem;
            font-weight: 400;
        }

        /* Sign In button */
        .btn-login {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #4f6ef7 0%, #6366f1 100%);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(79, 110, 247, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(79, 110, 247, 0.25);
        }

        /* Divider */
        .login-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .login-divider::before,
        .login-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e8eaef;
        }

        .login-divider span {
            color: #b0b7c3;
            font-size: 0.72rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* Home button inside card */
        .btn-home {
            width: 100%;
            padding: 0.85rem;
            background: #f5f6f8;
            border: 1.5px solid #e8eaef;
            border-radius: 14px;
            color: #64748b;
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-home:hover {
            background: #eef0f4;
            border-color: #d1d5db;
            color: #1a1d23;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .btn-home:active {
            transform: translateY(0);
        }

        /* Alerts */
        .login-alert {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.82rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .login-alert.error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .login-alert.success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 1.75rem;
            color: #c0c5ce;
            font-size: 0.72rem;
            font-weight: 400;
            letter-spacing: 0.02em;
        }

        .login-footer i {
            color: #d1d5db;
            margin-right: 0.25rem;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                max-width: 100%;
                padding: 1rem;
            }

            .login-card {
                padding: 1.75rem;
                border-radius: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-brand">
            <div class="login-brand-icon"><img src="/parking_ticketing_system_v2/parking_ticketing_sys/public/images/logo.png" alt="ParkEase Logo"></div>
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

            <form action="../controllers/AuthController.php?action=login" method="post" id="loginForm">
                <div class="form-group">
                    <label>Email</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" id="emailInput" placeholder="Enter your email" required autofocus>
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
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                </div>
                <button type="submit" class="btn-login" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <!-- Divider + Home -->
            <div class="login-divider"><span>or</span></div>
            <a href="home.php" class="btn-home">
                <i class="fas fa-home"></i> Go to Homepage
            </a>
        </div>

        <div class="login-footer">
            <i class="fas fa-shield-alt"></i> Secure Parking Management System
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

        // Subtle input focus glow on the card
        const loginCard = document.querySelector('.login-card');
        const inputs = document.querySelectorAll('.login-card input[type="email"], .login-card input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                loginCard.style.borderColor = 'rgba(79, 110, 247, 0.25)';
            });
            input.addEventListener('blur', () => {
                loginCard.style.borderColor = '#e8eaef';
            });
        });

        // Button loading state on submit
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        loginForm?.addEventListener('submit', () => {
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...';
            loginBtn.style.pointerEvents = 'none';
            loginBtn.style.opacity = '0.8';
        });
    </script>
</body>

</html>