<?php
session_start();

require_once '../../controllers/GuardController.php';

GuardController::checkin();

include '../layouts/header.php'; ?>

<h2>Check-in Vehicle</h2>

<?php $flash = getFlashMessage();
if ($flash): ?>
    <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'danger' : 'success'; ?>">
        <?php echo $flash['message']; ?>
    </div>
<?php endif; ?>

<form method="post" class="row g-3">
    <div class="col-md-6">
        <label for="plate_number" class="form-label">Plate Number</label>
        <input type="text" class="form-control form-control-lg" id="plate_number" name="plate_number" required autofocus>
    </div>
    <div class="col-md-6">
        <label for="slot_id" class="form-label">Parking Slot</label>
        <select class="form-control form-control-lg" id="slot_id" name="slot_id" required>
            <?php foreach ($availableSlots as $slot): ?>
                <option value="<?php echo $slot['id']; ?>"><?php echo $slot['area_name'] . ' - ' . $slot['slot_number']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-success btn-lg w-100">Check-in</button>
    </div>
</form>

<div class="mt-4">
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

<?php include '../layouts/footer.php'; ?>
