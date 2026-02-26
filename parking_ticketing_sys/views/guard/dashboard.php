<?php
session_start();

require_once '../../controllers/GuardController.php';

GuardController::dashboard();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Guard</title>
    <link rel="icon" type="image/png" href="/tecketing/parking_ticketing_sys/public/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/tecketing/parking_ticketing_sys/public/css/admin.css" rel="stylesheet">
</head>

<body class="admin-body">
    <div class="admin-wrapper">

        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon"><img src="/tecketing/parking_ticketing_sys/public/images/logo.png" alt="Logo"></div>
                <div class="sidebar-brand-text">ParkGuard<small>Guard Panel</small></div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-label">Main</li>
                <li class="sidebar-nav-item">
                    <a href="dashboard.php" class="sidebar-nav-link active"><i class="fas fa-th-large"></i> Dashboard</a>
                </li>
                <li class="sidebar-nav-label">Operations</li>
                <li class="sidebar-nav-item">
                    <a href="checkin.php" class="sidebar-nav-link"><i class="fas fa-sign-in-alt"></i> Check-in Vehicle</a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="checkout.php" class="sidebar-nav-link"><i class="fas fa-sign-out-alt"></i> Check-out Vehicle</a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar" style="background:#f59e0b;"><?php echo strtoupper(substr($_SESSION['user_name'] ?? 'G', 0, 1)); ?></div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guard'); ?></div>
                        <div class="sidebar-user-role">Guard</div>
                    </div>
                    <a href="/tecketing/parking_ticketing_sys/controllers/AuthController.php?action=logout" class="sidebar-logout-btn" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </aside>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <main class="admin-main">
            <div class="admin-topbar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button class="mobile-menu-btn" id="mobileMenuBtn"><i class="fas fa-bars"></i></button>
                    <div class="topbar-title">
                        <h1>Dashboard</h1>
                        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guard'); ?></p>
                    </div>
                </div>
                <div class="topbar-actions">
                    <a href="checkin.php" class="btn-admin-primary" style="background:#22c55e;text-decoration:none;">
                        <i class="fas fa-sign-in-alt"></i> Check-in
                    </a>
                    <a href="checkout.php" class="btn-admin-primary" style="background:#ef4444;text-decoration:none;">
                        <i class="fas fa-sign-out-alt"></i> Check-out
                    </a>
                </div>
            </div>

            <div class="admin-content">
                <!-- Stats -->
                <div class="stats-row">
                    <div class="stat-card green">
                        <div class="stat-card-icon green"><i class="fas fa-parking"></i></div>
                        <div class="stat-card-info">
                            <h3><?php echo count($availableSlots); ?></h3>
                            <p>Available Slots</p>
                        </div>
                    </div>
                    <div class="stat-card amber">
                        <div class="stat-card-icon amber"><i class="fas fa-ticket-alt"></i></div>
                        <div class="stat-card-info">
                            <h3><?php echo count($activeTickets); ?></h3>
                            <p>Active Tickets</p>
                        </div>
                    </div>
                </div>

                <!-- Available Slots -->
                <div class="section-header">
                    <h2><i class="fas fa-parking"></i> Available Slots</h2>
                </div>
                <div class="admin-card" style="margin-bottom:1.5rem;">
                    <div class="admin-card-body" style="padding:0;">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Area</th>
                                        <th>Slot Number</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($availableSlots)): ?>
                                        <?php foreach ($availableSlots as $slot): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($slot['area_name']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($slot['slot_number']); ?></td>
                                                <td><span class="admin-badge green">Available</span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="3" class="text-center" style="padding:2rem;color:var(--text-muted);">No available slots</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Active Tickets -->
                <div class="section-header">
                    <h2><i class="fas fa-ticket-alt"></i> Active Tickets</h2>
                </div>
                <div class="admin-card">
                    <div class="admin-card-body" style="padding:0;">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Plate Number</th>
                                        <th>Slot</th>
                                        <th>Checked In</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($activeTickets)): ?>
                                        <?php foreach ($activeTickets as $ticket): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($ticket['plate_number']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($ticket['area_name'] . ' — ' . $ticket['slot_number']); ?></td>
                                                <td><?php echo htmlspecialchars($ticket['checkin_time']); ?></td>
                                                <td><span class="admin-badge amber">Active</span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4" class="text-center" style="padding:2rem;color:var(--text-muted);">No active tickets</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        document.getElementById('mobileMenuBtn')?.addEventListener('click', () => { sidebar.classList.toggle('show'); overlay.classList.toggle('show'); });
        overlay?.addEventListener('click', () => { sidebar.classList.remove('show'); overlay.classList.remove('show'); });
    </script>
</body>

</html>