<?php
session_start();

require_once '../../controllers/GuardController.php';

GuardController::checkout();

include '../layouts/header.php'; ?>

<h2>Check-out Vehicle</h2>

<?php $flash = getFlashMessage();
if ($flash): ?>
    <div class="alert alert-<?php echo $flash['type'] == 'error' ? 'danger' : 'success'; ?>">
        <?php echo $flash['message']; ?>
    </div>
<?php endif; ?>

<form method="post" class="row g-3">
    <div class="col-12">
        <label for="ticket_id" class="form-label">Select Active Ticket</label>
        <select class="form-control form-control-lg" id="ticket_id" name="ticket_id" required>
            <?php foreach ($activeTickets as $ticket): ?>
                <option value="<?php echo $ticket['id']; ?>"><?php echo $ticket['plate_number'] . ' - ' . $ticket['area_name'] . ' ' . $ticket['slot_number'] . ' (Checked in: ' . $ticket['checkin_time'] . ')'; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-danger btn-lg w-100">Check-out</button>
    </div>
</form>

<div class="mt-4">
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

<?php include '../layouts/footer.php'; ?>