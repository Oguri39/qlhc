@if(Sentinel::inRole('quankho')||Sentinel::inRole('admin'))
<ul id="menu" class="page-sidebar-menu">
    <li {!! (Request::is('quankho') ? 'class="active"' : '' ) !!}>
        <a href="{{ URL::to('quankho') }}">
            <i class="livicon" data-name="" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            Trang Chủ
        </a>
    </li>

    <!-- <li {!! (Request::is('quankho') ? 'class="active"' : '' ) !!}>
        <a href="{{ URL::to('quankho') }}">
            <i class="livicon" data-name="" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            Trang Chủ
        </a>
    </li>
    <li {!! (Request::is('quankho/1_ds_hc') || Request::is('quankho/1_ds_nk') || Request::is('quankho/1_ds_tk') || Request::is('quankho/1_ds_dt') ? 'class="active"' : '' ) !!}>
        <a href="#">
            <i class="livicon" data-name="list-ul" data-c="#418BCA" data-hc="#418BCA" data-size="18" data-loop="true"></i>
            <span class="title">Danh Sách Hóa Chất</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('quankho/1_ds_hc') ? 'class="active"' : '' ) !!}>
                <a href="{{ URL::to('quankho/1_ds_hc') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Danh sách hóa chất
                </a>
            </li>
            <li {!! (Request::is('quankho/1_ds_nk') ? 'class="active"' : '' ) !!}>
                <a href="{{ URL::to('quankho/1_ds_nk') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Danh sách nhập kho
                </a>
            </li>

            <li {!! (Request::is('quankho/1_ds_tk') ? 'class="active"' : '' ) !!}>
                <a href="{{ URL::to('quankho/1_ds_tk') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Danh sách tồn kho
                </a>
            </li>

            <li {!! (Request::is('quankho/1_ds_dt') ? 'class="active"' : '' ) !!}>
                <a href="{{ URL::to('quankho/1_ds_dt') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Danh sách dự trù
                </a>
            </li>


        </ul>
    </li> -->

    <li {!! (Request::is('quankho/1_dsp_xetnghiem') || Request::is('quankho/advanced_tables') ? 'class="active"' : '' ) !!}>
        <a href="{{ URL::to('quankho/1_dsp_xetnghiem') }}">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#F89A14" data-hc="#F89A14" data-loop="true"></i>
            Danh mục hóa chất từng phòng xét nghiệm
        </a>
        <!-- <li {!! (Request::is('quankho/advanced_tables') ? 'class="active"' : '' ) !!}>
                <a href="{{ URL::to('quankho/advanced_tables') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Thống kê mức độ sử dụng
                </a>
            </li> -->
    </li>
    <li {!! (Request::is('quankho/1_nhap_hc') ? 'class="active"' : '' ) !!}>
        <a href="{{  URL::to('quankho/1_nhap_hc') }}">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#F89A14" data-hc="#F89A14" data-loop="true"></i>
            Nhập Hóa Chất Vào Kho
        </a>
    </li>
    <li {!! (Request::is('quankho/1_them_hc') ? 'class="active"' : '' ) !!}>
        <a href="{{  URL::to('quankho/1_them_hc') }}">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#F89A14" data-hc="#F89A14" data-loop="true"></i>
            Thêm Hóa Chất Vào Kho
        </a>
    </li>
    <!-- <li {!! (Request::is('quankho/1_phan_hc') ? 'class="active"' : '' ) !!}>
        <a href="{{  URL::to('quankho/1_phan_hc') }}">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#F89A14" data-hc="#F89A14" data-loop="true"></i>
            Phân Hóa Chất Vào Kho
        </a>
    </li> -->
    <li {!! (Request::is('quankho/1_nhatky_sd') ? 'class="active"' : '' ) !!}>
        <a href="{{  URL::to('quankho/1_nhatky_sd') }}">
            <i class="livicon" data-name="calendar" data-size="18" data-c="#EF6F6C" data-hc="#EF6F6C" data-loop="true"></i>
            Nhật Ký Sử Dụng
        </a>
    </li>

    @include('admin/layouts/menu')


</ul>
@endif
@if(Sentinel::inRole('quanphong')||Sentinel::inRole('admin'))

<ul id="menu" class="page-sidebar-menu">
    <li {!! (Request::is('quanphong') ? 'class="active"' : '' ) !!}>
        <a href="{{ URL::to('quanphong') }}">
            <i class="livicon" data-name="" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            Trang Chủ
        </a>
    </li>
    <!-- <li {!! (Request::is('quanphong/2_ds_hc') || Request::is('quanphong/2_hc_nv') || Request::is('quanphong/2_hc_tk') || Request::is('quanphong/hc_dt_p') || Request::is('quanphong/jtable') ? 'class="active"' : '' ) !!}>
        <a href="#">
            <i class="livicon" data-name="list-ul" data-c="#418BCA" data-hc="#418BCA" data-size="18" data-loop="true"></i>
            <span class="title">Danh Sách Hóa Chất</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">

            <li {!! (Request::is('quanphong/2_ds_hc') ? 'class="active"' : '' ) !!}>
                <a href="{{ URL::to('quanphong/2_ds_hc') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Danh sách hóa chất
                </a>
            </li>
            <li {!! (Request::is('quanphong/2_hc_nv') ? 'class="active"' : '' ) !!}>
                <a href="{{ URL::to('quanphong/2_hc_nv') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Danh sách hóa chất nhập về
                </a>
            </li>
            <li {!! (Request::is('quanphong/2_hc_tk') ? 'class="active"' : '' ) !!}>
                <a href="{{ URL::to('quanphong/2_hc_tk') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Danh sách tồn kho
                </a>
            </li>

            <li {!! (Request::is('quanphong/2_hc_hl') ? 'class="active"' : '' ) !!}>
                <a href="{{ URL::to('quanphong/2_hc_hl') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Danh sách hỏng lỗi
                </a>
            </li>

        </ul>
    </li> -->
    <!-- <li {!! (Request::is('quanphong/2_hc_sd') ? 'class="active"' : '' ) !!}>
        <a href="{{  URL::to('quanphong/2_hc_sd') }}">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#F89A14" data-hc="#F89A14" data-loop="true"></i>
            Nhập Hóa Chất Sử Dụng
        </a>
    </li> -->
    <!-- <li {!! (Request::is('quanphong/2_hc_hongloi') ? 'class="active"' : '' ) !!}>
        <a href="{{  URL::to('quanphong/2_hc_hongloi') }}">
            <i class="livicon" data-name="doc-portrait" data-size="18" data-c="#F89A14" data-hc="#F89A14" data-loop="true"></i>
            Nhập Hóa Chất Hỏng, Lỗi
        </a>
    </li> -->
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
    <br>
    <br>
    @include('admin/layouts/menu')
</ul>
@endif