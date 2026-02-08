<?php
session_start();

require_once '../../controllers/AdminController.php';

AdminController::dashboard();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Ticketing System - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/tecketing/parking_ticketing_sys/public/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Black Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-parking me-2"></i>Parking Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php">
                            <i class="fas fa-users me-1"></i>Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_areas.php">
                            <i class="fas fa-map-marker-alt me-1"></i>Areas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_slots.php">
                            <i class="fas fa-parking me-1"></i>Slots
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="analytics.php">
                            <i class="fas fa-chart-bar me-1"></i>Analytics
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-shield me-1"></i><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="/tecketing/parking_ticketing_sys/controllers/AuthController.php?action=logout">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">

                <!-- Welcome Card -->
                <div class="card mb-2 bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="card-title"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Overview</h5>
                                <p class="card-text">Get a comprehensive view of your parking system. Monitor key metrics, user activity, and system performance at a glance.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3 mb-3">
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
                    <div class="col-md-3 mb-3">
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
                    <div class="col-md-3 mb-3">
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
                    <div class="col-md-3 mb-3">
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
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>