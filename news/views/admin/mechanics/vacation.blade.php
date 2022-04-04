@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('employees/title.label')
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
    <h1>@lang('employees/title.employee') {{$firstname}} {{$lastname}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('employees/title.label')
            </a>
        </li>   
        <li><a href="#">@lang('employees/title.vacations')</a></li>     
    </ol>
</section>

<!-- Main content -->
<section class="content pl-3 pr-3">
    <div class="row">
        <div class="col-12">
        <div class="card ">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title my-2 float-left">
                    @lang('employees/title.vacations')
                </h4>                                
            </div>            
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">
                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                    <label>@lang('employees/message.vacation.title')</label><br/>
                    <table class="table table-bordered width100" border="1">
                        <tr>
                            <th><label>@lang('employees/title.day')</label></th>
                            <th width="20%"><input type="text" id="day" class="form-control" /></th>
                            <th><label>@lang('employees/title.hours')</label></th>
                            <th width="10%"><input type="number" min="0" id="hour" class="form-control" value="0"/></th>
                            <th><label>@lang('employees/title.description')</label></th>
                            <th width="40%"><input type="text" id="description" class="form-control" /></th>
                            <th><button id="btn_save" class="btn btn-success">@lang('button.save')</button></th> 
                        </tr>                      
                    </table>                    
                    
                </div>
                <br/>
                <table class="table table-striped table-bordered" id="table" width="100%">
                    <thead>
                     <tr>
                        <th></th>
                        <th >@lang('employees/title.day')</th>
                        <th >@lang('employees/title.hours')</th>
                        <th >@lang('employees/title.note')</th>    
                        <th ></th>                            
                     </tr>
                    </thead>
                    <tbody>  
                    </tbody>
                </table>
                <br/>
                <a href="{{route('mechanics.index')}}" class="btn btn-primary">@lang('employees/title.back')</a>
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
   
    $(function() {        
        var table = $('#table').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: true,
            ajax: "{!! route('mechanics.getdatavacation',$us_id) !!}",
            order: [],
            columns: [
                { data: 'actions', name: 'actions', orderable: false, searchable: false  },
                { data: 'day', name: 'day' },
                { data: 'va_hours', name: 'va_hours' },
                { data: 'va_title', name: 'va_title' },                
            ],                        
        });
        
        $('#table tbody').on('click', 'tr', function () {            
            var data = table.row( this ).data();  
            if(data.actions == "@lang('button.edit')"){
                $('#day').val(data.day.replace(/&quot;/g,'"'));
                $('#hour').val(data.va_hours.replace(/&quot;/g,'"'));
                $('#description').val(data.va_title.replace(/&quot;/g,'"'));            
            }
        });

        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

        $('#day').datetimepicker({
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
                
        $('#btn_save').click(function(){
            var a = $('#day').val();
            var b = $('#hour').val();
            var c = $('#description').val();
            // check login
            if(a != ''){            
                $.ajax({
                    url: "{{route('mechanics.savevacation')}}",
                    type: 'POST',
                    data: {
                        us_id : "{{$us_id}}",
                        day : a,                    
                        hours : b,
                        description : c,
                        _token : '{{csrf_token()}}',
                    },
                    error: function(err) {

                    },
                    success: function(data) {                                            
                        if(data == 1){                        
                            table.ajax.reload();
                        }
                    }
                });
            }

            return false;
        });
        
    });
    
</script>   
@stop
