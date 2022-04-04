@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('drivemap/title.label')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link href="{{ asset('css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
    type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/gmaps/css/examples.css') }}"/>
<link href="{{ asset('css/pages/googlemaps_custom.css') }}" rel="stylesheet">
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('drivemap/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('drivemap/title.label')
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
                <h4 class="card-title my-2 float-left" id="title_year">
                    @lang('drivemap/title.label')
                </h4>                                
            </div>            
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">
                <div class="form-group">
                    <div class="row">                        
                        <label for="fromdate" class="col-sm-2 control-label" style="margin-top: 5px;">@lang('drivemap/title.fromdate')</label>
                        <div class="col-sm-3">                            
                            <input type="text" id="fromdate" class="form-control" value="{{date('m/d/Y',strtotime('-1 week'))}}" />
                        </div>
                        <label for="todate" class="col-sm-2 control-label" style="margin-top: 5px;">@lang('drivemap/title.todate')</label>
                        <div class="col-sm-3">                            
                            <input type="text" id="todate" class="form-control" value="{{date('m/d/Y')}}"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="eq_id" class="col-sm-2 control-label" style="margin-top: 5px;">@lang('drivemap/title.equipment')</label>
                        <div class="col-sm-3">
                            {!!Form::select('eq_id', $listequipments, null, ['class' => 'form-control', 'id' => 'eq_id'])!!}
                        </div>
                        <label for="employee" class="col-sm-2 control-label" style="margin-top: 5px;">@lang('drivemap/title.employee')</label>
                        <div class="col-sm-3">
                            {!!Form::select('us_id', $listemployees, null, ['class' => 'form-control', 'id' => 'us_id'])!!}
                        </div>
                        <div class="col-sm-2">
                            <button id="btn_calculate" class="btn btn-success">@lang('drivemap/title.calculate')</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div id="gmap-markers" class="gmap"></div>
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
    <script type="text/javascript"
            src="http://maps.googleapis.com/maps/api/js?libraries=geometry&key={{ env('GOOGLE_MAPS_API_KEY') }}">
    </script>

    <script type="text/javascript" src="{{ asset('vendors/gmaps/js/gmaps.min.js') }}"></script>
<script>
    var map;

    function loadmarker(){
        var urlajax = "{{route('admin.drivemap.getdata')}}";
        $.ajax({
            url: urlajax,
            type: 'POST',
            data: {
                us_id : $('#us_id').val(),   
                fromdate: $('#fromdate').val(),
                todate : $('#todate').val(),
                eq_id : $('#eq_id').val(),
                _token : '{{csrf_token()}}',
            },
            error: function(err) {

            },
            success: function(data) {  
                var dats = JSON.parse(data); 
                console.log(dats);
                // var dat = dats;
                // for(var i = 0;i < map.markers.length;i++){
                //     map.markers[i].setMap(null);         
                // }                      
                // for(var i = 0;i < dat.length;i++){
                //     map.addMarker({
                //         lat: dat[i].lat,
                //         lng: dat[i].lng,
                //         title: dat[i].title,
                //         infoWindow: {
                //             content: '<p>' + dat[i].desc + '</p>',
                //         },
                //     });
                // }
                // if(dat.length > 0){
                //     map.setCenter(dat[0].lat, dat[0].lng);                                            
                // }                    
            }
        });
    }

    $(function() { 
        
        map = new GMaps({
            el: '#gmap-markers',
            lat: 38.9082624,
            lng: -77.0310385,
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });
                
        $('.gmap').closest('.form-group').on('resize', function() {
            $(window).trigger('resize');
        });

        $('#btn_calculate').click(function(){                                
            loadmarker();
        });  

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

        loadmarker();     
    });
    
</script>   
@stop
