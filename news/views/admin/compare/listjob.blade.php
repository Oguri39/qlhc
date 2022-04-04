@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('compare/title.label')
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
    <h1>@lang('compare/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('compare/title.label')
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
                    @lang('compare/title.label')
                </h4>                                
            </div>            
            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">                
                <a href="{{route('admin.compare.comparejob')}}" class="btn btn-primary">@lang('compare/title.back')</a>
                <br/><br/>
                <div id="div_content">{!! $textout !!}</div>
                <input type="hidden" name="listid" id="listid" value="{{$listid}}">
                <br/>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-10"></div>
                        <div class="col-sm-2">
                            <button class="btn btn-success" id="btn_confirm">@lang('compare/title.copyday')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div><!-- row-->
</section>
<div class="modal fade" id="modal_confirm" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('compare/title.copyday')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="div_show_message">
                @lang('compare/message.confirm.copy')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('button.cancel')</button>
                <button id="btn_copy" class="btn btn-danger Remove_square">@lang('button.save')</button>
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
    <script type="text/javascript">
        var selectfrom = [];
        var selectto = [];            
        var num = 0;

        function selectus(cur,id){
            var sel = $('#selus' + id).val();
            //console.log(cur + ' - ' + sel);
            
            var isnew = true;
            for(var i = 0;i < num;i++){
                if(selectfrom[i] == cur){                            
                    selectto[i] = sel;                                
                    isnew = false;
                    break;
                }
            }
            if(isnew){
                selectfrom[num] = cur;    
                selectto[num] = sel;                  
                num++;
            }                
            
        }

        $(function() {             
            $('#btn_confirm').click(function(){
                if(num > 0){
                    var isok = true;
                    for(var i = 0;i<num;i++){
                        for(var j = i + 1;j<num;j++){
                            if(selectfrom[i] == selectto[j] && selectto[j] != 0){
                                alert("@lang('compare/message.alert.duplicate')");
                                isok = false;
                                break;
                            }
                        }    
                    }
                    if(isok){
                        $('#modal_confirm').modal('toggle');
                    }                    
                }else{
                    alert("@lang('compare/message.empty.copy')");
                }
            });

            $('#btn_copy').click(function(){
                $.ajax({
                    url: "{{route('admin.compare.copyjobhour')}}",
                    type: 'POST',
                    data: {
                        listid : $('#listid').val(),
                        from : selectfrom,
                        to: selectto,                                                    
                        _token : '{{csrf_token()}}',
                    },
                    error: function(err) {

                    },
                    success: function(data) {                                            
                        $('#div_content').html(data[0]);
                        $('#listid').val(data[1]);
                    }
                });
                $('#modal_confirm').modal('hide');
            });
        });
    </script> 
@stop