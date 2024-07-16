// var SimplePieChart = document.querySelector("#simple-pie-chart");
// if (SimplePieChart !== null) {
//   var simplePieChartOptions = {
//     chart: {
//       width: 300,
//       type: "pie",
//     },
//     colors: ["#31ce3c", "#cc0000", "#e9e300"], // REGULAR, DROP, IRREGULAR
//     labels: _labels,
//     legend: {
//       position: "top",
//       horizontalAlign: "center",
//       markers: {
//         radius: 0,
//       },
//     },
//     series: [_series.REGULAR, _series.DROP, _series.IRREGULAR],
//   };

//   var simpleplePieChartRander = new ApexCharts(
//     SimplePieChart,
//     simplePieChartOptions
//   );

//   simpleplePieChartRander.render();
// }

var donutChart2 = document.querySelector("#donut-chart-2");
if (donutChart2 !== null) {
  var donutChartOptions2 = {
    chart: {
      type: "pie",
      height: 300,
    },

    colors: ["#31ce3c", "#e9e300", "#cc0000"], // REGULAR, DROP, IRREGULAR
    labels: _labels,
    series:  [_series.REGULAR, _series.IRREGULAR, _series.DROP],
    legend: {
      show: true,
      position: "top",
      horizontalAlign: "center",
      markers: {
        radius: 0,
      },
    },
    dataLabels: {
      enabled: true,
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return + val;
        },
      },
    },
  };

  var randerDonutchart2 = new ApexCharts(donutChart2, donutChartOptions2);

  randerDonutchart2.render();
}
