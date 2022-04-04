<ul id="menu" class="page-sidebar-menu">
    @if(Sentinel::inRole('mechanicadmin'))
    <li {!! (Request::is('mechanics.index') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('mechanics.index') }}">
            <i class="livicon" data-name="dashboard" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('general.dashboard')</span>
        </a>
    </li>
    @endif
    @if(Sentinel::inRole('admin'))
    <li {!! (Request::is('admin') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.dashboard') }}">
            <i class="livicon" data-name="dashboard" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('general.dashboard')</span>
        </a>
    </li>
    <li {!! (Request::is('admin/users') || Request::is('admin/bulk_import_users') || Request::is('admin/users/create') || Request::is('admin/user_profile') || Request::is('admin/users/*') || Request::is('admin/deleted_users') ? 'class="active"' : '' ) !!}>
        <a href="#">
            <i class="livicon" data-name="user" data-size="18" data-c="#6CC66C" data-hc="#6CC66C" data-loop="true"></i>
            <span class="title">Users</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('admin/users') ? 'class="active" id="active"' : '' ) !!}>
                <a href="{{ URL::to('admin/users') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Users
                </a>
            </li>
            <li {!! (Request::is('admin/users/create') ? 'class="active" id="active"' : '' ) !!}>
                <a href="{{ URL::to('admin/users/create') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Add New User
                </a>
            </li>
            <li {!! ((Request::is('admin/users/*')) && !(Request::is('admin/users/create')) || Request::is('admin/user_profile') ? 'class="active" id="active"' : '' ) !!}>
                <a href="{{ URL::route('admin.users.show',Sentinel::getUser()->id) }}">
                    <i class="fa fa-angle-double-right"></i>
                    View Profile
                </a>
            </li>            
        </ul>
    </li>
    <li {!! (Request::is('admin/roles') || Request::is('admin/roles/create') || Request::is('admin/roles/*') ? 'class="active"' : '' ) !!}>
        <a href="#">
            <i class="livicon" data-name="users" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">Roles</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('admin/roles') ? 'class="active" id="active"' : '' ) !!}>
                <a href="{{ URL::to('admin/roles') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Roles List
                </a>
            </li>
            <li {!! (Request::is('admin/roles/create') ? 'class="active" id="active"' : '' ) !!}>
                <a href="{{ URL::to('admin/roles/create') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Add New Role
                </a>
            </li>
        </ul>
    </li>  
    <li {!! (Request::is('admin/jobs') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.jobs') }}">
            <i class="livicon" data-name="servers" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('jobs/title.label')</span>
        </a>
    </li>    
    <li {!! (Request::is('admin/employees') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.employees') }}">
            <i class="livicon" data-name="user" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('employees/title.label')</span>
        </a>
    </li>
    <li {!! (Request::is('admin/holidays') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.holidays') }}">
            <i class="livicon" data-name="image" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('holidays/title.label')</span>
        </a>
    </li>

    <li {!! (Request::is('admin/compare/comparejob') || Request::is('admin/compare/compareday') ? 'class="active"' : '' ) !!}>
        <a href="#">
            <i class="livicon" data-name="compare" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('compare/title.label')</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('admin/compare/comparejob') ? 'class="active" id="active"' : '' ) !!}>
                <a href="{{ route('admin.compare.comparejob') }}">
                    <i class="fa fa-angle-double-right"></i>
                    @lang('compare/title.byjob')
                </a>
            </li>
            <li {!! (Request::is('admin/compare/compareday') ? 'class="active" id="active"' : '' ) !!}>
                <a href="{{ route('admin.compare.compareday') }}">
                    <i class="fa fa-angle-double-right"></i>
                    @lang('compare/title.byday')
                </a>
            </li>
        </ul>
    </li>  

    <li {!! (Request::is('admin/weekcalculate') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.weekcalculate') }}">
            <i class="livicon" data-name="warning" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('weekcalculate/title.label')</span>
        </a>
    </li>
    <li {!! (Request::is('admin/timecheck') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.timecheck') }}">
            <i class="livicon" data-name="warning" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('timecheck/title.label')</span>
        </a>
    </li>    
    <li {!! (Request::is('admin/export') || Request::is('admin/exportnote') ? 'class="active"' : '' ) !!}>
        <a href="#">
            <i class="livicon" data-name="download" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('export/title.label')</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('admin/export') ? 'class="active" id="active"' : '' ) !!}>
                <a href="{{ route('admin.export') }}">
                    <i class="fa fa-angle-double-right"></i>
                    @lang('export/title.exportjob')
                </a>
            </li>
            <li {!! (Request::is('admin/exportnote') ? 'class="active" id="active"' : '' ) !!}>
                <a href="{{ route('admin.exportnote') }}">
                    <i class="fa fa-angle-double-right"></i>
                    @lang('export/title.exportnote')
                </a>
            </li>
        </ul>
    </li>  


    <li {!! (Request::is('admin/employeemap') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.employeemap') }}">
            <i class="livicon" data-name="location" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('employeemap/title.employeemap')</span>
        </a>
    </li>  
    <li {!! (Request::is('admin/drivemap') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.drivemap') }}">
            <i class="livicon" data-name="location" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('drivemap/title.drivemap')</span>
        </a>
    </li>

    <li style="height: 50px;background-color: #66bef2; color: white">
        <a href="">            
            <span class="title">@lang('equipments/title.label')</span>
        </a>
    </li>

    <li {!! (Request::is('admin/equipmenttype') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.equipmenttype') }}">
            <i class="livicon" data-name="tag" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('equipmenttype/title.label')</span>
        </a>
    </li>      

    <li {!! (Request::is('admin/equipments') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.equipments') }}">
            <i class="livicon" data-name="car" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('equipments/title.label')</span>
        </a>
    </li> 
    
    <li {!! (Request::is('admin/equip_users') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.equip_users') }}">
            <i class="livicon" data-name="dashboard" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('equip_users/title.usage')</span>
        </a>
    </li>

    <li {!! (Request::is('admin/insreports') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.insreports') }}">
            <i class="livicon" data-name="check" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('insreports/title.label')</span>
        </a>
    </li>

    <li {!! (Request::is('admin/workorders') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.workorders') }}">
            <i class="livicon" data-name="wrench" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">@lang('workorders/title.label')</span>
        </a>
    </li>   
    @endif 
    <li style="height: 50px;"></li>  
    <li style="background-color: red;" {!! (Request::is('admin/logout') ? 'class="active"' : '' ) !!}>
        <a href="{{ URL::to('admin/logout') }}">
            <i class="livicon" data-name="sign-out" data-s="18"></i>
            Logout
        </a>
    </li>
    <!-- Menus generated by CRUD generator -->
    @include('admin/layouts/menu')
</ul>
