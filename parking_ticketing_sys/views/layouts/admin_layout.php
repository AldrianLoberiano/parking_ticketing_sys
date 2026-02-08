<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Ticketing System - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/tecketing/parking_ticketing_sys/public/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <nav class="admin-sidebar">
            <!-- Header integrated into sidebar -->
            <div class="sidebar-top-header">
                <div class="sidebar-brand">
                    <i class="fas fa-parking fa-2x text-primary me-3"></i>
                    <div>
                        <h5 class="mb-0">Parking System</h5>
                        <small class="text-muted">Admin Panel</small>
                    </div>
                </div>
                <div class="sidebar-user-info">
                    <div class="user-welcome">
                        <i class="fas fa-user-circle me-2"></i>
                        <span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></span>
                    </div>
                    <a href="/tecketing/parking_ticketing_sys/controllers/AuthController.php?action=logout" class="logout-link">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>

            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt me-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="analytics.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'analytics.php' ? 'active' : ''; ?>">
                        <i class="fas fa-chart-bar me-3"></i>
                        <span>Analytics</span>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="nav-section-title">Management</span>
                </li>

                <li class="nav-item">
                    <a href="manage_users.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>">
                        <i class="fas fa-users me-3"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage_areas.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_areas.php' ? 'active' : ''; ?>">
                        <i class="fas fa-map-marker-alt me-3"></i>
                        <span>Areas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage_slots.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_slots.php' ? 'active' : ''; ?>">
                        <i class="fas fa-parking me-3"></i>
                        <span>Slots</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="container-fluid p-4">
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>