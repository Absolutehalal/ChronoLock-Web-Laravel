var SimplePieChart = document.querySelector("#simple-pie-chart");
if (SimplePieChart !== null) {
  var simplePieChartOptions = {
    chart: {
      width: 300,
      type: "pie",
    },
    colors: ["#31ce3c", "#cc0000", "#e9e300"], // REGULAR, DROP, IRREGULAR
    labels: _labels,
    legend: {
      position: "top",
      horizontalAlign: "center",
      markers: {
        radius: 0,
      },
    },
    series: [_series.REGULAR, _series.DROP, _series.IRREGULAR],
  };

  var simpleplePieChartRander = new ApexCharts(
    SimplePieChart,
    simplePieChartOptions
  );

  simpleplePieChartRander.render();
}
