<?php
session_start();

require_once '../../controllers/AdminController.php';

AdminController::manageAreas();

include '../layouts/header.php'; ?>

<h2>Manage Parking Areas</h2>

<?php $flash = getFlashMessage();
if ($flash): ?>
    <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'danger' : 'success'; ?>">
        <?php echo $flash['message']; ?>
    </div>
<?php endif; ?>

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createAreaModal">Add Area</button>

<table class="table">
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
                <td><?php echo $area['name']; ?></td>
                <td><?php echo $area['location']; ?></td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editArea(<?php echo $area['id']; ?>, '<?php echo $area['name']; ?>', '<?php echo $area['location']; ?>')">Edit</button>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $area['id']; ?>">
                        <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Create Area Modal -->
<div class="modal fade" id="createAreaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="create" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Area Modal -->
<div class="modal fade" id="editAreaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editAreaId">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" id="editAreaName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Location</label>
                        <input type="text" name="location" id="editAreaLocation" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
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

<?php // include '../layouts/footer.php'; 
?>