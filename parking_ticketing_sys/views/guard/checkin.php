<?php
session_start();

require_once '../../controllers/GuardController.php';

GuardController::checkin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Ticketing System - Guard Check-in</title>
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
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="checkin.php">
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
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="checkin-card">
                    <div class="checkin-header">
                        <i class="fas fa-car"></i>
                        <h2>Vehicle Check-in</h2>
                        <p class="mb-0">Register vehicle arrival and assign parking space</p>
                    </div>

                    <div class="checkin-body">
                        <?php $flash = getFlashMessage();
                        if ($flash): ?>
                            <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'danger' : 'success'; ?> alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-<?php echo $flash['type'] == 'error' ? 'exclamation-triangle' : 'check-circle'; ?> me-2"></i>
                                <?php echo $flash['message']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($availableSlots)): ?>
                            <form method="post" class="checkin-form">
                                <div class="form-section">
                                    <div class="section-icon">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div class="section-content">
                                        <label for="plate_number" class="form-label fw-bold">License Plate Number</label>
                                        <input type="text" class="form-control form-control-lg shadow-sm" id="plate_number" name="plate_number"
                                            placeholder="Enter vehicle plate number (e.g., ABC-123)" required autofocus
                                            style="text-transform: uppercase;">
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Enter the vehicle's license plate number exactly as shown on the vehicle.
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <div class="section-icon">
                                        <i class="fas fa-parking"></i>
                                    </div>
                                    <div class="section-content">
                                        <label for="slot_id" class="form-label fw-bold">Available Parking Slot</label>
                                        <select class="form-select form-select-lg shadow-sm" id="slot_id" name="slot_id" required>
                                            <option value="" disabled selected>Choose an available parking slot...</option>
                                            <?php foreach ($availableSlots as $slot): ?>
                                                <option value="<?php echo $slot['id']; ?>" data-area="<?php echo htmlspecialchars($slot['area_name']); ?>">
                                                    <span class="fw-bold"><?php echo htmlspecialchars($slot['area_name']); ?></span> -
                                                    Slot <?php echo htmlspecialchars($slot['slot_number']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="form-text">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            Select from <?php echo count($availableSlots); ?> available parking spaces.
                                        </div>
                                    </div>
                                </div>

                                <div class="slot-preview mt-4" id="slotPreview" style="display: none;">
                                    <div class="card border-success shadow-sm">
                                        <div class="card-header bg-success text-white">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Selected Parking Space
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="parking-slot-display">
                                                <i class="fas fa-parking display-4 text-success mb-3"></i>
                                                <h4 id="selectedArea">-</h4>
                                                <h3 class="text-success fw-bold" id="selectedSlot">-</h3>
                                                <p class="text-muted mb-0">This slot will be marked as occupied</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="checkin-actions mt-4">
                                    <button type="submit" class="btn btn-checkin w-100">
                                        <i class="fas fa-car me-2"></i>
                                        Complete Check-in
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-parking-slash display-1 text-muted mb-3"></i>
                                    <h4 class="text-muted">No Available Slots</h4>
                                    <p class="text-muted">All parking spaces are currently occupied.</p>
                                    <a href="checkout.php" class="btn btn-warning">
                                        <i class="fas fa-right-from-bracket me-2"></i>
                                        Process Check-out
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="checkin-footer">
                        <a href="dashboard.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        // Auto-format plate number input
        document.getElementById('plate_number').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });

        // Show slot preview when selection changes
        document.getElementById('slot_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const areaName = selectedOption.getAttribute('data-area');
            const slotText = selectedOption.textContent.split(' - ')[1];

            if (areaName && slotText) {
                document.getElementById('selectedArea').textContent = areaName;
                document.getElementById('selectedSlot').textContent = slotText;
                document.getElementById('slotPreview').style.display = 'block';
                document.getElementById('slotPreview').scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            } else {
                document.getElementById('slotPreview').style.display = 'none';
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>