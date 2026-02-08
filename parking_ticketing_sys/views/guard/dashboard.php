<?php
session_start();

require_once '../../controllers/GuardController.php';

GuardController::dashboard();

include '../layouts/header.php'; ?>

<h2>Guard Dashboard</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Available Slots</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php foreach ($availableSlots as $slot): ?>
                        <li class="list-group-item"><?php echo $slot['area_name'] . ' - ' . $slot['slot_number']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Active Tickets</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php foreach ($activeTickets as $ticket): ?>
                        <li class="list-group-item"><?php echo $ticket['plate_number'] . ' - ' . $ticket['area_name'] . ' ' . $ticket['slot_number']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="checkin.php" class="btn btn-success btn-lg">Check-in Vehicle</a>
    <a href="checkout.php" class="btn btn-danger btn-lg">Check-out Vehicle</a>
    <a href="/tecketing/parking_ticketing_sys/controllers/AuthController.php?action=logout" class="btn btn-secondary">Logout</a>
</div>

<?php include '../layouts/footer.php'; ?>