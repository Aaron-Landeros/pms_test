document.addEventListener("DOMContentLoaded", function() {
    var passwordInput = document.getElementById('new_password');
    var showPasswordCheckbox = document.getElementById('show_password');

    // Only add event listeners if the elements exist
    if (passwordInput && showPasswordCheckbox) {
        showPasswordCheckbox.addEventListener('change', function() {
            if (this.checked) {
                passwordInput.type = 'text'; // Show password
            } else {
                passwordInput.type = 'password'; // Hide password
            }
        });
    }
});