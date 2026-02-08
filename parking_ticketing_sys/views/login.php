<?php
session_start();
require_once '../includes/functions.php';

if (isLoggedIn()) {
    $user = getCurrentUser();
    switch ($user['role']) {
        case 'admin':
            redirect('/parking_ticketing_sys/parking_ticketing_sys/views/admin/dashboard.php');
            break;
        case 'guard':
            redirect('/parking_ticketing_sys/parking_ticketing_sys/views/guard/dashboard.php');
            break;
        case 'employee':
            redirect('/parking_ticketing_sys/parking_ticketing_sys/views/employee/dashboard.php');
            break;
    }
}

$flash = getFlashMessage();
?>

<?php include 'layouts/header.php'; ?>

<div class="login-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-card">
                    <div class="login-header">
                        <i class="fas fa-parking"></i>
                        <h3>Parking System</h3>
                        <p class="mb-0">Please sign in to continue</p>
                    </div>
                    <div class="login-body">
                        <?php if ($flash): ?>
                            <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'danger' : 'success'; ?> alert-dismissible fade show" role="alert">
                                <i class="fas fa-<?php echo $flash['type'] == 'error' ? 'exclamation-triangle' : 'check-circle'; ?> me-2"></i>
                                <?php echo $flash['message']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        <form action="../controllers/AuthController.php?action=login" method="post">
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email address" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>
                        <div class="text-center mt-3">
                            <small class="text-muted">Secure login for parking management</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'layouts/footer.php'; ?>