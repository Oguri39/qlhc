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
                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                    {!!$data->equip!!}
                </div>
                <br/>                
                <form enctype="multipart/form-data" class="form-horizontal" action="{{route('admin.workorders.store')}}" method="POST"> 
                    <input type="hidden" name="eq_id" value="{{$eq_id}}">
                    <input type="hidden" name="ec_id" value="{{$ec_id}}">
                    <input type="hidden" name="backmode" value="{{$backmode}}">
                    <input type="hidden" name="wo_id" value="{{$wo_id}}">
                    <input type="hidden" name="wo_ec_field" value="{{$wo_ec_field}}">
                    <input type="hidden" name="wo_ec_extra" value="{{$wo_ec_extra}}">                
                    <input type="hidden" name="_token" value="{{csrf_token()}}">                       
                    <div class="form-group">
                        <div class="row">
                            <label for="wo_item" class="col-sm-2 control-label" style="margin-top: 5px;">@lang('workorders/title.item')</label>
                            <div class="col-sm-10">
                                <input type="text" name="wo_item" value="{{$data->wo_item}}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label for="wo_startdate" class="col-sm-2 control-label" style="margin-top: 5px;">@lang('workorders/title.startdate')</label>
                            <div class="col-sm-2">
                                <input type="text" name="wo_startdate" id="wo_startdate" value="{{$data->wo_startdate}}" class="form-control">
                            </div>
                            <div class="col-sm-1"></div>
                            <label class="col-sm-1 control-label" style="margin-top: 5px;">@lang('workorders/title.enddate')</label>
                            <div class="col-sm-2">
                                <input type="text" name="wo_enddate" id="wo_enddate" value="{{$data->wo_enddate}}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('workorders/title.description')</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="5" name="wo_description">{{$data->wo_description}}</textarea>                            
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('workorders/title.assignedto')</label>
                            <div class="col-sm-4">
                                {!!Form::select('wo_us_id', $listassigned, $data->wo_us_id, ['class' => 'form-control', 'id' => 'wo_us_id'])!!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('workorders/title.hours')</label>
                            <div class="col-sm-10">
                                <input type="number" step="0.01" name="wo_hours" value="{{$data->wo_hours}}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('workorders/title.priority')</label>
                            <div class="col-sm-2">
                                {!!Form::select('wo_priority', $listpriority, $data->wo_priority, ['class' => 'form-control', 'id' => 'wo_priority'])!!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 control-label" style="margin-top: 5px;">@lang('workorders/title.status')</label>
                            <div class="col-sm-2">
                                {!!Form::select('wo_status', $liststatus, $data->wo_status, ['class' => 'form-control', 'id' => 'wo_status'])!!}
                            </div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2">
                                <input type="submit" value="@lang('button.save')" class="btn btn-success">
                            </div>                                
                            <div class="col-sm-2">
                                @if($wo_id > 0)
                                <a id="a_del" class="btn btn-danger" data-toggle="modal" data-target="#delete_confirm">@lang('button.delete')</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
                @if($wo_id > 0)                
                <br/>
                <div class="form-group">
                    <div class="row">
                        <h4 class="card-title my-2 float-left">
                            @lang('workorders/title.partsused')<br/>
                        </h4>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-2">
                            <label class="control-label" style="margin-top: 5px;">@lang('workorders/title.date')</label> <input type="text" name="wp_date" id="wp_date" value="{{date('m/d/Y')}}" class="form-control">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" style="margin-top: 5px;">@lang('workorders/title.partnr')</label> <input type="text" name="wp_partnr" id="wp_partnr" class="form-control">
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" style="margin-top: 5px;">@lang('workorders/title.quantity')</label> <input type="text" name="wp_quantity" id="wp_quantity" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label" style="margin-top: 5px;">@lang('workorders/title.description')</label> <textarea name="wp_description" id="wp_description" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="col-sm-1">
                            <button id="btn_save" class="btn btn-success">@lang('button.save')</button>
                        </div>                                
                        <div class="col-sm-1">
                            <button class="btn btn-primary" onclick="resetpart();"> @lang('button.reset')</a>
                        </div>
                    </div>
                </div>
                <div class="form-group">                    
                    <table class="table table-striped table-bordered" id="table1" width="100%">
                        <thead>
                            <tr>                            
                                <th>@lang('workorders/title.id')</th>
                                <th>@lang('workorders/title.date')</th>
                                <th>@lang('workorders/title.partnr')</th>
                                <th>@lang('workorders/title.quantity')</th>
                                <th>@lang('workorders/title.description')</th>
                                <th></th>
                            </tr>
                        </thead>                    
                        <tbody>                        
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>@lang('workorders/title.id')</th>
                                <th>@lang('workorders/title.date')</th>
                                <th>@lang('workorders/title.partnr')</th>
                                <th>@lang('workorders/title.quantity')</th>
                                <th>@lang('workorders/title.description')</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>                    
                </div> 
                @endif                               
            </div>
        </div>
    </div>
    </div><!-- row-->
</section>
<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >@lang('workorders/title.deleteconfirm')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                @lang('workorders/message.confirm.delete')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('button.cancel')</button> &nbsp;<a href="{{route('admin.workorders.delete',[$backmode,$eq_id,$ec_id,$wo_id])}}" class="btn btn-danger Remove_square">@lang('button.delete')</a>                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<div class="modal fade" id="delete_confirm_wp" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >@lang('workorders/title.delete.confirm')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                @lang('workorders/message.confirm.delete')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('button.cancel')</button> &nbsp;<button class="btn btn-danger Remove_square" id="btn_delete">@lang('button.delete')</button>                
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
    var table;
    var curid = 0;
    function editpart(wp_id){
        var data = table.rows().data();
        for(var i = 0;i < data.length;i++){
            if(data[i].wp_id == wp_id){
                $('#wp_date').val(data[i].wp_datetext);
                $('#wp_partnr').val(data[i].wp_partnr);
                $('#wp_quantity').val(data[i].wp_quantity);
                $('#wp_description').val(data[i].wp_description);  
                curid = wp_id;                
                break;
            }
        }
        console.log(curid);
    }

    function deletepart(wp_id){
        $('#delete_confirm_wp').modal('toggle');
        curid = wp_id;        
    }

    function resetpart(){
        $('#wp_date').val('');
        $('#wp_partnr').val('');
        $('#wp_quantity').val('');
        $('#wp_description').val('');  
        curid = 0;
    }
    
    $(function() { 
        @if($wo_id > 0)

        table = $('#table1').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.workorders.getdatapart',$wo_id) !!}',
            order: [],
            columns: [
                { data: 'wp_id', name: 'wp_id', width: '5%' },
                { data: 'wp_datetext', name: 'wp_datetext', width: '10%' },
                { data: 'wp_partnr', name: 'wp_partnr', width: '25%' }, 
                { data: 'wp_quantity', name: 'wp_quantity', width: '10%' },
                { data: 'wp_description', name: 'wp_description', width: '30%' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, width: '20%' },                
            ],
            // createdRow: function( row, data, dataIndex ) {
            //     $('td', row).css('background-color', data['bgcolor']);
            // }
        });

        $('#btn_delete').click(function(){
            $.ajax({
                url: "{{route('admin.workorders.deletepart')}}",
                type: 'POST',
                data: {
                    wo_id : '{{ $wo_id }}',
                    wp_id : curid,
                    _token : '{{csrf_token()}}',
                },
                error: function(err) {

                },
                success: function(data) {                        
                    if(data == 1){
                        alert("@lang('workorders/message.success.delete')");
                        resetpart();
                        table.ajax.reload();                            
                    }else{
                        alert("@lang('workorders/message.error.delete')");
                    }
                }
            });   
            $('#delete_confirm_wp').modal('hide');
        });

        $('#btn_save').click(function(){
            var wp_date = $('#wp_date').val().trim();
            var wp_partnr = $('#wp_partnr').val().trim();
            var wp_quantity = $('#wp_quantity').val().trim();
            
            if(wp_date == '' || wp_partnr == '' || wp_quantity == '' ){
                alert("@lang('workorders/message.empty.save')");
            }else{            
                $.ajax({
                    url: "{{route('admin.workorders.storepart')}}",
                    type: 'POST',
                    data: {
                        wo_id : '{{$wo_id}}',
                        wp_id : curid,
                        wp_date : wp_date,
                        wp_partnr : wp_partnr,
                        wp_quantity : wp_quantity,
                        wp_description: $('#wp_description').val().trim(),
                        _token : '{{csrf_token()}}',
                    },
                    error: function(err) {

                    },
                    success: function(data) {                        
                        if(data == 1){
                            alert("@lang('workorders/message.success.save')");
                            resetpart();
                            table.ajax.reload();                            
                        }else{
                            alert("@lang('workorders/message.error.save')");
                        }
                    }
                });        
            }       
        });

        $('#wp_date').datetimepicker({
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
        @endif

        $('#wo_startdate').datetimepicker({
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

        $('#wo_enddate').datetimepicker({
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
