document.addEventListener("DOMContentLoaded", () => {
    // Select all input elements with the readonly attribute
    const readonlyInputs = document.querySelectorAll("input[readonly]");

    readonlyInputs.forEach((input) => {
        input.addEventListener("click", () => {
            Swal.fire({
                icon: "info",
                title: "Cannot Edit",
                text: "Input field cannot be edited.",
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 2000, 
                timerProgressBar: true
            });
        });
    });
});
