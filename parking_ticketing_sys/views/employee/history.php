<?php
session_start();

require_once '../../controllers/EmployeeController.php';

EmployeeController::history();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Ticketing System - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

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
                        <a class="nav-link me-3" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-3" href="register_vehicle.php">
                            <i class="fas fa-car me-1"></i>Register Vehicle
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active me-3" href="history.php">
                            <i class="fas fa-history me-1"></i>History
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-shield me-1"></i><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Employee'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
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
        <div class="row">
            <div class="col-12">

                <div class="row mb-4">
                    <div class="col-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Parking History</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card shadow border-0">
                            <div class="card-header bg-info text-white">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-history me-2"></i>
                                        <h4 class="mb-0">Parking History</h4>
                                    </div>
                                    <div class="badge bg-light text-info fs-6">
                                        <?php echo count($history ?? []); ?> records
                                    </div>
                                </div>
                                <p class="mb-0 mt-1 opacity-75">View all your past and current parking sessions</p>
                            </div>
                            <div class="card-body p-0">

                                <?php if (empty($history)): ?>
                                    <div class="empty-state">
                                        <i class="fas fa-parking"></i>
                                        <h5 class="text-muted">No Parking History</h5>
                                        <p class="text-muted">You haven't parked any vehicles yet. Start by registering a vehicle and parking it.</p>
                                        <div class="mt-3">
                                            <a href="register_vehicle.php" class="btn btn-primary me-2">
                                                <i class="fas fa-plus-circle me-1"></i>Register Vehicle
                                            </a>
                                            <a href="dashboard.php" class="btn btn-outline-secondary">
                                                <i class="fas fa-tachometer-alt me-1"></i>Go to Dashboard
                                            </a>
                                        </div>
                                    </div>
                                <?php else: ?>

                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th><i class="fas fa-id-card me-1"></i>Plate Number</th>
                                                    <th><i class="fas fa-parking me-1"></i>Parking Slot</th>
                                                    <th><i class="fas fa-sign-in-alt me-1"></i>Check-in Time</th>
                                                    <th><i class="fas fa-sign-out-alt me-1"></i>Check-out Time</th>
                                                    <th><i class="fas fa-clock me-1"></i>Duration</th>
                                                    <th><i class="fas fa-info-circle me-1"></i>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($history as $ticket): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-car text-primary me-2"></i>
                                                                <strong><?php echo htmlspecialchars($ticket['plate_number']); ?></strong>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-light text-dark">
                                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                                <?php echo htmlspecialchars($ticket['area_name'] . ' ' . $ticket['slot_number']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <i class="fas fa-calendar-check text-success me-1"></i>
                                                            <?php echo htmlspecialchars($ticket['checkin_time']); ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($ticket['checkout_time']): ?>
                                                                <i class="fas fa-calendar-times text-danger me-1"></i>
                                                                <?php echo htmlspecialchars($ticket['checkout_time']); ?>
                                                            <?php else: ?>
                                                                <span class="text-muted">-</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($ticket['checkout_time']) {
                                                                $checkin = new DateTime($ticket['checkin_time']);
                                                                $checkout = new DateTime($ticket['checkout_time']);
                                                                $interval = $checkin->diff($checkout);
                                                                echo '<i class="fas fa-hourglass-half text-warning me-1"></i>';
                                                                echo $interval->format('%h hours %i minutes');
                                                            } else {
                                                                echo '<span class="text-muted">-</span>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $ticket['status'] == 'active' ? 'warning' : 'success'; ?>">
                                                                <i class="fas fa-<?php echo $ticket['status'] == 'active' ? 'clock' : 'check-circle'; ?> me-1"></i>
                                                                <?php echo ucfirst($ticket['status']); ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="dashboard.php" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>