<?php
session_start();

require_once '../../controllers/AdminController.php';

AdminController::dashboard();

include '../layouts/header.php';
?>

<div class="admin-fullscreen">
    <div class="admin-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h2 mb-0"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</h1>
            <div class="admin-nav">
                <a href="manage_users.php" class="btn btn-outline-primary me-2">
                    <i class="fas fa-users me-1"></i>Users
                </a>
                <a href="manage_areas.php" class="btn btn-outline-success me-2">
                    <i class="fas fa-map-marker-alt me-1"></i>Areas
                </a>
                <a href="manage_slots.php" class="btn btn-outline-info me-2">
                    <i class="fas fa-parking me-1"></i>Slots
                </a>
                <a href="analytics.php" class="btn btn-outline-warning me-2">
                    <i class="fas fa-chart-bar me-1"></i>Analytics
                </a>
                <a href="/tecketing/parking_ticketing_sys/controllers/AuthController.php?action=logout" class="btn btn-outline-danger">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </div>

    <div class="admin-content">
        <!-- Welcome Card -->
        <div class="welcome-card mb-4">
            <div class="card-body text-center">
                <i class="fas fa-user-shield fa-3x text-primary mb-3"></i>
                <h4 class="card-title">Welcome to Admin Dashboard</h4>
                <p class="card-text">Manage your parking system efficiently. Monitor users, vehicles, and parking activities from this centralized control panel.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid mb-4">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo count($users); ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-car"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo count($vehicles); ?></h3>
                    <p>Total Vehicles</p>
                </div>
            </div>
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="fas fa-parking"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo count($slots); ?></h3>
                    <p>Total Slots</p>
                </div>
            </div>
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $analytics['active_tickets'] ?? 0; ?></h3>
                    <p>Active Tickets</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="actions-section">
            <h4 class="section-title"><i class="fas fa-bolt me-2"></i>Quick Actions</h4>
            <div class="actions-grid">
                <a href="manage_users.php" class="action-card">
                    <i class="fas fa-users fa-2x"></i>
                    <h5>Manage Users</h5>
                    <p>Add, edit, or remove system users</p>
                </a>
                <a href="manage_areas.php" class="action-card">
                    <i class="fas fa-map-marker-alt fa-2x"></i>
                    <h5>Manage Areas</h5>
                    <p>Configure parking areas and zones</p>
                </a>
                <a href="manage_slots.php" class="action-card">
                    <i class="fas fa-parking fa-2x"></i>
                    <h5>Manage Slots</h5>
                    <p>Control parking slot availability</p>
                </a>
                <a href="analytics.php" class="action-card">
                    <i class="fas fa-chart-bar fa-2x"></i>
                    <h5>View Analytics</h5>
                    <p>Analyze system performance and usage</p>
                </a>
            </div>
        </div>
    </div>
</div>

<?php // include '../layouts/footer.php'; 
?>