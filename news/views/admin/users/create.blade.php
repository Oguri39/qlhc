@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    Add User
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link href="{{ asset('vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/select2/css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/iCheck/css/all.css') }}"  rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/pages/wizard.css') }}" rel="stylesheet">
    <!--end of page level css-->
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>Add New User</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li><a href="#"> Users</a></li>
            <li class="active">Add New User</li>
        </ol>
    </section>
    <section class="content pr-3 pl-3">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 my-3">
                <div class="card ">
                    <div class="card-header bg-primary text-white">
                        <span class="float-left my-2">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            Add New User
                        </span>
                        <a href="{{ URL('admin/bulk_import_users') }}" class="float-right btn btn-success">
                            <i class="fa fa-plus fa-fw"></i>Bulk Import</a>

                    </div>
                    <div class="card-body">
                        <!--main content-->
                        <form id="commentForm" action="{{ route('admin.users.store') }}"
                              method="POST" enctype="multipart/form-data" class="form-horizontal">
                            <!-- CSRF Token -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                            <div id="rootwizard">
                                <div class="tab-content">
                                    <h2 class="hidden">&nbsp;</h2>
                                    <div class="form-group {{ $errors->first('first_name', 'has-error') }}">
                                        <div class="row">
                                        <label for="first_name" class="col-sm-2 control-label">First Name *</label>
                                        <div class="col-sm-10">
                                            <input id="first_name" name="first_name" type="text"
                                                   placeholder="First Name" class="form-control required"
                                                   value="{!! old('first_name') !!}"/>

                                            {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                                        </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                        <div class="row">                                            <label for="last_name" class="col-sm-2 control-label">Last Name *</label>
                                        <div class="col-sm-10">
                                            <input id="last_name" name="last_name" type="text" placeholder="Last Name"
                                                   class="form-control required" value="{!! old('last_name') !!}"/>

                                            {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    </div>


                                    <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                        <div class="row">
                                        <label for="email" class="col-sm-2 control-label">Email *</label>
                                        <div class="col-sm-10">
                                            <input id="email" name="email" placeholder="E-mail" type="text"
                                                   class="form-control required email" value="{!! old('email') !!}"/>
                                            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    </div>

                                    <div class="form-group {{ $errors->first('password', 'has-error') }}">
                                        <div class="row">
                                        <label for="password" class="col-sm-2 control-label">Password *</label>
                                        <div class="col-sm-10">
                                            <input id="password" name="password" type="password" placeholder="Password"
                                                   class="form-control required" value="{!! old('password') !!}"/>
                                            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    </div>

                                    <div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
                                        <div class="row">
                                            <label for="password_confirm" class="col-sm-2 control-label">Confirm Password *</label>
                                            <div class="col-sm-10">
                                                <input id="password_confirm" name="password_confirm" type="password"
                                                       placeholder="Confirm Password " class="form-control required"/>
                                                {!! $errors->first('password_confirm', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group required">
                                        <div class="row">
                                        <label for="role" class="col-sm-2 control-label">Role *</label>
                                        <div class="col-sm-10">
                                            <select class="form-control required" title="Select role..." name="role"
                                                    id="role">
                                                <option value="">Select</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                            @if($role->id == old('role')) selected="selected" @endif >{{ $role->name}}</option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('role', '<span class="help-block">:message</span>') !!}
                                        </div>
                                        </div>
                                        <span class="help-block">{{ $errors->first('role', ':message') }}</span>
                                    </div>
                                    
                                    <div class="form-group required">
                                        <div class="row">
                                            <label for="sc_id" class="col-sm-2 control-label">Schultes *</label>
                                            <div class="col-sm-10">
                                                {!! Form::select('sc_id',$sc,null,['class' => 'form-control']) !!}                                            
                                            </div>
                                        </div>                                        
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-10"></div>
                                            <div class="col-sm-2"><input type="submit" value="@lang('button.save')" class="btn btn-success"/></div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="dob" value="">    
                                <input type="hidden" name="bio" value="">        
                                <input type="hidden" name="gender" value="male">        
                                <input type="hidden" name="country" value="US">        
                                <input type="hidden" name="user_state" value="">        
                                <input type="hidden" name="city" value="">    
                                <input type="hidden" name="address" value="">        
                                <input type="hidden" name="postal" value="">        
                                <input type="hidden" name="activate" value="1">        
                                <input type="hidden" name="user_state" value="">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--row end-->
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('vendors/iCheck/js/icheck.js') }}"></script>
    <script src="{{ asset('vendors/moment/js/moment.min.js') }}" ></script>
    <script src="{{ asset('vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
    <script src="{{ asset('vendors/select2/js/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pages/adduser.js') }}"></script>
    
@stop
