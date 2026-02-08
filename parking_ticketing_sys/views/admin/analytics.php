<?php
session_start();
require_once '../../controllers/AdminController.php';

AdminController::dashboard(); // to get analytics
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
                        <a class="nav-link" href="dashboard.php">
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
                        <a class="nav-link active" href="analytics.php">
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
                            <li><a class="dropdown-item" href="../../controllers/AuthController.php?action=logout">
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
                <div class="card mb-2 bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="card-title"><i class="fas fa-chart-bar me-2"></i>Analytics & Insights</h5>
                                <p class="card-text">Analyze parking system performance. View detailed statistics, trends, and reports to make data-driven decisions.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-ticket-alt fa-2x text-primary mb-2"></i>
                                <h5 class="card-title">Total Tickets</h5>
                                <h3 class="text-primary"><?php echo $analytics['total_tickets'] ?? 0; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h5 class="card-title">Active Tickets</h5>
                                <h3 class="text-warning"><?php echo $analytics['active_tickets'] ?? 0; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <i class="fas fa-hourglass-half fa-2x text-info mb-2"></i>
                                <h5 class="card-title">Average Duration</h5>
                                <h3 class="text-info"><?php echo round($analytics['avg_duration'] ?? 0, 2); ?>h</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="mb-3"><i class="fas fa-history me-2"></i>Recent Tickets</h3>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-car me-2"></i>Plate Number</th>
                                        <th><i class="fas fa-user me-2"></i>Owner</th>
                                        <th><i class="fas fa-parking me-2"></i>Slot</th>
                                        <th><i class="fas fa-sign-in-alt me-2"></i>Check-in</th>
                                        <th><i class="fas fa-sign-out-alt me-2"></i>Check-out</th>
                                        <th><i class="fas fa-info-circle me-2"></i>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($tickets, 0, 20) as $ticket): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($ticket['plate_number']); ?></td>
                                            <td><?php echo htmlspecialchars($ticket['owner_name']); ?></td>
                                            <td><?php echo htmlspecialchars($ticket['area_name'] . ' ' . $ticket['slot_number']); ?></td>
                                            <td><?php echo htmlspecialchars($ticket['checkin_time']); ?></td>
                                            <td><?php echo htmlspecialchars($ticket['checkout_time'] ?: '-'); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $ticket['status'] == 'active' ? 'warning' : 'success'; ?>">
                                                    <?php echo ucfirst($ticket['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>