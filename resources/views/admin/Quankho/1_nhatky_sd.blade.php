@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Nhật Ký Sử Dụng
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/buttons.bootstrap4.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/colReorder.bootstrap4.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/rowReorder.bootstrap4.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/scroller.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/tables.css') }}" />
<link href="{{ asset('vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/clockface/css/clockface.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" type="text/css" />
<style>
    #table1_filter {
        margin-bottom: 10px;
    }

    .nav-item {
        margin-bottom: 10px;
        margin-left: 0px !important;
    }
</style>
@stop

{{-- Page content --}}
@section('content')

<section class="content-header">

    <!--section starts-->
    <h1>Nhật Ký Báo Cáo Muộn</h1>
    <ol class="breadcrumb">
        <li>
            <a href="#.{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                Nhật Ký Sử Dụng
            </a>
        </li>

    </ol>
</section>
<!--section ends-->
<section class="content pl-3 pr-3">
    <div class="row">
        <div class="col-lg-12 my-3">
            <div class="card filterable">
                <div class="card-header bg-primary text-white clearfix  ">
                    <div class="float-left">
                        <div class="caption">
                            <i class="livicon" data-name="calendar" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                            Danh Sách Phòng Xét Nghiệm
                        </div>
                    </div>

                </div>
                <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">
                    <div class="card-body">
                        <!-- <form id="commentForm" method="post" action=""> -->
                        <div id="rootwizard">
                            <ul class="nav nav-pills">
                                @if($phong)
                                <li class="nav-item">
                                    <a href="#tab{{$phong[0]['phong']->ma_phong}}" data-toggle="tab" class="nav-link color_accrd">{{$phong[0]['phong']->ten_phong}} <span class="badge badge-danger event_count">{{$phong[0]['thongBao']}}</span></a>
                                </li>
                                @endif
                                @foreach($phong as $p)
                                {{logger($phong)}}
                                @endforeach
                            </ul>
                            <!-- <div class="float-right">
                                    <div class="caption">
                                        <button type="submit" class="btn btn-primary btn-sm hvr-wobble-vertical"
                                            value="gửi">Xác Nhận</button>
                                    </div>
                                </div> -->
                            <div class="tab-content">
                                @foreach($danhSach as $ds)
                                <div class="tab-pane {{$ds['abc']}}" id="tab{{$ds['phong']->ma_phong}}">
                                    <form id="commentForm" method="post" action="1_nhatky_sd/{{$ds['phong']->ma_phong}}">
                                        @csrf
                                        <div class="float-right">
                                            <div class="caption">
                                                <input type="hidden" name="ma_phong" value="{{$ds['phong']->ma_phong}}">
                                                <button type="submit" class="btn btn-primary btn-sm hvr-wobble-vertical" value="gửi">Xác Nhận</button>
                                            </div>
                                        </div>
                                    </form>
                                    <table style="text-align: center;" class="table table-striped table-bordered" id="table1" width="100%">
                                        <thead>
                                            <tr>

                                                <th>STT</th>
                                                <th>Mã Hóa Chất</th>
                                                <th>Tên Hóa Chất</th>
                                                <th>Số Lô</th>
                                                <th>Phòng</th>
                                                <th>Số Lượng <br> Sử Dụng Trong Ngày</th>
                                                <th>Số Lượng <br> Sử Dụng Còn Lại</th>
                                                <th>Ngày Cập Nhập
                                                    <br>(Năm-Tháng-Ngày)
                                                </th>
                                                <th>Trạng Thái</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ds['hoaChatPhong'] as $hoaChat)


                                            <tr>
                                                <td>
                                                    {{$hoaChat['stt']}}
                                                </td>
                                                <td>
                                                    {{$hoaChat['hoachat']->ma_danh_muc_hoa_chat}}
                                                </td>
                                                <td>
                                                    {{$hoaChat['hoachat']->ten_hoa_chat}}
                                                </td>
                                                <td>
                                                    {{$hoaChat['lo']->so_lo}}
                                                </td>
                                                <td>
                                                    {{$hoaChat['phong']->ten_phong}}
                                                </td>
                                                <td>
                                                    {{$hoaChat['nhatky']->so_luong_su_dung_trong_ngay}}
                                                </td>
                                                <td>
                                                    {{$hoaChat['nhatky']->so_luong_con_lai}}
                                                </td>
                                                <td>{{$hoaChat['nhatky']->ngay_cap_nhap}}</td>
                                                @if($hoaChat['nhatky']->xac_nhan_cua_quan_kho == 0)
                                                <td>
                                                    <form id="commentForm" method="post" action="1_nhatky_sd/{{$ds['phong']->ma_phong}}">
                                                        @csrf
                                                        <div class="caption">
                                                            <input type="hidden" name="ma_phong" value="{{$hoaChat['phong']->ma_phong}}">
                                                            <input type="hidden" name="ma_nhat_ky_su_dung_hoa_chat" value="{{$hoaChat['nhatky']->ma_nhat_ky_su_dung_hoa_chat}}">
                                                            <button type="submit" class="btn btn-primary btn-sm" value="gửi">Xác Nhận</button>
                                                        </div>
                                                    </form>
                                                </td>
                                                @else
                                                <td>
                                                    <div class="caption">
                                                        <button type="submit" class="btn btn-secondary">Đã Xác Nhận</button>
                                                    </div>
                                                </td>
                                                @endif
                                                <!-- <td id="Nhap" class="money">
                                        6000
                                    </td>
                                    <td>
                                        500
                                    </td> -->
                                            </tr>

                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                @endforeach
                                <div class="tab-pane" id="tab2">
                                    <table class="table table-striped table-bordered" id="table1" width="100%">
                                        <thead>
                                            </tbody>
                                    </table>
                                </div>
                                <div id="myModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">User Register</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <p>You Submitted Successfully.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- row-->

</section>
<!-- content -->

@stop

{{-- page level scripts --}}
@section('footer_scripts')

<script type="text/javascript" src="{{ asset('vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/jeditable/js/jquery.jeditable.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.bootstrap4.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.buttons.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.colReorder.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.responsive.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.rowReorder.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/buttons.colVis.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/buttons.html5.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/buttons.bootstrap4.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/pdfmake.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.scroller.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/pages/table-advanced.js') }}"></script>

<script src="{{ asset('vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/clockface/js/clockface.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/pages/datepicker.js') }}" type="text/javascript"></script>
@stop