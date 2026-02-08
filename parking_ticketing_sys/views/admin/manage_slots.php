<?php
session_start();

require_once '../../controllers/AdminController.php';

AdminController::manageSlots();

include '../layouts/admin_layout.php'; ?>

<h2><i class="fas fa-parking me-2"></i>Manage Parking Slots</h2>

<?php $flash = getFlashMessage();
if ($flash): ?>
    <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'danger' : 'success'; ?> alert-dismissible fade show">
        <i class="fas fa-<?php echo $flash['type'] == 'error' ? 'exclamation-triangle' : 'check-circle'; ?> me-2"></i>
        <?php echo $flash['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createSlotModal">
    <i class="fas fa-plus me-2"></i>Add Slot
</button>

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
                                <span class="badge bg-<?php echo $slot['status'] == 'available' ? 'success' : ($slot['status'] == 'occupied' ? 'warning' : 'secondary'); ?>">
                                    <?php echo ucfirst($slot['status']); ?>
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