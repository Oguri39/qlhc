@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('jobs/title.label')
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
    <h1>@lang('jobs/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('jobs/title.label')
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
                    @lang('jobs/title.label')
                </h4>                
            </div>
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">
                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                    <table class="table table-bordered width100" border="1">
                        <tr>
                            <th><label>@lang('jobs/title.jobnr')</label></th>
                            <th width="30%"><input type="text" id="jobnr" class="form-control" /></th>
                            <th><label>@lang('jobs/title.company')</label></th>
                            <th width="30%"><input type="text" id="company" class="form-control"/></th>
                            <th width="10%"><label>@lang('jobs/title.payrate')</label></th>
                            <th width="15%">
                                <select id="payrate" class="form-control">
                                    <option value="0">@lang('jobs/title.normal')</option>
                                    <option value="1">@lang('jobs/title.high')</option>
                                </select>
                            </th>
                        </tr>
                        <tr>
                            <th><label>@lang('jobs/title.dateopen')</label></th>
                            <th width="30%"><input type="text" id="dateopen" class="form-control"/></th>
                            <th><label>@lang('jobs/title.admin')</label></th>
                            <th width="30%"><input type="text" id="admin" class="form-control"/></th>
                            <th width="10%"><label>@lang('jobs/title.status')</label></th>
                            <th width="15%">
                                <select id="status" class="form-control">
                                    <option value="0">@lang('jobs/title.active')</option>
                                    <option value="1">@lang('jobs/title.notactive')</option>
                                </select>
                            </th>
                        </tr>                                                
                        <tr>
                            <th><label>@lang('jobs/title.description')</label></th>
                            <th width="30%" colspan="3">
                                <textarea id="description" class="form-control"></textarea>
                            </th>
                            <th colspan="2"><a id="a_save" class="btn btn-success" data-toggle="modal" data-target="#modal_confirm"><font color="white">@lang('button.save')</font></a> <a id="a_del" style="display: none;" class="btn btn-danger" data-toggle="modal" data-target="#delete_confirm"><font color="white">@lang('button.delete')</font></a> <button id="btn_new" class="btn btn-primary" onclick="resetnew();">@lang('button.reset')</button></th>
                        </tr>
                    </table>
                </div>
                <br/>
                <table class="table table-striped table-bordered" id="table" width="100%">
                    <thead>
                     <tr>
                        <th >@lang('jobs/title.id')</th>
                        <th >@lang('jobs/title.jobnr')</th>
                        <th >@lang('jobs/title.company')</th>    
                        <th >@lang('jobs/title.description')</th>
                        <th >@lang('jobs/title.admin')</th>
                        <th >@lang('jobs/title.dateopen')</th>
                        <th >@lang('jobs/title.payrate')</th>
                        <th >@lang('jobs/title.status')</th>                            
                     </tr>
                    </thead>
                    <tbody>  
                    </tbody>
                    <tfoot>
                     <tr>
                        <th >@lang('jobs/title.id')</th>
                        <th >@lang('jobs/title.jobnr')</th>
                        <th >@lang('jobs/title.company')</th>    
                        <th >@lang('jobs/title.description')</th>
                        <th >@lang('jobs/title.admin')</th>
                        <th >@lang('jobs/title.dateopen')</th>
                        <th >@lang('jobs/title.payrate')</th>
                        <th >@lang('jobs/title.status')</th>                            
                     </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    </div><!-- row-->
</section>
<div class="modal fade" id="modal_confirm" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteLabel">@lang('jobs/title.savejob')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="div_show_message">
                @lang('jobs/message.confirm.save')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('button.cancel')</button>
                <button id="btn_save" class="btn btn-danger Remove_square">@lang('button.save')</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteLabel">@lang('jobs/title.deletejob')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                @lang('jobs/message.confirm.delete')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('button.cancel')</button>
                <button id="btn_delete" class="btn btn-danger Remove_square">@lang('button.delete')</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>

<div class="modal fade" id="modal_changeactive" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >@lang('jobs/title.changeactive')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="div_show_message_changeactive">
                @lang('jobs/message.confirm.changeactive')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('button.cancel')</button>
                <button id="btn_changeactive" class="btn btn-success Remove_square">@lang('button.save')</button>
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
    <script src="{{ asset('vendors/chartjs/js/Chart.js') }}"></script>
<script>
    var idchangeactice = 0;
    var statuschangeactive = -1;
    function resetnew(){
        $('#jobnr').val("");
        $('#company').val("");
        $('#payrate').val(0);
        $('#dateopen').val("");
        $('#admin').val("");
        $('#status').val(0);
        $('#description').val("");
        jobid = 0;                       
        $('#a_del').hide();
        $('#btn_new').hide();
    }

    function checkdata(dat){
        if(dat == null || dat == undefined){
            return '';            
        }else{
            return dat;
        }
    }

    function changeactive(id,status){
        idchangeactice = id;
        statuschangeactive = (status + 1) % 2;
        var text = status == 0 ? "@lang('jobs/title.notactive')" : "@lang('jobs/title.active')";
        var str = "@lang('jobs/message.confirm.changeactive')" + ' ' + text + '?';
        $('#div_show_message_changeactive').html(str);
        $('#modal_changeactive').modal('toggle');
    }

    $(function() {
        var jobid = 0;
        var table = $('#table').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.jobs.getdata') !!}',
            columns: [
                { data: 'jid', name: 'jid' },
                { data: 'nr', name: 'nr' },
                { data: 'company', name: 'company' },
                { data: 'description', name: 'description' },
                { data: 'padmin', name: 'padmin'},
                { data: 'dateopen', name:'dateopen'},
                { data: 'jpaytypetext', name: 'jpaytypetext'},
                { data: 'jstatustext', name: 'jstatustext'},
            ]
        });   

        $('#table tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();            
            $('#jobnr').val(checkdata(data.nr).replace(/&quot;/g,'"'));
            $('#company').val(checkdata(data.company).replace(/&quot;/g,'"'));
            $('#payrate').val(checkdata(data.jpaytype));
            $('#dateopen').val(checkdata(data.dateopen));
            $('#admin').val(checkdata(data.padmin).replace(/&quot;/g,'"'));
            $('#status').val(checkdata(data.jstatus));
            $('#description').val(checkdata(data.description).replace(/&quot;/g,'"'));
            jobid = data.jid;               
            $('#a_del').show();  
            $('#btn_new').show();       
        });

        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

        $('#dateopen').datetimepicker({
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

        $('#btn_new').hide();

        $(document).on("click", "#a_save", function(){            
            if($('#dateopen').val().trim() == ''){
                $('#div_show_message').html("@lang('jobs/message.empty.save')");
                $('#btn_save').hide();
            }else{
                if(jobid == 0){
                    $('#div_show_message').html("@lang('jobs/message.empty.save')");
                    $('#btn_save').hide();
                }else{
                    $('#div_show_message').html("@lang('jobs/message.confirm.save')");
                    $('#btn_save').show();
                }
            }            
            return false;
        });

        $('#btn_save').click(function(){
            var urlajax = "{{route('admin.jobs.update')}}";
            if(jobid == 0){
                urlajax = "{{route('admin.jobs.store')}}";
            }

            $.ajax({
                url: urlajax,
                type: 'POST',
                data: {
                    jid : jobid,
                    jobnr : $('#jobnr').val(),
                    company : $('#company').val(),
                    payrate : $('#payrate').val(),
                    dateopen : $('#dateopen').val(),
                    admin : $('#admin').val(),
                    status : $('#status').val(),
                    description : $('#description').val(),
                    _token : '{{csrf_token()}}',
                },
                error: function(err) {

                },
                success: function(data) {                        
                    if(data > 0){
                        alert("@lang('jobs/message.success.save')");
                        resetnew();
                        table.ajax.reload();
                    }else{
                        alert("@lang('jobs/message.error.save')");
                    }
                }
            });
            $('#modal_confirm').modal('hide');
        });

        $('#btn_delete').click(function(){
            $.ajax({
                url: "{{route('admin.jobs.delete')}}",
                type: 'POST',
                data: {
                    jid : jobid,
                    _token : '{{csrf_token()}}',
                },
                error: function(err) {

                },
                success: function(data) {                        
                    if(data > 0){
                        alert("@lang('jobs/message.success.delete')");
                        resetnew();
                        table.ajax.reload();
                    }else{
                        alert("@lang('jobs/message.error.delete')");
                    }

                }
            });
            $('#delete_confirm').modal('hide');
        });

        $('#btn_changeactive').click(function(){
            $.ajax({
                url: "{{route('admin.jobs.changeactive')}}",
                type: 'POST',
                data: {
                    jid : idchangeactice,
                    status: statuschangeactive,
                    _token : '{{csrf_token()}}',
                },
                error: function(err) {

                },
                success: function(data) {                        
                    if(data > 0){
                        alert("@lang('jobs/message.success.changeactive')");                        
                        table.ajax.reload();
                    }else{
                        alert("@lang('jobs/message.error.changeactive')");
                    }

                }
            });
            $('#modal_changeactive').modal('hide');
        });
    });
    var $url_path = '{!! url('/') !!}';
    $('#delete_confirm').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var $recipient = button.data('id');
        var modal = $(this)
        modal.find('.modal-footer a').prop("href",$url_path+"/admin/users/"+$recipient+"/delete");
    })

</script>   
@stop
