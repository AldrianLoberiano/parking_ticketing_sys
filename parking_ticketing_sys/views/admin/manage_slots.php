<?php
session_start();

require_once '../../controllers/AdminController.php';

AdminController::manageSlots();

include '../layouts/header.php'; ?>

<h2>Manage Parking Slots</h2>

<?php $flash = getFlashMessage();
if ($flash): ?>
    <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'danger' : 'success'; ?>">
        <?php echo $flash['message']; ?>
    </div>
<?php endif; ?>

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createSlotModal">Add Slot</button>

<table class="table">
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
                <td><?php echo $slot['area_name']; ?></td>
                <td><?php echo $slot['slot_number']; ?></td>
                <td><span class="badge bg-<?php echo $slot['status'] == 'available' ? 'success' : 'danger'; ?>"><?php echo $slot['status']; ?></span></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $slot['id']; ?>">
                        <button type="submit" name="delete" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Create Slot Modal -->
<div class="modal fade" id="createSlotModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add Slot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Area</label>
                        <select name="area_id" class="form-control" required>
                            <?php foreach ($areas as $area): ?>
                                <option value="<?php echo $area['id']; ?>"><?php echo $area['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Slot Number</label>
                        <input type="text" name="slot_number" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="create" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php // include '../layouts/footer.php'; 
?>