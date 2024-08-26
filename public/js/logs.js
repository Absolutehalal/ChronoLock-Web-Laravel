const adminID = `<i class="mdi mdi-alpha-i-box"></i> User ID`;
const studentID = `<i class="mdi mdi-alpha-i-box"></i> User ID`;
const labInChargeID = `<i class="mdi mdi-alpha-i-box"></i> User ID`;
const technicianID = `<i class="mdi mdi-alpha-i-box"></i> User ID`;

document.addEventListener("DOMContentLoaded", function () {
    // Admin
    document.querySelectorAll(".admin-id").forEach(function (item) {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById(
                "adminIDDropdown"
            ).innerHTML = `<i class="mdi mdi-alpha-i-box"></i> ${this.textContent}`;
        });
    });

    $("#adminResetButton").on("click", function (e) {
        e.preventDefault();
        document.getElementById("adminIDDropdown").innerHTML = adminID;
    });

    // Student
    document.querySelectorAll(".student-id").forEach(function (item) {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById(
                "studentIDDropdown"
            ).innerHTML = `<i class="mdi mdi-alpha-i-box"></i> ${this.textContent}`;
        });
    });

    $("#studentResetButton").on("click", function (e) {
        e.preventDefault();
        document.getElementById("studentIDDropdown").innerHTML = studentID;
    });

    // Lab In Charge
    document.querySelectorAll(".lab-in-charge-id").forEach(function (item) {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById(
                "labInChargeIDDropdown"
            ).innerHTML = `<i class="mdi mdi-alpha-i-box"></i> ${this.textContent}`;
        });
    });

    $("#LabInChargeResetButton").on("click", function (e) {
        e.preventDefault();
        document.getElementById("labInChargeIDDropdown").innerHTML = labInChargeID;
    });

    // Technician
    document.querySelectorAll(".technician-id").forEach(function (item) {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById(
                "technicianIDDropdown"
            ).innerHTML = `<i class="mdi mdi-alpha-i-box"></i> ${this.textContent}`;
        });
    });

    $("#technicianResetButton").on("click", function (e) {
        e.preventDefault();
        document.getElementById("technicianIDDropdown").innerHTML = technicianID;
    });

    // Faculty
    document.querySelectorAll(".faculty-id").forEach(function (item) {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById(
                "facultyIDDropdown"
            ).innerHTML = `<i class="mdi mdi-alpha-i-box"></i> ${this.textContent}`;
        });
    });

    $("#facultyResetButton").on("click", function (e) {
        e.preventDefault();
        document.getElementById("facultyIDDropdown").innerHTML = technicianID;
    });
});
