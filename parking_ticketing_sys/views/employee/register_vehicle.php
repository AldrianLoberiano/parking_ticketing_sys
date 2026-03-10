<?php
session_start();

require_once '../../controllers/EmployeeController.php';

EmployeeController::registerVehicle();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Vehicle — Employee</title>
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
                    <a href="register_vehicle.php" class="sidebar-nav-link active"><i class="fas fa-plus-circle"></i> Register Vehicle</a>
                </li>
                <li class="sidebar-nav-label">History</li>
                <li class="sidebar-nav-item">
                    <a href="history.php" class="sidebar-nav-link"><i class="fas fa-history"></i> Parking History</a>
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
                        <h1>Register Vehicle</h1>
                        <p>Add a new vehicle to your account</p>
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
                        <h3><i class="fas fa-car me-2"></i>Vehicle Details</h3>
                    </div>
                    <div class="admin-card-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label" style="font-size:0.82rem;font-weight:600;color:var(--text-secondary);">Plate Number</label>
                                <input type="text" class="form-control" name="plate_number" placeholder="e.g. ABC-1234" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size:0.82rem;font-weight:600;color:var(--text-secondary);">Model</label>
                                <input type="text" class="form-control" name="model" placeholder="e.g. Toyota Corolla">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size:0.82rem;font-weight:600;color:var(--text-secondary);">Color</label>
                                <input type="text" class="form-control" name="color" placeholder="e.g. White">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size:0.82rem;font-weight:600;color:var(--text-secondary);">Plate Number Photo</label>
                                <div class="upload-area" id="uploadArea">
                                    <i class="fas fa-camera"></i>
                                    <p>Click to upload or drag a photo of the plate number</p>
                                    <input type="file" name="plate_image" id="plateImage" accept="image/*">
                                    <img id="imagePreview" class="upload-preview" alt="Preview">
                                </div>
                            </div>
                            <div style="display:flex;gap:0.5rem;">
                                <button type="submit" class="btn-admin-primary" style="background:#22c55e;">
                                    <i class="fas fa-save"></i> Register Vehicle
                                </button>
                                <a href="dashboard.php" class="btn-admin-primary" style="background:var(--text-secondary);text-decoration:none;">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </form>
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

        // Image preview
        document.getElementById('plateImage')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.src = e.target.result;
                    preview.style.display = 'inline-block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>