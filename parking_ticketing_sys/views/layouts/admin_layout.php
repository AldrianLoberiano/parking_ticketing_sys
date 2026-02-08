<?php include '../layouts/header.php'; ?>

<div class="admin-layout">
    <!-- Sidebar -->
    <nav class="admin-sidebar">
        <div class="sidebar-header">
            <h5><i class="fas fa-cog me-2"></i>Admin Panel</h5>
        </div>
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="analytics.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'analytics.php' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar me-2"></i>Analytics
                </a>
            </li>
            <li class="nav-item">
                <a href="manage_users.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users me-2"></i>Manage Users
                </a>
            </li>
            <li class="nav-item">
                <a href="manage_areas.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_areas.php' ? 'active' : ''; ?>">
                    <i class="fas fa-map-marker-alt me-2"></i>Manage Areas
                </a>
            </li>
            <li class="nav-item">
                <a href="manage_slots.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_slots.php' ? 'active' : ''; ?>">
                    <i class="fas fa-parking me-2"></i>Manage Slots
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

<?php include '../layouts/footer.php'; ?>