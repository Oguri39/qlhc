@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('equipmenttype/title.label')
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
    <h1>@lang('equipmenttype/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('equipmenttype/title.label')
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
                    @lang('equipmenttype/title.label')
                </h4>                
            </div>
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">
                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                    <div class="form-group">
                        <div class="row">
                            <label for="et_title" class="col-sm-1 control-label" style="margin-top: 5px;">@lang('equipmenttype/title.title')</label>
                            <div class="col-sm-3">                                
                                <input type="text" id="et_title" class="form-control" />
                            </div>
                            <div class="col-sm-3">
                                {!!Form::select('et_checklist', $listchecklist, null, ['class' => 'form-control', 'id' => 'et_checklist'])!!}
                            </div>
                            <div class="col-sm-1">
                                <button id="btn_save" class="btn btn-success">@lang('button.save')</button>
                            </div>
                            <div class="col-sm-1">
                                <a class="btn btn-danger" id="a_del" data-toggle="modal" data-target="#delete_confirm" onclick="showtitledelete();" >@lang('button.delete')</a>
                            </div>
                            <div class="col-sm-2">
                                <button id="btn_new" class="btn btn-primary">@lang('equipmenttype/title.createnew')</button>
                            </div>
                        </div>
                    </div>                    
                </div>
                <br/>
                <table class="table table-striped table-bordered" id="table" width="100%">
                    <thead>
                        <tr>
                            <th >@lang('equipmenttype/title.id')</th>
                            <th >@lang('equipmenttype/title.title')</th>
                            <th >@lang('equipmenttype/title.checklist')</th>                            
                        </tr>
                    </thead>
                    <tbody>  
                    </tbody>
                    <tfoot>
                        <tr>
                            <th >@lang('equipmenttype/title.id')</th>
                            <th >@lang('equipmenttype/title.title')</th>
                            <th >@lang('equipmenttype/title.checklist')</th>                            
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    </div><!-- row-->
</section>
<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteLabel">@lang('equipmenttype/title.deleteequiptmenttype')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="deletecontent">
                @lang('equipmenttype/message.confirm.delete')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('button.cancel')</button>
                <button id="btn_delete" class="btn btn-danger Remove_square">@lang('button.delete')</button>
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
    function refreshnew(){
        $('#et_title').val("");
        $('#et_checklist').val(0);
        et_id = 0;               
        $('#a_del').hide();         
        $('#btn_new').hide();
    }

    function showtitledelete(){
        $('#deletecontent').html("@lang('equipmenttype/message.confirm.delete')");
        $('#deletellabel').html("@lang('equipmenttype/title.deleteequiptmenttype')");
        $('#btn_delete').show();
    }

    $(function() {
        var et_id = 0;
        var table = $('#table').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.equipmenttype.getdata') !!}',
            columns: [
                { data: 'et_id', name: 'et_id' },
                { data: 'et_title', name: 'et_title' },
                { data: 'et_checklisttext', name: 'et_checklisttext' },                
            ]
        });   

        $('#table tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();                        
            $('#et_title').val(data.et_title.replace(/&quot;/g,'"'));
            $('#et_checklist').val(data.et_checklist);
            et_id = data.et_id;               
            $('#a_del').show();         
            $('#btn_new').show();         
        });

        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

        $('#btn_new').click(function(){
            refreshnew();        
        });
                
        $('#btn_save').click(function(){
            var title = $('#et_title').val().trim();
            if(title != ''){
                var urlajax = "{{route('admin.equipmenttype.store')}}";            
                $.ajax({
                    url: urlajax,
                    type: 'POST',
                    data: {
                        et_id: et_id,
                        et_title : $('#et_title').val(),
                        et_checklist: $('#et_checklist').val(),                        
                        _token : '{{csrf_token()}}',
                    },
                    error: function(err) {

                    },
                    success: function(data) {                        
                        if(data > 0){
                            alert("@lang('equipmenttype/message.success.save')");  
                            refreshnew();
                            table.ajax.reload();  
                        }else{
                            alert("@lang('equipmenttype/message.error.save')");
                        }
                    }
                });
            }else{
                $('#deletecontent').html("@lang('equipmenttype/message.empty.save')");
                $('#deletellabel').html("@lang('equipmenttype/title.saveet')");
                $('#btn_delete').hide();
                $('#delete_confirm').modal('toggle');
            }
        });

        $('#btn_delete').click(function(){
            $.ajax({
                url: "{{route('admin.equipmenttype.delete')}}",
                type: 'POST',
                data: {
                    et_id: et_id,
                    _token : '{{csrf_token()}}',
                },
                error: function(err) {

                },
                success: function(data) {                        
                    if(data > 0){
                        alert("@lang('equipmenttype/message.success.delete')");  
                        refreshnew();                      
                        table.ajax.reload();
                    }else{
                        alert("@lang('equipmenttype/message.error.delete')");
                    }
                }
            });
            $('#delete_confirm').modal('hide');
        });
        $('#a_del').hide();
        $('#btn_new').hide();
    });
    
</script>   
@stop
