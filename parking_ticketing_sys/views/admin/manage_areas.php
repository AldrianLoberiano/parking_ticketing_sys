<?php
session_start();

require_once '../../controllers/AdminController.php';

AdminController::manageAreas();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Areas — Parking Admin</title>
    <link rel="icon" type="image/png" href="/tecketing/parking_ticketing_sys/public/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/tecketing/parking_ticketing_sys/public/css/admin.css" rel="stylesheet">
    <script>document.documentElement.className='sidebar-collapsed';var sc=localStorage.getItem('sidebarCollapsed');if(sc==='false'){document.documentElement.className='';}else{document.documentElement.className='sidebar-collapsed';}</script>
</head>

<body class="admin-body" id="adminBody">
    <div class="admin-wrapper">

        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon"><img src="/tecketing/parking_ticketing_sys/public/images/logo.png" alt="Logo"></div>
                <div class="sidebar-brand-text">ParkAdmin<small>Ticketing System</small></div>
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
                    <a href="manage_areas.php" class="sidebar-nav-link active" data-title="Areas"><i class="fas fa-map-marker-alt"></i> Areas</a>
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
                        <h1>Area Management</h1>
                        <p>Create and organize parking zones</p>
                    </div>
                </div>
                <div class="topbar-actions">
                    <button class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#createAreaModal">
                        <i class="fas fa-plus"></i> Add Area
                    </button>
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

                <div class="section-header">
                    <h2><i class="fas fa-map-marker-alt"></i> All Areas (<?php echo count($areas); ?>)</h2>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="areaSearch" placeholder="Search areas...">
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-body" style="padding:0;">
                        <div class="table-responsive">
                            <table class="admin-table" id="areasTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($areas)): ?>
                                        <?php foreach ($areas as $area): ?>
                                            <tr data-name="<?php echo strtolower($area['name']); ?>" data-location="<?php echo strtolower($area['location'] ?? ''); ?>">
                                                <td><strong><?php echo htmlspecialchars($area['name']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($area['location'] ?? '—'); ?></td>
                                                <td>
                                                    <div style="display:flex;gap:0.35rem;">
                                                        <button class="btn-admin-sm btn-edit" onclick="editArea(<?php echo $area['id']; ?>, '<?php echo htmlspecialchars($area['name']); ?>', '<?php echo htmlspecialchars($area['location'] ?? ''); ?>')" title="Edit">
                                                            <i class="fas fa-pen"></i>
                                                        </button>
                                                        <form method="post" style="display:inline;">
                                                            <input type="hidden" name="id" value="<?php echo $area['id']; ?>">
                                                            <button type="submit" name="delete" class="btn-admin-sm btn-delete" onclick="return confirm('Delete this area?')" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="3" class="text-center" style="padding:2rem;color:var(--text-muted);">No areas found. Add one to get started.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Create Area Modal -->
                <div class="modal fade" id="createAreaModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Area</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="post">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Area Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="e.g. Basement Level 1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="location" class="form-label">Location</label>
                                        <input type="text" name="location" class="form-control" placeholder="e.g. Building A">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" name="create" class="btn-admin-primary">Create Area</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Area Modal -->
                <div class="modal fade" id="editAreaModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Area</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="post">
                                <div class="modal-body">
                                    <input type="hidden" name="id" id="editAreaId">
                                    <div class="mb-3">
                                        <label for="editAreaName" class="form-label">Area Name</label>
                                        <input type="text" name="name" id="editAreaName" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="editAreaLocation" class="form-label">Location</label>
                                        <input type="text" name="location" id="editAreaLocation" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" name="update" class="btn-admin-primary">Update Area</button>
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
        (function(){const s=localStorage.getItem('sidebarCollapsed');if(s==='false'){body.classList.remove('sidebar-collapsed');}else{body.classList.add('sidebar-collapsed');}})();
        document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
            const isMobile = window.innerWidth <= 991;
            if (isMobile) { sidebar.classList.toggle('show'); overlay.classList.toggle('show'); }
            else { body.classList.toggle('sidebar-collapsed'); localStorage.setItem('sidebarCollapsed', body.classList.contains('sidebar-collapsed')); }
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });

        // Edit area
        function editArea(id, name, location) {
            document.getElementById('editAreaId').value = id;
            document.getElementById('editAreaName').value = name;
            document.getElementById('editAreaLocation').value = location;
            new bootstrap.Modal(document.getElementById('editAreaModal')).show();
        }

        // Search
        document.getElementById('areaSearch')?.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#areasTable tbody tr[data-name]').forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const loc = row.getAttribute('data-location') || '';
                row.style.display = (!q || name.includes(q) || loc.includes(q)) ? '' : 'none';
            });
        });
    </script>
</body>

</html>