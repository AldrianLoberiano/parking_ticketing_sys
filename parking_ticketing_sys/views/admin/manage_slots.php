<?php
session_start();

require_once '../../controllers/AdminController.php';

AdminController::manageSlots();
?>
<?php
if (!isset($slots)) $slots = [];
if (!isset($areas)) $areas = [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slots — ParkEase</title>
    <link rel="icon" type="image/png" href="/tecketing/tecketing/parking_ticketing_sys/public/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/tecketing/tecketing/parking_ticketing_sys/public/css/admin.css" rel="stylesheet">
    <script>
        document.documentElement.className = 'sidebar-collapsed';
        var sc = localStorage.getItem('sidebarCollapsed');
        if (sc === 'false') {
            document.documentElement.className = '';
        } else {
            document.documentElement.className = 'sidebar-collapsed';
        }
    </script>
</head>

<body class="admin-body" id="adminBody">
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
                    <a href="dashboard.php" class="sidebar-nav-link" data-title="Dashboard"><i class="fas fa-th-large"></i> Dashboard</a>
                </li>
                <li class="sidebar-nav-label">Management</li>
                <li class="sidebar-nav-item">
                    <a href="manage_users.php" class="sidebar-nav-link" data-title="Users"><i class="fas fa-users"></i> Users</a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="manage_vehicles.php" class="sidebar-nav-link" data-title="Vehicles"><i class="fas fa-car"></i> Vehicles</a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="manage_areas.php" class="sidebar-nav-link" data-title="Areas"><i class="fas fa-map-marker-alt"></i> Areas</a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="manage_slots.php" class="sidebar-nav-link active" data-title="Slots"><i class="fas fa-parking"></i> Slots</a>
                </li>
                <li class="sidebar-nav-label">System</li>
                <li class="sidebar-nav-item">
                    <a href="backup.php" class="sidebar-nav-link" data-title="Backup & Recovery"><i class="fas fa-database"></i> Backup & Recovery</a>
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

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <main class="admin-main">
            <div class="admin-topbar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button class="mobile-menu-btn" id="mobileMenuBtn"><i class="fas fa-bars"></i></button>
                    <div class="topbar-title">
                        <h1>Slot Management</h1>
                        <p>Configure and monitor parking slots</p>
                    </div>
                </div>
                <div class="topbar-actions">
                    <button class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#createSlotModal">
                        <i class="fas fa-plus"></i> Add Slot
                    </button>
                </div>
            </div>

            <div class="admin-content">
                <?php $flash = getFlashMessage();
                if ($flash): ?>
                    <div class="admin-alert <?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                        <i class="fas fa-<?php echo $flash['type'] == 'error' ? 'exclamation-circle' : 'check-circle'; ?>"></i>
                        <?php echo htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>

                <div class="section-header">
                    <h2><i class="fas fa-parking"></i> All Slots (<?php echo count($slots); ?>)</h2>
                    <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="slotSearch" placeholder="Search slot number...">
                        </div>
                        <select class="filter-select" id="areaFilter">
                            <option value="">All Areas</option>
                            <?php foreach ($areas as $area): ?>
                                <option value="<?php echo strtolower($area['name']); ?>"><?php echo htmlspecialchars($area['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-body" style="padding:0;">
                        <div class="table-responsive">
                            <table class="admin-table" id="slotsTable">
                                <thead>
                                    <tr>
                                        <th>Area</th>
                                        <th>Slot Number</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($slots)): ?>
                                        <?php foreach ($slots as $slot): ?>
                                            <?php
                                            $status = isset($slot['status']) ? (string) $slot['status'] : '';
                                            $statusColors = ['available' => 'green', 'occupied' => 'amber'];
                                            $statusColor = $statusColors[$status] ?? 'gray';
                                            ?>
                                            <tr data-area="<?php echo strtolower($slot['area_name']); ?>" data-slot="<?php echo strtolower($slot['slot_number']); ?>">
                                                <td><strong><?php echo htmlspecialchars($slot['area_name']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($slot['slot_number']); ?></td>
                                                <td>
                                                    <span class="admin-badge <?php echo $statusColor; ?>">
                                                        <?php echo htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8'); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <form method="post" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?php echo $slot['id']; ?>">
                                                        <button type="submit" name="delete" class="btn-admin-sm btn-delete" onclick="return confirm('Delete this slot?')" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center" style="padding:2rem;color:var(--text-muted);">No slots found. Add areas first, then create slots.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Create Slot Modal -->
                <div class="modal fade" id="createSlotModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Slot</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="post">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="area_id" class="form-label">Area</label>
                                        <select class="form-select" id="area_id" name="area_id" required>
                                            <option value="">Select an area</option>
                                            <?php foreach ($areas as $area): ?>
                                                <option value="<?php echo $area['id']; ?>"><?php echo htmlspecialchars($area['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="slot_number" class="form-label">Slot Number</label>
                                        <input type="text" name="slot_number" class="form-control" placeholder="e.g. A-01" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" name="create" class="btn-admin-primary">Create Slot</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const body = document.body;
        (function() {
            const s = localStorage.getItem('sidebarCollapsed');
            if (s === 'false') {
                body.classList.remove('sidebar-collapsed');
            } else {
                body.classList.add('sidebar-collapsed');
            }
        })();
        document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
            const isMobile = window.innerWidth <= 991;
            if (isMobile) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', body.classList.contains('sidebar-collapsed'));
            }
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });

        // Search & Filter
        const searchInput = document.getElementById('slotSearch');
        const areaFilter = document.getElementById('areaFilter');

        function filterSlots() {
            const q = searchInput.value.toLowerCase();
            const area = areaFilter.value.toLowerCase();
            document.querySelectorAll('#slotsTable tbody tr[data-area]').forEach(row => {
                const rowArea = row.getAttribute('data-area');
                const rowSlot = row.getAttribute('data-slot');
                const matchSearch = !q || rowSlot.includes(q);
                const matchArea = !area || rowArea === area;
                row.style.display = (matchSearch && matchArea) ? '' : 'none';
            });
        }

        searchInput?.addEventListener('input', filterSlots);
        areaFilter?.addEventListener('change', filterSlots);
    </script>
</body>

</html>