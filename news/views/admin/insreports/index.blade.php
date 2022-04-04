@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('insreports/title.label')
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
    <h1>@lang('insreports/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('insreports/title.label')
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
                    @lang('insreports/title.reports')
                </h4>                
            </div>
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md"> 
                <div class="form-group">
                    <div class="row"> 
                        <div class="col-sm-4">
                            <input type="checkbox" id="showreport"> <label class="control-label">@lang('insreports/title.showreport')</label>
                        </div>
                    </div>
                </div>               
                <table class="table table-striped table-bordered" id="table" width="100%">
                    <thead>
                        <tr>                            
                            <th>@lang('insreports/title.id')</th>
                            <th>@lang('insreports/title.date')</th>
                            <th>@lang('insreports/title.code')</th>
                            <th>@lang('insreports/title.eqtype')</th>
                            <th>@lang('insreports/title.equipment')</th>
                            <th>@lang('insreports/title.user')</th>
                            <th>@lang('insreports/title.type')</th>
                            <th>@lang('insreports/title.problems')</th>
                            <th>@lang('insreports/title.workorders')</th>                            
                            <th>@lang('insreports/title.status')</th> 
                        </tr>
                    </thead>                    
                    <tbody>                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>@lang('insreports/title.id')</th>
                            <th>@lang('insreports/title.date')</th>
                            <th>@lang('insreports/title.code')</th>
                            <th>@lang('insreports/title.eqtype')</th>
                            <th>@lang('insreports/title.equipment')</th>
                            <th>@lang('insreports/title.user')</th>
                            <th>@lang('insreports/title.type')</th>
                            <th>@lang('insreports/title.problems')</th>
                            <th>@lang('insreports/title.workorders')</th>                            
                            <th>@lang('insreports/title.status')</th>
                        </tr>
                    </tfoot>
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
    $(function() {
        var table = $('#table').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.insreports.getdata') !!}' + "?show=0",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'date', name: 'date' },
                { data: 'code', name: 'code' }, 
                { data: 'eqtype', name: 'eqtype' },
                { data: 'equipment', name: 'equipment' },
                { data: 'user', name: 'user' },
                { data: 'type', name: 'type' },
                { data: 'problems', name: 'problems' },
                { data: 'workorders', name: 'workorders' },
                { data: 'status', name: 'status' },                
            ],
            // createdRow: function( row, data, dataIndex ) {
            //     $('td', row).css('background-color', data['bgcolor']);
            // }
        });

        $('#showreport').click(function(){
            var isshow = 0;            
            if( $(this).is(':checked')) isshow = 1;
            table.ajax.url('{!! route('admin.insreports.getdata') !!}' + "?show=" + isshow).load();
        })

    });
    
</script>   
@stop
