<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable
            ([['X', '1', '2', '3', '4', '5', '6'],
              [1, 2, 3, 4, 5, 6, 7],
              [2, 3, 4, 5, 6, 7, 8],
              [3, 4, 5, 6, 7, 8, 9],
              [4, 5, 6, 7, 8, 9, 10],
              [5, 6, 7, 8, 9, 10, 11],
              [6, 7, 8, 9, 10, 11, 12]
        ]);

        var options = {
          legend: 'none',
          series: {
            0: { color: '#e2431e' },
            1: { color: '#e7711b' },
            2: { color: '#f1ca3a' },
            3: { color: '#6f9654' },
            4: { color: '#1c91c0' },
            5: { color: '#43459d' },
          }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
					<div class="card-body">
						<div class="app" id="loadbieudo">
                        
                        <div id="chart_div" style="width: 900px; height: 500px;"></div>
                        <p>biểu đồ a</p>
						</div>
					</div>

<script src="{{ asset('vendors/frappe/frappe-charts.min.iife.js') }}"></script>
<script src="{{ asset('vendors/highcharts/highcharts.js') }}" charset="utf-8"></script>

