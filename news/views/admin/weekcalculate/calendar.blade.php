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
                    @lang('weekcalculate/title.label')
                </h4>                                
            </div>            
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-2">
                            <a href="{{route('admin.weekcalculate.details',[$datainput->weekstart,$datainput->weekend,$datainput->selectedyear])}}" class="btn btn-primary">@lang('weekcalculate/title.backtothelist')</a>
                        </div>
                        <div class="col-sm-2">
                            <button id="btn_create" class="btn btn-success">
                                @lang('weekcalculate/title.adddate')
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label for="inp_minutes" class="col-sm-1 control-label" style="margin-top: 5px;">@lang('weekcalculate/title.minutes')</label>
                        <div class="col-sm-5">
                            <input type="number" id="inp_minutes" class="form-control" value="60" min="0" />                                
                        </div>
                        <div class="col-sm-2">
                            <button id="btn_calculate" class="btn btn-info">@lang('weekcalculate/title.calculate')</button>
                        </div>
                    </div>
                </div>
                <div id="div_content"></div>
            </div>
        </div>
    </div>
    </div><!-- row-->
</section>
<div class="modal fade" id="modal_confirm" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteLabel">@lang('weekcalculate/title.recalculate')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                @lang('weekcalculate/message.warning.recalculate')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('button.cancel')</button>
                <button id="btn_save" class="btn btn-danger Remove_square">@lang('button.ok')</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<div class="modal fade" id="modal_create" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">            
            <div class="modal-header">
                <h4 class="modal-title" id="storelabel">@lang('weekcalculate/title.createdate')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <input type="hidden" id="c_wkd_id" value="0">
                        <table width="100%">
                            <tr>
                                <td width="2%"></td>
                                <td width="73%">
                                    <table width="100%">
                                        <tr>
                                            <td><label for="c_wkd_us_id" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.employee') &nbsp;</label></td>
                                            <td colspan="4">
                                                {!!Form::select('c_wkd_us_id', $listemployees, null, ['class' => 'form-control', 'id' => 'c_wkd_us_id'])!!}
                                            </td>
                                            <td style="width: 10px;"></td>
                                        </tr>
                                        <tr>
                                            <td><label for="c_wkd_day" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.workday') &nbsp;</label></td>
                                            <td colspan="4">
                                                <input type="text" id="c_wkd_day" class="form-control" style="width: 110px;"/>
                                            </td>
                                            <td style="width: 10px;"></td>
                                        </tr>

                                        <tr>
                                            <td><label for="c_wkd_driller_helper" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.drillerh') &nbsp;</label></td>
                                            <td colspan="4">
                                                {!!Form::select('c_wkd_driller_helper', $listdrillers, null, ['class' => 'form-control', 'id' => 'c_wkd_driller_helper', 'style' => 'width: 90px;'])!!}
                                            </td>
                                            <td style="width: 10px;"></td>
                                        </tr>
                                        <tr>
                                            <td><label for="c_wkd_truck_driver" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.truckdriver') &nbsp;</label></td>
                                            <td colspan="4">
                                                {!!Form::select('c_wkd_truck_driver', $listtrucks, null, ['class' => 'form-control', 'id' => 'c_wkd_truck_driver', 'style' => 'width: 90px;'])!!}
                                            </td>
                                            <td style="width: 10px;"></td>
                                        </tr>
                                        <tr>
                                            <td><label for="c_wkd_liveexp" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.liveexpenses') &nbsp;</label></td>
                                            <td colspan="4">
                                                {!!Form::select('c_wkd_liveexp', $listlunch, null, ['class' => 'form-control', 'id' => 'c_wkd_liveexp', 'style' => 'width: 90px;'])!!}
                                            </td>
                                            <td style="width: 10px;"></td>
                                        </tr>
                                        <tr>
                                            <td><label for="c_wkd_lunch" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.lunch') &nbsp;</label></td>
                                            <td>
                                                {!!Form::select('c_wkd_lunch', $listlunch, null, ['class' => 'form-control', 'id' => 'c_wkd_lunch', 'style' => 'width: 90px;'])!!}
                                            </td>
                                            <td>
                                                <label class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.lunchtime') &nbsp;</label>
                                            </td>
                                            <td>
                                                <input type="text" id="c_wkd_lunchtimed" class="form-control" style="width: 110px;">
                                            </td> 
                                            <td>
                                                <input type="text" id="c_wkd_lunchtimeh" class="form-control" style="width: 90px;">
                                            </td>                                            
                                            <td style="width: 10px;"></td>
                                        </tr>
                                        <tr>
                                            <td><label for="c_wkd_miles" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.miles') &nbsp;</label></td>
                                            <td colspan="4">
                                                <input type="text" id="c_wkd_miles" class="form-control"/>
                                            </td>
                                            <td style="width: 10px;"></td>
                                        </tr>
                                        <tr>
                                            <td><label for="c_wkd_shift_work" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.shiftwork') &nbsp;</label></td>
                                            <td colspan="4">
                                                <input type="text" id="c_wkd_shift_work" class="form-control"/>
                                            </td>
                                            <td style="width: 10px;"></td>
                                        </tr>
                                    </table>   
                                </td>
                                <td width="23%">
                                    <table width="100%">
                                        <tr>
                                            <td><label for="c_wkd_notes" class="control-label" style="margin-top: 5px;">@lang('weekcalculate/title.notes')</label></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <textarea id="c_wkd_notes" class="form-control" rows="12"></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="2%"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('button.cancel')</button>
                        </div>
                        <div class="col-sm-2">
                            <button id="btn_store" class="btn btn-success Remove_square">@lang('button.save')</button>
                        </div>
                        <div class="col-sm-2">
                            <button id="btn_delete" class="btn btn-danger Remove_square">@lang('button.delete')</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="div_edithour">                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<div class="modal fade" id="modal_hour" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">            
            <div class="modal-header">
                <h4 class="modal-title" id="storehourlabel">@lang('weekcalculate/title.addjob')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="width: 100%;">                
                <div id="div_listhours" style="width: 100%;"></div><br/>
                <div class="form-group" style="width: 100%;">
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <input type="hidden" id="c_hrs_wkd_id" value="0">
                        <input type="hidden" id="c_hrs_id" value="0">
                        <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('weekcalculate/title.starttime')</label>
                        <div class="col-sm-2">
                            <input type="text" id="c_hrs_starttimed" class="form-control" />
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="c_hrs_starttimeh" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="form-group" style="width: 100%;">
                    <div class="row"> 
                        <div class="col-sm-2"></div>                       
                        <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('weekcalculate/title.endtime')</label>
                        <div class="col-sm-2">
                            <input type="text" id="c_hrs_endtimed" class="form-control" />
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="c_hrs_endtimeh" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="form-group" style="width: 100%;">
                    <div class="row">           
                        <div class="col-sm-2"></div>             
                        <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('weekcalculate/title.job')</label>
                        <div class="col-sm-6">
                            <input type="hidden" id="c_hrs_jobid" class="form-control" value="0" />
                            <input type="text" id="c_hrs_job" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="form-group" style="width: 100%;">
                    <div class="row">    
                        <div class="col-sm-2"></div>                    
                        <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('weekcalculate/title.regular')</label>
                        <div class="col-sm-3">
                            <input type="number" id="c_hrs_regular" class="form-control" min="0" />
                        </div>
                    </div>
                </div>
                <div class="form-group" style="width: 100%;">
                    <div class="row">  
                        <div class="col-sm-2"></div>                      
                        <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('weekcalculate/title.ovth')</label>
                        <div class="col-sm-3">
                            <input type="number" id="c_hrs_ovt" class="form-control" min="0" />
                        </div>
                    </div>
                </div>
                <div class="form-group" style="width: 100%;">
                    <div class="row">     
                        <div class="col-sm-2"></div>                   
                        <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('weekcalculate/title.double')</label>
                        <div class="col-sm-3">
                            <input type="number" id="c_hrs_double" class="form-control" min="0" />
                        </div>
                    </div>
                </div>
                <div class="form-group" style="width: 100%;">
                    <div class="row"> 
                        <div class="col-sm-3"></div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('button.cancel')</button>
                        </div>
                        <div class="col-sm-2">
                            <button id="btn_hour_store" class="btn btn-success Remove_square">@lang('button.save')</button>
                        </div>
                        <div class="col-sm-2">
                            <button id="btn_hour_delete" class="btn btn-danger Remove_square">@lang('button.delete')</button>
                        </div>
                    </div>                
                </div>
            </div>            
        </div>
        <!-- /.modal-content -->
    </div>
</div>
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
    var wkd_id_recal = 0;
    function loadData(){
        var urlajax = "{{route('admin.weekcalculate.getcalendar')}}";
        $.ajax({
            url: urlajax,
            type: 'POST',
            data: {
                fromWeek : "{{$datainput->fromWeek}}",
                us_id : "{{$datainput->us_id}}",
                wkd_day_from : "{{$datainput->wkd_day_from}}",
                wkd_day_to : "{{$datainput->wkd_day_to}}",
                weekstart : "{{$datainput->weekstart}}",
                weekend : "{{$datainput->weekend}}",
                selectedyear : "{{$datainput->selectedyear}}",
                wkd_day : "{{$datainput->wkd_day}}",
                wkd_status : "",
                minutes : $('#inp_minutes').val(),
                _token : '{{csrf_token()}}',
            },
            error: function(err) {

            },
            success: function(data) {                        
                $('#div_content').empty();
                $('#div_content').html(data);
            }
        });
    }

    function lockdate(sel, wkd_id_lock){
        var urlajax = "{{route('admin.weekcalculate.lockdate')}}";
        $.ajax({
            url: urlajax,
            type: 'POST',
            data: {
                wkd_id_lock : wkd_id_lock,
                wkd_lock: sel.value,
                _token : '{{csrf_token()}}',
            },
            error: function(err) {

            },
            success: function(data) {                        
                if(data == 1){
                    loadData();
                }
            }
        });
    }

    function recalculate(wkd_id){
        wkd_id_recal = wkd_id;
        $('#modal_confirm').modal('toggle');        
    }

    function filldatajob(dat){
        //$('#div_listhours').empty();
        if(dat.edit == "0" || dat.edit == 0){            
            $('#c_hrs_id').val("0");
            $('#c_hrs_starttimed').val("");
            $('#c_hrs_endtimed').val("");
            $('#c_hrs_starttimeh').val("");
            $('#c_hrs_endtimeh').val("");            
            $('#c_hrs_job').val("");
            $('#c_hrs_jobid').val("");
            $('#c_hrs_regular').val("");
            $('#c_hrs_ovt').val("");
            $('#c_hrs_double').val("");
            $('#btn_hour_delete').hide();
            $('#storehourlabel').html("@lang('weekcalculate/title.addjob')");                        
        }else{
            $('#c_hrs_id').val(dat.hrs_id);
            $('#c_hrs_starttimed').val(dat.hrs_starttimed);
            $('#c_hrs_endtimed').val(dat.hrs_endtimed);            
            $('#c_hrs_starttimeh').val(dat.hrs_starttimeh);
            $('#c_hrs_endtimeh').val(dat.hrs_endtimeh);            
            $('#c_hrs_job').val(dat.jobdescription);
            $('#c_hrs_jobid').val(dat.hrs_jobid);
            $('#c_hrs_regular').val(dat.hrs_regular);
            $('#c_hrs_ovt').val(dat.hrs_ovt);
            $('#c_hrs_double').val(dat.hrs_double);
            $('#btn_hour_delete').show();
            $('#storehourlabel').html("@lang('weekcalculate/title.editjob')");            
        }
        $('#div_listhours').html(dat.listhours);
    }

    function filldatamodal(dat){
        if(dat == ''){            
            $('#c_wkd_id').val("0");
            $('#c_wkd_day').val("");
            $('#c_wkd_driller_helper').val(0);
            $('#c_wkd_truck_driver').val(0);
            $('#c_wkd_liveexp').val(0);
            $('#c_wkd_lunch').val(0);
            $('#c_wkd_lunchtimed').val("");
            $('#c_wkd_lunchtimeh').val("");
            $('#c_wkd_miles').val("");
            $('#c_wkd_shift_work').val("");
            $('#c_wkd_notes').val("");
            $('#btn_delete').hide();
            $('#storelabel').html("@lang('weekcalculate/title.createdate')");
            $('#div_edithour').html("");
        }else{
            $('#c_wkd_id').val(dat.wkd_id);
            $('#c_wkd_us_id').val(dat.wkd_us_id);
            $('#c_wkd_day').val(dat.wkd_day);
            $('#c_wkd_driller_helper').val(dat.wkd_driller_helper);
            $('#c_wkd_truck_driver').val(dat.wkd_truck_driver);
            $('#c_wkd_liveexp').val(dat.wkd_liveexp);
            $('#c_wkd_lunch').val(dat.wkd_lunch);
            $('#c_wkd_lunchtimed').val(dat.wkd_lunchtimed);
            $('#c_wkd_lunchtimeh').val(dat.wkd_lunchtimeh);
            $('#c_wkd_miles').val(dat.wkd_miles);
            $('#c_wkd_shift_work').val(dat.wkd_shift_work);
            $('#c_wkd_notes').val(dat.wkd_notes);
            $('#btn_delete').show();
            $('#storelabel').html("@lang('weekcalculate/title.editdate')");
            $('#div_edithour').html(dat.hours);            
        }
    }

    function openeditdate(wkd_id){
        var urlajax = "{{route('admin.weekcalculate.editdate')}}";
        $.ajax({
            url: urlajax,
            type: 'POST',
            data: {
                wkd_id : wkd_id,                           
                _token : '{{csrf_token()}}',
            },
            error: function(err) {

            },
            success: function(data) {                                        
                var dat = JSON.parse(data);
                filldatamodal(dat);
                $('#modal_create').modal('toggle');                    
            }
        });        
    }

    function openedithour(hrs_id,wkd_id){
        var urlajax = "{{route('admin.weekcalculate.edithour')}}";
        $('#c_hrs_wkd_id').val(wkd_id);
        $.ajax({
            url: urlajax,
            type: 'POST',
            data: {
                hrs_id : hrs_id,       
                wkd_id: wkd_id,                    
                _token : '{{csrf_token()}}',
            },
            error: function(err) {

            },
            success: function(data) {                                        
                var dat = JSON.parse(data);
                filldatajob(dat);                               
                $('#modal_hour').modal('toggle');                    
            }
        });
        
    }

    function dorecalculate(){
        var urlajax = "{{route('admin.weekcalculate.recalculate')}}";
        $.ajax({
            url: urlajax,
            type: 'POST',
            data: {
                wkd_id : wkd_id_recal,     
                isdel : 0,           
                _token : '{{csrf_token()}}',
            },
            error: function(err) {

            },
            success: function(data) {                        
                if(data == 1){
                    loadData();
                }
            }
        });
    }

    function save_val(va, wk) {
        var urlajax = "{{route('admin.weekcalculate.savestatus')}}";
        $.ajax({
            url: urlajax,
            type: 'POST',
            data: {
                wkd_id : wk,     
                status : va,           
                _token : '{{csrf_token()}}',
            },
            error: function(err) {

            },
            success: function(data) {                        

            }
        });        
    }

    $(function() { 
        $('#btn_calculate').click(function(){
            loadData();
        });       

        $('#btn_save').click(function(){
            dorecalculate();
            $('#modal_confirm').modal('hide');
        });

        $('#btn_delete').click(function(){
            var check = confirm("@lang('weekcalculate/message.warning.delete.date')");
            if(check){
                var urlajax = "{{route('admin.weekcalculate.deletedate')}}";
                $.ajax({
                    url: urlajax,
                    type: 'POST',
                    data: {
                        wkd_id : $('#c_wkd_id').val(),                    
                        _token : '{{csrf_token()}}',
                    },
                    error: function(err) {

                    },
                    success: function(data) {   
                        console.log(data);                     
                        if(data == 1){
                            loadData();
                        }
                    }
                });
                $('#modal_create').modal('hide');
            }
        });

        $('#btn_hour_delete').click(function(){
            var check = confirm("@lang('weekcalculate/message.warning.delete.job')");
            if(check){
                var urlajax = "{{route('admin.weekcalculate.deletehour')}}";
                $.ajax({
                    url: urlajax,
                    type: 'POST',
                    data: {
                        hrs_id : $('#c_hrs_id').val(),                    
                        _token : '{{csrf_token()}}',
                    },
                    error: function(err) {

                    },
                    success: function(data) {   
                        if(data == 1){
                            openeditdate($('#c_hrs_wkd_id').val());
                        }
                    }
                });
                $('#modal_create').modal('hide');                
                $('#modal_hour').modal('hide');
            }
        });

        $('#btn_store').click(function(){
            var urlajax = "{{route('admin.weekcalculate.storedate')}}";
            $.ajax({
                url: urlajax,
                type: 'POST',
                data: {
                    wkd_id : $('#c_wkd_id').val(),
                    wkd_us_id: $('#c_wkd_us_id').val(),
                    wkd_day: $('#c_wkd_day').val(),
                    wkd_driller_helper : $('#c_wkd_driller_helper').val(),
                    wkd_truck_driver: $('#c_wkd_truck_driver').val(),
                    wkd_liveexp: $('#c_wkd_liveexp').val(),
                    wkd_lunch: $('#c_wkd_lunch').val(),
                    wkd_lunchtimed: $('#c_wkd_lunchtimed').val(),
                    wkd_lunchtimeh: $('#c_wkd_lunchtimeh').val(),
                    wkd_miles: $('#c_wkd_miles').val(),
                    wkd_shift_work: $('#c_wkd_shift_work').val(),
                    wkd_notes: $('#c_wkd_notes').val(),
                    _token : '{{csrf_token()}}',
                },
                error: function(err) {

                },
                success: function(data) {   
                    console.log(data);                     
                    if(data == 1){
                        loadData();
                    }
                }
            });
            $('#modal_create').modal('hide');
        });

        $('#btn_hour_store').click(function(){
            if (($('#c_hrs_jobid').val().trim() == '') && ($('#c_hrs_job').val().length > 0)) {
                alert( "@lang('weekcalculate/message.error.searchjob')" );
            }else{
                var urlajax = "{{route('admin.weekcalculate.storehour')}}";
                $.ajax({
                    url: urlajax,
                    type: 'POST',
                    data: {
                        hrs_wkd_id : $('#c_hrs_wkd_id').val(),
                        hrs_id : $('#c_hrs_id').val(),
                        hrs_starttimed: $('#c_hrs_starttimed').val(),
                        hrs_endtimed: $('#c_hrs_endtimed').val(),
                        hrs_starttimeh: $('#c_hrs_starttimeh').val(),
                        hrs_endtimeh : $('#c_hrs_endtimeh').val(),            
                        hrs_job : $('#c_hrs_job').val(),
                        hrs_jobid : $('#c_hrs_jobid').val(),
                        hrs_regular : $('#c_hrs_regular').val(),
                        hrs_ovt : $('#c_hrs_ovt').val(),
                        hrs_double : $('#c_hrs_double').val(),
                        _token : '{{csrf_token()}}',
                    },
                    error: function(err) {

                    },
                    success: function(data) {                       
                        if(data == 1){
                           openeditdate($('#c_hrs_wkd_id').val());    
                        }
                    }
                });
                $('#modal_create').modal('hide');                
                $('#modal_hour').modal('hide');
            }
        });
        
        $('#btn_create').click(function(){
            filldatamodal("");
            $('#modal_create').modal('toggle');
        });

        $('#c_wkd_day').datetimepicker({
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

        $('#c_hrs_starttimed').datetimepicker({
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

        $('#c_hrs_starttimeh').datetimepicker({
            viewMode: 'days',            
            format: 'HH:mm:ss',
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
            },
        });

        $('#c_hrs_endtimed').datetimepicker({
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

        $('#c_hrs_endtimeh').datetimepicker({
            viewMode: 'days',            
            format: 'HH:mm:ss',
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
            },
        });
        
        $('#c_wkd_lunchtimed').datetimepicker({
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

        $('#c_wkd_lunchtimeh').datetimepicker({
            viewMode: 'days',            
            format: 'HH:mm:ss',
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
            },
        });

        var options = {
            //url: "resources/countries.json",
            url: function(phrase) {
                //return "jobsearch.php?q=" + phrase;                
                return "{{route('admin.weekcalculate.searchjob')}}" + "?q=" + phrase;
            },
            getValue: "text",
            list: {
                onSelectItemEvent: function() {
                    var index = $("#c_hrs_job").getSelectedItemData().id;
                    $("#c_hrs_jobid").val(index).trigger("change");
                }
            },
            theme: "square"
        };

        $("#c_hrs_job").easyAutocomplete(options);
        
        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });        
        loadData();
    });
    
</script>   
@stop
