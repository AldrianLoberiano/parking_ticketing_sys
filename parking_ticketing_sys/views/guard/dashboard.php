<?php
session_start();

require_once '../../controllers/GuardController.php';

GuardController::dashboard();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Ticketing System - Guard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/parking_ticketing_sys/parking_ticketing_sys/public/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Black Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-shield-halved me-2"></i>Parking Guard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#guardNavbar" aria-controls="guardNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="guardNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="checkin.php">
                            <i class="fas fa-car me-1"></i>Check-in
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="checkout.php">
                            <i class="fas fa-right-from-bracket me-1"></i>Check-out
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="guardDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-shield me-1"></i><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guard'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="guardDropdown">
                            <li><a class="dropdown-item" href="/parking_ticketing_sys/parking_ticketing_sys/controllers/AuthController.php?action=logout">
                                    <i class="fas fa-right-from-bracket me-1"></i>Logout
                                </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-4">
        <!-- Welcome Header -->
        <div class="welcome-section mb-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="dashboard-title">
                        <i class="fas fa-shield-halved me-3"></i>
                        Security Guard Dashboard
                    </h1>
                    <p class="dashboard-subtitle text-muted">Monitor parking activity and manage vehicle check-ins/check-outs</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="current-time">
                        <i class="fas fa-clock me-2"></i>
                        <span id="currentTime"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-section mb-4">
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card available-slots">
                        <div class="stat-icon">
                            <i class="fas fa-parking"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number"><?php echo count($availableSlots); ?></h3>
                            <p class="stat-label">Available Slots</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card occupied-slots">
                        <div class="stat-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number"><?php echo count($activeTickets); ?></h3>
                            <p class="stat-label">Occupied Slots</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card total-slots">
                        <div class="stat-icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number"><?php echo count($availableSlots) + count($activeTickets); ?></h3>
                            <p class="stat-label">Total Slots</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card occupancy-rate">
                        <div class="stat-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number">
                                <?php
                                $total = count($availableSlots) + count($activeTickets);
                                echo $total > 0 ? round((count($activeTickets) / $total) * 100) : 0;
                                ?>%
                            </h3>
                            <p class="stat-label">Occupancy Rate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Available Slots -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header-custom">
                        <i class="fas fa-parking text-success me-2"></i>
                        <h5 class="mb-0">Available Parking Slots</h5>
                        <span class="badge bg-success ms-auto"><?php echo count($availableSlots); ?> slots</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($availableSlots)): ?>
                            <div class="slots-grid">
                                <?php foreach ($availableSlots as $slot): ?>
                                    <div class="slot-item available">
                                        <div class="slot-info">
                                            <div class="slot-number"><?php echo htmlspecialchars($slot['slot_number']); ?></div>
                                            <div class="slot-area"><?php echo htmlspecialchars($slot['area_name']); ?></div>
                                        </div>
                                        <div class="slot-status">
                                            <i class="fas fa-check-circle text-success"></i>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-parking-slash fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No Available Slots</h6>
                                <p class="text-muted small">All parking spaces are currently occupied</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Active Tickets -->
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-header-custom">
                        <i class="fas fa-ticket-alt text-warning me-2"></i>
                        <h5 class="mb-0">Active Parking Tickets</h5>
                        <span class="badge bg-warning ms-auto"><?php echo count($activeTickets); ?> active</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($activeTickets)): ?>
                            <div class="tickets-list">
                                <?php foreach ($activeTickets as $ticket): ?>
                                    <div class="ticket-item">
                                        <div class="ticket-icon">
                                            <i class="fas fa-car text-primary"></i>
                                        </div>
                                        <div class="ticket-info">
                                            <div class="ticket-plate fw-bold"><?php echo htmlspecialchars($ticket['plate_number']); ?></div>
                                            <div class="ticket-slot text-muted">
                                                <?php echo htmlspecialchars($ticket['area_name'] . ' - ' . $ticket['slot_number']); ?>
                                            </div>
                                            <div class="ticket-time small text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                Since <?php echo date('M d, H:i', strtotime($ticket['checkin_time'])); ?>
                                            </div>
                                        </div>
                                        <div class="ticket-actions">
                                            <span class="badge bg-success">Active</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">No Active Tickets</h6>
                                <p class="text-muted small">No vehicles are currently parked</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions-section mt-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-bolt text-warning me-2"></i>
                        Quick Actions
                    </h5>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="checkin.php" class="btn btn-success btn-lg px-4">
                            <i class="fas fa-car me-2"></i>
                            Check-in Vehicle
                        </a>
                        <a href="checkout.php" class="btn btn-danger btn-lg px-4">
                            <i class="fas fa-right-from-bracket me-2"></i>
                            Check-out Vehicle
                        </a>
                        <a href="/parking_ticketing_sys/parking_ticketing_sys/controllers/AuthController.php?action=logout" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="fas fa-right-from-bracket me-2"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour12: true,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('currentTime').textContent = timeString;
        }

        updateTime();
        setInterval(updateTime, 1000);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>