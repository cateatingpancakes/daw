function bindShowPassword(checkboxId, passwordId) {
    const checkbox = document.getElementById(checkboxId);
    const password = document.getElementById(passwordId);

    if (checkbox && password) {
        checkbox.addEventListener("change", function() {
            password.type = this.checked ? "text" : "password";
        });
    }
}
