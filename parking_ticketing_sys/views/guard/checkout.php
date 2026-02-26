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
    <title>Check-out — Guard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/tecketing/parking_ticketing_sys/public/css/admin.css" rel="stylesheet">
</head>

<body class="admin-body">
    <div class="admin-wrapper">

        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon" style="background:#f59e0b;"><i class="fas fa-shield-alt"></i></div>
                <div class="sidebar-brand-text">ParkGuard<small>Guard Panel</small></div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-label">Main</li>
                <li class="sidebar-nav-item">
                    <a href="dashboard.php" class="sidebar-nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
                </li>
                <li class="sidebar-nav-label">Operations</li>
                <li class="sidebar-nav-item">
                    <a href="checkin.php" class="sidebar-nav-link"><i class="fas fa-sign-in-alt"></i> Check-in Vehicle</a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="checkout.php" class="sidebar-nav-link active"><i class="fas fa-sign-out-alt"></i> Check-out Vehicle</a>
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
                        <h1>Check-out Vehicle</h1>
                        <p>Process a vehicle leaving the parking area</p>
                    </div>
                </div>
            </div>

            <div class="admin-content">
                <?php $flash = getFlashMessage();
                if ($flash): ?>
                    <div class="admin-alert <?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                        <i class="fas fa-<?php echo $flash['type'] == 'error' ? 'exclamation-circle' : 'check-circle'; ?>"></i>
                        <?php echo $flash['message']; ?>
                    </div>
                <?php endif; ?>

                <div class="admin-card" style="max-width:600px;">
                    <div class="admin-card-header">
                        <h3><i class="fas fa-sign-out-alt me-2" style="color:#ef4444;"></i>Vehicle Check-out</h3>
                    </div>
                    <div class="admin-card-body">
                        <?php if (!empty($activeTickets)): ?>
                            <form method="post">
                                <div class="mb-3">
                                    <label class="form-label" style="font-size:0.82rem;font-weight:600;color:var(--text-secondary);">Select Active Ticket</label>
                                    <select class="form-select" name="ticket_id" required style="font-size:1rem;padding:0.75rem;">
                                        <option value="">Select a ticket to check out</option>
                                        <?php foreach ($activeTickets as $ticket): ?>
                                            <option value="<?php echo $ticket['id']; ?>">
                                                <?php echo htmlspecialchars($ticket['plate_number'] . ' — ' . $ticket['area_name'] . ' ' . $ticket['slot_number'] . ' (Since: ' . $ticket['checkin_time'] . ')'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div style="display:flex;gap:0.5rem;">
                                    <button type="submit" class="btn-admin-primary" style="background:#ef4444;flex:1;justify-content:center;padding:0.75rem;">
                                        <i class="fas fa-check"></i> Confirm Check-out
                                    </button>
                                    <a href="dashboard.php" class="btn-admin-primary" style="background:var(--text-secondary);text-decoration:none;">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </a>
                                </div>
                            </form>
                        <?php else: ?>
                            <div style="text-align:center;padding:2rem;color:var(--text-muted);">
                                <i class="fas fa-inbox" style="font-size:2rem;margin-bottom:1rem;opacity:0.3;display:block;"></i>
                                <p style="margin:0;">No active tickets to check out.</p>
                                <a href="dashboard.php" class="btn-admin-primary" style="background:var(--text-secondary);text-decoration:none;margin-top:1rem;display:inline-flex;">
                                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                                </a>
                            </div>
                        <?php endif; ?>
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