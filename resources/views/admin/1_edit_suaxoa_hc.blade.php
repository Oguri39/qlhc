@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Chỉnh Sửa Hóa Chất
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
    <h1>Quản Lý Hóa Chất</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Trang Chủ
            </a>
        </li>
        <li><a href="#"> Sủa Xóa Hóa Chất</a></li>
        <li class="active">chỉnh sửa hóa chất</li>
    </ol>
</section>
<section class="content pr-3 pl-3">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 my-3">
            <div class="card ">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">
                        <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                        Hóa Chất : <p class="user_name_max"></p>
                    </h3>
                    <span class="float-right clickable">
                        <i class="fa fa-chevron-up"></i>
                    </span>
                </div>
                <form action="" method="post">
                    <div class="card-body">
                        <!--main content-->
                        <!-- CSRF Token -->
                        @csrf


                        <div id="rootwizard">
                            <ul>
                                <li class="nav-item"><a href="#tab1" data-toggle="tab" class="nav-link">Chỉnh Sửa Hóa
                                        Chất</a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane " id="tab1">
                                    <h2 class="hidden">&nbsp;</h2>

                                    <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                        <div class="row"> <label for="last_name" class="col-sm-2 control-label">Tên Hóa Chất
                                                *</label>
                                            <div class="col-sm-10">
                                                <select id="last_name" onchange="hoaChat(value)" name="ma_danh_muc_hoa_chat" type="text" placeholder="Tên Hóa Chất" class="form-control required" value="">
                                                    <option value="{{$danhSach['hoaChatCu']->ma_danh_muc_hoa_chat}}">
                                                        {{$danhSach['hoaChatCu']->ten_hoa_chat}}
                                                    </option>
                                                    @foreach($danhSach['hoaChat'] as $ds)
                                                    @if($danhSach['hoaChatCu']->ma_danh_muc_hoa_chat !=
                                                    $ds->ma_danh_muc_hoa_chat)
                                                    <option value="{{$ds->ma_danh_muc_hoa_chat}}">{{$ds->ten_hoa_chat}}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>

                                                {!! $errors->first('last_name', '<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                        <div class="row">
                                            <label for="email" class="col-sm-2 control-label">Tên Nhà Thầu *</label>
                                            <div class="col-sm-10">
                                                <select id="email" onchange="noiSanXuat(value)" name="ma_cong_ty_cung_ung" placeholder="Tên Nhà Thầu" type="text" class="form-control required email" value="">
                                                    <option value="{{$danhSach['congTyCu']->ma_cong_ty_cung_ung}}">
                                                        {{$danhSach['congTyCu']->ten_cong_ty_cung_ung}}
                                                    </option>
                                                    @foreach($danhSach['congTy'] as $ds)
                                                    @if($danhSach['congTyCu']->ma_cong_ty_cung_ung !=
                                                    $ds->ma_cong_ty_cung_ung)
                                                    <option value="{{$ds->ma_cong_ty_cung_ung}}">
                                                        {{$ds->ten_cong_ty_cung_ung}}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                        <div class="row"> <label for="last_name" class="col-sm-2 control-label">Nước Sản
                                                Xuất
                                                *</label>
                                            <div class="col-sm-10">
                                                <select id="last_name" name="noi_san_xuat" type="text" placeholder="Nước Sản Xuất" class="form-control required" value="">
                                                    <option id="noisanxuat" value="{{$danhSach['congTyCu']->noi_san_xuat}}">
                                                        {{$danhSach['congTyCu']->noi_san_xuat}}
                                                    </option>

                                                </select>

                                                {!! $errors->first('last_name', '<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                        <div class="row"> <label for="last_name" class="col-sm-2 control-label">Số Lô
                                                *</label>
                                            <div class="col-sm-10">
                                                <input id="last_name" name="so_lo" type="number" placeholder="Số Lô" class="form-control required" value="{{$danhSach['lo']->so_lo}}">

                                                {!! $errors->first('last_name', '<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                        <div class="row"> <label for="last_name" class="col-sm-2 control-label">Đơn Vị Tính
                                                *</label>
                                            <div class="col-sm-10">
                                                <select id="last_name" name="don_vi_tinh" type="text" placeholder="Nước Sản Xuất" class="form-control required" value="">
                                                    <option id="donvitinh" value="{{$danhSach['hoaChatCu']->don_vi_tinh}}">
                                                        {{$danhSach['hoaChatCu']->don_vi_tinh}}
                                                    </option>
                                                </select>

                                                {!! $errors->first('last_name', '<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                        <div class="row"> <label for="last_name" class="col-sm-2 control-label">Số Lượng
                                                Tính
                                                *</label>
                                            <div class="col-sm-10">
                                                <input id="last_name" name="so_luong_tinh" type="number" min="0" placeholder="Số Lượng Tính" class="form-control required" value="{{$danhSach['lo']->so_luong_tinh}}" />

                                                {!! $errors->first('last_name', '<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                        <div class="row"> <label for="last_name" class="col-sm-2 control-label">Đơn Vị Đóng
                                                Gói
                                                *</label>
                                            <div class="col-sm-10">
                                                <select id="last_name" name="don_vi_dong_goi" type="number" placeholder="Nước Sản Xuất" class="form-control required" value="">
                                                    <option id="donvidonggoi" value="{{$danhSach['hoaChatCu']->don_vi_dong_goi}}">
                                                        {{$danhSach['hoaChatCu']->don_vi_dong_goi}}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                        <div class="row"> <label for="last_name" class="col-sm-2 control-label">Số Lượng Gói
                                                *</label>
                                            <div class="col-sm-10">
                                                <input id="last_name" name="so_luong_dong_goi" type="number" placeholder="Số Lượng Gói" min="0" class="form-control required" value="{{$danhSach['lo']->so_luong_dong_goi}}" />

                                                {!! $errors->first('last_name', '<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                        <div class="row"> <label for="last_name" class="col-sm-2 control-label">Hạn Sử Dụng
                                                *</label>
                                            <div class="col-sm-10">
                                                <input id="last_name" name="han_su_dung" type="date" placeholder="Hạn Sử Dụng" class="form-control required" value="{{$danhSach['lo']->han_su_dung}}" />

                                                {!! $errors->first('last_name', '<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <ul class="pager wizard">

                                    <li class="next finish" style="display:none;"><a class="btn btn-raised btn-success btn-large" href="{{ route('quankho.suaxoahc') }}">Quay Lại</a></li>
                                    <li class="next finish" style="display:none;"><button class="btn btn-raised btn-success btn-large" type="submit">Sửa</button></li>
                                </ul>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </form>
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
<!-- <script>
    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        var $state = $(
            '<span><img src="{{asset('
            img / countries_flags ')}}/' + state.element.value.toLowerCase() +
            '.png" class="img-flag" width="20px" height="20px" /> ' + state.text + '</span>'
        );
        return $state;

    }
    $(".country_field").select2({
        templateResult: formatState,
        templateSelection: formatState,
        placeholder: "select a country",
        theme: "bootstrap"
    });
</script>
<script>
    function noiSanXuat(id) {
        console.log(id);
        @foreach($danhSach['congTy'] as $ds)
        if ({
                {
                    $ds - > ma_cong_ty_cung_ung
                }
            } == id) {
            document.getElementById("noisanxuat").innerHTML = "{{$ds->noi_san_xuat}}";
            document.getElementById("noisanxuat").value = "{{$ds->noi_san_xuat}}";
        }
        @endforeach


    }

    function hoaChat(id) {
        @foreach($danhSach['hoaChat'] as $ds)
        if ({
                {
                    $ds - > ma_danh_muc_hoa_chat
                }
            } == id) {
            document.getElementById("donvitinh").innerHTML = "{{$ds->don_vi_tinh}}";
            document.getElementById("donvitinh").value = "{{$ds->ma_danh_muc_hoa_chat}}";
            document.getElementById("donvidonggoi").innerHTML = "{{$ds->don_vi_dong_goi}}";
            document.getElementById("donvidonggoi").value = "{{$ds->don_vi_dong_goi}}";
        }
        @endforeach
    }
</script> -->
@stop