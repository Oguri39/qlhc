@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('insreports/title.label')
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
    <h1>@lang('insreports/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('insreports/title.label')
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
                    {{$reporttitle}}
                </h4>                     
            </div>
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">                
                <div class="form-group">
                    <div class="row">
                        <a href="{{$routeback}}" class="btn btn-primary">@lang('insreports/title.backtothelist')</a>
                    </div>
                </div>
                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                    {!!$equip!!}
                </div>
                <br/>
                <div class="form-group">                    
                    <table class="table table-striped table-bordered" id="table1" width="100%">
                        <thead>
                            <tr>
                                <th></th>                            
                                <th>@lang('insreports/title.equipmentclass')</th>
                                <th>@lang('insreports/title.equipment')</th>
                                <th>@lang('insreports/title.workordernr')</th>
                                <th>@lang('insreports/title.description')</th>
                                <th>@lang('insreports/title.startdate')</th>
                                <th>@lang('insreports/title.enddate')</th>
                                <th>@lang('insreports/title.assignedto')</th>
                                <th>@lang('insreports/title.status')</th>                                
                            </tr>
                        </thead>                    
                        <tbody>   
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>@lang('insreports/title.equipmentclass')</th>
                                <th>@lang('insreports/title.equipment')</th>
                                <th>@lang('insreports/title.workordernr')</th>
                                <th>@lang('insreports/title.description')</th>
                                <th>@lang('insreports/title.startdate')</th>
                                <th>@lang('insreports/title.enddate')</th>
                                <th>@lang('insreports/title.assignedto')</th>
                                <th>@lang('insreports/title.status')</th> 
                            </tr>
                        </tfoot>
                    </table>                    
                </div> 
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-2">
                            {!!Form::select('closeopen', $liststatus, null, ['class' => 'form-control', 'id' => 'closeopen'])!!}
                        </div>
                        <div class="col-sm-4">
                            <button class="btn btn-success" id="btn_closeopen">@lang('insreports/title.closeopen')</button>
                        </div>
                    </div>
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
<script>
       
    $(function() { 
        table = $('#table1').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.insreports.getdatapart',[$eq_id,$ec_id]) !!}',
            order: [],
            columns: [
                { data: 'checkb', name: 'checkb' },
                { data: 'equipclass', name: 'equipclass' },
                { data: 'equip', name: 'equip' }, 
                { data: 'worknr', name: 'worknr' },
                { data: 'desc', name: 'desc' },
                { data: 'start', name: 'start' }, 
                { data: 'end', name: 'end' },
                { data: 'asign', name: 'asign' },
                { data: 'stat', name: 'stat' }, 
            ],
            // createdRow: function( row, data, dataIndex ) {
            //     $('td', row).css('background-color', data['bgcolor']);
            // }
        });

        $('#btn_closeopen').click(function(){
            var valor = [];
            var listname = [];
            $('input.insreport[type=checkbox]').each(function () {
                if (this.checked){
                    valor.push($(this).val());
                    listname.push($(this).attr('name'));
                }
            });
            var closeopen = $('#closeopen').val();
            if(valor.length > 0){
                $.ajax({
                    url: "{{route('admin.insreports.closeopen')}}",
                    type: 'POST',
                    data: {
                        closeopen: closeopen,
                        valor: valor,
                        listname: listname,
                        eq_id: '{{$eq_id}}',
                        ec_id: '{{$ec_id}}',
                        _token : '{{csrf_token()}}',
                    },
                    error: function(err) {

                    },
                    success: function(data) {                        
                        if(data == 1){
                            alert("@lang('workorders/message.success.save')");
                            table.ajax.reload();                            
                        }else{
                            alert("@lang('workorders/message.error.save')");
                        }
                    }
                });
            }
        })
    });
    
</script>   
@stop
