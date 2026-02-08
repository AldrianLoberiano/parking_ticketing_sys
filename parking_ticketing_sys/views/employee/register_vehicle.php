<?php
session_start();

require_once '../../controllers/EmployeeController.php';

EmployeeController::registerVehicle();

include '../layouts/header.php'; ?>

<h2>Register Vehicle</h2>

<?php $flash = getFlashMessage();
if ($flash): ?>
    <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'danger' : 'success'; ?>">
        <?php echo $flash['message']; ?>
    </div>
<?php endif; ?>

<form method="post" class="row g-3">
    <div class="col-md-6">
        <label for="plate_number" class="form-label">Plate Number</label>
        <input type="text" class="form-control" id="plate_number" name="plate_number" required>
    </div>
    <div class="col-md-6">
        <label for="model" class="form-label">Model</label>
        <input type="text" class="form-control" id="model" name="model">
    </div>
    <div class="col-md-6">
        <label for="color" class="form-label">Color</label>
        <input type="text" class="form-control" id="color" name="color">
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Register Vehicle</button>
    </div>
</form>

<div class="mt-4">
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

<?php include '../layouts/footer.php'; ?>