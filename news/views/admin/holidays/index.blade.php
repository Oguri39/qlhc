@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('holidays/title.label')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link href="{{ asset('vendors/fullcalendar/css/fullcalendar.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('vendors/fullcalendar/css/fullcalendar.print.css') }}" rel="stylesheet" media='print'
      type="text/css">
<link href="{{ asset('vendors/iCheck/css/all.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('css/pages/calendar_custom.css') }}" rel="stylesheet" type="text/css"/>
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
    <h1>@lang('holidays/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.holidays') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('holidays/title.label')
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
                    @lang('holidays/title.label')
                </h4>                
            </div>
            <div class="card-body">
                <div id="calendar"></div>
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
    <script src="{{ asset('vendors/fullcalendar/js/fullcalendar.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendors/iCheck/js/icheck.js') }}"></script>
<script>

$(document).ready(function() {

    var datelist = [
        @foreach($listdate as $key => $value)
        {!! $value->ho_day!!},
        @endforeach
    ];
    var startdate = "";
    var isfeitch = 0;

    function getdatalist(startdate1){
        if(startdate1 != startdate){
            $.ajax({
                url: "{{route('admin.holidays.getdata')}}",
                type: 'POST',
                data: {
                    date : startdate1,
                    _token : '{{csrf_token()}}',
                },
                error: function(err) {

                },
                success: function(data) {                                                                
                    if(data.length > 0){
                        datelist = data;                        
                        startdate = startdate1;                        
                    }
                }
            });           
        } 
    }

    $('#calendar').fullCalendar({
        themeSystem: 'bootstrap4',
        displayEventTime: false,
        header: {
            left: 'prev today',
            center: 'title',
            right: 'next',
        },
        firstDay: 1,
        buttonText: {
            prev: '',
            next: '',
            today: 'Today',            
        },
        viewRender: function (view, element) {
            var a = view.start._d;
            var month = (a.getMonth()  + 1);
            var day = a.getDate();
            if(month < 10) month = "0" + month;
            if(day < 10) day = "0" + day;
            var startdate1 = a.getFullYear() + "-" + month + "-" + day;
            getdatalist(startdate1);
                  
        },
        dayRender: function(date, cell) {
            var a = date.get('year');            
            var b = date.get('month') + 1;
            var c = date.get('date');
            if(b < 10) b = "0" + b;
            if(c < 10) c = "0" + c;
            var today = a + "-" + b + "-" + c;
            for(var i = 0; i < datelist.length;i++){
                if (datelist[i] == today){
                    cell.css("background-color", "#FFF0C3");                        
                }    
            }            
        },                
        editable: false,
        eventLimit: true,
        droppable: false,        
        dayClick: function(date, jsEvent, view) {
            //console.log('Clicked on: ' + date.format());            
            var daychoose = $(this);
            $.ajax({
                url: "{{route('admin.holidays.saveday')}}",
                type: 'POST',
                data: {
                    date : date.format(),
                    _token : '{{csrf_token()}}',
                },
                error: function(err) {

                },
                success: function(data) {                                            
                    if(data == 1){
                        daychoose.css('background-color', '#FFF0C3');
                    }else if(data == -1){
                        daychoose.css('background-color', '');
                    }
                }
            });
            

          }
    });    

    $('.fc-button-prev').click(function(){
       alert('prev is clicked, do something');
    });

    $('.fc-button-next').click(function(){
       alert('nextis clicked, do something');
    });
});


</script>   
@stop
