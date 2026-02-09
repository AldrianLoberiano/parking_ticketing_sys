<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Ticketing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/parking_ticketing_sys/parking_ticketing_sys/public/css/style.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark login-navbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-parking me-2"></i>Parking System
            </a>
            <div class="navbar-nav ms-auto">
                <?php if (isLoggedIn()): ?>
                    <span class="navbar-text me-3">
                        <i class="fas fa-user-circle me-1"></i>Welcome, <?php echo getCurrentUser()['name']; ?>
                    </span>
                    <a class="nav-link" href="/parking_ticketing_sys/parking_ticketing_sys/controllers/AuthController.php?action=logout">
                        <i class="fas fa-right-from-bracket me-1"></i>Logout
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mt-4">