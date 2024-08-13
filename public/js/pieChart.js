document.addEventListener("DOMContentLoaded", function () {
    var SimplePieChart = document.querySelector("#simple-pie-chart");

    if (SimplePieChart !== null) {
        fetch("/student-status-counts-chart")
            .then((response) => response.json())
            .then((data) => {
                // Check if all series data is zero
                var isEmpty =
                    data.regularCount === 0 &&
                    data.irregularCount === 0 &&
                    data.dropCount === 0;

                if (isEmpty) {
                    // Display "No data available" if all counts are zero
                    SimplePieChart.innerHTML =
                        "<div style='text-align: center; font-size:30px'>No data available</div>";
                } else {
                    var simplePieChartOptions = {
                        chart: {
                            width: 312,
                            type: "pie",
                            animations: {
                                enabled: true,
                                easing: "easeinout",
                                speed: 800,
                            },
                        },
                        colors: ["#31ce3c", "#e9e300", "#cc0000"],
                        labels: ["Regular", "Irregular", "Drop"],
                        legend: {
                            position: "top",
                            horizontalAlign: "center",
                            markers: {
                                radius: 0,
                            },
                        },
                        series: [
                            data.regularCount,
                            data.irregularCount,
                            data.dropCount,
                        ],
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
            });
    }
});
