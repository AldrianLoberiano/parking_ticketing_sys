<?php
session_start();

require_once '../../controllers/AdminController.php';

AdminController::manageSlots();
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
                        <a class="nav-link me-3" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-3" href="manage_users.php">
                            <i class="fas fa-users me-1"></i>Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-3" href="manage_areas.php">
                            <i class="fas fa-map-marker-alt me-1"></i>Areas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active me-3" href="manage_slots.php">
                            <i class="fas fa-parking me-1"></i>Slots
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-3" href="analytics.php">
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
                                    <i class="fas fa-right-from-bracket me-1"></i>Logout
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
                <div class="card mb-2 bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="card-title"><i class="fas fa-parking me-2"></i>Slot Management</h5>
                                <p class="card-text">Configure parking slots effectively. Create, edit, and monitor slot availability across different areas for optimal parking management.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <?php $flash = getFlashMessage();
                if ($flash): ?>
                    <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'danger' : 'success'; ?> alert-dismissible fade show">
                        <i class="fas fa-<?php echo $flash['type'] == 'error' ? 'exclamation-triangle' : 'check-circle'; ?> me-2"></i>
                        <?php echo htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="text-center mb-4">
                    <button class="btn btn-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#createSlotModal">
                        <i class="fas fa-plus me-2"></i>Add Slot
                    </button>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Area</th>
                                        <th>Slot Number</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($slots as $slot): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($slot['area_name']); ?></td>
                                            <td><?php echo htmlspecialchars($slot['slot_number']); ?></td>
                                            <td>
                                                <?php
                                                $status = isset($slot['status']) ? (string) $slot['status'] : '';
                                                $allowedStatusClasses = [
                                                    'available' => 'success',
                                                    'occupied'  => 'warning',
                                                ];
                                                $statusClass = isset($allowedStatusClasses[$status]) ? $allowedStatusClasses[$status] : 'secondary';
                                                $statusLabel = htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8');
                                                ?>
                                                <span class="badge bg-<?php echo $statusClass; ?>">
                                                    <?php echo $statusLabel; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="id" value="<?php echo $slot['id']; ?>">
                                                    <button type="submit" name="delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this slot?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
                                <h5 class="modal-title">Add New Slot</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="post">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="area_id" class="form-label">Area</label>
                                        <select class="form-select" id="area_id" name="area_id" required>
                                            <option value="">Select Area</option>
                                            <?php foreach ($areas as $area): ?>
                                                <option value="<?php echo $area['id']; ?>"><?php echo htmlspecialchars($area['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="slot_number" class="form-label">Slot Number</label>
                                        <input type="text" name="slot_number" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="create" class="btn btn-primary">Create Slot</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
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