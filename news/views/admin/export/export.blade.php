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
<link rel="stylesheet" href="{{asset('css/easy-autocomplete.css')}}" type="text/css"/>
<link rel="stylesheet" href="{{asset('css/easy-autocomplete.themes.min.css')}}" type="text/css"/>
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
                    @lang('export/title.label')
                </h4>                
            </div>
            <div class="card-body">                
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-1">
                            <a href="{{route('admin.export')}}" class="btn btn-primary">@lang('button.cancel')</a>
                        </div>
                        <div class="col-sm-4">
                            <form enctype="multipart/form-data" class="form-horizontal" action="{{route('admin.export.exportcsv')}}" method="POST"> 
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="fromdate" value="{{$fromdate}}">
                                <input type="hidden" name="todate" value="{{$todate}}">
                                <input type="hidden" name="hrs_jobid" value="{{$hrs_jobid}}">
                                <input type="hidden" name="listeq" value="{{$listeqtext}}">
                                <input type="submit" class="btn btn-success" value="@lang('export/title.exportcsv')" />
                            </form>
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
                                <th>@lang('export/title.costcode')</th>
                                <th>@lang('export/title.date')</th>
                                <th>@lang('export/title.hours')</th>
                                <th>@lang('export/title.amount')</th>
                                <th>@lang('export/title.payrate')</th>
                                <th>@lang('export/title.earncode')</th>
                                <th>@lang('export/title.shiftno')</th>
                                <th>@lang('export/title.deptno')</th>
                                <th>@lang('export/title.tradeno')</th>
                                <th>@lang('export/title.union')</th>
                                <th>@lang('export/title.taxtable')</th>
                                <th>@lang('export/title.unused')</th>   
                            </tr>
                        </thead>
                        <tbody>  
                            @foreach($data as $key => $r)
                            <tr>
                                <td>{{$r['employeeNr']}}</td>
                                <td>{{$r['employeeName']}}</td>
                                <td>{{$r['jobNr']}}</td>
                                <td>{{$r['costCode']}}</td>
                                <td>{{$r['date']}}</td>
                                <td>{{$r['hours']}}</td>
                                <td>{{$r['amount']}}</td>
                                <td>{{$r['payrate']}}</td>
                                <td>{{$r['earnCode']}}</td>
                                <td>{{$r['shiftNr']}}</td>
                                <td>{{$r['deptNo']}}</td>
                                <td>{{$r['tradeNr']}}</td>
                                <td>{{$r['union']}}</td>
                                <td>{{$r['taxtable']}}</td>
                                <td>{{$r['unused']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>@lang('export/title.employeeno')</th>
                                <th>@lang('export/title.name')</th>
                                <th>@lang('export/title.jobno')</th>
                                <th>@lang('export/title.costcode')</th>
                                <th>@lang('export/title.date')</th>
                                <th>@lang('export/title.hours')</th>
                                <th>@lang('export/title.amount')</th>
                                <th>@lang('export/title.payrate')</th>
                                <th>@lang('export/title.earncode')</th>
                                <th>@lang('export/title.shiftno')</th>
                                <th>@lang('export/title.deptno')</th>
                                <th>@lang('export/title.tradeno')</th>
                                <th>@lang('export/title.union')</th>
                                <th>@lang('export/title.taxtable')</th>
                                <th>@lang('export/title.unused')</th>   
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
    <script src="{{ asset('js/jquery.easy-autocomplete.min.js')}}"></script>
    <script src="{{ asset('js/jquery.easy-autocomplete.js')}}"></script>
<script>
            
    $(function() {     
               
        var table = $('#table').DataTable({
            order: [],            
            pageLength: 100,
        });
    });

</script>   
@stop
