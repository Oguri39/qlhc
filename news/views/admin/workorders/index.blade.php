@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('workorders/title.label')
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
    <h1>@lang('workorders/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('workorders/title.label')
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
                    @lang('workorders/title.label')
                </h4>                
            </div>
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">                
                <table class="table table-striped table-bordered" id="table" width="100%">
                    <thead>
                        <tr>                            
                            <th>@lang('workorders/title.id')</th>
                            <th id="colPriority">@lang('workorders/title.priority')</th>
                            <th>@lang('workorders/title.code')</th>
                            <th id="colEqType">@lang('workorders/title.eqtype')</th>
                            <th id="colEquipment">@lang('workorders/title.equipment')</th>
                            <th>@lang('workorders/title.inspectionnr')</th>
                            <th>@lang('workorders/title.item')</th>
                            <th id="colAssigned">@lang('workorders/title.assignedto')</th>
                            <th>@lang('workorders/title.startdate')</th>
                            <th>@lang('workorders/title.enddate')</th>
                            <th id="colStatus">@lang('workorders/title.status')</th>
                        </tr>
                    </thead>                    
                    <tbody>                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>@lang('workorders/title.id')</th>
                            <th>@lang('workorders/title.priority')</th>
                            <th>@lang('workorders/title.code')</th>
                            <th>@lang('workorders/title.eqtype')</th>
                            <th>@lang('workorders/title.equipment')</th>
                            <th>@lang('workorders/title.inspectionnr')</th>
                            <th>@lang('workorders/title.item')</th>
                            <th>@lang('workorders/title.assignedto')</th>
                            <th>@lang('workorders/title.startdate')</th>
                            <th>@lang('workorders/title.enddate')</th>
                            <th>@lang('workorders/title.status')</th>
                        </tr>
                    </tfoot>
                </table>
                <br/>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-2">
                            {!!Form::select('openclose', $listoc, null, ['class' => 'form-control', 'id' => 'openclose'])!!}
                        </div>
                        <div class="col-sm-4">
                            <button id="btnchoose" class="btn btn-success">@lang('workorders/title.closeopen')</button>
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
    
<script>
    $(function() {
        var table = $('#table').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.workorders.getdata') !!}',
            columns: [
                { data: 'wo_id', name: 'wo_id' },
                { data: 'wo_priority', name: 'wo_priority' },
                { data: 'eq_internalcode', name: 'eq_internalcode' }, 
                { data: 'et_title', name: 'et_title' },
                { data: 'eq_name', name: 'eq_name' },
                { data: 'wo_ec_id', name: 'wo_ec_id' },
                { data: 'wo_item', name: 'wo_item' },
                { data: 'assignedto', name: 'assignedto' },
                { data: 'startdatetext', name: 'startdatetext' },
                { data: 'enddatetext', name: 'enddatetext' },
                { data: 'statustext', name: 'statustext' },                              
            ],
            // createdRow: function( row, data, dataIndex ) {
            //     $('td', row).css('background-color', data['bgcolor']);
            // }
        });

        $('#btnchoose').click(function(){
            $.ajax({
                url: "{{route('admin.workorders.updateopenclose')}}",
                type: 'POST',
                data: {
                    openclose : $('#openclose').val(),
                    _token : '{{csrf_token()}}',
                },
                error: function(err) {

                },
                success: function(data) {                        
                    
                }
            }); 
        })

    });
    
</script>   
@stop
