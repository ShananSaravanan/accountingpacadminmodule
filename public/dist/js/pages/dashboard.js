/*
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/

/* global moment:false, Chart:false, Sparkline:false */

  // $('#revenue-chart').get(0).getContext('2d');
  var salesChart, pieChart, myBarChart;
  var salesGraphChartData = {
    labels: ['2011 Q1', '2011 Q2', '2011 Q3', '2011 Q4', '2012 Q1', '2012 Q2', '2012 Q3', '2012 Q4', '2013 Q1', '2013 Q2'],
    datasets: [
      {
        label: 'Revenue',
        fill: false,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        backgroundColor: ['#ff0000', '#00ff00', '#0000ff', '#ffff00'], // Specify colors here
        borderColor: '#efefef',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#efefef',
        pointBackgroundColor: '#efefef',
        data: []
      }
    ]
  }
  var storageGraphData = {
    labels: ['created_at', 'updated_at', 'deleted_at'],
    datasets: [
        {
            label: 'Counts',
            fill: false,
            borderColor: ['green', 'blue', 'red'],
            data: []
        }
    ]
};

  var salesGraphChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#efefef'
        },
        gridLines: {
          display: false,
          color: '#efefef',
          drawBorder: false
        }
      }],
      yAxes: [{
        ticks: {
          fontColor: '#efefef'
        },
        gridLines: {
          display: true,
          color: '#efefef',
          drawBorder: false
        }
      }]
    }
  }

  var salesChartData = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [
        {
            label: 'Digital Goods',
            backgroundColor: 'rgba(60,141,188,0.9)',
            borderColor: 'rgba(60,141,188,0.8)',
            pointRadius: false,
            pointColor: '#3b8bba',
            pointStrokeColor: 'rgba(60,141,188,1)',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data: []
        }
        // Add other datasets as needed
    ]
};

  var salesChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
        display: false
    },
    scales: {
        xAxes: [{
            scaleLabel: {
                display: true,
                labelString: 'Firm Names' // Add your x-axis label text here
            },
            gridLines: {
                display: false
            }
        }],
        yAxes: [{
            scaleLabel: {
                display: true,
                labelString: 'Assignments Completed' // Add your y-axis label text here
            },
            gridLines: {
                display: false
            },
            ticks: {
              beginAtZero: true // Set this to true to start the y-axis at 0
          }
        }]
    }
};

  var pieData = {
    labels: [
      'Instore Sales',
      'Download Sales',
      'Mail-Order Sales'
    ],
    datasets: [
      {
        data: [30, 12, 20],
        backgroundColor: ['#f56954', '#00a65a', '#f39c12']
      }
    ]
  }
  var pieOptions = {
    legend: {
      display: false
    },
    maintainAspectRatio: false,
    responsive: true
  }
  // Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  // eslint-disable-next-line no-unused-vars
  
  
  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  

// Add this array of colors
var chartColors = ['#f56954', '#00a65a', '#f39c12', '#007bff', '#6f42c1', '#28a745', '#dc3545'];

function handleTableChange(element) {
    var selectedmodel = element.value;

    axios.get('/get-stats/' + selectedmodel)
        .then(function (response) {
            var alldata = response.data;

            var name = alldata.map(function (data) {
                return data.name;
            });

            var counts = alldata.map(function (data) {
                return data.count;
            });

            // Clear existing data and labels
            salesChartData.labels = [];
            salesChartData.datasets[0].data = [];
            pieData.labels = [];
            pieData.datasets[0].data = [];

            // Update with new data and labels
            salesChartData.labels = name;
            salesChartData.datasets[0].data = counts;
            pieData.labels = name;
            pieData.datasets[0].data = counts;

            // Clear existing instances
            if (salesChart) {
                salesChart.destroy();
            }

            if (pieChart) {
                pieChart.destroy();
            }

            if (myBarChart) {
                myBarChart.destroy();
            }
            var xname, yname;

            // Add this array of colors
            var colorIndex = 0;
            var datasetColors = [];

            if (selectedmodel == "firm") {
                xname = "Firm Names";
                yname = "Assignments Completed";
                datasetColors = chartColors.slice(0, counts.length); // Use the first N colors
            } else if (selectedmodel == "honorificcode") {
                xname = "Honorific Codes";
                yname = "User Counts";
                datasetColors = chartColors.slice(0, counts.length);
            } else if (selectedmodel == "package") {
                xname = "Package Name";
                yname = "Subscriptions";
                datasetColors = chartColors.slice(0, counts.length);
            } else if (selectedmodel == "user") {
                xname = "Roles";
                yname = "User Counts";
                datasetColors = chartColors.slice(0, counts.length);
            } else if (selectedmodel == "addresstype") {
                xname = "Address Types";
                yname = "Counts";
                datasetColors = chartColors.slice(0, counts.length);
            } else if (selectedmodel == "businesstype") {
                xname = "Business Types";
                yname = "Counts";
                datasetColors = chartColors.slice(0, counts.length);
            } else if (selectedmodel == "firmtype") {
                xname = "Firm Types";
                yname = "Counts";
                datasetColors = chartColors.slice(0, counts.length);
            }

            // Update axis labels based on selected model
            salesChartOptions.scales.xAxes[0].scaleLabel.labelString = xname; // Update X-axis label
            salesChartOptions.scales.yAxes[0].scaleLabel.labelString = yname; // Update Y-axis label

            // Create new instances with custom colors
            var salesChartCanvas = document.getElementById('revenue-chart-canvas').getContext('2d');
            salesChart = new Chart(salesChartCanvas, {
                type: 'line',
                data: {
                    labels: name,
                    datasets: [{
                        label: yname,
                        backgroundColor: datasetColors,
                        borderColor: datasetColors,
                        borderWidth: 1,
                        data: counts
                    }]
                },
                options: salesChartOptions
            });

            var pieChartCanvas = $('#sales-chart-canvas').get(0).getContext('2d');
            pieChart = new Chart(pieChartCanvas, {
                type: 'doughnut',
                data: pieData,
                backgroundColor: datasetColors,
                borderColor: datasetColors,
                options: pieOptions
            });

            var barGraph = document.getElementById('bar-graph-canvas').getContext('2d');
            myBarChart = new Chart(barGraph, {
              type: 'bar',
              data: {
                  labels: name,
                  datasets: [{
                      label: yname,
                      backgroundColor: datasetColors, // Use the same colors as line chart
                      borderColor: datasetColors, // Use the same colors as line chart
                      borderWidth: 1,
                      data: counts
                  }]
              },
              options: salesChartOptions
          });

        })
        .catch(function (error) {
            console.error('Error fetching stats', error);
        });
}

function handleTransactionTrend() {
  $('#highestAmount').val(''); // Assuming you have an HTML element with id 'highestAmount'
  $('#lowestAmount').val('');  // Assuming you have an HTML element with id 'lowestAmount'
  $('#averageAmount').val(''); // Assuming you have an HTML element with id 'averageAmount'

  axios.get('/get-transaction-trend/')
      .then(function (response) {
          var alldata = response.data;

          // Extract labels and data from the response
          var labels = alldata.stats.map(function (data) {
              return data.yearQuarter; // Assuming 'yearQuarter' is the key in your response
          });

          var data = alldata.stats.map(function (data) {
              return data.totalAmount; // Assuming 'totalAmount' is the key in your response
          });

          // Find min and max amounts
          var minAmount = Math.min(...alldata.stats.map(data => data.totalAmount));
          var maxAmount = Math.max(...alldata.stats.map(data => data.totalAmount));

          // Find the corresponding month and year for min and max amounts
          var minData = alldata.stats.find(data => data.totalAmount === minAmount);
          var maxData = alldata.stats.find(data => data.totalAmount === maxAmount);

          var minMonths = minData.monthName;
          var maxMonths = maxData.monthName;
          var minYears = minData.year;
          var maxYears = maxData.year;

          console.log(alldata);

          var avgAmount = alldata.stats.reduce((sum, data) => sum + data.totalAmount, 0) / alldata.stats.length;


          // Update the salesGraphChartData object
          salesGraphChartData.labels = labels;
          salesGraphChartData.datasets[0].data = data;

          // Display additional stats
          $('#highestAmount').text('RM ' + maxAmount.toFixed(2) + ' (' + maxMonths + ' ' + maxYears + ')');
          $('#lowestAmount').text('RM ' + minAmount.toFixed(2) + ' (' + minMonths + ' ' + minYears + ')');
          $('#averageAmount').text('RM ' + avgAmount.toFixed(2));

          // Create the chart with updated data
          var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d');
          var salesGraphChart = new Chart(salesGraphChartCanvas, {
              type: 'line',
              data: salesGraphChartData,
              options: salesGraphChartOptions
          });
      })
      .catch(function (error) {
          console.error('Error fetching transaction trend', error);
      });
}









// Define the lineChart variable outside the function to keep track of the instance
var lineChart;

function handleStorageRecords(element) {
    var selectedmodel = element.value;
    $('#createCount').text('');
    $('#updateCount').text('');
    $('#deleteCount').text('');

    axios.get('/get-specific-record-details/' + selectedmodel)
        .then(function (response) {
            var alldata = response.data;

            console.log('alldata:', alldata);

            var allMonths = [...new Set([...Object.keys(alldata.creates), ...Object.keys(alldata.updates), ...Object.keys(alldata.softDeletes)])];
            allMonths.sort((a, b) => new Date(a) - new Date(b));

            var createData = allMonths.map(month => alldata.creates[month] || 0);
            var updateData = allMonths.map(month => alldata.updates[month] || 0);
            var deleteData = allMonths.map(month => alldata.softDeletes[month] || 0);

            var storageGraphCanvas = $('#line-chart2').get(0).getContext('2d');

            var storageGraphData = {
                labels: allMonths,
                datasets: [
                    {
                        label: 'Created At',
                        borderColor: 'rgba(0, 255, 0, 1)', // Green
                        data: createData,
                        fill: false
                    },
                    {
                        label: 'Updated At',
                        borderColor: 'rgba(0, 0, 255, 1)', // Blue
                        data: updateData,
                        fill: false
                    },
                    {
                        label: 'Soft Deleted',
                        borderColor: 'rgba(255, 0, 0, 1)', // Red
                        data: deleteData,
                        fill: false
                    }
                ]
            };

            // Destroy existing instance if it exists
            if (lineChart) {
                lineChart.destroy();
            }

            // Create a new instance with updated data
            lineChart = new Chart(storageGraphCanvas, {
                type: 'line',
                data: storageGraphData,
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'month',
                                displayFormats: {
                                    month: 'MMMM YYYY'
                                }
                            },
                            title: {
                                display: true,
                                text: 'Month Year'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            type: 'linear',
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Count'
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

        })
        .catch(function (error) {
            console.error('Error fetching specific record details', error);
        });
}











// Call the function when the page loads



$(function () {
  
  'use strict'
  $(document).ready(function () {
    // Trigger handleTableChange on page load
    handleTableChange($('#mySelect')[0]);
    handleTransactionTrend();
    handleStorageRecords($('#mySelect2')[0]); // [0] is used to get the DOM element from the jQuery object

    // Set up the change event handler for future changes
});
  // Make the dashboard widgets sortable Using jquery UI
  $('.connectedSortable').sortable({
    placeholder: 'sort-highlight',
    connectWith: '.connectedSortable',
    handle: '.card-header, .nav-tabs',
    forcePlaceholderSize: true,
    zIndex: 999999
  })
  $('.connectedSortable .card-header').css('cursor', 'move')

  // jQuery UI sortable for the todo list
  $('.todo-list').sortable({
    placeholder: 'sort-highlight',
    handle: '.handle',
    forcePlaceholderSize: true,
    zIndex: 999999
  })

  // bootstrap WYSIHTML5 - text editor
  $('.textarea').summernote()

  $('.daterange').daterangepicker({
    ranges: {
      Today: [moment(), moment()],
      Yesterday: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days': [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month': [moment().startOf('month'), moment().endOf('month')],
      'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate: moment()
  }, function (start, end) {
    // eslint-disable-next-line no-alert
    alert('You chose: ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
  })

  /* jQueryKnob */
  $('.knob').knob()

  // jvectormap data
  var visitorsData = {
    US: 398, // USA
    SA: 400, // Saudi Arabia
    CA: 1000, // Canada
    DE: 500, // Germany
    FR: 760, // France
    CN: 300, // China
    AU: 700, // Australia
    BR: 600, // Brazil
    IN: 800, // India
    GB: 320, // Great Britain
    RU: 3000 // Russia
  }
  // World map by jvectormap
  $('#world-map').vectorMap({
    map: 'usa_en',
    backgroundColor: 'transparent',
    regionStyle: {
      initial: {
        fill: 'rgba(255, 255, 255, 0.7)',
        'fill-opacity': 1,
        stroke: 'rgba(0,0,0,.2)',
        'stroke-width': 1,
        'stroke-opacity': 1
      }
    },
    series: {
      regions: [{
        values: visitorsData,
        scale: ['#ffffff', '#0154ad'],
        normalizeFunction: 'polynomial'
      }]
    },
    onRegionLabelShow: function (e, el, code) {
      if (typeof visitorsData[code] !== 'undefined') {
        el.html(el.html() + ': ' + visitorsData[code] + ' new visitors')
      }
    }
  })

  // // Sparkline charts
  // var sparkline1 = new Sparkline($('#sparkline-1')[0], { width: 80, height: 50, lineColor: '#92c1dc', endColor: '#ebf4f9' })
  // var sparkline2 = new Sparkline($('#sparkline-2')[0], { width: 80, height: 50, lineColor: '#92c1dc', endColor: '#ebf4f9' })
  // var sparkline3 = new Sparkline($('#sparkline-3')[0], { width: 80, height: 50, lineColor: '#92c1dc', endColor: '#ebf4f9' })

  // sparkline1.draw([1000, 1200, 920, 927, 931, 1027, 819, 930, 1021])
  // sparkline2.draw([515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921])
  // sparkline3.draw([15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21])

  // The Calender
  $('#calendar').datetimepicker({
    format: 'L',
    inline: true
  })
  $('#mySelect').change(function () {
    handleTableChange(this);
});
$('#mySelect2').change(function () {
  handleStorageRecords(this);
});
  // SLIMSCROLL FOR CHAT WIDGET
  $('#chat-box').overlayScrollbars({
    height: '250px'
  })

 
  /* Chart.js Charts */
  // Sales chart
  

  // Donut Chart
  

  // Sales graph chart
 
})
