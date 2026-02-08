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
    <title>Parking Ticketing System - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="/parking_ticketing_sys/parking_ticketing_sys/public/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Black Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-parking me-2"></i>Parking Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage_users.php">
                            <i class="fas fa-users me-1"></i>Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_areas.php">
                            <i class="fas fa-map-marker-alt me-1"></i>Areas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_slots.php">
                            <i class="fas fa-parking me-1"></i>Slots
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="analytics.php">
                            <i class="fas fa-chart-bar me-1"></i>Analytics
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-shield me-1"></i><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="/parking_ticketing_sys/parking_ticketing_sys/controllers/AuthController.php?action=logout">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">

                <!-- Welcome Card -->
                <div class="card mb-2 bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="card-title"><i class="fas fa-users me-2"></i>User Management</h5>
                                <p class="card-text">Manage system users efficiently. Add, edit, and monitor user accounts and permissions across different roles.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <?php $flash = getFlashMessage();
                if ($flash): ?>
                    <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'danger' : 'success'; ?> alert-dismissible fade show">
                        <i class="fas fa-<?php echo $flash['type'] == 'error' ? 'exclamation-triangle' : 'check-circle'; ?> me-2"></i>
                        <?php echo $flash['message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="text-center mb-4">
                    <button class="btn btn-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="fas fa-plus me-2"></i>Add User
                    </button>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : ($user['role'] == 'guard' ? 'warning' : 'info'); ?>">
                                                    <?php echo ucfirst($user['role']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $user['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                                    <?php echo ucfirst($user['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $user['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(<?php echo $user['id']; ?>)">
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
                                <h5 class="modal-title">Create New User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="post">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
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
                                    <button type="submit" name="create" class="btn btn-primary">Create User</button>
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
                                    <h5 class="modal-title">Edit User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="post">
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
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
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select class="form-select" name="status" required>
                                                <option value="active" <?php echo $user['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                                <option value="inactive" <?php echo $user['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="update" class="btn btn-primary">Update User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <script>
                    function deleteUser(id) {
                        if (confirm('Are you sure you want to delete this user?')) {
                            const form = document.createElement('form');
                            form.method = 'post';
                            form.innerHTML = '<input type="hidden" name="id" value="' + id + '"><input type="hidden" name="delete" value="1">';
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }

                    // Modal accessibility fixes
                    document.addEventListener('DOMContentLoaded', function() {
                        // Handle modal show events
                        const modals = document.querySelectorAll('.modal');
                        modals.forEach(modal => {
                            modal.addEventListener('show.bs.modal', function() {
                                // Remove aria-hidden when modal is shown
                                this.removeAttribute('aria-hidden');
                            });

                            modal.addEventListener('shown.bs.modal', function() {
                                // Focus management - focus first focusable element
                                const focusableElements = this.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                                if (focusableElements.length > 0) {
                                    focusableElements[0].focus();
                                }
                            });

                            modal.addEventListener('hide.bs.modal', function() {
                                // Add aria-hidden when modal is hidden
                                this.setAttribute('aria-hidden', 'true');
                            });
                        });
                    });
                </script>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
