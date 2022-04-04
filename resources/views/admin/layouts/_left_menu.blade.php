@if(Sentinel::inRole('quankho')||Sentinel::inRole('admin'))
<ul id="menu" class="page-sidebar-menu">
    <li {!! (Request::is('quankho') ? 'class="active"' : '' ) !!}>
        <a href="{{ URL::to('quankho') }}">
            <i class="livicon" data-name="" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            Trang Chủ
        </a>
    </li>
</ul>
@endif
@if(Sentinel::inRole('admin'))
<ul id="menu" class="page-sidebar-menu">

    <li {!! (Request::is('admin/users') ? 'class="active"' : '' ) !!}>
        <a href="{{ URL::to('admin/users') }}">
            <i class="livicon" data-name="user" title="Users" data-loop="true" data-color="#6CC66C" data-hc="#6CC66C" data-s="25"></i>
            Danh sách user
        </a>
    </li>
</ul>
@endif
@if(Sentinel::inRole('quankho')||Sentinel::inRole('admin'))
<ul id="menu" class="page-sidebar-menu">
    <li {!! (Request::is('quankho/1_dsp_xetnghiem') || Request::is('quankho/advanced_tables') ? 'class="active"' : '' ) !!}>
        <a href="{{ URL::to('quankho/1_dsp_xetnghiem') }}">

            <i class="livicon" data-name="columns" data-size="18" data-color="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            Danh mục hóa chất từng phòng xét nghiệm
        </a>
    </li>
    <li {!! (Request::is('quankho/1_nhap_hc') ? 'class="active"' : '' ) !!}>
        <a href="{{  URL::to('quankho/1_nhap_hc') }}">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-color="#6CC66C" data-hc="#6CC66C" data-loop="true"></i>
            Nhập Hóa Chất Vào Kho
        </a>
    </li>
    <!-- <li {!! (Request::is('quankho/1_nhatky_sd') ? 'class="active"' : '' ) !!}>
        <a href="{{  URL::to('quankho/1_nhatky_sd') }}">
            <i class="livicon" data-name="calendar" data-size="18" data-c="#EF6F6C" data-hc="#EF6F6C" data-loop="true"></i>
            Nhật Ký Sử Dụng
        </a>
    </li> -->
</ul>
@endif
@if(Sentinel::inRole('quanphong'))
<ul id="menu" class="page-sidebar-menu">
    <li {!! (Request::is('quanphong') ? 'class="active"' : '' ) !!}>
        <a href="{{ URL::to('quanphong') }}">
            <i class="livicon" data-name="" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            Trang Chủ
        </a>
    </li>
    <li {!! (Request::is('quanphong/2_dieuchuyen_hc') ? 'class="active"' : '' ) !!}>
        <a href="{{  URL::to('quanphong/2_dieuchuyen_hc') }}">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#F89A14" data-hc="#F89A14" data-loop="true"></i>
            Điều Chuyển Hóa Chất
        </a>
    </li>
    <li {!! (Request::is('quanphong/2_nhatky_sd') ? 'class="active"' : '' ) !!}>
        <a href="{{  URL::to('quanphong/2_nhatky_sd') }}">
            <i class="livicon" data-name="calendar" data-size="18" data-c="#EF6F6C" data-hc="#EF6F6C" data-loop="true"></i>
            Nhật Ký Sử Dụng
        </a>
    </li>
</ul>
@endif