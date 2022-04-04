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
<link rel="stylesheet" href="{{ asset('css/pages/jscharts.css') }}" />
<link rel="stylesheet" href="{{asset('css/select2.min.css')}}" type="text/css"/>
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>@lang('compare/title.label')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.export') }}">
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
                <h4 class="card-title my-2 float-left">
                    @lang('compare/title.byjob')
                </h4>                
            </div>
            <div class="card-body">                                          
                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                    <table class="table table-bordered width100" id="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>@lang('compare/title.date')</th>
                                <th>@lang('compare/title.name')</th>                                
                            </tr>
                        </thead>
                        <tbody>  
                            @foreach($data as $key => $user)
                            <tr>
                                <td>
                                    <input type="checkbox" id="check{{$user->wkd_id}}" class="form-control" onclick="clickcheck('{{trim($user->wkd_day)}}', '{{ $user->wkd_id}}');">
                                </td>                                
                                <td>
                                    {{ $user->wkd_daytext }}
                                </td>
                                <td>
                                    {{$user->firstname}} {{$user->lastname}}
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>@lang('compare/title.date')</th>
                                <th>@lang('compare/title.name')</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <form id="formcompare" action="{{route('admin.compare.listcomparejob')}}" method="POST">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="listid" id="listid" value="">
                                <input type="hidden" name="day" id="day" value="">
                                <input type="submit" class="btn btn-success" value="@lang('compare/title.label')"/>
                            </form>
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
    <script src="{{ asset('js/select2.min.js')}}"></script>
    
<script>
    
    var curdaycheck = '';
    var listcheck = [];
    var numcheck = 0;

    function clickcheck(day,id){
        var check = $('#check' + id);
        if(check.is(":checked") == true){
            if(curdaycheck != '' && day != curdaycheck){
                alert("@lang('compare/message.alert.wrongdate')");
                check.prop('checked', false);
            }else{
                listcheck[numcheck] = id;
                numcheck++;
                if(numcheck == 1) curdaycheck = day;
            }
        }else{
            for(var i = 0;i< listcheck.length;i++){
                if(listcheck[i] == id){
                    listcheck[i] = 0;
                }
            }
            numcheck--;
            if(numcheck == 0) curdaycheck = '';
        }
    }

    $(function() { 
        
        var table = $('#table').DataTable({
            responsive: true,
            pageLength: 100,             
        });

        $('#formcompare').submit(function(){
            var list = '';
            if(numcheck < 2){
                alert("@lang('compare/message.empty.compare')");
                return false;
            }
            
            for(var i = 0;i < listcheck.length;i++){
                if(listcheck[i] > 0){
                    list += ',' + listcheck[i];
                }                
            }
            list = list.substring(1);
            $('#listid').val(list);
            $('#day').val(curdaycheck);
            return true;
        });                
    });

</script>   
@stop
