@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('jobs/title.label')
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
    <h1>@lang('jobs/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('jobs/title.label')
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
                    @lang('jobs/title.label')
                </h4>                
            </div>
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">                
                <table class="table table-striped table-bordered" id="table" width="100%">
                    <thead>
                     <tr>
                        <th >@lang('jobs/title.id')</th>
                        <th >@lang('jobs/title.jobnr')</th>
                        <th >@lang('jobs/title.company')</th>    
                        <th >@lang('jobs/title.description')</th>
                        <th >@lang('jobs/title.admin')</th>
                        <th >@lang('jobs/title.dateopen')</th>
                        <th >@lang('jobs/title.payrate')</th>
                        <th >@lang('jobs/title.status')</th>                            
                     </tr>
                    </thead>
                    <tbody>  
                    </tbody>
                    <tfoot>
                     <tr>
                        <th >@lang('jobs/title.id')</th>
                        <th >@lang('jobs/title.jobnr')</th>
                        <th >@lang('jobs/title.company')</th>    
                        <th >@lang('jobs/title.description')</th>
                        <th >@lang('jobs/title.admin')</th>
                        <th >@lang('jobs/title.dateopen')</th>
                        <th >@lang('jobs/title.payrate')</th>
                        <th >@lang('jobs/title.status')</th>                            
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
    <script src="{{ asset('vendors/chartjs/js/Chart.js') }}"></script>
<script>
    function changeactive(id,status){
        
    }

    $(function() {        
        var table = $('#table').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.jobs.getdata') !!}',
            columns: [
                { data: 'jid', name: 'jid' },
                { data: 'nr', name: 'nr' },
                { data: 'company', name: 'company' },
                { data: 'description', name: 'description' },
                { data: 'padmin', name: 'padmin'},
                { data: 'dateopen', name:'dateopen'},
                { data: 'jpaytypetext', name: 'jpaytypetext'},
                { data: 'jstatustext', name: 'jstatustext'},
            ]
        });   

        $('#table tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();            
            var jobid = data.jid;               
            window.location = "{{ route('admin.compare.getdatajob') }}?jid=" + jobid;            
        });

    });
    var $url_path = '{!! url('/') !!}';
    $('#delete_confirm').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var $recipient = button.data('id');
        var modal = $(this)
        modal.find('.modal-footer a').prop("href",$url_path+"/admin/users/"+$recipient+"/delete");
    })

</script>   
@stop
