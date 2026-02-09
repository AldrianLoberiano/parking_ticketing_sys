<?php
session_start();

require_once '../../controllers/GuardController.php';

GuardController::checkout();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Ticketing System - Guard Check-out</title>
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
                        <a class="nav-link me-3" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-3" href="checkin.php">
                            <i class="fas fa-car me-1"></i>Check-in
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active me-3" href="checkout.php">
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
                <div class="checkout-card">
                    <div class="checkout-header">
                        <i class="fas fa-right-from-bracket"></i>
                        <h2>Vehicle Check-out</h2>
                        <p class="mb-0">Process vehicle departure and free up parking space</p>
                    </div>

                    <div class="checkout-body">
                        <?php $flash = getFlashMessage();
                        if ($flash): ?>
                            <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'danger' : 'success'; ?> alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-<?php echo $flash['type'] == 'error' ? 'exclamation-triangle' : 'check-circle'; ?> me-2"></i>
                                <?php echo $flash['message']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($activeTickets)): ?>
                            <form method="post" class="checkout-form">
                                <div class="form-section">
                                    <div class="section-icon">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                    <div class="section-content">
                                        <label for="ticket_id" class="form-label fw-bold">Select Active Parking Ticket</label>
                                        <select class="form-select form-select-lg shadow-sm" id="ticket_id" name="ticket_id" required>
                                            <option value="" disabled selected>Choose a ticket to check out...</option>
                                            <?php foreach ($activeTickets as $ticket): ?>
                                                <option value="<?php echo $ticket['id']; ?>" data-details='<?php echo json_encode($ticket); ?>'>
                                                    <span class="fw-bold"><?php echo htmlspecialchars($ticket['plate_number']); ?></span> -
                                                    <?php echo htmlspecialchars($ticket['area_name'] . ' ' . $ticket['slot_number']); ?>
                                                    <small class="text-muted">(Since: <?php echo date('M d, H:i', strtotime($ticket['checkin_time'])); ?>)</small>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Select the vehicle ticket you want to check out from the parking system.
                                        </div>
                                    </div>
                                </div>

                                <div class="ticket-details mt-4" id="ticketDetails" style="display: none;">
                                    <div class="card border-warning shadow-sm">
                                        <div class="card-header bg-warning text-dark">
                                            <i class="fas fa-car me-2"></i>
                                            Vehicle Details
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-2"><strong>License Plate:</strong> <span id="plateNumber">-</span></p>
                                                    <p class="mb-2"><strong>Parking Area:</strong> <span id="areaName">-</span></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-2"><strong>Slot Number:</strong> <span id="slotNumber">-</span></p>
                                                    <p class="mb-2"><strong>Check-in Time:</strong> <span id="checkinTime">-</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="checkout-actions mt-4">
                                    <button type="submit" class="btn btn-checkout w-100">
                                        <i class="fas fa-right-from-bracket me-2"></i>
                                        Complete Check-out
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-parking display-1 text-muted mb-3"></i>
                                    <h4 class="text-muted">No Active Tickets</h4>
                                    <p class="text-muted">There are currently no vehicles checked into the parking system.</p>
                                    <a href="checkin.php" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>
                                        Check-in a Vehicle
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="checkout-footer">
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
        // Show ticket details when selection changes
        document.getElementById('ticket_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const details = selectedOption.getAttribute('data-details');

            if (details) {
                const ticket = JSON.parse(details);
                document.getElementById('plateNumber').textContent = ticket.plate_number;
                document.getElementById('areaName').textContent = ticket.area_name;
                document.getElementById('slotNumber').textContent = ticket.slot_number;
                document.getElementById('checkinTime').textContent = new Date(ticket.checkin_time).toLocaleString();
                document.getElementById('ticketDetails').style.display = 'block';
            } else {
                document.getElementById('ticketDetails').style.display = 'none';
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>