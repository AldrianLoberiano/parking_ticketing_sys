<?php
session_start();

require_once '../../controllers/EmployeeController.php';

EmployeeController::history();

include '../layouts/header.php'; ?>

<h2>Parking History</h2>

<table class="table">
    <thead>
        <tr>
            <th>Plate Number</th>
            <th>Slot</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Duration</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($history as $ticket): ?>
            <tr>
                <td><?php echo $ticket['plate_number']; ?></td>
                <td><?php echo $ticket['area_name'] . ' ' . $ticket['slot_number']; ?></td>
                <td><?php echo $ticket['checkin_time']; ?></td>
                <td><?php echo $ticket['checkout_time'] ?: '-'; ?></td>
                <td>
                    <?php
                    if ($ticket['checkout_time']) {
                        $checkin = new DateTime($ticket['checkin_time']);
                        $checkout = new DateTime($ticket['checkout_time']);
                        $interval = $checkin->diff($checkout);
                        echo $interval->format('%h hours %i minutes');
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
                <td><span class="badge bg-<?php echo $ticket['status'] == 'active' ? 'warning' : 'success'; ?>"><?php echo $ticket['status']; ?></span></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="mt-4">
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

<?php include '../layouts/footer.php'; ?>
