@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('export/title.label')
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
<link rel="stylesheet" href="{{asset('css/select2.min.css')}}" type="text/css"/>
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('export/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.export') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('export/title.label')
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
                    @lang('export/title.exportnote')
                </h4>                
            </div>
            <div class="card-body">                                
                <div class="form-group">
                    <div class="row">
                        <label class="control-label col-sm-1" style="margin-top: 5px;">@lang('export/title.employee')</label> 
                        <div class="col-sm-11">
                            {!!Form::select('exportemployee', $listemployees, null, ['class' => 'form-control', 'id' => 'exportemployee'])!!}
                            
                        </div>                              
                    </div>                  
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="control-label col-sm-1" style="margin-top: 5px;">@lang('export/title.job')</label> 
                        <div class="col-sm-11">
                            <select id="exportjob" name="exportjob" class="form-control">
                                <option value="0">---</option>
                            </select>                            
                        </div>                              
                    </div>                  
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6" id="divexportcsv">                            
                        </div>                              
                    </div>                  
                </div>                
                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                    <table class="table table-bordered width100" id="table">
                        <thead>
                            <tr>
                                <th>@lang('export/title.employeeno')</th>
                                <th>@lang('export/title.name')</th>
                                <th>@lang('export/title.jobno')</th>
                                <th>@lang('export/title.notes')</th>
                                <th>@lang('export/title.wkd_end_time')</th>
                                <th>@lang('export/title.hrs_end_time')</th>                                
                            </tr>
                        </thead>
                        <tbody>  
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>@lang('export/title.employeeno')</th>
                                <th>@lang('export/title.name')</th>
                                <th>@lang('export/title.jobno')</th>
                                <th>@lang('export/title.notes')</th>
                                <th>@lang('export/title.wkd_end_time')</th>
                                <th>@lang('export/title.hrs_end_time')</th>
                            </tr>
                        </tfoot>
                    </table>
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
    <script src="{{ asset('js/select2.min.js')}}"></script>
    
<script>
            
    $(function() {     
        $('#exportjob').select2();   
        $('#exportemployee').select2();

        $('#exportemployee').on('change',function(){
            var opt = $('#exportemployee').val();
            if(opt == 0){
                $("#exportjob option").remove();
                $("#exportjob").append('<option value="0">---</option>');
                $('#divexportcsv').html('');
            }else{
                $.ajax({
                    url: "{{route('admin.exportnote.getlistjob')}}",
                    type: 'POST',
                    data: {
                        us_id : opt,                    
                        _token : '{{csrf_token()}}',
                    },
                    error: function(err) {

                    },
                    success: function(data) {      
                        //console.log(data); 
                        $("#exportjob option").remove();
                        $("#exportjob").append('<option value="0">---</option>');
                        for(var i = 0;i < data.length;i++){
                            $("#exportjob").append('<option value="' + data[i][0] + '">' + data[i][1] + '</option>');    
                        }
                    }
                });
            }
        });

        var table = $('#table').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.exportnote.getdatanote') !!}',
            //order: [[ 6, "desc" ],[0, "asc"]],
            columns: [
                { data: 'employeeno', name: 'employeeno' },
                { data: 'name', name: 'name' },
                { data: 'jobno', name: 'jobno' },
                { data: 'notes', name: 'notes' },
                { data: 'wkd_end_time', name: 'wkd_end_time'},
                { data: 'hrs_end_time', name:'hrs_end_time'},                
            ], 
        });

        $('#exportjob').on('change', function(){
            var link = "{!! route('admin.exportnote.getdatanote') !!}" + "?jid=" + $('#exportjob').val() + "&us_id=" + $('#exportemployee').val();
            var link1 = link + "&opt=0";
            var link2 = link + "&opt=1";
            table.ajax.url(link1).load();
            if($('#exportjob').val() == 0){
                $('#divexportcsv').html('');
            }else{
                $('#divexportcsv').html('<a href="' + link2 + '" class="btn btn-warning" style="margin-top: 10px;margin-right: 40px;">' + "@lang('export/title.exportcsv')" + '</a>');
            }
        });

    });

</script>   
@stop
