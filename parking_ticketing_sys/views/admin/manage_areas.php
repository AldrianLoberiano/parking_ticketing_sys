<?php
session_start();

require_once '../../controllers/AdminController.php';

AdminController::manageAreas();

include '../layouts/admin_layout.php'; ?>

<h2><i class="fas fa-map-marker-alt me-2"></i>Manage Parking Areas</h2>

<?php $flash = getFlashMessage();
if ($flash): ?>
    <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'danger' : 'success'; ?> alert-dismissible fade show">
        <i class="fas fa-<?php echo $flash['type'] == 'error' ? 'exclamation-triangle' : 'check-circle'; ?> me-2"></i>
        <?php echo $flash['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createAreaModal">
    <i class="fas fa-plus me-2"></i>Add Area
</button>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($areas as $area): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($area['name']); ?></td>
                            <td><?php echo htmlspecialchars($area['location']); ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editArea(<?php echo $area['id']; ?>, '<?php echo htmlspecialchars($area['name']); ?>', '<?php echo htmlspecialchars($area['location']); ?>')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $area['id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this area?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
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
                <h5 class="modal-title">Add New Area</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="create" class="btn btn-primary">Create Area</button>
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
                <h5 class="modal-title">Edit Area</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editAreaId">
                    <div class="mb-3">
                        <label for="editAreaName" class="form-label">Name</label>
                        <input type="text" name="name" id="editAreaName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editAreaLocation" class="form-label">Location</label>
                        <input type="text" name="location" id="editAreaLocation" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update" class="btn btn-primary">Update Area</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editArea(id, name, location) {
        document.getElementById('editAreaId').value = id;
        document.getElementById('editAreaName').value = name;
        document.getElementById('editAreaLocation').value = location;
        new bootstrap.Modal(document.getElementById('editAreaModal')).show();
    }
</script>