<?php
session_start();
require_once '../../controllers/AdminController.php';
require_once '../../includes/functions.php';

requireRole('admin');

$flash = getFlashMessage();

// Handle backup download
if (isset($_POST['backup'])) {
    $dbHost = DB_HOST;
    $dbName = DB_NAME;
    $dbUser = DB_USER;
    $dbPass = DB_PASS;

    $backupDir = __DIR__ . '/../../backups/';
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0777, true);
    }

    $filename = 'backup_' . date('Y-m-d_His') . '.sql';
    $filepath = $backupDir . $filename;

    // Use mysqldump
    $mysqldumpPath = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
    $command = "\"$mysqldumpPath\" --host=$dbHost --user=$dbUser " . ($dbPass ? "--password=$dbPass " : "") . "$dbName > \"$filepath\"";
    exec($command, $output, $returnCode);

    if ($returnCode === 0 && file_exists($filepath)) {
        // Send file as download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    } else {
        flashMessage('error', 'Backup failed. Check mysqldump path.');
        header('Location: backup.php');
        exit;
    }
}

// Handle restore
if (isset($_POST['restore']) && isset($_FILES['sql_file'])) {
    $file = $_FILES['sql_file'];
    if ($file['error'] === UPLOAD_ERR_OK && pathinfo($file['name'], PATHINFO_EXTENSION) === 'sql') {
        $sql = file_get_contents($file['tmp_name']);

        try {
            global $pdo;
            $pdo->exec($sql);
            flashMessage('success', 'Database restored successfully from: ' . htmlspecialchars($file['name']));
        } catch (PDOException $e) {
            flashMessage('error', 'Restore failed: ' . $e->getMessage());
        }
        header('Location: backup.php');
        exit;
    } else {
        flashMessage('error', 'Please upload a valid .sql file.');
        header('Location: backup.php');
        exit;
    }
}

// List existing backups
$backupDir = __DIR__ . '/../../backups/';
$backups = [];
if (is_dir($backupDir)) {
    $files = glob($backupDir . '*.sql');
    foreach ($files as $file) {
        $backups[] = [
            'name' => basename($file),
            'size' => round(filesize($file) / 1024, 1),
            'date' => date('Y-m-d H:i:s', filemtime($file))
        ];
    }
    usort($backups, function ($a, $b) { return strcmp($b['date'], $a['date']); });
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup & Recovery — ParkEase</title>
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
                    <a href="manage_vehicles.php" class="sidebar-nav-link" data-title="Vehicles"><i class="fas fa-car"></i> Vehicles</a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="manage_areas.php" class="sidebar-nav-link" data-title="Areas"><i class="fas fa-map-marker-alt"></i> Areas</a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="manage_slots.php" class="sidebar-nav-link" data-title="Slots"><i class="fas fa-parking"></i> Slots</a>
                </li>
                <li class="sidebar-nav-label">System</li>
                <li class="sidebar-nav-item">
                    <a href="backup.php" class="sidebar-nav-link active" data-title="Backup & Recovery"><i class="fas fa-database"></i> Backup & Recovery</a>
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
                        <h1>Backup & Recovery</h1>
                        <p>Create backups and restore your database</p>
                    </div>
                </div>
            </div>

            <div class="admin-content">
                <?php if ($flash): ?>
                    <div class="admin-alert <?php echo $flash['type'] == 'error' ? 'error' : 'success'; ?>">
                        <i class="fas fa-<?php echo $flash['type'] == 'error' ? 'exclamation-circle' : 'check-circle'; ?>"></i>
                        <?php echo $flash['message']; ?>
                    </div>
                <?php endif; ?>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.5rem;">
                    <!-- Create Backup -->
                    <div class="admin-card">
                        <div class="admin-card-header">
                            <h3><i class="fas fa-download me-2" style="color:var(--accent-blue);"></i>Create Backup</h3>
                        </div>
                        <div class="admin-card-body">
                            <p style="font-size:0.85rem;color:var(--text-secondary);margin-bottom:1rem;">
                                Download a full backup of the <strong>parking_ticketing</strong> database as an SQL file.
                            </p>
                            <form method="post">
                                <button type="submit" name="backup" class="btn-admin-primary" style="width:100%;justify-content:center;">
                                    <i class="fas fa-download"></i> Download Backup
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Restore Backup -->
                    <div class="admin-card">
                        <div class="admin-card-header">
                            <h3><i class="fas fa-upload me-2" style="color:var(--accent-amber);"></i>Restore Database</h3>
                        </div>
                        <div class="admin-card-body">
                            <p style="font-size:0.85rem;color:var(--text-secondary);margin-bottom:1rem;">
                                Upload an SQL file to restore a previous backup. <strong style="color:var(--accent-red);">This will overwrite current data.</strong>
                            </p>
                            <form method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="sql_file" accept=".sql" required>
                                </div>
                                <button type="submit" name="restore" class="btn-admin-primary" style="width:100%;justify-content:center;background:var(--accent-amber);" onclick="return confirm('This will overwrite all current data. Are you sure?')">
                                    <i class="fas fa-upload"></i> Restore Backup
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Backup History -->
                <div class="section-header">
                    <h2><i class="fas fa-history"></i> Backup History</h2>
                </div>
                <div class="admin-card">
                    <div class="admin-card-body" style="padding:0;">
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Filename</th>
                                        <th>Size</th>
                                        <th>Date Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($backups)): ?>
                                        <?php foreach ($backups as $backup): ?>
                                            <tr>
                                                <td><i class="fas fa-file-code me-2" style="color:var(--accent-blue);"></i><strong><?php echo htmlspecialchars($backup['name']); ?></strong></td>
                                                <td><?php echo $backup['size']; ?> KB</td>
                                                <td><?php echo $backup['date']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="3" class="text-center" style="padding:2rem;color:var(--text-muted);">No backups found. Create your first backup above.</td></tr>
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
    </script>
</body>

</html>
