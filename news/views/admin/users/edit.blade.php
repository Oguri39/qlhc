@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Edit User
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<!--page level css -->
<link href="{{ asset('vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('vendors/select2/css/select2.min.css') }}" type="text/css" rel="stylesheet">
<link href="{{ asset('vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendors/iCheck/css/all.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/pages/wizard.css') }}" rel="stylesheet">
@stop
<!--end of page level css-->


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>Edit user</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li><a href="#"> Users</a></li>
        <li class="active">Edit User</li>
    </ol>
</section>
<section class="content pr-3 pl-3">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 my-3">
            <div class="card ">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">
                        <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                            data-loop="true"></i>
                        Editing user : <p class="user_name_max">{!! $user->first_name!!} {!! $user->last_name!!}</p>
                    </h3>
                    <span class="float-right clickable">
                        <i class="fa fa-chevron-up"></i>
                    </span>
                </div>
                <div class="card-body">
                    <!--main content-->
                    {!! Form::model($user, ['url' => URL::to('admin/users/'. $user->id.''), 'method' => 'put', 'class'
                    => 'form-horizontal','id'=>'commentForm', 'enctype'=>'multipart/form-data','files'=> true]) !!}
                    {{ csrf_field() }}
                    <!-- CSRF Token -->


                    <div id="rootwizard">
                        <div class="tab-content">
                            <h2 class="hidden">&nbsp;</h2>
                            <div class="form-group {{ $errors->first('first_name', 'has-error') }}">
                                <div class="row">
                                    <label for="first_name" class="col-sm-2 control-label">First Name *</label>
                                    <div class="col-sm-10">
                                        <input id="first_name" name="first_name" type="text"
                                            placeholder="First Name" class="form-control required"
                                            value="{!! old('first_name', $user->first_name) !!}" />

                                        {!! $errors->first('first_name', '<span class="help-block">:message</span>')
                                        !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                <div class="row"> <label for="last_name" class="col-sm-2 control-label">Last Name
                                        *</label>
                                    <div class="col-sm-10">
                                        <input id="last_name" name="last_name" type="text" placeholder="Last Name"
                                            class="form-control required"
                                            value="{!! old('last_name', $user->last_name) !!}" />

                                        {!! $errors->first('last_name', '<span class="help-block">:message</span>')
                                        !!}
                                    </div>
                                </div>
                            </div>


                            <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                <div class="row">
                                    <label for="email" class="col-sm-2 control-label">Email *</label>
                                    <div class="col-sm-10">
                                        <input id="email" name="email" placeholder="E-mail" type="text"
                                            class="form-control required email"
                                            value="{!! old('email', $user->email) !!}" />
                                        {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('password', 'has-error') }}">

                                <p class="text-warning">If you don't want to change password... please leave them
                                    empty</p>
                                <div class="row">
                                    <label for="password" class="col-sm-2 control-label">Password *</label>
                                    <div class="col-sm-10">
                                        <input id="password" name="password" type="password" placeholder="Password"
                                            class="form-control required" value="{!! old('password') !!}" />
                                        {!! $errors->first('password', '<span class="help-block">:message</span>')
                                        !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
                                <div class="row">
                                    <label for="password_confirm" class="col-sm-2 control-label">Confirm Password
                                        *</label>
                                    <div class="col-sm-10">
                                        <input id="password_confirm" name="password_confirm" type="password"
                                            placeholder="Confirm Password " class="form-control required" />
                                        {!! $errors->first('password_confirm', '<span
                                            class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group required">
                                <div class="row">
                                    <label for="role" class="col-sm-2 control-label">Role *</label>
                                    <div class="col-sm-10">
                                        <select class="form-control required" title="Select role..."
                                            name="roles[]" id="roles">
                                            <option value="">Select</option>
                                            @foreach($roles as $role)
                                            <option value="{!! $role->id !!}"
                                                {{ (array_key_exists($role->id, $userRoles) ? ' selected="selected"' : '') }}
                                                @if($user->id==1&&$role->id>=2) disabled @endif @if($user->id==2 &&
                                                $role->id!=2) disabled @endif>{{ $role->name }}</option>
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
                                        {!! Form::select('sc_id',$sc,$user->sc_id,['class' => 'form-control']) !!}                                        
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
                    </div>
                    {!! Form::close() !!}
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
<script src="{{ asset('vendors/moment/js/moment.min.js') }}"></script>
<script src="{{ asset('vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/bootstrapwizard/jquery.bootstrap.wizard.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/pages/edituser.js') }}"></script>
@stop
