@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('employees/title.label')
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
    <h1>@lang('employees/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('employees/title.label')
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
                    {{$data->reporttitle}}
                </h4>                     
            </div>
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">                
                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                    {!!$data->equip!!}
                </div>                
                <form enctype="multipart/form-data" class="form-horizontal" action="{{route('admin.equip_users.storecheck')}}" method="POST"> 
                <input type="hidden" name="eq_id" value="{{$eq_id}}">
                <input type="hidden" name="ec_id" value="{{$ec_id}}">
                <input type="hidden" name="backmode" value="{{$backmode}}">
                <input type="hidden" name="_token" value="{{csrf_token()}}">                       
                <div class="form-group">
                    <table style="width: 100%" border="1">
                        <tr>
                            <td>
                                <label class="control-label" style="margin-top: 5px;">@lang('equipments/title.date'): {{$data->date}}</label>
                            </td>
                            <td>
                                <label class="control-label" style="margin-top: 5px;">@lang('equipments/title.inspector'): {{$data->inspector}}</label>
                            </td>
                            <td>
                                <label class="control-label" style="margin-top: 5px;">@lang('equipments/title.type'): </label>
                                @for($i = 0;$i<4;$i++)
                                    <input type="radio" name="ec_ins_mai_rep" value="{{$i}}" {{$data->type[$i]}} />&nbsp;{{$data->typelist[$i]}}&nbsp;&nbsp; 
                                @endfor
                            </td>
                            <td>
                                <table>
                                    <tr>                                        
                                        <td><label class="control-label" style="margin-top: 5px;">@lang('equipments/title.status'):</label>&nbsp;</td>
                                        <td>{!!Form::select('ec_status', $data->liststatus, $data->ec_status, ['class' => 'form-control', 'id' => 'ec_status'])!!}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <label class="control-label" style="margin-top: 5px;">@lang('equipments/title.notes'):</label><br/>
                                <textarea name="ec_notes" rows="3" style="width: 100%">{{$data->notes}}</textarea>
                            </td>
                            <td>
                                &nbsp;&nbsp;<input type="submit" name="sub" value="@lang('button.save')" class="btn btn-success"> &nbsp; 
                                @if($ec_id != "" && $ec_id > 0)
                                <a data-toggle="modal" data-target="#delete_confirm" class="btn btn-danger Remove_square">@lang('button.delete')</a>                
                                @endif
                            </td>    
                        </tr>
                    </table>
                </div>
                <div class="form-group">
                    <div class="row">
                        {!!$panel!!}
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
            <div class="modal-body" id="deletecontent">
                @lang('equip_users/message.confirm.delete')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('button.cancel')</button> &nbsp;<a href="{{route('admin.equip_users.deletecheck',[$backmode,$eq_id,$ec_id])}}" class="btn btn-danger Remove_square">@lang('button.delete')</a>                
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
@stop
