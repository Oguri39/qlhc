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
                <form enctype="multipart/form-data" class="form-horizontal" action="{{route('admin.export.export')}}" method="POST"> 
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="form-group">
                    <div class="row">
                        <table>
                            <tr>
                                <td>
                                    <label class="control-label" style="text-align: right;margin-top: 5px;">@lang('export/title.fromdate')</label> 
                                </td>
                                <td>
                                    <input type="text" id="fromdate" name="fromdate" class="form-control" value="{{date('m/d/Y',strtotime('-1 week'))}}" style="width: 120px;" />
                                </td>
                                <td>
                                    <label class="control-label" style="text-align: right;margin-top: 5px;">@lang('export/title.todate')</label> 
                                </td>                                
                                <td>
                                    <input type="text" id="todate" name="todate" class="form-control" value="{{date('m/d/Y')}}" style="width: 120px;"/>
                                </td>
                                <td>
                                    <label class="control-label" style="text-align: right;margin-top: 5px;">@lang('export/title.job')</label> 
                                </td>
                                <td>
                                    <input type="hidden" id="filterJob" name="filterJob" value="">
                                    <input type="text" id="selectjob" name="selectjob" class="form-control" />
                                </td>
                                <td>
                                    <input type="submit" class="btn btn-success" value="@lang('export/title.calculate')"/>
                                </td>
                                <td>
                                    <button id="btn_clear_filter" class="btn btn-danger">@lang('export/title.clearfilter')</button>
                                </td>
                            </tr>
                        </table>
                    </div>                  
                </div>
                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                    <table class="table table-bordered width100" id="table" border="1">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="form-control" id="checkall1"/></th>
                                <th style="text-align: center;">@lang('export/title.checkuncheck')</th>                                
                            </tr>
                        </thead>
                        <tbody>  
                            @foreach($employees as $key => $r)
                            <tr>
                                <td align="center"><input type='checkbox' class='form-control' id="ck_{{$r->us_id}}" name="listeq[]" value="{{$r->us_id}}"/>
                                </td>
                                <td align="center">{{$r->firstname}} {{$r->lastname}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><input type="checkbox" class="form-control" id="checkall2"/></th>
                                <th style="text-align: center;">@lang('export/title.checkuncheck')</th>                                
                            </tr>
                        </tfoot>
                    </table>
                </div>
                </form>
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

        $('#btn_calculate').click(function(){            
            
        }); 

        var options = {
            //url: "resources/countries.json",
            url: function(phrase) {
                //return "jobsearch.php?q=" + phrase;                
                return "{{route('admin.weekcalculate.searchjob')}}" + "?q=" + phrase;
            },
            getValue: "text",
            list: {
                // match: {
                //     enabled: true
                // },
                onSelectItemEvent: function() {
                    var index = $("#selectjob").getSelectedItemData().id;
                    $("#filterJob").val(index).trigger("change");
                }
            },
            theme: "square"
        };

        $("#selectjob").easyAutocomplete(options);

        $('#btn_clear_filter').click(function(){
            window.location.reload();
        });
        
        var table = $('#table').DataTable({
            order: [],
            columns: [
                { orderable: false, searchable: false },
                { },
            ],
            pageLength: 100,
        });

        $('#checkall1').click(function(){
            if(this.checked){
                $('input').prop('checked',true);
            }else{
                $('input').prop('checked',false);
            }
        });

        $('#checkall2').click(function(){
            if(this.checked){
                $('input').prop('checked',true);
            }else{
                $('input').prop('checked',false);
            }
        });        

    });

</script>   
@stop
