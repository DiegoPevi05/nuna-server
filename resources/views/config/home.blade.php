@push('styles')
    <style>
        @keyframes fadeInAnimation {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-notification {
            animation: fadeInAnimation 800ms ease-in-out;
        }
    </style>


@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
          var chartLineData = @json($import_last_days);


          document.addEventListener("DOMContentLoaded", function() {
            window.ApexCharts && (new ApexCharts(document.getElementById('chart-demo-line'), {
              chart: {
                type: "line",
                fontFamily: 'inherit',
                height: 240,
                parentHeightOffset: 0,
                toolbar: {
                  show: false,
                },
                animations: {
                  enabled: false
                },
              },
              fill: {
                opacity: 1,
              },
              stroke: {
                width: 2,
                lineCap: "round",
                curve: "straight",
              },
              series: [{
                name: "Sesiones",
                data: chartLineData.map(record => record.total_amount)
              }],
              tooltip: {
                theme: 'dark'
              },
              grid: {
                padding: {
                  top: -20,
                  right: 0,
                  left: -4,
                  bottom: -4
                },
                strokeDashArray: 4,
              },
              xaxis: {
                labels: {
                  padding: 0,
                },
                tooltip: {
                  enabled: false
                },
                type: 'datetime',
              },
              yaxis: {
                title:{
                  text:"Nuevos soles",
                  style:{
                    colors:tabler.getColor("secondary")
                  },
                  offsetX: -10
                },
                labels: {
                  padding: 4,
                  formatter: function(val) {
                    return "S/."+val 
                  },
                },
              },
              labels: chartLineData.map(record => record.created_date),
              colors: [tabler.getColor("primary")],
              legend: {
                show: true,
                position: 'bottom',
                offsetY: 12,
                markers: {
                  width: 10,
                  height: 10,
                  radius: 100,
                },
                itemMargin: {
                  horizontal: 8,
                  vertical: 8
                },
              },
            })).render();
          });


            var chartBarsData = @json($meetingsData);

            // Create an object to store service data
            var serviceData = {};

            // Process the input data to populate serviceData
            chartBarsData.forEach(function(item) {
                if (!serviceData[item.service_name]) {
                    serviceData[item.service_name] = {
                        name: item.service_name,
                        data: {}
                    };
                }
                serviceData[item.service_name].data[item.month] = item.quantity || 0;
            });

            // Extract series data from serviceData
            var seriesData = Object.values(serviceData).map(function(service) {
                var data = Object.values(service.data);
                return {
                    name: service.name,
                    data: data
                };
            });

            // Extract labels from the available months
            var labelsData = Object.keys(serviceData[Object.keys(serviceData)[0]].data).sort();


            document.addEventListener("DOMContentLoaded", function() {
                window.ApexCharts && (new ApexCharts(document.getElementById('chart-demo-bar'), {
                  chart: {
                    type: "bar",
                    fontFamily: 'inherit',
                    height: 240,
                    parentHeightOffset: 0,
                    toolbar: {
                      show: false,
                    },
                    animations: {
                      enabled: false
                    },
                    stacked: true,
                  },
                  plotOptions: {
                    bar: {
                      barHeight: '50%',
                      horizontal: false,
                    }
                  },
                  dataLabels: {
                    enabled: false,
                  },
                  fill: {
                    opacity: 1,
                  },
                  series: seriesData,
                  tooltip: {
                    theme: 'dark'
                  },
                  grid: {
                    padding: {
                      top: -20,
                      right: 0,
                      left: -4,
                      bottom: -4
                    },
                    strokeDashArray: 4,
                  },
                  xaxis: {
                    labels: {
                      padding: 0,
                      formatter: function(val) {
                        return val 
                      },
                    },
                    tooltip: {
                      enabled: false
                    },
                    axisBorder: {
                      show: false,
                    },
                    categories: labelsData,
                  },
                  yaxis: {
                    title:{
                      text:"Ctd. Sesiones",
                      style:{
                        colors:tabler.getColor("primary")
                      },
                      offsetX: -10
                    },
                    labels: {
                      padding: 4,
                    },
                  },
                  colors: [tabler.getColor("purple"), tabler.getColor("green"), tabler.getColor("yellow"), tabler.getColor("red"), tabler.getColor("primary")],
                  legend: {
                    show: true,
                    position: 'bottom',
                    offsetY: 12,
                    markers: {
                      width: 10,
                      height: 10,
                      radius: 100,
                    },
                    itemMargin: {
                      horizontal: 8,
                      vertical: 8
                    },
                  },
                })).render();
              });
    </script>
@endpush
