// Custom JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on plate number input for guard check-in
    const plateInput = document.getElementById('plate_number');
    if (plateInput) {
        plateInput.focus();
    }

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('button[name="delete"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });
});