<!-- <div class="card-body">
						<div class="app">
						{{ $line->container() }}
						{{ $line->script() }}
						</div>
</div> -->
<!-- <h1>xin chào</h1>
<div class="col-lg-7 col-md-6 col-12 my-3">
				<div class="card ">
					<div class="card-header bg-primary text-white ">
						<span>
							<i class="livicon" data-name="barchart" data-size="16" data-loop="true" data-c="#fff"
							data-hc="#fff"></i> Hóa Chất
						</span>
						<span class="float-right">
							<i class="fa fa-chevron-up showhide clickable"></i>
							
						</span>
					</div>
					<div class="card-body">
						<div class="app"> -->
						{{ $line->container() }}
						{{ $line->script() }}
						<!-- </div>
					</div>
				</div>
			</div> -->

<script src="{{ asset('vendors/highcharts/highcharts.js') }}" charset="utf-8"></script>
<script src="{{ asset('vendors/frappe/frappe-charts.min.iife.js') }}"></script>
<script src="{{ asset('vendors/chartjs/js/Chart.js') }}" charset="utf-8"></script>