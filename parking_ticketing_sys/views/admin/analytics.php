<?php
session_start();

require_once '../../controllers/AdminController.php';

AdminController::dashboard(); // to get analytics

include '../layouts/header.php'; ?>

<h2>Parking Analytics</h2>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Total Tickets</h5>
                <h3><?php echo $analytics['total_tickets'] ?? 0; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Active Tickets</h5>
                <h3><?php echo $analytics['active_tickets'] ?? 0; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Average Duration (hours)</h5>
                <h3><?php echo round($analytics['avg_duration'] ?? 0, 2); ?></h3>
            </div>
        </div>
    </div>
</div>

<h3 class="mt-4">Recent Tickets</h3>
<table class="table">
    <thead>
        <tr>
            <th>Plate</th>
            <th>Owner</th>
            <th>Slot</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach (array_slice($tickets, 0, 20) as $ticket): ?>
            <tr>
                <td><?php echo $ticket['plate_number']; ?></td>
                <td><?php echo $ticket['owner_name']; ?></td>
                <td><?php echo $ticket['area_name'] . ' ' . $ticket['slot_number']; ?></td>
                <td><?php echo $ticket['checkin_time']; ?></td>
                <td><?php echo $ticket['checkout_time'] ?: '-'; ?></td>
                <td><span class="badge bg-<?php echo $ticket['status'] == 'active' ? 'warning' : 'success'; ?>"><?php echo $ticket['status']; ?></span></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php // include '../layouts/footer.php'; 
?>