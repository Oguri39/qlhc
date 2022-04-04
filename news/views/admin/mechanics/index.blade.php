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
                    <div id="divtitle">@lang('employees/title.alllabel')</div>
                </h4>
                <div align="center" style="width: 100%; position: absolute;">
                    <button class="btn btn-sm btn-warning" id="btn_load" style="margin-top: 10px;">@lang('employees/title.showall')</button>
                    <a href="{{route('mechanics.export')}}" class="btn btn-sm btn-success float-right" id="btn_export" style="margin-top: 10px;margin-right: 40px;">@lang('employees/title.export')</a>
                </div>                     
            </div>

            <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">
                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                    <form id="form_save" class="form-horizontal">
                    <table class="table table-bordered width100" border="1">
                        <tr>
                            <th><label>@lang('employees/title.id')</label></th>
                            <th width="18%"><input type="text" id="employeeid" class="form-control" /></th>
                            <th><label>@lang('employees/title.login')</label></th>
                            <th width="18%"><input type="text" id="login" class="form-control" placeholder="Login must be unique" required="true"/></th>
                            <th><label>@lang('employees/title.password')</label></th>
                            <th width="18%"><input type="text" id="password" class="form-control" placeholder="Password min 8 characters" required="true" minlength="8" /></th>
                            <th><label>@lang('employees/title.hired')</label></th>
                            <th width="18%"><input type="text" id="hired" class="form-control" required="true"/></th>
                        </tr>

                        <tr>
                            <th><label>@lang('employees/title.firstname')</label></th>
                            <th width="18%"><input type="text" id="firstname" class="form-control" required="true"/></th>
                            <th><label>@lang('employees/title.lastname')</label></th>
                            <th width="18%"><input type="text" id="lastname" class="form-control" required="true"/></th>
                            <th><label>@lang('employees/title.born')</label></th>
                            <th width="18%"><input type="text" id="born" class="form-control" required="true" /></th>
                            <th><label>@lang('employees/title.state')</label></th>
                            <th width="18%"><input type="text" id="state" class="form-control" required="true"/></th>
                        </tr>

                        <tr>
                            <th><label>@lang('employees/title.address')</label></th>
                            <th width="18%"><input type="text" id="address" class="form-control" required="true"/></th>
                            <th><label>@lang('employees/title.city')</label></th>
                            <th width="18%"><input type="text" id="city" class="form-control" required="true"/></th>
                            <th><label>@lang('employees/title.zip')</label></th>
                            <th width="18%"><input type="text" id="zip" class="form-control" required="true"/></th>
                            <th><label>@lang('employees/title.liveexpressrate')</label></th>
                            <th width="18%"><input type="text" id="liveexpressrate" class="form-control" value="11.2" required="true"/></th>
                        </tr>

                        <tr>
                            <th><label>@lang('employees/title.tradenr')</label></th>
                            <th width="18%"><input type="text" id="tradenr" class="form-control" placeholder="ex: 12"/></th>
                            <th><label>@lang('employees/title.deptnr')</label></th>
                            <th width="18%"><input type="text" id="deptnr" class="form-control" placeholder="ex: 12" required="true"/></th>
                            <th><label>@lang('employees/title.tel')</label></th>
                            <th width="18%"><input type="text" id="tel" class="form-control" /></th>
                            <th><label>@lang('employees/title.active')</label></th>
                            <th width="18%">
                                <select id="active1" name="active1" class="form-control">
                                    <option value="0">@lang('employees/title.notactive')</option>
                                    <option value="1">@lang('employees/title.active')</option>
                                </select>
                            </th>
                        </tr>
                        <tr>
                            <th><label>@lang('employees/title.drivetimerate')</label></th>
                            <th width="18%"><input type="text" id="drivetimerate" class="form-control" value="18" required="true"/></th>
                            <th><label>@lang('employees/title.drivecdltimerate')</label></th>
                            <th width="18%"><input type="text" id="drivecdltimerate" class="form-control" required="true"/></th>
                            <th><label>@lang('employees/title.milesrate')</label></th>
                            <th width="18%"><input type="text" id="milesrate" class="form-control" required="true"/></th>
                            <th colspan="2"><input type="submit" value="@lang('button.save')" class="btn btn-success"/> <a id="a_del" style="display: none;" class="btn btn-danger" data-toggle="modal" data-target="#delete_confirm"><font color="white">@lang('button.delete')</font></a> <button id="btn_new" class="btn btn-primary" onclick="resetnew();">@lang('button.reset')</button></th>
                        </tr>                          
                    </table>                    
                    </form>
                </div>
                <br/>
                <table class="table table-striped table-bordered" id="table" width="100%">
                    <thead>
                     <tr>
                        <th >@lang('employees/title.id')</th>
                        <th >@lang('employees/title.login')</th>                            
                        <th >@lang('employees/title.firstname')</th>
                        <th >@lang('employees/title.lastname')</th>                        
                        <th >@lang('employees/title.active')</th>
                        <th ></th>
                        <th >@lang('employees/title.workingtime')</th>
                        <th > </th>                            
                     </tr>
                    </thead>
                    <tbody>  
                    </tbody>
                    <tfoot>
                     <tr>
                        <th >@lang('employees/title.id')</th>
                        <th >@lang('employees/title.login')</th>                            
                        <th >@lang('employees/title.firstname')</th>
                        <th >@lang('employees/title.lastname')</th>                        
                        <th >@lang('employees/title.active')</th>                        
                        <th ></th>                            
                        <th >@lang('employees/title.workingtime')</th>                            
                        <th ></th>                            
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
                <h4 class="modal-title" id="deleteLabel">@lang('employees/title.saveemployee')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="div_show_message">
                @lang('employees/message.confirm.save')
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
                <h4 class="modal-title" id="deleteLabel">@lang('employees/title.deleteemployee')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="div_show_del">
                @lang('employees/message.confirm.delete')
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
    <script src="{{ asset('vendors/chartjs/js/Chart.js') }}"></script>
<script>
    var us_id = 0;
    var optshow = 1;
    var listclock = [];
    var listid = [];
    var numclock = 0;

    function startwork(id){
        var wkd_id = $('#wkdid' + id).val();
        $.ajax({
            url: "{{route('mechanics.startwork')}}",
            type: 'POST',
            data: {
                us_id : id,
                wkd_id : wkd_id,                                    
                _token : '{{csrf_token()}}',
            },
            error: function(err) {

            },
            success: function(data) {                                            
                if(data[0] == 1){       
                    $('#start' + id).hide();                    
                    $('#end' + id).show();
                    $('#starttime' + id).val(data[1]);
                    $('#wkdid' + id).val(data[2]);
                    $('#hrsid' + id).val(data[3]);
                }
            }
        });
    }

    function endwork(id){
        var wkd_id = $('#wkdid' + id).val();
        var hrs_id = $('#hrsid' + id).val();
        $.ajax({
            url: "{{route('mechanics.endwork')}}",
            type: 'POST',
            data: {
                us_id : id,
                wkd_id : wkd_id,            
                hrs_id: hrs_id,                        
                _token : '{{csrf_token()}}',
            },
            error: function(err) {

            },
            success: function(data) {                                            
                if(data == 1){       
                    $('#start' + id).show();
                    $('#starttime' + id).html('');  
                    $('#hrsid' + id).val('0'); 
                    $('#end' + id).hide();
                }
            }
        });
    }

    function resetnew(){
        us_id = 0;
        $('#employeeid').val("");
        $('#login').val("");
        $('#password').val("");
        $('#firstname').val("");
        $('#lastname').val("");
        $('#born').val("");
        $('#hired').val("");
        $('#address').val("");
        $('#city').val("");
        $('#state').val("");
        $('#zip').val("");
        $('#tel').val("");
        $('#tradenr').val("");
        $('#deptnr').val("");
        $('#liveexpressrate').val("11.2");
        $('#drivetimerate').val("18");
        $('#drivecdltimerate').val("");
        $('#milesrate').val("");
        $('#active1').val(0);         
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

    function showpad(val){
        if(val < 10){
            return '0' + val;
        }else{
            return val;
        }
    }
    
    $(function() {        
        var table = $('#table').DataTable({
            responsive: true,
            pageLength: 100,
            processing: true,
            serverSide: true,
            ajax: "{{ route('mechanics.getdata',1) }}",
            //order: [[ 6, "desc" ],[0, "asc"]],
            columns: [
                { data: 'userId', name: 'userId' },
                { data: 'login', name: 'login' },                
                { data: 'firstname', name: 'firstname' },
                { data: 'lastname', name: 'lastname'},                
                { data: 'activetext', name: 'activetext'},
                { data: 'actions', name: 'actions', orderable: false, searchable: false },
                { data: 'clock', name: 'clock', orderable: false, searchable: false },
                { data: 'addwork', name: 'addwork', orderable: false, searchable: false },
            ],            
            createdRow: function( row, data, dataIndex ) {
                if(data['activetext'] == "@lang('general.no')"){
                    $('td', row).css('background-color', '#888888');
                }
            }
        });
        
        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            $('.starttime').each(function(i, obj) {
                var id = obj.id.substring(9);
                //console.log(id);
                var hrsid = $('#hrsid' + id).val();
                if(hrsid == 0){
                    $('#divshowtime' + id).html("00:00:00");
                }else{
                    var begin = $('#starttime' + id).val();
                    var from = new Date(begin).getTime();
                    var distance = now - from - (7000 * 60 * 60);
                    var hours = Math.floor(distance / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    $('#divshowtime' + id).html(showpad(hours) + ':' + showpad(minutes) + ':' + showpad(seconds));
                }
            });            
        }, 1000);
        
        function checkSubmitform(){
            var a = $('#login').val().trim();          
            // check login
            if(a != ''){            
                $.ajax({
                    url: "{{route('mechanics.checkloginunique')}}",
                    type: 'POST',
                    data: {
                        login : a,                    
                        _token : '{{csrf_token()}}',
                    },
                    error: function(err) {

                    },
                    success: function(data) {                                            
                        if(data == 1 || us_id > 0){                        
                            $('#div_show_message').html("@lang('employees/message.confirm.save')");
                            $('#btn_save').show();
                        }else{
                            $('#div_show_message').html("@lang('employees/message.error.login')"); 
                            $('#btn_save').hide();  
                        }
                        $('#modal_confirm').modal('toggle');
                    }
                });
            }
        }

        function submitform(){
            var login = $('#login').val().trim();        
            var urlajax = "{{route('mechanics.store')}}";
            if(us_id > 0){
                urlajax = "{{route('mechanics.update')}}";
            } 
                    
            // check login
            if(login != ''){
                $.ajax({
                    url: urlajax,
                    type: 'POST',
                    data: {
                        us_id: us_id,
                        id:   $('#employeeid').val().trim(),
                        login :     login,
                        password:   $('#password').val().trim(),
                        firstname:  $('#firstname').val().trim(),
                        lastname:   $('#lastname').val().trim(),
                        born:   $('#born').val().trim(),
                        hired:   $('#hired').val().trim(),
                        address:   $('#address').val().trim(),
                        city:   $('#city').val().trim(),
                        zip:   $('#zip').val().trim(),
                        state:   $('#state').val().trim(),
                        tradenr: $('#tradenr').val().trim(),
                        deptnr:   $('#deptnr').val().trim(),
                        tel:    $('#tel').val().trim(),
                        liveexpressrate:   $('#liveexpressrate').val().trim(),
                        drivetimerate:   $('#drivetimerate').val().trim(),
                        drivecdltimerate:   $('#drivecdltimerate').val().trim(),
                        milesrate:   $('#milesrate').val().trim(),                    
                        active: $('#active1').val(),
                        _token : '{{csrf_token()}}',
                    },
                    error: function(err) {

                    },
                    success: function(data) {                        
                        if(data > 0){
                            $('#div_show_message').html("@lang('employees/message.success.save')");
                            $('#btn_save').hide();
                            table.ajax.reload();
                        }else{
                            $('#div_show_message').html("@lang('employees/message.error.save')"); 
                            $('#btn_save').hide();  
                        }
                        //$('#modal_confirm').modal('toggle');
                    }
                });
            }
        }   

        $('#table tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();  
            us_id = data.us_id;            
            $('#employeeid').val(checkdata(data.userId).replace(/&quot;/g,'"'));
            $('#login').val(checkdata(data.login).replace(/&quot;/g,'"'));
            $('#password').val(checkdata(data.password).replace(/&quot;/g,'"'));
            $('#firstname').val(checkdata(data.firstname).replace(/&quot;/g,'"'));
            $('#lastname').val(checkdata(data.lastname).replace(/&quot;/g,'"'));
            $('#born').val(checkdata(data.born));
            $('#hired').val(checkdata(data.hired));
            $('#address').val(checkdata(data.address1).replace(/&quot;/g,'"'));
            $('#city').val(checkdata(data.city).replace(/&quot;/g,'"'));
            $('#state').val(checkdata(data.state).replace(/&quot;/g,'"'));
            $('#zip').val(checkdata(data.zip).replace(/&quot;/g,'"'));
            $('#tel').val(checkdata(data.tel).replace(/&quot;/g,'"'));
            $('#tradenr').val(checkdata(data.tradeNr).replace(/&quot;/g,'"'));
            $('#deptnr').val(checkdata(data.departmentNr).replace(/&quot;/g,'"'));
            $('#liveexpressrate').val(checkdata(data.liveawayexp).replace(/&quot;/g,'"'));
            $('#drivetimerate').val(checkdata(data.drivetimerate).replace(/&quot;/g,'"'));
            $('#drivecdltimerate').val(checkdata(data.drivetimeratecdl).replace(/&quot;/g,'"'));
            $('#milesrate').val(checkdata(data.milesrate).replace(/&quot;/g,'"'));
            $('#active1').val(checkdata(data.us_active));                  
            $('#a_del').show();    
            $('#div_show_del').html("@lang('employees/message.confirm.delete')");
            $('#btn_delete').show();   
            $('#btn_new').show();  
        });

        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });

        $('#born').datetimepicker({
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

        $('#hired').datetimepicker({
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
        
        $('#form_save').submit(function(){
            checkSubmitform();
            return false;
        });

        $('#btn_new').hide();

        $('#btn_save').click(function(){
            submitform();
            return false;
        });

        $('#btn_load').click(function(){
            if(optshow == 0){
                optshow = 1;
                var url = '{!! route('mechanics.getdata',1) !!}';
                $('#divtitle').html("@lang('employees/title.activelabel')");
                $('#btn_load').text("@lang('employees/title.showall')");
                table.ajax.url(url).load();
            }else{
                optshow = 0;
                var url = '{!! route('mechanics.getdata',0) !!}';
                $('#divtitle').html("@lang('employees/title.alllabel')");
                $('#btn_load').text("@lang('employees/title.showactive')");
                table.ajax.url(url).load();
            }
        });

        $('#btn_delete').click(function(){
            if(us_id > 0){
                $.ajax({
                    url: "{{route('mechanics.delete')}}",
                    type: 'POST',
                    data: {
                        us_id : us_id,
                        _token : '{{csrf_token()}}',
                    },
                    error: function(err) {

                    },
                    success: function(data) {                        
                        if(data > 0){
                            $('#div_show_del').html("@lang('employees/message.success.delete')");
                            resetnew();
                            table.ajax.reload();
                        }else{                        
                            $('#div_show_del').html("@lang('employees/message.error.delete')");
                        }
                        $('#btn_delete').hide();
                    }
                });
            }else{
                $('#delete_confirm').modal("hide");
            }            
        });
    });
    
</script>   
@stop
