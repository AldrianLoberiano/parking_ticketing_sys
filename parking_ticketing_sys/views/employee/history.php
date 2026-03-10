<?php
session_start();

require_once '../../controllers/EmployeeController.php';

EmployeeController::history();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking History — Employee</title>
    <link rel="icon" type="image/png" href="/parking_ticketing_system_v2/parking_ticketing_sys/public/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/parking_ticketing_system_v2/parking_ticketing_sys/public/css/admin.css" rel="stylesheet">
</head>

<body class="admin-body">
    <div class="admin-wrapper">

        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon"><img src="/parking_ticketing_system_v2/parking_ticketing_sys/public/images/logo.png" alt="Logo"></div>
                <div class="sidebar-brand-text">ParkPortal<small>Employee Panel</small></div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-label">Main</li>
                <li class="sidebar-nav-item">
                    <a href="dashboard.php" class="sidebar-nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
                </li>
                <li class="sidebar-nav-label">Vehicles</li>
                <li class="sidebar-nav-item">
                    <a href="register_vehicle.php" class="sidebar-nav-link"><i class="fas fa-plus-circle"></i> Register Vehicle</a>
                </li>
                <li class="sidebar-nav-label">History</li>
                <li class="sidebar-nav-item">
                    <a href="history.php" class="sidebar-nav-link active"><i class="fas fa-history"></i> Parking History</a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar" style="background:#22c55e;"><?php echo strtoupper(substr($_SESSION['user_name'] ?? 'E', 0, 1)); ?></div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Employee'); ?></div>
                        <div class="sidebar-user-role">Employee</div>
                    </div>
                    <a href="/parking_ticketing_system_v2/parking_ticketing_sys/controllers/AuthController.php?action=logout" class="sidebar-logout-btn" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </aside>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <main class="admin-main">
            <div class="admin-topbar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button class="mobile-menu-btn" id="mobileMenuBtn"><i class="fas fa-bars"></i></button>
                    <div class="topbar-title">
                        <h1>Parking History</h1>
                        <p>View all your past and active parking tickets</p>
                    </div>
                </div>
                <div class="topbar-actions">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="historySearch" placeholder="Search plate...">
                    </div>
                </div>
            </div>

            <div class="admin-content">
                <div class="admin-card">
                    <div class="admin-card-body" style="padding:0;">
                        <div class="table-responsive">
                            <table class="admin-table" id="historyTable">
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
                                    <?php if (!empty($history)): ?>
                                        <?php foreach ($history as $ticket): ?>
                                            <tr data-plate="<?php echo htmlspecialchars(strtolower($ticket['plate_number']), ENT_QUOTES, 'UTF-8'); ?>">
                                                <td><strong><?php echo htmlspecialchars($ticket['plate_number']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($ticket['area_name'] . ' — ' . $ticket['slot_number']); ?></td>
                                                <td><?php echo htmlspecialchars($ticket['checkin_time']); ?></td>
                                                <td><?php echo htmlspecialchars($ticket['checkout_time'] ?: '—'); ?></td>
                                                <td>
                                                    <?php
                                                    if ($ticket['checkout_time']) {
                                                        $checkin = new DateTime($ticket['checkin_time']);
                                                        $checkout = new DateTime($ticket['checkout_time']);
                                                        $interval = $checkin->diff($checkout);
                                                        echo $interval->format('%hh %im');
                                                    } else {
                                                        echo '—';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <span class="admin-badge <?php echo $ticket['status'] == 'active' ? 'amber' : 'green'; ?>">
                                                        <?php echo ucfirst($ticket['status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center" style="padding:2rem;color:var(--text-muted);">No parking history found</td></tr>
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

        // Search
        document.getElementById('historySearch')?.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#historyTable tbody tr[data-plate]').forEach(row => {
                row.style.display = (!q || row.getAttribute('data-plate').includes(q)) ? '' : 'none';
            });
        });
    </script>
</body>

</html>