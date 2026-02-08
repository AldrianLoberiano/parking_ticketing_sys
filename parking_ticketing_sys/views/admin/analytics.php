<?php
session_start();

require_once '../../controllers/AdminController.php';

AdminController::dashboard(); // to get analytics

include '../layouts/admin_layout.php'; ?>

<h2><i class="fas fa-chart-bar me-2"></i>Parking Analytics</h2>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body text-center">
                <i class="fas fa-ticket-alt fa-2x text-primary mb-2"></i>
                <h5 class="card-title">Total Tickets</h5>
                <h3 class="text-primary"><?php echo $analytics['total_tickets'] ?? 0; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-warning">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                <h5 class="card-title">Active Tickets</h5>
                <h3 class="text-warning"><?php echo $analytics['active_tickets'] ?? 0; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-info">
            <div class="card-body text-center">
                <i class="fas fa-hourglass-half fa-2x text-info mb-2"></i>
                <h5 class="card-title">Average Duration</h5>
                <h3 class="text-info"><?php echo round($analytics['avg_duration'] ?? 0, 2); ?>h</h3>
            </div>
        </div>
    </div>
</div>

<h3 class="mb-3"><i class="fas fa-history me-2"></i>Recent Tickets</h3>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><i class="fas fa-car me-2"></i>Plate Number</th>
                        <th><i class="fas fa-user me-2"></i>Owner</th>
                        <th><i class="fas fa-parking me-2"></i>Slot</th>
                        <th><i class="fas fa-sign-in-alt me-2"></i>Check-in</th>
                        <th><i class="fas fa-sign-out-alt me-2"></i>Check-out</th>
                        <th><i class="fas fa-info-circle me-2"></i>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($tickets, 0, 20) as $ticket): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ticket['plate_number']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['owner_name']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['area_name'] . ' ' . $ticket['slot_number']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['checkin_time']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['checkout_time'] ?: '-'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $ticket['status'] == 'active' ? 'warning' : 'success'; ?>">
                                    <?php echo ucfirst($ticket['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>