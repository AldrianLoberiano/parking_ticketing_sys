<?php
session_start();

require_once '../../controllers/EmployeeController.php';

EmployeeController::dashboard();

include '../layouts/header.php'; ?>

<h2>Employee Dashboard</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>My Vehicles</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php foreach ($vehicles as $vehicle): ?>
                        <li class="list-group-item"><?php echo $vehicle['plate_number'] . ' - ' . $vehicle['model'] . ' (' . $vehicle['color'] . ')'; ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="register_vehicle.php" class="btn btn-primary mt-3">Register New Vehicle</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Active Parking Tickets</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php foreach ($activeTickets as $ticket): ?>
                        <li class="list-group-item"><?php echo $ticket['plate_number'] . ' - ' . $ticket['area_name'] . ' ' . $ticket['slot_number'] . ' (Since: ' . $ticket['checkin_time'] . ')'; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="history.php" class="btn btn-info">View Parking History</a>
    <a href="/tecketing/parking_ticketing_sys/controllers/AuthController.php?action=logout" class="btn btn-danger">Logout</a>
</div>

<?php include '../layouts/footer.php'; ?>