<?php
session_start();

require_once '../../controllers/EmployeeController.php';

EmployeeController::dashboard();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Ticketing System - Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/parking_ticketing_sys/parking_ticketing_sys/public/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Black Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-parking me-2"></i>Parking Employee
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active me-3" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-3" href="register_vehicle.php">
                            <i class="fas fa-car me-1"></i>Register Vehicle
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-3" href="history.php">
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

                <!-- Welcome Card -->
                <div class="card mb-4 bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="card-title"><i class="fas fa-tachometer-alt me-2"></i>Employee Dashboard</h5>
                                <p class="card-text">Manage your vehicles and track your parking sessions.</p>
                                <?php if (!empty($vehicles) && empty($activeTickets)): ?>
                                    <div class="mt-3">
                                        <small class="text-white-50">
                                            <i class="fas fa-info-circle me-1"></i>
                                            You have <?php echo count($vehicles); ?> registered vehicle(s). Visit the guard station to check in.
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="mb-4 fw-bold">
                    <i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard Overview
                </h2>

                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-primary text-white d-flex align-items-center">
                                <i class="fas fa-car me-2"></i>
                                <h5 class="mb-0">My Vehicles</h5>
                                <span class="badge bg-light text-primary ms-auto"><?php echo count($vehicles ?? []); ?> registered</span>
                            </div>
                            <div class="card-body">
                                <?php if (empty($vehicles)): ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-car text-muted" style="font-size: 3rem;"></i>
                                        <h6 class="text-muted mt-3">No vehicles registered</h6>
                                        <p class="text-muted small">Register your first vehicle to start parking</p>
                                        <a href="register_vehicle.php" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i>Register Vehicle
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="row g-3">
                                        <?php foreach ($vehicles as $vehicle): ?>
                                            <div class="col-12">
                                                <div class="d-flex align-items-center p-3 vehicle-item">
                                                    <div class="flex-shrink-0 me-3">
                                                        <i class="fas fa-car text-primary" style="font-size: 1.5rem;"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($vehicle['plate_number']); ?></h6>
                                                        <p class="mb-0 text-muted small">
                                                            <?php echo htmlspecialchars($vehicle['model']); ?>
                                                            <?php if ($vehicle['color']): ?>
                                                                <span class="badge bg-secondary ms-1"><?php echo htmlspecialchars($vehicle['color']); ?></span>
                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="mt-3">
                                        <a href="register_vehicle.php" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i>Add Another Vehicle
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-warning text-dark d-flex align-items-center">
                                <i class="fas fa-clock me-2"></i>
                                <h5 class="mb-0">Active Parking Sessions</h5>
                                <span class="badge bg-light text-warning ms-auto"><?php echo count($activeTickets ?? []); ?> active</span>
                            </div>
                            <div class="card-body">
                                <?php if (empty($activeTickets)): ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-parking text-muted" style="font-size: 3rem;"></i>
                                        <h6 class="text-muted mt-3">No active parking sessions</h6>
                                        <p class="text-muted small mb-3">Your vehicles are registered but not currently parked</p>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Next Step:</strong> Visit the parking guard to check in your vehicle and get assigned a parking spot.
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="row g-3">
                                        <?php foreach ($activeTickets as $ticket): ?>
                                            <div class="col-12">
                                                <div class="d-flex align-items-center p-3 border rounded bg-light">
                                                    <div class="flex-shrink-0 me-3">
                                                        <i class="fas fa-parking text-warning" style="font-size: 1.5rem;"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($ticket['plate_number']); ?></h6>
                                                        <p class="mb-1 text-muted small">
                                                            <i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($ticket['area_name'] . ' - Slot ' . $ticket['slot_number']); ?>
                                                        </p>
                                                        <p class="mb-0 text-muted small">
                                                            <i class="fas fa-calendar-check me-1"></i>Since: <?php echo htmlspecialchars($ticket['checkin_time']); ?>
                                                        </p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <span class="badge bg-warning text-dark">Active</span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Available Parking Spots Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-success text-white d-flex align-items-center">
                                <i class="fas fa-parking me-2"></i>
                                <h5 class="mb-0">Available Parking Spots</h5>
                                <span class="badge bg-light text-success ms-auto">Ready for parking</span>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-3">These parking spots are currently available. Visit the guard station to check in your vehicle.</p>
                                <div class="row g-3">
                                    <?php
                                    // Get available slots from database
                                    require_once '../../models/ParkingSlot.php';
                                    require_once '../../models/ParkingArea.php';
                                    $availableSlots = ParkingSlot::getAvailable();
                                    $areas = ParkingArea::getAll();
                                    $areaMap = [];
                                    foreach ($areas as $area) {
                                        $areaMap[$area['id']] = $area['name'];
                                    }

                                    if (empty($availableSlots)): ?>
                                        <div class="col-12">
                                            <div class="text-center py-4">
                                                <i class="fas fa-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                                                <h6 class="text-muted mt-2">No parking spots available</h6>
                                                <p class="text-muted small">All parking spots are currently occupied. Please check back later.</p>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <?php foreach (array_slice($availableSlots, 0, 12) as $slot): // Show first 12 available slots 
                                        ?>
                                            <div class="col-md-3 col-sm-6">
                                                <div class="text-center p-3 border rounded bg-light">
                                                    <i class="fas fa-parking text-success mb-2" style="font-size: 1.5rem;"></i>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($areaMap[$slot['area_id']] ?? 'Area') . ' - Slot ' . $slot['slot_number']; ?></div>
                                                    <small class="text-muted">Available</small>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php if (count($availableSlots) > 12): ?>
                                            <div class="col-12 text-center mt-3">
                                                <small class="text-muted">And <?php echo count($availableSlots) - 12; ?> more spots available...</small>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>