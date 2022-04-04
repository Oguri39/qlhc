@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('timecheck/title.label')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link href="{{ asset('css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
    type="text/css" />
<link rel="stylesheet" href="{{asset('css/easy-autocomplete.css')}}" type="text/css"/>
<link rel="stylesheet" href="{{asset('css/easy-autocomplete.themes.min.css')}}" type="text/css"/>
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('timecheck/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('timecheck/title.label')
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
                    @lang('timecheck/title.label')
                </h4>                                
            </div>            
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">
                <div class="form-group">
                    <div class="row">
                        <label for="minutes" class="col-sm-2 control-label" style="margin-top: 5px;">@lang('timecheck/title.minutes')</label>
                        <div class="col-sm-2">
                            <input type="number" id="minutes" value="60" class="form-control" min="0">
                        </div>
                        <div class="col-sm-1"></div>
                        <label for="employee" class="col-sm-2 control-label" style="margin-top: 5px;">@lang('timecheck/title.employee')</label>
                        <div class="col-sm-4">
                            {!!Form::select('us_id', $listemployees, null, ['class' => 'form-control', 'id' => 'us_id'])!!}
                        </div>                        
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="fromdate" class="col-sm-2 control-label" style="margin-top: 5px;">@lang('timecheck/title.fromdate')</label>
                        <div class="col-sm-2">                            
                            <input type="text" id="fromdate" class="form-control" value="{{date('m/d/Y',strtotime('-1 month'))}}" />
                        </div>
                        <div class="col-sm-1"></div>
                        <label for="todate" class="col-sm-2 control-label" style="margin-top: 5px;">@lang('timecheck/title.todate')</label>
                        <div class="col-sm-2">                            
                            <input type="text" id="todate" class="form-control" value="{{date('m/d/Y')}}"/>
                        </div>
                        <div class="col-sm-1">
                            <button id="btn_calculate" class="btn btn-success">@lang('timecheck/title.calculate')</button>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered" id="table" width="100%">
                    <thead>
                     <tr>
                        <th></th>                                                 
                     </tr>
                    </thead>
                    <tbody>  
                    </tbody>
                </table>                                     
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

<script>
    var table;    

    $(function() { 
        var myData = {
                    fromdate: $('#fromdate').val().trim(),
                    todate: $('#todate').val().trim(),
                    minutes: $('#minutes').val().trim(),
                    us_id: $('#us_id').val(),
                };                  
        table = $('#table').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: false,
            ajax: {
                url : "{!! route('admin.timecheck.getdata') !!}",
                type: 'POST',
                data: function ( d ) {
                   return  $.extend(d, myData);
                },
            },
            order: [],
            columns: [
                //{ data: 'actions', name: 'actions', orderable: false, searchable: false  },
                { data: 'content', name: 'content', orderable: false },
            ],                        
        });

        $('#btn_calculate').click(function(){                                
            myData = {
                    fromdate: $('#fromdate').val().trim(),
                    todate: $('#todate').val().trim(),
                    minutes: $('#minutes').val().trim(),
                    us_id: $('#us_id').val(),
                };
            table.ajax.reload();
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
    });
    
</script>   
@stop
