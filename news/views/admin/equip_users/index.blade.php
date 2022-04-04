@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('equip_users/title.label')
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
    <h1>@lang('equip_users/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('equip_users/title.label')
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
                    @lang('equip_users/title.label')
                </h4>                
            </div>
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">                
                <table class="table table-striped table-bordered" id="table" width="100%">
                    <thead>
                     <tr>
                        <th >@lang('equip_users/title.id')</th>
                        <th >@lang('equip_users/title.employee')</th>
                        <th >@lang('equip_users/title.equipid')</th>
                        <th >@lang('equip_users/title.equiptype')</th>
                        <th >@lang('equip_users/title.equipment')</th>
                        <th >@lang('equip_users/title.starttime')</th>
                        <th >@lang('equip_users/title.endtime')</th>
                        <th >@lang('equip_users/title.odometerstart')</th>
                        <th >@lang('equip_users/title.odometerend')</th>
                        <th >@lang('equip_users/title.starthour')</th>
                        <th >@lang('equip_users/title.endhour')</th>
                        <th >@lang('equip_users/title.backtoshop')</th>  
                        <th >@lang('equip_users/title.startcheckid')</th>
                        <th >@lang('equip_users/title.endcheckid')</th>                            
                     </tr>
                    </thead>
                    <tbody>  
                    </tbody>
                    <tfoot>
                     <tr>
                        <th >@lang('equip_users/title.id')</th>
                        <th >@lang('equip_users/title.employee')</th>
                        <th >@lang('equip_users/title.equipid')</th>
                        <th >@lang('equip_users/title.equiptype')</th>
                        <th >@lang('equip_users/title.equipment')</th>
                        <th >@lang('equip_users/title.starttime')</th>
                        <th >@lang('equip_users/title.endtime')</th>
                        <th >@lang('equip_users/title.odometerstart')</th>
                        <th >@lang('equip_users/title.odometerend')</th>
                        <th >@lang('equip_users/title.starthour')</th>
                        <th >@lang('equip_users/title.endhour')</th>
                        <th >@lang('equip_users/title.backtoshop')</th>  
                        <th >@lang('equip_users/title.startcheckid')</th>
                        <th >@lang('equip_users/title.endcheckid')</th>                            
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
            ajax: '{!! route('admin.equip_users.getdata') !!}',
            columns: [
                { data: 'eu_id', name: 'eu_id' },
                { data: 'employeename', name: 'employeename' },
                { data: 'et_title', name: 'et_title' }, 
                { data: 'eq_internalcode', name: 'eq_internalcode' },
                { data: 'eq_name', name: 'eq_name' },
                { data: 'eu_starttext', name: 'eu_starttext' },
                { data: 'eu_endtext', name: 'eu_endtext' },
                { data: 'eu_miles_start', name: 'eu_miles_start' },
                { data: 'eu_miles_end', name: 'eu_miles_end' },
                { data: 'eu_nrhoursstart', name: 'eu_nrhoursstart' },
                { data: 'eu_nrhoursend', name: 'eu_nrhoursend' },
                { data: 'eu_onsitetext', name: 'eu_onsitetext' },
                { data: 'eu_ec_starttext', name: 'eu_ec_starttext' },
                { data: 'eu_ec_endtext', name: 'eu_ec_endtext' },               
            ],
            createdRow: function( row, data, dataIndex ) {
                $('td', row).css('background-color', data['bgcolor']);
            }
        });   
    });
    
</script>   
@stop
