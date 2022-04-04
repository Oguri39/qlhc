@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('dashboard/title.label')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link href="{{ asset('css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
    type="text/css" />
<link rel="stylesheet" href="{{ asset('css/pages/jscharts.css') }}" />
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('dashboard/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('dashboard/title.label')
            </a>
        </li>        
    </ol>
</section>

<!-- Main content -->
<section class="content pl-3 pr-3">
    <div class="row">
        <div class="col-12">
        <div class="card ">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title my-2 float-left">
                    @lang('dashboard/title.label')
                </h4>                
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="row">
                        <label for="fromdate" class="col-sm-2 control-label" style="text-align: right;margin-top: 5px;">@lang('dashboard/title.fromdate')</label>
                        <div class="col-sm-2">  
                            <div class="input-group">                                
                                <input type="text" id="fromdate" class="form-control" value="{{date('m/d/Y',strtotime('-1 week'))}}"/>
                            </div>  
                        </div>
                        <label for="todate" class="col-sm-1 control-label" style="text-align: right;margin-top: 5px;">@lang('dashboard/title.todate')</label>
                        <div class="col-sm-2">  
                            <div class="input-group">
                                <input type="text" id="todate" class="form-control" value="{{date('m/d/Y')}}"/>
                            </div>  
                        </div>
                        <label for="jobnr" class="col-sm-1 control-label" style="text-align: right;margin-top: 5px;">@lang('dashboard/title.jobnr')</label>
                        <div class="col-sm-2">  
                            <input type="text" id="jobnr" class="form-control" value="" />
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-success" id="btn_calculate">@lang('dashboard/title.calculate')</button>
                        </div>  
                    </div>
                    <br/>
                    <div class="row" id="div_pov_miles"></div>
                </div>
                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                    <table class="table table-bordered width100" id="table" border="1">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="text-align: center;">@lang('dashboard/title.regular')</th>
                                <th style="text-align: center;">@lang('dashboard/title.overtime')</th>
                                <th style="text-align: center;">@lang('dashboard/title.double')</th>
                                <th style="text-align: center;">@lang('dashboard/title.total')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background-color: rgb(237,194,64);">
                                <th>@lang('dashboard/title.onjob')</th>
                                @for($i = 0;$i < 4; $i++)
                                <td style="text-align: center;"><div id="div0_{{$i}}"></div></td>
                                @endfor
                            </tr>

                            <tr style="background-color: rgb(175,216,248);">
                                <th>@lang('dashboard/title.shop')</th>  
                                @for($i = 0;$i < 4; $i++)
                                <td style="text-align: center;"><div id="div1_{{$i}}"></div></td>
                                @endfor                          
                            </tr>

                            <tr style="background-color: rgb(203,105,105);">
                                <th>@lang('dashboard/title.drive')</th>   
                                @for($i = 0;$i < 4; $i++)
                                <td style="text-align: center;"><div id="div2_{{$i}}"></div></td>
                                @endfor                         
                            </tr>

                            <tr style="background-color: rgb(77,167,77);">
                                <th>@lang('dashboard/title.vacation')</th>   
                                @for($i = 0;$i < 4; $i++)
                                <td style="text-align: center;"><div id="div3_{{$i}}"></div></td>
                                @endfor                         
                            </tr>

                            <tr style="background-color: rgb(178,104,237);">
                                <th>@lang('dashboard/title.holiday')</th> 
                                @for($i = 0;$i < 4; $i++)
                                <td style="text-align: center;"><div id="div4_{{$i}}"></div></td>
                                @endfor                           
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-md-6 col-12 my-3">
                        <!-- Basic charts strats here-->
                        <div class="card ">
                            <div class="card-header bg-secondary text-white">
                                <span>
                                    <i class="livicon" data-name="barchart" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                                    @lang('dashboard/title.work_distribution')
                                </span>
                                        <span class="float-right">
                                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                                            <i class="fa fa-fw fa-times removepanel clickable"></i>
                                        </span>
                            </div>
                            <div class="card-body">
                                <div>
                                    <canvas id="pie-chart1" width="800" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-md-6 col-12 my-3">
                        <!-- Basic charts strats here-->
                        <div class="card ">
                            <div class="card-header bg-secondary text-white">
                                <span>
                                    <i class="livicon" data-name="barchart" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                                    @lang('dashboard/title.time_distribution')
                                </span>
                                        <span class="float-right">
                                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                                            <i class="fa fa-fw fa-times removepanel clickable"></i>
                                        </span>
                            </div>
                            <div class="card-body">
                                <div>
                                    <canvas id="pie-chart2" width="800" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div><!-- row-->
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script src="{{ asset('vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>    
    <script src="{{ asset('vendors/clockface/js/clockface.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendors/chartjs/js/Chart.js') }}"></script>
<script>
    var myPie1 = undefined;
    var myPie2 = undefined;
    var label1 = ["@lang('dashboard/title.onjob')", "@lang('dashboard/title.shop')", "@lang('dashboard/title.drive')","@lang('dashboard/title.vacation')","@lang('dashboard/title.holiday')"];
    var label2 = ["@lang('dashboard/title.regular')", "@lang('dashboard/title.overtime')", "@lang('dashboard/title.double')"];

    var pieData1 = {
        labels: label1,
        datasets: [
            {
                data: [0, 0, 0, 0, 0],
                backgroundColor: ['#EDC240', '#AFD8F8', '#CB6969','#4DA74D','#B268ED'],
                hoverBackgroundColor: ['#EDC240', '#AFD8F8', '#CB6969','#4DA74D','#B268ED'],
            },
        ],
    };

    var pieOption = {
        responsive: true,
        legend: {
            position: 'right',
            labels: {
                fontColor: "black",
                boxWidth: 30,
                padding: 20
            }
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                   var dataset = data.datasets[tooltipItem.datasetIndex];
                   var label = data.labels[tooltipItem.datasetIndex];
                   var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                      return previousValue + currentValue;
                   });
                   var currentValue = dataset.data[tooltipItem.index];
                   var precentage = Math.floor(((currentValue / total) * 100) + 0.5);
                   return precentage + "%, " + label;
                }
            }
        },
        animation: {
            onComplete: function () {
                var ctx = this.chart.ctx;
                ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
                ctx.textAlign = 'center';
                ctx.textBaseline = 'bottom';
                var labeldata = this.data.labels;
                this.data.datasets.forEach(function (dataset) {
                    for (var i = 0; i < dataset.data.length; i++) {
                        var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
                        total = dataset._meta[Object.keys(dataset._meta)[0]].total,
                        mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
                        start_angle = model.startAngle,
                        end_angle = model.endAngle,
                        mid_angle = start_angle + (end_angle - start_angle)/2;

                        var x = mid_radius * Math.cos(mid_angle) - 20;
                        var y = mid_radius * Math.sin(mid_angle);

                        ctx.fillStyle = '#fff';
                        if (i == 3){ // Darker text color for lighter background
                            ctx.fillStyle = '#444';
                        }
                        var percent = Math.round(dataset.data[i]/total*100);                  
                        if(percent >= 10) {
                            ctx.fillText(String(percent) + "%", model.x + x, model.y + y + 15);
                            ctx.fillText(labeldata[i], model.x + x + 10, model.y + y);
                        }
                    }
                });               
            }
        },
    };

    var pieData2 = {
        labels: label2,
        datasets: [
            {
                data: [0, 0, 0],
                backgroundColor: ['#EDC240', '#AFD8F8', '#CB6969'],
                hoverBackgroundColor: ['#EDC240', '#AFD8F8', '#CB6969'],
            },
        ],
    };

    function showTime(minutes) {
        var hours = Math.floor(minutes / 60);
        var min = minutes - (hours * 60);
        var out = "";
        if(hours < 10) out += "0" + hours;
        else out += hours;
        if(min < 10) out += ":0" + min;
        else out += ":" + min;
        return out;
    }

    function loaddata(){
        $.ajax({
            url: "{{route('admin.dashboard.getdata')}}",
            type: 'POST',
            data: {
                fromdate : $('#fromdate').val(),
                todate : $('#todate').val(),
                jobnr : $('#jobnr').val(),
                _token : '{{csrf_token()}}',
            },
            error: function(err) {

            },
            success: function(data) {                              
                var text = '<label for="pov_miles" class="col-sm-3 control-label" style="text-align: left;">' + "@lang('dashboard/title.pov_miles')" + ': ' + data[1] + '</label>';
                $('#div_pov_miles').html(text);   

                var dat = data[0];
                var dat1 = [];
                var dat2 = [];
                var dat1l = [];
                var dat2l = [];

                for(var i = 0;i<5;i++){
                    for(var j = 0;j < 4;j++){
                        $('#div' + i + '_' + j).html(showTime(dat[i][j])); 
                    }
                    dat1[i] = dat[i][3];
                } 

                for(var j = 0;j < 3;j++){
                    dat2[j] = 0;
                    for(var i = 0;i<5;i++){
                        dat2[j] += dat[i][j];
                    }
                }

                for(var i = 0;i<5;i++){
                    dat1l[i] = label1[i] + " (" + showTime(dat1[i]) + " h)";
                }
                pieData1.labels = dat1l;

                for(var i = 0;i<3;i++){
                    dat2l[i] = label2[i] + " (" + showTime(dat2[i]) + " h)";
                }
                pieData2.labels = dat2l;

                var selector1 = '#pie-chart1';
                var selector2 = '#pie-chart2';
                $(selector1).attr('width',$(selector1).parent().width());
                $(selector2).attr('width',$(selector2).parent().width());

                pieData1.datasets[0].data = dat1;
                pieData2.datasets[0].data = dat2;

                if(myPie1 == undefined){
                    myPie1 = new Chart($(selector1), {
                        type: 'pie',
                        data: pieData1,
                        options: pieOption,
                   });    
                }else{
                    //myPie1.data = pieData1;
                    myPie1.update();
                }

                if(myPie2 == undefined){
                    myPie2 = new Chart($(selector2), {
                        type: 'pie',
                        data: pieData2,
                        options: pieOption,
                   });    
                }else{
                    //myPie2.data = pieData2;
                    myPie2.update();
                }                
            },
        });
    }

    $(function() {
        
        $('#fromdate').datetimepicker({
            viewMode: 'days',
            format: 'MM/DD/YYYY',
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
            },

        });

        $('#todate').datetimepicker({
            viewMode: 'days',
            format: 'MM/DD/YYYY',
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
            },
        });

        $('#btn_calculate').click(function(){            
            loaddata();  
        }); 

        loaddata();
    });

</script>   
@stop
