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
<link rel="stylesheet" href="{{ asset('css/pages/jscharts.css') }}" />
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
                    @lang('equipments/title.title')
                </h4>                
            </div>
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">
                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <a href="{{route('admin.equipments.edit',0)}}" class="btn btn-success">@lang('equipments/title.addnewequipment')</a>
                            </div>                            
                        </div>
                    </div>                                    
                    <br/>
                    <table class="table table-striped table-bordered" id="table" width="100%">
                        <thead>
                         <tr>
                            <th >@lang('equipments/title.qr')<input type="checkbox" class="form-control" id="checkall1"/></th>
                            <th >@lang('equipments/title.id')</th>
                            <th >@lang('equipments/title.type')</th>  
                            <th >@lang('equipments/title.internalcode')</th>
                            <th >@lang('equipments/title.name')</th>
                            <th >@lang('equipments/title.drive')</th>  
                            <th >@lang('equipments/title.status')</th>
                            <th >@lang('equipments/title.lastcheck')</th>
                            <th >@lang('equipments/title.lastyearlym')</th>  
                            <th >@lang('equipments/title.checkinmiles')</th>
                            <th >@lang('equipments/title.checkinhours')</th>
                            <th >@lang('equipments/title.inuse')</th>
                            <th >@lang('equipments/title.hasrepairrequest')</th>  
                         </tr>
                        </thead>
                        <tbody>  
                        </tbody>
                        <tfoot>
                            <th >@lang('equipments/title.qr')<input type="checkbox" class="form-control" id="checkall2"/></th>
                            <th >@lang('equipments/title.id')</th>
                            <th >@lang('equipments/title.type')</th>  
                            <th >@lang('equipments/title.internalcode')</th>
                            <th >@lang('equipments/title.name')</th>
                            <th >@lang('equipments/title.drive')</th>  
                            <th >@lang('equipments/title.status')</th>
                            <th >@lang('equipments/title.lastcheck')</th>
                            <th >@lang('equipments/title.lastyearlym')</th>  
                            <th >@lang('equipments/title.checkinmiles')</th>
                            <th >@lang('equipments/title.checkinhours')</th>
                            <th >@lang('equipments/title.inuse')</th>
                            <th >@lang('equipments/title.hasrepairrequest')</th>
                        </tfoot>
                    </table>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-5">                                
                                <button id="btn_qr" class="btn btn-warning"><font color="white">@lang('equipments/title.selectqr')</font></button>
                            </div>                            
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    </div><!-- row-->
</section>
<div class="modal fade" id="drive_confirm" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteLabel">@lang('equipments/title.changedrive')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                @lang('equipments/message.confirm.drive')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('button.cancel')</button>
                <button id="btn_drive" class="btn btn-danger Remove_square">@lang('button.ok')</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<div class="modal fade" id="qr_modal" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteLabel">@lang('equipments/title.printqr')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="show_qr"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('button.cancel')</button>
            </div>
        </div>        
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
    var curdrive = 0;
    var curstate = 0;

    function setdrive(state, eq_id){
        curstate = state;
        curdrive = eq_id;
        $('#drive_confirm').modal('toggle');
    }

    $(function() {        
        table = $('#table').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.equipments.getdata') !!}',
            order:[],
            columns: [
                { data: 'qr', name: 'qr', orderable: false, searchable: false  },
                { data: 'eq_id', name: 'eq_id' },
                { data: 'et_title', name: 'et_title' }, 
                { data: 'eq_internalcode', name: 'eq_internalcode' },
                { data: 'eq_name', name: 'eq_name' },
                { data: 'eq_candrive', name: 'eq_candrive' }, 
                { data: 'eq_status', name: 'eq_status' },
                { data: 'eq_notes', name: 'eq_notes' },
                { data: 'eq_notes', name: 'eq_notes' },
                { data: 'eq_check_in_miles', name: 'eq_check_in_miles' },                
                { data: 'eq_check_in_hours', name: 'eq_check_in_hours' },
                { data: 'eq_in_use', name: 'eq_in_use' },
                { data: 'eq_has_rreq', name: 'eq_has_rreq' },                  
            ]
        });   

        // $('#table tbody').on('click', 'tr', function () {
        //     var data = table.row( this ).data();
            
        // });

        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

        $('#btn_qr').click(function(){
            var valor = [];
            var listname = [];
            $('input.equipqr[type=checkbox]').each(function () {
                if (this.checked){
                    valor.push($(this).val());
                    listname.push($(this).attr('name'));
                }
            });

            if(valor.length > 0){
                var urlajax = "{{route('admin.equipments.exportqr')}}";            
                $.ajax({
                    url: urlajax,
                    type: 'POST',
                    data: {
                        valor : valor,
                        listname : listname,
                        _token : '{{csrf_token()}}',
                    },
                    error: function(err) {

                    },
                    success: function(data) {                        
                        if(data != ''){
                            $('#show_qr').empty();
                            var head = '<table class="table table-striped table-bordered" id="table" width="100%" border="1"><tbody><tr>'; 
                            var tail = '</tr></tbody></table>';
                            var out = head + data + tail;
                            $('#show_qr').html(out);
                            $('#qr_modal').modal('toggle');
                        }else{
                            $('#show_qr').empty();
                        }
                    }
                });                
            }else{
                $('#show_qr').empty();
            }
        });
                
        $('#btn_drive').click(function(){
            if(curdrive > 0){
                var urlajax = "{{route('admin.equipments.changedrive')}}";            
                $.ajax({
                    url: urlajax,
                    type: 'POST',
                    data: {
                        eq_id : curdrive,
                        state : curstate,
                        _token : '{{csrf_token()}}',
                    },
                    error: function(err) {

                    },
                    success: function(data) {                        
                        if(data > 0){
                            curdrive = 0;
                            curstate = 0;
                            table.ajax.reload();    
                        }
                    }
                });
                $('#drive_confirm').modal('hide');
            }
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
