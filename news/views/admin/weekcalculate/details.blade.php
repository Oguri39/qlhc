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
<link rel="stylesheet" href="{{asset('css/easy-autocomplete.css')}}" type="text/css"/>
<link rel="stylesheet" href="{{asset('css/easy-autocomplete.themes.min.css')}}" type="text/css"/>
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
                        <div class="col-sm-2">
                            <a href="{{route('admin.weekcalculate')}}" class="btn btn-primary">@lang('weekcalculate/title.backtothelist')</a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="filterAdmin" class="col-sm-1 control-label" style="margin-top: 5px;">@lang('weekcalculate/title.admin')</label>
                        <div class="col-sm-2">
                            <select id="filterAdmin" class="form-control">
                                <option value="@">@lang('weekcalculate/title.all')</option> 
                                @foreach($listpadmin as $key => $value)
                                <option value="{{$value->padmin}}">{{$value->padmin}}</option>
                                @endforeach
                            </select>     
                        </div>
                        <label for="selectjob" class="col-sm-1 control-label" style="margin-top: 5px;">@lang('weekcalculate/title.job')</label>
                        <div class="col-sm-5">
                            <input type="hidden" id="filterJob" value="">
                            <input type="text" id="selectjob" class="form-control"/>                                
                        </div>
                        <div class="col-sm-1">
                            <button id="btn_filter" class="btn btn-warning">@lang('weekcalculate/title.filter')</button>
                        </div>
                        <div class="col-sm-2">
                            <button id="btn_clear_filter" class="btn btn-danger">@lang('weekcalculate/title.clearfilter')</button>
                        </div>

                    </div>
                </div>
                <table class="table table-striped table-bordered" id="table" width="100%">
                    <thead>
                     <tr>
                        <th >@lang('weekcalculate/title.employee')</th>
                        <th >@lang('weekcalculate/title.monday')</th>
                        <th >@lang('weekcalculate/title.tuesday')</th>
                        <th >@lang('weekcalculate/title.wednesday')</th>
                        <th >@lang('weekcalculate/title.thursday')</th>
                        <th >@lang('weekcalculate/title.friday')</th>
                        <th >@lang('weekcalculate/title.saturday')</th>
                        <th >@lang('weekcalculate/title.sunday')</th>
                        <th style="text-align: center;">@lang('weekcalculate/title.tot')</th>   
                    @if ($SCOMPANYCALC==1)
                        <th >@lang('weekcalculate/title.torecalculate')</th>   
                        <th></th>
                    @endif                         
                     </tr>
                    </thead>
                    <tbody>  
                    </tbody>
                    <tfoot>
                     <tr>
                        <th >@lang('weekcalculate/title.employee')</th>
                        <th >@lang('weekcalculate/title.monday')</th>
                        <th >@lang('weekcalculate/title.tuesday')</th>
                        <th >@lang('weekcalculate/title.wednesday')</th>
                        <th >@lang('weekcalculate/title.thursday')</th>
                        <th >@lang('weekcalculate/title.friday')</th>
                        <th >@lang('weekcalculate/title.saturday')</th>
                        <th >@lang('weekcalculate/title.sunday')</th>
                        <th style="text-align: center;">@lang('weekcalculate/title.tot')</th>   
                    @if ($SCOMPANYCALC==1)
                        <th >@lang('weekcalculate/title.torecalculate')</th>   
                        <th></th>
                    @endif                         
                     </tr>
                    </tfoot>
                </table>     
                <br/>
                <table class="table table-striped table-bordered" width="100%">
                    <tbody>
                    <tr>
                        <td width="7%">
                            <font color="#000000">&check;</font> = @lang('weekcalculate/title.ok')
                        </td>
                        <td width="15%">
                            <font color="#FF0000">&check;</font> = @lang('weekcalculate/title.toberecalculated')
                        </td>
                        <td width="15%">
                            <font color="#FFD700">&check;</font> = @lang('weekcalculate/title.notyetapproved')</font>
                        </td>
                        <td width="27%">
                            <font color="#FF0000">&check; !!</font> = @lang('weekcalculate/title.alert')
                        </td>
                        <td width="13%">
                            <font color="#000000">S</font> = @lang('weekcalculate/title.shift')
                        </td>
                        <td width="13%"><font color="#000000">T</font> = @lang('weekcalculate/title.truckdriver')
                        </td>
                        <td style="background-color:#6897bb" width="22%"> = @lang('weekcalculate/title.filterfound')
                        </td>
                    </tr>
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
    
    <script src="{{ asset('js/jquery.easy-autocomplete.min.js')}}"></script>
    <script src="{{ asset('js/jquery.easy-autocomplete.js')}}"></script>

<script>
    var table;
    function recalculatedetail(weekstart,weekend,us_id){
        if(weekstart != "" && weekend != "" && us_id != ""){
            var urlajax = "{{route('admin.weekcalculate.recalculatedetail')}}";
            $.ajax({
                url: urlajax,
                type: 'POST',
                data: {
                    us_id : us_id,   
                    weekstart: weekstart,
                    weekend : weekend,                       
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
    }

    $(function() {                   
        table = $('#table').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: false,
            ajax: "{!! route('admin.weekcalculate.getdatadetails',[$wstart,$wend,$year]) !!}" + "?filterAdmin=" + $('#filterAdmin').val() + "&filterJob=",
            order: [],
            columns: [
                //{ data: 'actions', name: 'actions', orderable: false, searchable: false  },
                { data: 'out_em', name: 'out_em' },
                { data: 'out_mon', name: 'out_mon', orderable: false, searchable: false },
                { data: 'out_tue', name: 'out_tue', orderable: false, searchable: false },    
                { data: 'out_wed', name: 'out_wed', orderable: false, searchable: false },
                { data: 'out_thu', name: 'out_thu', orderable: false, searchable: false },
                { data: 'out_fri', name: 'out_fri', orderable: false, searchable: false },  
                { data: 'out_sat', name: 'out_sat', orderable: false, searchable: false },
                { data: 'out_sun', name: 'out_sun', orderable: false, searchable: false },
                { data: 'out_tot', name: 'out_tot', orderable: false, searchable: false },
            @if ($SCOMPANYCALC==1)
                { data: 'out_torecalculate', name: 'out_torecalculate' },
                { data: 'out_torecalculate2', name: 'out_torecalculate2' },
            @endif                  
            ],                        
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

        $('#btn_filter').click(function(){                 
            if (($('#filterJob').val().trim() == '') && ($('#selectjob').val().length > 0)) {
                alert( "@lang('weekcalculate/message.error.searchjob')" );

            }else{   
                var url = "{!! route('admin.weekcalculate.getdatadetails',[$wstart,$wend,$year]) !!}" + "?filterAdmin=" + $('#filterAdmin').val() + "&filterJob=" + $('#filterJob').val().trim();    
                table.ajax.url(url).load();                
            }
        });

        $('#btn_clear_filter').click(function(){
            window.location.reload();
        });
    });
    
</script>   
@stop
