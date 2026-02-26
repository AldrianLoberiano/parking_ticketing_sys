<?php
session_start();
require_once '../../controllers/AdminController.php';

AdminController::dashboard(); // to get analytics + tickets

// Extract globals into local scope
$tickets = $GLOBALS['tickets'] ?? [];
$analytics = $GLOBALS['analytics'] ?? [];

// Gather data for charts
    foreach ($tickets as $ticket) {
        $status = $ticket['status'] ?? 'unknown';
        if (isset($ticketsByStatus[$status])) {
            $ticketsByStatus[$status]++;
        }
        $area = $ticket['area_name'] ?? 'Unknown';
        if (!isset($ticketsByArea[$area])) {
            $ticketsByArea[$area] = 0;
        }
        $ticketsByArea[$area]++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics — Parking Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/tecketing/parking_ticketing_sys/public/css/admin.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>

<body class="admin-body">
    <div class="admin-wrapper">

        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon"><i class="fas fa-parking"></i></div>
                <div class="sidebar-brand-text">ParkAdmin<small>Ticketing System</small></div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-label">Main</li>
                <li class="sidebar-nav-item">
                    <a href="dashboard.php" class="sidebar-nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
                </li>
                <li class="sidebar-nav-label">Management</li>
                <li class="sidebar-nav-item">
                    <a href="manage_users.php" class="sidebar-nav-link"><i class="fas fa-users"></i> Users</a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="manage_areas.php" class="sidebar-nav-link"><i class="fas fa-map-marker-alt"></i> Areas</a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="manage_slots.php" class="sidebar-nav-link"><i class="fas fa-parking"></i> Slots</a>
                </li>
                <li class="sidebar-nav-label">Reports</li>
                <li class="sidebar-nav-item">
                    <a href="analytics.php" class="sidebar-nav-link active"><i class="fas fa-chart-bar"></i> Analytics</a>
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
                    <a href="/tecketing/parking_ticketing_sys/controllers/AuthController.php?action=logout" class="sidebar-logout-btn" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </aside>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <main class="admin-main">
            <div class="admin-topbar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button class="mobile-menu-btn" id="mobileMenuBtn"><i class="fas fa-bars"></i></button>
                    <div class="topbar-title">
                        <h1>Analytics</h1>
                        <p>Track performance and ticket activity</p>
                    </div>
                </div>
                <div class="topbar-actions">
                    <span class="topbar-date"><i class="far fa-calendar-alt"></i> <span id="currentDate"></span></span>
                </div>
            </div>

            <div class="admin-content">
                <!-- Stat Cards -->
                <div class="stats-row">
                    <div class="stat-card blue">
                        <div class="stat-card-icon blue"><i class="fas fa-ticket-alt"></i></div>
                        <div class="stat-card-info">
                            <h3><?php echo $analytics['total_tickets'] ?? 0; ?></h3>
                            <p>Total Tickets</p>
                        </div>
                    </div>
                    <div class="stat-card amber">
                        <div class="stat-card-icon amber"><i class="fas fa-clock"></i></div>
                        <div class="stat-card-info">
                            <h3><?php echo $analytics['active_tickets'] ?? 0; ?></h3>
                            <p>Active Tickets</p>
                        </div>
                    </div>
                    <div class="stat-card purple">
                        <div class="stat-card-icon purple"><i class="fas fa-hourglass-half"></i></div>
                        <div class="stat-card-info">
                            <h3><?php echo round($analytics['avg_duration'] ?? 0, 1); ?>h</h3>
                            <p>Average Duration</p>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.5rem;">
                    <div class="chart-container">
                        <h3><i class="fas fa-chart-bar me-2" style="color:var(--accent-blue);"></i>Tickets by Status</h3>
                        <canvas id="statusChart" height="220"></canvas>
                    </div>
                    <div class="chart-container">
                        <h3><i class="fas fa-chart-bar me-2" style="color:var(--accent-green);"></i>Tickets by Area</h3>
                        <canvas id="areaChart" height="220"></canvas>
                    </div>
                </div>

                <!-- Tickets Table -->
                <div class="section-header">
                    <h2><i class="fas fa-list"></i> All Tickets</h2>
                    <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="ticketSearch" placeholder="Search plate or owner...">
                        </div>
                        <select class="filter-select" id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-body" style="padding:0;">
                        <div class="table-responsive">
                            <table class="admin-table" id="ticketsTable">
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
                                        <?php foreach ($tickets as $ticket): ?>
                                            <tr data-plate="<?php echo htmlspecialchars(strtolower($ticket['plate_number']), ENT_QUOTES); ?>" data-owner="<?php echo htmlspecialchars(strtolower($ticket['owner_name']), ENT_QUOTES); ?>" data-status="<?php echo $ticket['status']; ?>">
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
                                        <tr><td colspan="6" class="text-center" style="padding:2rem;color:var(--text-muted);">No tickets found</td></tr>
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
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });

        // Mobile sidebar
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        document.getElementById('mobileMenuBtn')?.addEventListener('click', () => { sidebar.classList.toggle('show'); overlay.classList.toggle('show'); });
        overlay?.addEventListener('click', () => { sidebar.classList.remove('show'); overlay.classList.remove('show'); });

        // Search & Filter
        const searchInput = document.getElementById('ticketSearch');
        const statusFilter = document.getElementById('statusFilter');
        function filterTickets() {
            const query = searchInput.value.toLowerCase();
            const status = statusFilter.value;
            document.querySelectorAll('#ticketsTable tbody tr[data-plate]').forEach(row => {
                const plate = row.getAttribute('data-plate');
                const owner = row.getAttribute('data-owner');
                const rowStatus = row.getAttribute('data-status');
                const matchSearch = !query || plate.includes(query) || owner.includes(query);
                const matchStatus = !status || rowStatus === status;
                row.style.display = matchSearch && matchStatus ? '' : 'none';
            });
        }
        searchInput?.addEventListener('input', filterTickets);
        statusFilter?.addEventListener('change', filterTickets);

        // Charts
        const chartColors = {
            blue: '#4f6ef7',
            green: '#22c55e',
            amber: '#f59e0b',
            red: '#ef4444',
            purple: '#8b5cf6',
            cyan: '#06b6d4'
        };

        // Status Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'bar',
            data: {
                labels: ['Active', 'Completed'],
                datasets: [{
                    label: 'Tickets',
                    data: [<?php echo $ticketsByStatus['active']; ?>, <?php echo $ticketsByStatus['completed']; ?>],
                    backgroundColor: [chartColors.amber, chartColors.green],
                    borderRadius: 6,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Area Chart
        new Chart(document.getElementById('areaChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($ticketsByArea)); ?>,
                datasets: [{
                    label: 'Tickets',
                    data: <?php echo json_encode(array_values($ticketsByArea)); ?>,
                    backgroundColor: [chartColors.blue, chartColors.green, chartColors.amber, chartColors.purple, chartColors.cyan, chartColors.red],
                    borderRadius: 6,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</body>

</html>