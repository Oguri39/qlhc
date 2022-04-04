@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('weekcalculate/title.label')
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
    <h1>@lang('weekcalculate/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('weekcalculate/title.label')
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
                    @lang('weekcalculate/title.label') @lang('weekcalculate/title.for_year') {{date('Y')}}
                </h4>                                
            </div>            
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">
                <div class="form-group">
                    <div class="row">
                        <label for="selectyear" class="col-sm-1 control-label" style="margin-top: 5px;">@lang('weekcalculate/title.year')</label>
                        <div class="col-sm-2">
                            <select id="selectyear" class="form-control">
                                @for($i = intval(date('Y'));$i > 2014;$i--)
                                <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>     
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered" id="table" width="100%">
                    <thead>
                     <tr>
                        <th></th>
                        <th >@lang('weekcalculate/title.week')</th>
                        <th >@lang('weekcalculate/title.worksheet_receive')</th>
                        <th >@lang('weekcalculate/title.worksheet_approve')</th>  
                        @if($SCOMPANYCALC == 1)
                        <th >@lang('weekcalculate/title.worksheet_calc')</th>  
                        @endif  
                        <th ></th>                            
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
    <script src="{{ asset('vendors/chartjs/js/Chart.js') }}"></script>
<script>
   
    $(function() {        
        var table = $('#table').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: true,
            ajax: "{!! route('admin.weekcalculate.getdata') !!}" + "?year=" + $('#selectyear').val(),
            order: [],
            columns: [
                { data: 'actions', name: 'actions', orderable: false, searchable: false  },
                { data: 'week', name: 'week' },
                { data: 'nrTot', name: 'nrTot' },
                { data: 'nrTotA', name: 'nrTotA' },
                @if($SCOMPANYCALC == 1)
                { data: 'nrTotC', name: 'nrTotC' },      
                @endif          
            ],                        
        });

        $('#selectyear').on('change',function(){            
            table.ajax.url("{!! route('admin.weekcalculate.getdata') !!}" + "?year=" + $('#selectyear').val()).load();
        });
    });
    
</script>   
@stop
