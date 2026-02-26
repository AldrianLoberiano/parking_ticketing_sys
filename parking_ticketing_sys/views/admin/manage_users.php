<?php
session_start();

require_once '../../controllers/AdminController.php';

AdminController::manageUsers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users — Parking Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/tecketing/parking_ticketing_sys/public/css/admin.css" rel="stylesheet">
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
                    <a href="manage_users.php" class="sidebar-nav-link active"><i class="fas fa-users"></i> Users</a>
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
                        <h1>User Management</h1>
                        <p>Add, edit, and manage user accounts</p>
                    </div>
                </div>
                <div class="topbar-actions">
                    <button class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="fas fa-plus"></i> Add User
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

                <!-- Search -->
                <div class="section-header">
                    <h2><i class="fas fa-users"></i> All Users (<?php echo count($users); ?>)</h2>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="userSearch" placeholder="Search by name or email...">
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-body" style="padding:0;">
                        <div class="table-responsive">
                            <table class="admin-table" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr data-name="<?php echo strtolower($user['name']); ?>" data-email="<?php echo strtolower($user['email']); ?>">
                                            <td><strong><?php echo htmlspecialchars($user['name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td>
                                                <?php
                                                $roleColors = ['admin' => 'red', 'guard' => 'amber', 'employee' => 'blue'];
                                                $roleColor = $roleColors[$user['role']] ?? 'gray';
                                                ?>
                                                <span class="admin-badge <?php echo $roleColor; ?>"><?php echo ucfirst($user['role']); ?></span>
                                            </td>
                                            <td>
                                                <div style="display:flex;gap:0.35rem;">
                                                    <button class="btn-admin-sm btn-edit" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $user['id']; ?>" title="Edit">
                                                        <i class="fas fa-pen"></i>
                                                    </button>
                                                    <button class="btn-admin-sm btn-delete" onclick="deleteUser(<?php echo $user['id']; ?>)" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Create User Modal -->
                <div class="modal fade" id="createUserModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Create New User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="post">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <select class="form-select" id="role" name="role" required>
                                            <option value="employee">Employee</option>
                                            <option value="guard">Guard</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" name="create" class="btn-admin-primary">Create User</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit User Modals -->
                <?php foreach ($users as $user): ?>
                    <div class="modal fade" id="editUserModal<?php echo $user['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="post">
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email Address</label>
                                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <select class="form-select" name="role" required>
                                                <option value="employee" <?php echo $user['role'] == 'employee' ? 'selected' : ''; ?>>Employee</option>
                                                <option value="guard" <?php echo $user['role'] == 'guard' ? 'selected' : ''; ?>>Guard</option>
                                                <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" name="update" class="btn-admin-primary">Update User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
        overlay?.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });

        // Delete user
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                const form = document.createElement('form');
                form.method = 'post';
                form.innerHTML = '<input type="hidden" name="id" value="' + id + '"><input type="hidden" name="delete" value="1">';
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Search
        document.getElementById('userSearch')?.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#usersTable tbody tr').forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const email = row.getAttribute('data-email') || '';
                row.style.display = (!q || name.includes(q) || email.includes(q)) ? '' : 'none';
            });
        });
    </script>
</body>

</html>