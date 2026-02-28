<?php
session_start();
require_once '../../controllers/AdminController.php';

AdminController::manageVehicles();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles — ParkEase</title>
    <link rel="icon" type="image/png" href="/tecketing/tecketing/parking_ticketing_sys/public/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/tecketing/tecketing/parking_ticketing_sys/public/css/admin.css" rel="stylesheet">
    <script>document.documentElement.className='sidebar-collapsed';var sc=localStorage.getItem('sidebarCollapsed');if(sc==='false'){document.documentElement.className='';}else{document.documentElement.className='sidebar-collapsed';}</script>
</head>

<body class="admin-body" id="adminBody">
    <div class="admin-wrapper">

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
                    <a href="manage_vehicles.php" class="sidebar-nav-link active" data-title="Vehicles"><i class="fas fa-car"></i> Vehicles</a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="manage_areas.php" class="sidebar-nav-link" data-title="Areas"><i class="fas fa-map-marker-alt"></i> Areas</a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="manage_slots.php" class="sidebar-nav-link" data-title="Slots"><i class="fas fa-parking"></i> Slots</a>
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
                    <a href="/tecketing/tecketing/parking_ticketing_sys/controllers/AuthController.php?action=logout" class="sidebar-logout-btn" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </aside>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <main class="admin-main">
            <div class="admin-topbar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button class="mobile-menu-btn" id="mobileMenuBtn"><i class="fas fa-bars"></i></button>
                    <div class="topbar-title">
                        <h1>Manage Vehicles</h1>
                        <p>View and manage all registered vehicles</p>
                    </div>
                </div>
                <div class="topbar-actions">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="vehicleSearch" placeholder="Search plate, model, owner...">
                    </div>
                    <select class="filter-select" id="typeFilter">
                        <option value="">All Types</option>
                        <option value="car">Car</option>
                        <option value="motorcycle">Motorcycle</option>
                        <option value="suv">SUV</option>
                        <option value="van">Van</option>
                        <option value="truck">Truck</option>
                        <option value="bicycle">Bicycle</option>
                    </select>
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

                <!-- Stats -->
                <div class="stats-row">
                    <div class="stat-card blue">
                        <div class="stat-card-icon blue"><i class="fas fa-car"></i></div>
                        <div class="stat-card-info">
                            <h3><?php echo count($vehicles); ?></h3>
                            <p>Total Vehicles</p>
                        </div>
                    </div>
                    <?php
                    $typeCounts = [];
                    foreach ($vehicles as $v) {
                        $type = strtolower($v['model'] ?? 'Unknown');
                        // Try to detect vehicle type from model name
                        if (stripos($type, 'motor') !== false || stripos($type, 'bike') !== false) $vtype = 'Motorcycle';
                        elseif (stripos($type, 'suv') !== false) $vtype = 'SUV';
                        elseif (stripos($type, 'van') !== false) $vtype = 'Van';
                        elseif (stripos($type, 'truck') !== false) $vtype = 'Truck';
                        else $vtype = 'Car';
                        if (!isset($typeCounts[$vtype])) $typeCounts[$vtype] = 0;
                        $typeCounts[$vtype]++;
                    }
                    $colors = ['Car' => 'green', 'Motorcycle' => 'amber', 'SUV' => 'purple', 'Van' => 'cyan', 'Truck' => 'red'];
                    $icons = ['Car' => 'fa-car', 'Motorcycle' => 'fa-motorcycle', 'SUV' => 'fa-truck-monster', 'Van' => 'fa-shuttle-van', 'Truck' => 'fa-truck'];
                    foreach ($typeCounts as $type => $count):
                    ?>
                    <div class="stat-card <?php echo $colors[$type] ?? 'blue'; ?>">
                        <div class="stat-card-icon <?php echo $colors[$type] ?? 'blue'; ?>"><i class="fas <?php echo $icons[$type] ?? 'fa-car'; ?>"></i></div>
                        <div class="stat-card-info">
                            <h3><?php echo $count; ?></h3>
                            <p><?php echo $type; ?>s</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Vehicles Table -->
                <div class="section-header">
                    <h2><i class="fas fa-car"></i> All Vehicles</h2>
                </div>

                <div class="admin-card">
                    <div class="admin-card-body" style="padding:0;">
                        <div class="table-responsive">
                            <table class="admin-table" id="vehiclesTable">
                                <thead>
                                    <tr>
                                        <th>Plate Number</th>
                                        <th>Model / Type</th>
                                        <th>Color</th>
                                        <th>Owner</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($vehicles)): ?>
                                        <?php foreach ($vehicles as $vehicle): ?>
                                            <tr data-plate="<?php echo htmlspecialchars(strtolower($vehicle['plate_number']), ENT_QUOTES); ?>"
                                                data-model="<?php echo htmlspecialchars(strtolower($vehicle['model'] ?? ''), ENT_QUOTES); ?>"
                                                data-owner="<?php echo htmlspecialchars(strtolower($vehicle['owner_name']), ENT_QUOTES); ?>">
                                                <td><strong><?php echo htmlspecialchars($vehicle['plate_number']); ?></strong></td>
                                                <td>
                                                    <?php
                                                    $model = strtolower($vehicle['model'] ?? '');
                                                    if (stripos($model, 'motor') !== false || stripos($model, 'bike') !== false) {
                                                        echo '<i class="fas fa-motorcycle me-1" style="color:var(--accent-amber);"></i>';
                                                    } elseif (stripos($model, 'suv') !== false) {
                                                        echo '<i class="fas fa-truck-monster me-1" style="color:var(--accent-purple);"></i>';
                                                    } elseif (stripos($model, 'van') !== false) {
                                                        echo '<i class="fas fa-shuttle-van me-1" style="color:var(--accent-cyan);"></i>';
                                                    } elseif (stripos($model, 'truck') !== false) {
                                                        echo '<i class="fas fa-truck me-1" style="color:var(--accent-red);"></i>';
                                                    } else {
                                                        echo '<i class="fas fa-car me-1" style="color:var(--accent-green);"></i>';
                                                    }
                                                    echo htmlspecialchars($vehicle['model'] ?? 'N/A');
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($vehicle['color'])): ?>
                                                        <span style="display:inline-flex;align-items:center;gap:0.35rem;">
                                                            <span style="width:14px;height:14px;border-radius:50%;background:<?php echo htmlspecialchars($vehicle['color']); ?>;border:1px solid rgba(0,0,0,0.15);display:inline-block;"></span>
                                                            <?php echo htmlspecialchars($vehicle['color']); ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span style="color:var(--text-muted);">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($vehicle['owner_name']); ?></td>
                                                <td><?php echo htmlspecialchars($vehicle['created_at'] ?? '—'); ?></td>
                                                <td>
                                                    <div style="display:flex;gap:0.35rem;">
                                                        <button class="btn-admin-sm btn-edit" data-bs-toggle="modal" data-bs-target="#editVehicle<?php echo $vehicle['id']; ?>" title="Edit">
                                                            <i class="fas fa-pen"></i>
                                                        </button>
                                                        <form method="post" style="display:inline;" onsubmit="return confirm('Delete this vehicle?')">
                                                            <input type="hidden" name="id" value="<?php echo $vehicle['id']; ?>">
                                                            <button type="submit" name="delete" class="btn-admin-sm btn-delete" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editVehicle<?php echo $vehicle['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"><i class="fas fa-car me-2"></i>Edit Vehicle</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" value="<?php echo $vehicle['id']; ?>">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Plate Number</label>
                                                                    <input type="text" class="form-control" name="plate_number" value="<?php echo htmlspecialchars($vehicle['plate_number']); ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Model / Type</label>
                                                                    <select class="form-select" name="model" required>
                                                                        <option value="">Select type</option>
                                                                        <?php
                                                                        $types = ['Car', 'Motorcycle', 'SUV', 'Van', 'Truck', 'Bicycle'];
                                                                        foreach ($types as $t):
                                                                        ?>
                                                                            <option value="<?php echo $t; ?>" <?php echo ($vehicle['model'] == $t) ? 'selected' : ''; ?>><?php echo $t; ?></option>
                                                                        <?php endforeach; ?>
                                                                        <?php if (!in_array($vehicle['model'], $types)): ?>
                                                                            <option value="<?php echo htmlspecialchars($vehicle['model']); ?>" selected><?php echo htmlspecialchars($vehicle['model']); ?></option>
                                                                        <?php endif; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Color</label>
                                                                    <input type="text" class="form-control" name="color" value="<?php echo htmlspecialchars($vehicle['color'] ?? ''); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="update" class="btn-admin-primary">Save Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center" style="padding:2rem;color:var(--text-muted);">No vehicles registered yet</td></tr>
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
        const body = document.body;
        (function(){const s=localStorage.getItem('sidebarCollapsed');if(s==='false'){body.classList.remove('sidebar-collapsed');}else{body.classList.add('sidebar-collapsed');}})();
        document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
            const isMobile = window.innerWidth <= 991;
            if (isMobile) { sidebar.classList.toggle('show'); overlay.classList.toggle('show'); }
            else { body.classList.toggle('sidebar-collapsed'); localStorage.setItem('sidebarCollapsed', body.classList.contains('sidebar-collapsed')); }
        });
        overlay?.addEventListener('click', () => { sidebar.classList.remove('show'); overlay.classList.remove('show'); });

        // Search & Filter
        const searchInput = document.getElementById('vehicleSearch');
        const typeFilter = document.getElementById('typeFilter');

        function filterVehicles() {
            const query = searchInput.value.toLowerCase();
            const type = typeFilter.value.toLowerCase();
            document.querySelectorAll('#vehiclesTable tbody tr[data-plate]').forEach(row => {
                const plate = row.getAttribute('data-plate');
                const model = row.getAttribute('data-model');
                const owner = row.getAttribute('data-owner');
                const matchSearch = !query || plate.includes(query) || model.includes(query) || owner.includes(query);
                const matchType = !type || model.includes(type);
                row.style.display = matchSearch && matchType ? '' : 'none';
            });
        }

        searchInput?.addEventListener('input', filterVehicles);
        typeFilter?.addEventListener('change', filterVehicles);
    </script>
</body>

</html>
