<?php
session_start();

require_once '../../controllers/AdminController.php';

AdminController::dashboard();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — ParkEase</title>
    <link rel="icon" type="image/png" href="/tecketing/tecketing/parking_ticketing_sys/public/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/tecketing/tecketing/parking_ticketing_sys/public/css/admin.css" rel="stylesheet">
</head>

<body class="admin-body">
    <div class="admin-wrapper">

        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon"><img src="/tecketing/tecketing/parking_ticketing_sys/public/images/logo.png" alt="Logo"></div>
                <div class="sidebar-brand-text">ParkEase<small>Parking Control and Monitoring</small></div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-label">Main</li>
                <li class="sidebar-nav-item">
                    <a href="dashboard.php" class="sidebar-nav-link active"><i class="fas fa-th-large"></i> Dashboard</a>
                </li>
                <li class="sidebar-nav-label">Management</li>
                <li class="sidebar-nav-item">
                    <a href="manage_users.php" class="sidebar-nav-link"><i class="fas fa-users"></i> Users</a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="manage_vehicles.php" class="sidebar-nav-link"><i class="fas fa-car"></i> Vehicles</a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="manage_areas.php" class="sidebar-nav-link"><i class="fas fa-map-marker-alt"></i> Areas</a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="manage_slots.php" class="sidebar-nav-link"><i class="fas fa-parking"></i> Slots</a>
                </li>
                <li class="sidebar-nav-label">Reports</li>
                <li class="sidebar-nav-item">
                    <a href="analytics.php" class="sidebar-nav-link"><i class="fas fa-chart-bar"></i> Analytics</a>
                </li>
                <li class="sidebar-nav-label">System</li>
                <li class="sidebar-nav-item">
                    <a href="backup.php" class="sidebar-nav-link"><i class="fas fa-database"></i> Backup & Recovery</a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar"><?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?></div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></div>
                        <div class="sidebar-user-role">Administrator</div>
                    </div>
                    <a href="/tecketing/tecketing/parking_ticketing_sys/controllers/AuthController.php?action=logout" class="sidebar-logout-btn" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-topbar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button class="mobile-menu-btn" id="mobileMenuBtn"><i class="fas fa-bars"></i></button>
                    <div class="topbar-title">
                        <h1>Dashboard</h1>
                        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></p>
                    </div>
                </div>
                <div class="topbar-actions">
                    <span class="topbar-date"><i class="far fa-calendar-alt"></i> <span id="currentDate"></span></span>
                </div>
            </div>

            <div class="admin-content">
                <!-- Stats -->
                <div class="stats-row">
                    <div class="stat-card blue">
                        <div class="stat-card-icon blue"><i class="fas fa-users"></i></div>
                        <div class="stat-card-info">
                            <h3><?php echo count($users); ?></h3>
                            <p>Total Users</p>
                        </div>
                    </div>
                    <div class="stat-card green">
                        <div class="stat-card-icon green"><i class="fas fa-car"></i></div>
                        <div class="stat-card-info">
                            <h3><?php echo count($vehicles); ?></h3>
                            <p>Registered Vehicles</p>
                        </div>
                    </div>
                    <div class="stat-card cyan">
                        <div class="stat-card-icon cyan"><i class="fas fa-parking"></i></div>
                        <div class="stat-card-info">
                            <h3><?php echo count($slots); ?></h3>
                            <p>Total Slots</p>
                        </div>
                    </div>
                    <div class="stat-card amber">
                        <div class="stat-card-icon amber"><i class="fas fa-ticket-alt"></i></div>
                        <div class="stat-card-info">
                            <h3><?php echo $analytics['active_tickets'] ?? 0; ?></h3>
                            <p>Active Tickets</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="section-header">
                    <h2><i class="fas fa-history"></i> Recent Tickets</h2>
                </div>
                <div class="admin-card">
                    <div class="admin-card-body" style="padding:0;">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Plate Number</th>
                                        <th>Owner</th>
                                        <th>Slot</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($tickets)): ?>
                                        <?php foreach (array_slice($tickets, 0, 10) as $ticket): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($ticket['plate_number']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($ticket['owner_name']); ?></td>
                                                <td><?php echo htmlspecialchars($ticket['area_name'] . ' — ' . $ticket['slot_number']); ?></td>
                                                <td><?php echo htmlspecialchars($ticket['checkin_time']); ?></td>
                                                <td><?php echo htmlspecialchars($ticket['checkout_time'] ?: '—'); ?></td>
                                                <td>
                                                    <span class="admin-badge <?php echo $ticket['status'] == 'active' ? 'amber' : 'green'; ?>">
                                                        <?php echo ucfirst($ticket['status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center" style="padding:2rem;color:var(--text-muted);">No tickets yet</td></tr>
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
        // Date display
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });

        // Mobile sidebar toggle
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const menuBtn = document.getElementById('mobileMenuBtn');

        menuBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    </script>
</body>

</html>