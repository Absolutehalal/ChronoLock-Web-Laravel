$(document).ready(function () {
    var SimplePieChart = document.querySelector("#donut-chart-2");

    if (SimplePieChart !== null) {
        fetchData();
    }

    function fetchData() {
        $.ajax({
            type: "GET",
            url: "/student-status-counts-chart",
            dataType: "json",
            success: function (response) {
                if (response.length === 0) {
                    SimplePieChart.innerHTML =
                        "<div style='text-align: center; font-size:30px; height: 310px; padding-top: 100px; color: #cc0000;'>No data available.</div>";
                    return;
                }

                // Assume the response has these keys
                var regularCount = response.regularCount || 0;
                var irregularCount = response.irregularCount || 0;
                var dropCount = response.dropCount || 0;

                // Check if all series data is zero
                var isEmpty =
                    regularCount === 0 &&
                    irregularCount === 0 &&
                    dropCount === 0;

                if (isEmpty) {
                    // Display "No data available" if all counts are zero
                    SimplePieChart.innerHTML =
                        "<div style='text-align: center; font-size:30px; height: 310px; padding-top: 100px; color: #cc0000'>No data available.</div>";
                } else {
                    var simplePieChartOptions = {
                        chart: {
                            width: 312,
                            type: "donut",
                            animations: {
                                enabled: true,
                                easing: "easeinout",
                                speed: 800,
                            },
                            toolbar: {
                                show: true,
                            },
                        },
                        colors: ["#31ce3c", "#e9e300", "#cc0000"],
                        labels: ["Regular", "Irregular", "Drop"],
                        legend: {
                            position: "top",
                            horizontalAlign: "left",
                            markers: {
                                radius: 0,
                            },
                        },
                        series: [regularCount, irregularCount, dropCount],
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return val + " student/s";
                                },
                            },
                        },
                    };

                    var simplePieChartRenderer = new ApexCharts(
                        SimplePieChart,
                        simplePieChartOptions
                    );

                    simplePieChartRenderer.render();
                }
            },
            error: function (error) {
                SimplePieChart.innerHTML =
                    "<div style='text-align: center; font-size:30px; height: 310px; padding-top: 100px; color: #cc0000;'>Error Loading Data.</div>";
                console.error("Error fetching data:", error);
            },
        });
    }
});
