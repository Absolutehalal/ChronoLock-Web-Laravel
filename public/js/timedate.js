// Function to update live date and time
    function updateLiveDateTime() {
        var now = new Date();
        var dateTimeString = now.toLocaleString("en-US", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
            hour: "numeric",
            minute: "numeric",
            second: "numeric",
            hour12: true,
        });
        document.getElementById("liveDateTime").textContent = dateTimeString;
    }

    // Update live date and time every second
    setInterval(updateLiveDateTime, 1000);

    // Initial call to update live date and time
    updateLiveDateTime();
