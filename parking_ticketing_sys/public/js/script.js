// Parking Ticketing System JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on plate number input
    var plateInput = document.getElementById('plate_number');
    if (plateInput) {
        plateInput.focus();
        plateInput.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
    }

    // Delete confirmation
    var deleteButtons = document.querySelectorAll('button[name="delete"]');
    for (var i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    }

    // Form validation
    var checkinForm = document.getElementById('checkinForm');
    if (checkinForm) {
        checkinForm.addEventListener('submit', function(e) {
            var plateNumber = document.getElementById('plate_number').value.trim();
            var slotId = document.getElementById('slot_id').value;

            if (!plateNumber || !slotId) {
                e.preventDefault();
                alert('Please complete all required fields before submitting.');
                return false;
            }

            var submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                submitBtn.disabled = true;
            }
        });
    }
});