<?php
session_start();

require_once '../../controllers/EmployeeController.php';

EmployeeController::registerVehicle();
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
                        <a class="nav-link me-3" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active me-3" href="register_vehicle.php">
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
                                <h5 class="card-title"><i class="fas fa-car me-2"></i>Register New Vehicle</h5>
                                <p class="card-text">Add your vehicle details to the parking system for easy check-in</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card shadow border-0">
                            <div class="card-header bg-primary text-white">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    <h4 class="mb-0">Register New Vehicle</h4>
                                </div>
                                <p class="mb-0 mt-1 opacity-75">Add your vehicle information to start parking</p>
                            </div>
                            <div class="card-body p-4">

                                <?php $flash = getFlashMessage();
                                if ($flash): ?>
                                    <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'danger' : 'success'; ?> alert-dismissible fade show" role="alert">
                                        <i class="fas fa-<?php echo $flash['type'] == 'error' ? 'exclamation-triangle' : 'check-circle'; ?> me-2"></i>
                                        <?php echo $flash['message']; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>

                                <form method="post" class="row g-4">
                                    <div class="col-md-6">
                                        <label for="plate_number" class="form-label fw-bold">
                                            <i class="fas fa-id-card me-1 text-primary"></i>Plate Number *
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-car"></i></span>
                                            <input type="text" class="form-control form-control-lg" id="plate_number" name="plate_number"
                                                placeholder="e.g., ABC-123" required>
                                        </div>
                                        <div class="form-text">Enter your vehicle license plate number</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="model" class="form-label fw-bold">
                                            <i class="fas fa-car-side me-1 text-primary"></i>Vehicle Model
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                            <input type="text" class="form-control form-control-lg" id="model" name="model"
                                                placeholder="e.g., Toyota Camry">
                                        </div>
                                        <div class="form-text">Optional: Enter make and model</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="color" class="form-label fw-bold">
                                            <i class="fas fa-palette me-1 text-primary"></i>Color
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-fill-drip"></i></span>
                                            <input type="text" class="form-control form-control-lg" id="color" name="color"
                                                placeholder="e.g., White">
                                        </div>
                                        <div class="form-text">Optional: Enter vehicle color</div>
                                    </div>

                                    <div class="col-12">
                                        <hr class="my-4">
                                        <div class="d-flex gap-3">
                                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                                <i class="fas fa-save me-2"></i>Register Vehicle
                                            </button>
                                            <a href="dashboard.php" class="btn btn-outline-secondary btn-lg px-4">
                                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                                            </a>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>