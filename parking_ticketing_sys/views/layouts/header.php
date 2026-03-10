<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="script-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com;">
    <title>ParkEase — Parking Control and Monitoring System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/parking_ticketing_system_v2/parking_ticketing_sys/public/css/style.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark login-navbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="/parking_ticketing_system_v2/parking_ticketing_sys/public/images/logo.png" alt="ParkEase Logo" style="height:40px; width:auto; margin-right:8px; vertical-align:middle;">
                ParkEase
            </a>
            <div class="navbar-nav ms-auto">
                <?php if (isLoggedIn()): ?>
                    <span class="navbar-text me-3">
                        <i class="fas fa-user-circle me-1"></i>Welcome, <?php echo getCurrentUser()['name']; ?>
                    </span>
                    <a class="nav-link" href="/parking_ticketing_system_v2/parking_ticketing_sys/controllers/AuthController.php?action=logout">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mt-4">