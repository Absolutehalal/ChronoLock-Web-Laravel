document.addEventListener("DOMContentLoaded", function () {
    const showPassword = document.querySelector("#show-password");
    const passwordField = document.querySelector("#password");

    const showConfirmPassword = document.querySelector(
        "#show-password-confirm"
    );
    const passwordConfirmationField = document.querySelector(
        "#password_confirmation"
    );

    showPassword.addEventListener("click", function () {
        this.classList.toggle("fa-eye");

        const type =
            passwordField.getAttribute("type") === "password"
                ? "text"
                : "password";

        // Toggle password field visibility
        passwordField.setAttribute("type", type);
    });

    showConfirmPassword.addEventListener("click", function () {
        this.classList.toggle("fa-eye");

        const type =
            passwordConfirmationField.getAttribute("type") === "password"
                ? "text"
                : "password";

        // Toggle password field visibility
        passwordConfirmationField.setAttribute("type", type);
    });
});
