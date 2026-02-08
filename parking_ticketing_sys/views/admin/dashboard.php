<?php
session_start();

require_once '../../controllers/AdminController.php';

AdminController::dashboard();

include '../layouts/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
            </div>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="card mb-4 bg-primary text-white">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title"><i class="fas fa-user-shield me-2"></i>Welcome, Admin!</h5>
                    <p class="card-text">Manage your parking system efficiently. Monitor users, vehicles, and parking activities.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-left-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title text-primary"><i class="fas fa-users me-2"></i>Total Users</h5>
                            <h2 class="mb-0"><?php echo count($users); ?></h2>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-left-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title text-success"><i class="fas fa-car me-2"></i>Total Vehicles</h5>
                            <h2 class="mb-0"><?php echo count($vehicles); ?></h2>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-car fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-left-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title text-info"><i class="fas fa-parking me-2"></i>Total Slots</h5>
                            <h2 class="mb-0"><?php echo count($slots); ?></h2>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-parking fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-left-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title text-warning"><i class="fas fa-ticket-alt me-2"></i>Active Tickets</h5>
                            <h2 class="mb-0"><?php echo $analytics['active_tickets'] ?? 0; ?></h2>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-ticket-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="manage_users.php" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="manage_areas.php" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-map-marker-alt me-2"></i>Manage Areas
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="manage_slots.php" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-parking me-2"></i>Manage Slots
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="analytics.php" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-chart-bar me-2"></i>View Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../layouts/footer.php'; ?>