$(document).ready(function () {
    var horBarChart2 = document.querySelector("#horizontal-bar-chart2");

    if (horBarChart2 !== null) {
        fetchData();
    }

    function fetchData() {
        $.ajax({
            type: "GET",
            url: "/student-counts-chart",
            dataType: "json",
            success: function (response) {
                if (response.length === 0) {
                    horBarChart2.innerHTML =
                        "<div style='text-align: center; font-size:30px; height: 310px; padding-top: 100px; color: #cc0000;'>No data available.</div>";
                    return;
                }

                var categories = [];
                var seriesData = [];

                response.forEach((item) => {
                    categories.push(item.class_info);
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
                        enabled: true,
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
                        theme: "light",
                        x: {
                            show: true,
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
            },
            error: function (error) {
                horBarChart2.innerHTML =
                    "<div style='text-align: center; font-size:30px; height: 310px; padding-top: 100px; color: #cc0000;'>Error Loading Data.</div>";
                console.error("Error fetching data:", error);
            },
        });
    }
});
