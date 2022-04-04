@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('equipments/title.label')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link href="{{ asset('css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
    type="text/css" />
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('equipments/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('equipments/title.label')
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
                    @lang('equipments/title.title_detail')
                </h4>                     
            </div>
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">                
                <div class="form-group">
                    <div class="row">
                        <a href="{{route('admin.equipments')}}" class="btn btn-primary">@lang('equipments/title.backtoequipment')</a>
                    </div>
                </div>
                <br/>                
                <form enctype="multipart/form-data" class="form-horizontal" action="{{route('admin.equipments.store')}}" method="POST"> 
                    <input type="hidden" name="eq_id" value="{{$eq_id}}">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">                       
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.internalcode')</label>
                            <div class="col-sm-10">
                                <input type="text" name="eq_internalcode" value="{{$data->eq_internalcode}}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.type')</label>
                            <div class="col-sm-4">    
                                {!!Form::select('eq_et_id', $listtype, $data->eq_et_id, ['class' => 'form-control', 'id' => 'eq_et_id'])!!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.model')</label>
                            <div class="col-sm-10">
                                <input type="text" name="eq_model" value="{{$data->eq_model}}" class="form-control" required="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.name')</label>
                            <div class="col-sm-10">
                                <input type="text" name="eq_name" value="{{$data->eq_name}}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.description')</label>
                            <div class="col-sm-10">
                                <textarea name="eq_description" row="5" class="form-control">{{$data->eq_description}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.company')</label>
                            <div class="col-sm-10">
                                {!!Form::select('eq_company', $listcompanies, $data->eq_company, ['class' => 'form-control', 'id' => 'eq_company'])!!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.department')</label>
                            <div class="col-sm-10">
                                {!!Form::select('eq_department', $listdepartments, $data->eq_department, ['class' => 'form-control', 'id' => 'eq_department'])!!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.operator')</label>
                            <div class="col-sm-10">
                                {!!Form::select('eq_us_id', $listoperators, $data->eq_us_id, ['class' => 'form-control', 'id' => 'eq_us_id'])!!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.associatedto')</label>
                            <div class="col-sm-10">
                                {!!Form::select('eq_eq_id', $listassoc, $data->eq_eq_id, ['class' => 'form-control', 'id' => 'eq_eq_id'])!!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.vin')</label>
                            <div class="col-sm-10">
                                <input type="text" name="eq_vin" value="{{$data->eq_vin}}" class="form-control" required="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.lic')</label>
                            <div class="col-sm-10">
                                <input type="text" name="eq_lic" value="{{$data->eq_lic}}" class="form-control" required="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.year')</label>
                            <div class="col-sm-10">
                                <input type="text" name="eq_year" id="eq_year" value="{{$data->eq_year}}" placeholder="YYYY" maxlength="4" class="form-control" required="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.gvw')</label>
                            <div class="col-sm-10">
                                <input type="text" name="eq_gvw" value="{{$data->eq_gvw}}" class="form-control" required="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.regisrequire')</label>
                            <div class="col-sm-3">
                                {!!Form::select('eq_regreq', $listyesno, $data->eq_regreq, ['class' => 'form-control', 'id' => 'eq_regreq'])!!}
                            </div>
                            <div class="col-sm-1"></div>
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.type')</label>
                            <div class="col-sm-4">
                                <input type="text" name="eq_regtype" class="form-control" value="{{$data->eq_regtype}}" required="">
                            </div>                            
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.expiration')</label>
                            <div class="col-sm-3">
                                <input type="text" name="eq_regexp" id="eq_regexp" class="form-control" value="{{$data->eq_regexp}}">
                            </div>                            
                            <div class="col-sm-1"></div>
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.cost')</label>
                            <div class="col-sm-4">
                                <input type="text" name="eq_regcost" class="form-control" value="{{$data->eq_regcost}}" required="">
                            </div>                           
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.titlebank')</label>
                            <div class="col-sm-10">
                                {!!Form::select('eq_titlebank', $listyesno, $data->eq_titlebank, ['class' => 'form-control', 'id' => 'eq_titlebank'])!!}  
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.insurancerequire')</label>
                            <div class="col-sm-3">
                                {!!Form::select('eq_insurancereq', $listyesno, $data->eq_insurancereq, ['class' => 'form-control', 'id' => 'eq_insurancereq'])!!}           
                            </div>                            
                            <div class="col-sm-1"></div>
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.expiration')</label>
                            <div class="col-sm-4">
                                <input type="text" name="eq_insuranceexp" id="eq_insuranceexp" class="form-control" value="{{$data->eq_insuranceexp}}">
                            </div>                           
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.ezpassrequire')</label>
                            <div class="col-sm-3">
                                {!!Form::select('eq_ezpassreq', $listyesno, $data->eq_ezpassreq, ['class' => 'form-control', 'id' => 'eq_ezpassreq'])!!}            
                            </div>                            
                            <div class="col-sm-1"></div>
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.number')</label>
                            <div class="col-sm-4">
                                <input type="text" name="eq_ezpass" class="form-control" value="{{$data->eq_ezpass}}" required="">
                            </div>                           
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.dotrequire')</label>
                            <div class="col-sm-3">
                                {!!Form::select('eq_detreq', $listyesno, $data->eq_detreq, ['class' => 'form-control', 'id' => 'eq_detreq'])!!}                          
                            </div>                            
                            <div class="col-sm-1"></div>
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.expiration')</label>
                            <div class="col-sm-4">
                                <input type="text" name="eq_detexp" id="eq_detexp" class="form-control" value="{{$data->eq_detexp}}">
                            </div>                           
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.dotsticker')</label>
                            <div class="col-sm-10">
                                <input type="text" name="eq_detsticker" value="{{$data->eq_detsticker}}" class="form-control" required="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.nyrequire')</label>
                            <div class="col-sm-3">
                                {!!Form::select('eq_nyinspreq', $listyesno, $data->eq_nyinspreq, ['class' => 'form-control', 'id' => 'eq_nyinspreq'])!!}  
                            </div>                            
                            <div class="col-sm-1"></div>
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.expiration')</label>
                            <div class="col-sm-4">
                                <input type="text" name="eq_nyinsexp" id="eq_nyinsexp" class="form-control" value="{{$data->eq_nyinsexp}}">
                            </div>                           
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.nyhut')</label>
                            <div class="col-sm-10">
                                <input type="text" name="eq_nyhut" value="{{$data->eq_nyhut}}" class="form-control" required="">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.iftarequire')</label>
                            <div class="col-sm-3">
                                {!!Form::select('eq_ifta', $listyesno, $data->eq_ifta, ['class' => 'form-control', 'id' => 'eq_ifta'])!!}     
                            </div>                            
                            <div class="col-sm-1"></div>
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.expiration')</label>
                            <div class="col-sm-4">
                                <input type="text" name="eq_iftaexp" id="eq_iftaexp" class="form-control" value="{{$data->eq_iftaexp}}">
                            </div>                           
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.iftanumber')</label>
                            <div class="col-sm-10">
                                <input type="text" name="eq_iftanr" value="{{$data->eq_iftanr}}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.checkinmiles')</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="eq_check_in_miles" value="{{$data->eq_check_in_miles}}" required="">                           
                            </div>                            
                            <div class="col-sm-1"></div>
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.checkinhours')</label>
                            <div class="col-sm-4">
                                <input type="text" name="eq_check_in_hours" class="form-control" value="{{$data->eq_check_in_hours}}" required="">
                            </div>                           
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="wo_startdate" class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.startdate')</label>
                            <div class="col-sm-3">
                                <input type="text" name="eq_date_start" id="eq_date_start" value="{{$data->eq_date_start}}" class="form-control">
                            </div>
                            <div class="col-sm-1"></div>
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.enddate')</label>
                            <div class="col-sm-4">
                                <input type="text" name="eq_date_end" id="eq_date_end" value="{{$data->eq_date_end}}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.notes')</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="5" name="eq_notes">{{$data->eq_notes}}</textarea>                            
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.drive')</label>
                            <div class="col-sm-2">
                                {!!Form::select('eq_candrive', $listyesno, $data->eq_candrive, ['class' => 'form-control', 'id' => 'eq_candrive'])!!}
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('equipments/title.status')</label>
                            <div class="col-sm-2">
                                {!!Form::select('eq_status', $liststatus, $data->eq_status, ['class' => 'form-control', 'id' => 'eq_status'])!!}
                            </div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2">
                                <input type="submit" value="@lang('button.save')" class="btn btn-success">
                            </div>                                
                            <div class="col-sm-2">
                                @if($eq_id > 0)
                                <a class="btn btn-danger" data-toggle="modal" data-target="#delete_confirm"><font color="white">@lang('button.delete')</font></a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>                
            </div>
        </div>
    </div>
    </div><!-- row-->
</section>
<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >@lang('equipments/title.deleteconfirm')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                @lang('equipments/message.confirm.delete')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('button.cancel')</button> &nbsp;<a href="{{route('admin.equipments.delete',$eq_id)}}" class="btn btn-danger Remove_square">@lang('button.delete')</a>                
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
<script>        
    $(function() { 
        
        $('#eq_date_start').datetimepicker({
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

        $('#eq_date_end').datetimepicker({
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

        $('#eq_year').datetimepicker({
            viewMode: 'days',            
            format: 'YYYY',
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
            },
        });   

        $('#eq_regexp').datetimepicker({
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

        $('#eq_insuranceexp').datetimepicker({
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

        $('#eq_detexp').datetimepicker({
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

        $('#eq_nyinsexp').datetimepicker({
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

        $('#eq_iftaexp').datetimepicker({
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

    });
    
</script>   
@stop
