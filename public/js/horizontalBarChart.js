document.addEventListener("DOMContentLoaded", function () {
    var horBarChart2 = document.querySelector("#horizontal-bar-chart2");
    if (horBarChart2 !== null) {
        fetch("/student-counts-chart")
            .then((response) => response.json())
            .then((data) => {
                if (data.length === 0) {
                    horBarChart2.innerHTML =
                    "<div style='text-align: center; font-size:30px; height: 310px; padding-top: 100px; color: #cc0000''>No data available.</div>";
                    return;
                }

                var categories = [];
                var seriesData = [];

                data.forEach((item) => {
                    categories.push(item.class_info); // Use the formatted class info
                    seriesData.push(item.total);
                });

                var options = {
                    chart: {
                        height: 300,
                        type: "bar",
                        toolbar: {
                            show: true,
                        },
                        animations: {
                            enabled: true,
                            easing: "easeinout",
                            speed: 800,
                        },
                    },
                    colors: ["#007bff", "#faafca"],
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            barHeight: "50%",
                            dataLabels: {
                                position: "top",
                            },
                        },
                    },
                    legend: {
                        show: true,
                        position: "top",
                        horizontalAlign: "right",
                        markers: {
                            width: 20,
                            height: 3,
                            radius: 0,
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        show: true,
                        width: 1,
                        colors: ["#fff"],
                    },
                    series: [
                        {
                            data: seriesData,
                        },
                    ],
                    xaxis: {
                        categories: categories,
                    },
                    tooltip: {
                        theme: "dark",
                        x: {
                            show: false,
                        },
                        y: {
                            title: {
                                formatter: () => "Student/s Per Section",
                            },
                        },
                    },
                };

                var chart = new ApexCharts(horBarChart2, options);
                chart.render();
            })
            .catch((error) => {
                horBarChart2.innerHTML = "<p>Error loading data</p>";
                console.error("Error fetching data:", error);
            });
    }
});
