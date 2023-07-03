// Add validation for password fields
const password = document.getElementById("password");
const confirm_password = document.getElementById("confirm-password");

function validatePassword() {
if (password.value != confirm_password.value) {
confirm_password.setCustomValidity("Passwords do not match");
} else {
confirm_password.setCustomValidity("");
}
}

// Add event listeners to password fields
password.addEventListener("change", validatePassword);
confirm_password.addEventListener("keyup", validatePassword);

// Add form submission handling
const resetPasswordForm = document.getElementById("reset-password-form");
resetPasswordForm.addEventListener("submit", function(event) {
event.preventDefault();
if (password.value != confirm_password.value) {
alert("Passwords do not match");
} else {
// Submit the form
resetPasswordForm.submit();
}
});




