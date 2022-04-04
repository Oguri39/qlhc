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
                    @lang('compare/title.byday')
                </h4>                
            </div>
            <div class="card-body">  
                <div class="form-group">
                    <div class="row">                        
                        <label class="control-label col-sm-1" style="margin-top: 5px;">@lang('compare/title.date')</label>
                        <div class="col-sm4">
                            <input type="text" id="todate" class="form-control" value="{{date('m/d/Y')}}"/>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-success" id="btn_ok">@lang('compare/title.ok')</button>
                        </div>  
                    </div>
                </div>                              
                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                    <table class="table table-bordered width100" id="table">
                        <thead>
                            <tr>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>                              
                        </tbody>                        
                    </table>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <form id="formcompare" action="{{route('admin.compare.listcomparejob')}}" method="POST">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="listid" id="listid" value="">                                
                                <input type="submit" class="btn btn-success" value="@lang('compare/title.comparehour')"/>
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
    var listcheck = [];
    var numcheck = 0;
    function clickcheck(us_id,wkd_id){
        var check = $('#check' + wkd_id);
        if(check.is(":checked") == true){            
            listcheck[numcheck] = wkd_id;
            numcheck++;
        }else{
            for(var i = 0;i< listcheck.length;i++){
                if(listcheck[i] == id){
                    listcheck[i] = 0;
                }
            }
            numcheck--;            
        }
    }
    
    $(function() { 
        
        var table = $('#table').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.compare.getdataday') !!}',
            order: [],
            columns: [
                { data: 'action', name: 'action', orderable: false, searchable: false },                
            ], 
        });       

        $('#todate').datetimepicker({
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

        $('#formcompare').submit(function(){
            var list = '';
            if(numcheck < 1){
                alert("@lang('compare/message.empty.compare1')");
                return false;
            }
            
            for(var i = 0;i < listcheck.length;i++){
                if(listcheck[i] > 0){
                    list += ',' + listcheck[i];
                }                
            }
            list = list.substring(1);
            $('#listid').val(list);

            return true;
        });

        $('#btn_ok').click(function(){            
            var todate = $('#todate').val().trim();
            str = todate.split('/');
            if(str.length == 3){
                todate = str[2] + '-' + str[0] + '-' + str[1];    
            }else{
                todate = '';
            }

            if(todate != ''){
                var link = "{{ route('admin.compare.getdataday') }}" + '?todate=' + todate;
                table.ajax.url(link).load();                
            }            
        });
        
    });

</script>   
@stop
