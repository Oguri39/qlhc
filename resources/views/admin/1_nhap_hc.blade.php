@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Phân Hóa Chất
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
<link type="text/css" href="{{ asset('vendors/bootstrap-multiselect/css/bootstrap-multiselect.css') }}" rel="stylesheet" />
<link href="{{ asset('vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" />
<link href="{{ asset('vendors/selectize/css/selectize.css') }}" rel="stylesheet" />
<link href="{{ asset('vendors/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" />
<link href="{{ asset('vendors/iCheck/css/all.css') }}" rel="stylesheet" />
<link href="{{ asset('vendors/iCheck/css/line/line.css') }}" rel="stylesheet" />
<link href="{{ asset('vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" />
<link href="{{ asset('vendors/switchery/css/switchery.css') }}" rel="stylesheet" />
<link href="{{ asset('css/pages/formelements.css') }}" rel="stylesheet" />
<link href="{{ asset('vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/iCheck/css/all.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/pickadate/css/default.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/pickadate/css/default.date.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/pickadate/css/default.time.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/airDatepicker/css/datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/flatpickr/css/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/pages/adv_date_pickers.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link rel="stylesheet" href="{{asset('vendors/datatables/css/buttons.bootstrap4.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/select2/css/select2.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/select2/css/select2-bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/tables.css') }}" />

<link rel="stylesheet" href="{{ asset('vendors/animate/animate.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/hover/css/hover-min.css') }}" />
<link href="{{ asset('css/pages/transitions.css') }}" rel="stylesheet" />
<link href="{{ asset('vendors/pickadate/css/default.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/pickadate/css/default.date.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/pickadate/css/default.time.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/airDatepicker/css/datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/flatpickr/css/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/pages/adv_date_pickers.css') }}" rel="stylesheet" type="text/css" />
<style>
    #table1_filter {
        margin-bottom: 10px;
    }
</style>
<style>
    .container {
        margin-top: 20px;
    }

    .image-preview-input {
        position: relative;
        overflow: hidden;
        margin: 0px;
        color: #333;
        background-color: #fff;
        border-color: #ccc;
    }

    .image-preview-input input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        padding: 0;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        filter: alpha(opacity=0);
    }

    .image-preview-input-title {
        margin-left: 2px;
    }

    .image_radius {
        border-top-right-radius: 4px !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 4px !important;
    }

    .fileinput .thumbnail>img {
        width: 100%;
    }

    .color_a {
        color: #333;
    }

    .btn-file>input {
        width: auto;
    }
</style>

@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
    <!--section starts-->
    <h1>Quản Lý Hóa Chất</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                Trang Chủ
            </a>
        </li>
        <li>
            <a href="#">Nhập Hóa Chất</a>
        </li>
    </ol>
</section>
<!--section ends-->
<section class="content pl-3 pr-3">

    <div class="row">
        <div class="col-lg-12 my-3">
            <div class="card filterable" style="overflow:auto;">
                <div class="card-header bg-success text-white clearfix  ">
                    <div class="float-left">
                        <a class="btn btn-raised btn-primary btn-large hvr-wobble-horizontal" data-toggle="modal" data-href="#stack1" href="#stack1">Thêm mới hóa chất</a>
                    </div>
                    <div class="float-right">
                        <a class="btn btn-raised btn-primary btn-large hvr-wobble-vertical" data-toggle="modal" data-href="#stack1" href="#stack2">Nhập từ excel </a>
                        <button type="button" class="btn btn-primary btn-sm hvr-wobble-vertical" id="addButton">Thêm
                            dòng</button>
                        <button type="button" class="btn btn-danger btn-sm hvr-wobble-vertical" id="delButton">Xoá
                            dòng</button>
                    </div>
                </div>
                <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">
                    <form class="form-horizontal" method="post" action="1_nhap_hc">
                        @csrf
                        <table class="table table-bordered table-responsive display nowrap" id="table3" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col" style="width:600px !important">Tên Hóa Chất</th>
                                    <th scope="col">Số Lô</th>
                                    <th scope="col">Nơi Sản Xuất - cung cấp</th>

                                    <th scope="col">Số Lượng Nhập Kho</th>
                                    <th scope="col">Số lượng đóng gói</th>
                                    <th scope="col">Ngày Hết Hạn</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <select size="1" id="row-1-name" name="idDanh_muc_hoa_chat[0]">
                                            <option>Select value...</option>
                                            @foreach($danhMucHoaChat as $danhMuc)
                                            <option value="{{$danhMuc['maHoaChat']}}">{{$danhMuc['tenHoaChat']}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input style="width:100px !important" type="number" class="form-control" id="row-1-number" name="so_lo[0]" value="0" min="1">
                                    </td>
                                    <td>
                                        <select size="1" min="0" id="row-1-SanXuat" name="idCong_ty_cung_ung[0]">
                                            <option>Select value...</option>
                                            @foreach($congTyCungUng as $danhMuc)

                                            <option value="{{$danhMuc['maSanXuat']}}">{{$danhMuc['congTyCungUng']}} - {{$danhMuc['maSanXuat']}}</option>

                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input style="width:150px !important" type="number" class="form-control" id="row-1-number" name="so_luong[0]" value="0" min="1">
                                    </td>
                                    <td>
                                        <input style="width:150px !important" type="number" class="form-control" id="row-1-number" name="so_luong_dong_goi[0]" value="0" min="1">
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input name="han_su_dung[0]" class=" form-control" type="date" min="0" placeholder="Chọn ngày">
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <div class="form-position">
                            <div class="row">
                                <div class="col-md-3 ">
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-info hvr-wobble-horizontal">
                                        <a href="./">Quay Về</a>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-position">
                            <div class="row">
                                <div class="col-md-12  col-sm-12 col-12  col-lg-12 text-right">
                                    <button type="submit" class="btn btn-primary btn-sm hvr-wobble-horizontal">Gửi </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-example-modal-sm in" id="stack1" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm Hóa Chất Mới</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="form-horizontal" method="post" action="1_them_hc">
                    @csrf
                    <div class="row">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 col-lg-3 col-12 control-label" for="name">Tên Hóa
                                        Chất</label>
                                    <div class="col-md-9 col-lg-9 col-12">
                                        <input id="name" name="ten_hoa_chat" type="text" placeholder="Tên Hóa Chất" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 col-lg-3 col-12 control-label" for="name">Mã Hóa
                                        Chất</label>
                                    <div class="col-md-9 col-lg-9 col-12">
                                        <input id="name" name="ma_hoa_chat" type="number" placeholder="Mã" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 col-lg-3 col-12 control-label" for="name">Đơn
                                        Vị</label>
                                    <div class="col-md-9 col-lg-9 col-12">
                                        <input id="name" name="don_vi_tinh" type="text" placeholder="Đơn vị" class="form-control " required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 col-lg-3 col-12 control-label" for="name">Quy
                                        Cách Đóng
                                        Gói</label>
                                    <div class="col-md-9 col-lg-9 col-12">
                                        <input id="name" name="don_vi_dong_goi" type="text" placeholder="đơn vị đóng gói" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 col-lg-3 col-12 control-label" for="name">Nước Sản Xuất</label>
                                    <div class="col-md-9 col-lg-9 col-12">
                                        <input id="name" name="noi_san_xuat" type="text" placeholder="Nước sản xuất" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label class="col-md-3 col-lg-3 col-12 control-label" for="name">Công Ty
                                        Cung
                                        Ứng</label>
                                    <div class="col-md-9 col-lg-9 col-12">
                                        <input id="name" name="ten_cong_ty_cung_ung" type="text" placeholder="Công Ty Cung Ứng" class="form-control" required>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Thoát </button>
                        <div class="form-position">
                            <div class="row">
                                <div class="col-md-12  col-sm-12 col-12  col-lg-12 text-right">
                                    <button type="submit" class="btn btn-primary btn-sm hvr-wobble-horizontal">Gửi </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-sm in" id="stack2" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm file </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form action="ecxelupload" method="post" enctype="multipart/form-data">
                    @csrf
                    Select ecxel to upload:
                    <!-- <input type="file" name="newfile" id="fileToUpload">
                    <input type="submit" value="Upload" name="submit"> -->
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-3 col-lg-3 col-12 control-label" for="upload">File
                                Upload</label>
                            <div class="col-md-9 col-12 col-lg-9">
                                <div class="input-group image-preview">
                                    <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                    <!-- don't give a name === doesn't send on POST/GET -->
                                    <span class="input-group-btn">
                                        <!-- image-preview-clear button -->
                                        <button type="button" class="btn btn-secondary image-preview-clear" style="display:none; border-radius:0 !important; border: 1px solid rgba(0, 0, 0, 0.16);">
                                            <span class="fa  fa-times"></span> Clear
                                        </button>
                                        <!-- image-preview-input -->
                                        <div class="btn btn-secondary image_radius image-preview-input" style="margin-left:-3px;">
                                            <span class="fa fa-folder-open"></span>
                                            <span class="image-preview-input-title">Browse</span>
                                            <input type="file" id="fileToUpload" name="newfile"> <!-- rename it -->
                                        </div>
                                    </span>
                                </div><!-- /input-group image-preview [TO HERE]-->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Thoát </button>
                        <div class="form-position">
                            <div class="row">
                                <div class="col-md-12  col-sm-12 col-12  col-lg-12 text-right">
                                    <button type="submit" class="btn btn-primary btn-sm hvr-wobble-horizontal">Gửi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


@stop

{{-- page level scripts --}}
@section('footer_scripts')

<script type="text/javascript" src="{{ asset('vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.bootstrap4.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.responsive.js') }}"></script>
<!--<script type="text/javascript" src="{{asset('vendors/datatables/js/buttons.bootstrap4.js')}}"></script>-->
<script type="text/javascript" src="{{ asset('vendors/select2/js/select2.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/pages/table-advanced2.js') }}"></script>
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
<script language="javascript" type="text/javascript" src="{{ asset('vendors/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
<script language="javascript" type="text/javascript" src="{{ asset('vendors/select2/js/select2.js') }}"></script>
<script language="javascript" type="text/javascript" src="{{ asset('vendors/select2/js/select2.js') }}"></script>

<script language="javascript" type="text/javascript" src="{{ asset('vendors/sifter/sifter.js') }}"></script>
<script language="javascript" type="text/javascript" src="{{ asset('vendors/microplugin/microplugin.js') }}"></script>
<script language="javascript" type="text/javascript" src="{{ asset('vendors/selectize/js/selectize.min.js') }}">
</script>
<script language="javascript" type="text/javascript" src="{{ asset('vendors/iCheck/js/icheck.js') }}"></script>
<script language="javascript" type="text/javascript" src="{{ asset('vendors/bootstrap-switch/js/bootstrap-switch.js') }}"></script>
<script language="javascript" type="text/javascript" src="{{ asset('vendors/switchery/js/switchery.js') }}"></script>
<script language="javascript" type="text/javascript" src="{{ asset('vendors/bootstrap-maxlength/js/bootstrap-maxlength.js') }}"></script>
<script language="javascript" type="text/javascript" src="{{ asset('vendors/card/js/jquery.card.js') }}"></script>
<script language="javascript" type="text/javascript" src="{{ asset('js/pages/custom_elements.js') }}"></script>

<!-- <script>
    $('table.table3').DataTable({
        responsive: true,
    });
    $(document).ready(function() {
        $('#table3').DataTable({
            scrollX: true,
        });
    });
</script> -->

<script type="text/javascript">
    $(document).ready(function() {
        //table tools example
        $('#table1').DataTable({
            dom: '<"m-t-10"B><"m-t-10 pull-left"f><"m-t-10 pull-right"l>rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        });

        // add row, delete row example
        var table3 = $('#table3').DataTable({
            order: [
                [0, 'desc']
            ],
            responsive: true,
            "language": {
                "emptyTable": "Không có bản ghi phù hợp",
                "sLengthMenu": "Hiển thị _MENU_ bản ghi",
                "search": "Tìm kiếm:",
                "info": "Đang hiển thị trang _START_ của _END_ trong số _TOTAL_ mục",
                "paginate": {
                    "previous": "Trang trước",
                    "next": "Trang sau"
                }
            },
            "lengthMenu": [
                [-1],
                ["All"]
            ],
        });
        //total number of existing rows
        var counter = 2;

        //row addition code
        $('#addButton').on('click', function() {
            table3.row
                .add([
                    counter,
                    '<select size="1" id="row-' + counter + '-name" name="idDanh_muc_hoa_chat[' + (counter - 1) + ']"><option value="">Select value...</option>@foreach($danhMucHoaChat as $danhMuc)<option value="{{$danhMuc['
                    maHoaChat ']}}">{{$danhMuc['
                    tenHoaChat ']}}</option>@endforeach</select>',
                    '<input type="number" class="form-control" id="row-' + counter + '-number" name="so_lo[' + (counter - 1) + ']"value="0" min = "1">',
                    '<select size="1" id="row-' + counter + '-DongGoi" name="idCong_ty_cung_ung[' + (counter - 1) + ']"><option value="">Select value...</option> @foreach($congTyCungUng as $danhMuc)<option value="{{$danhMuc['
                    maSanXuat ']}}">{{$danhMuc['
                    congTyCungUng ']}} - {{$danhMuc['
                    maSanXuat ']}}</option>@endforeach</select>',
                    '<input type="number" class="form-control" id="row-1-number" name="so_luong[' + (counter - 1) + ']"value="0"min="1">',
                    '<input type="number" class="form-control" id="row-1-number" name="so_luong_dong_goi[' + (counter - 1) + ']"value="0"min="1">',
                    '<div class="input-group"><input class="flatpickr flatpickr-input form-control" type="date" name="han_su_dung[' + (counter - 1) + ']" placeholder="Chọn ngày"></div>',
                ])
                .draw();
            $("#row-" + counter + "-name").select2();
            $("#row-" + counter + "-DongGoi").select2();
            $("#row-" + counter + "-DonVi").select2();
            $("#row-" + counter + "-SanXuat").select2();
            $("#row-" + counter + "-CungCap").select2();
            counter++;
        });

        //row deletion code

        $('#table3 tbody').on('click', 'tr', function() {
            if ($(this).hasClass('danger')) {
                $(this).removeClass('danger');
            } else {
                table3.$('tr.danger').removeClass('danger');
                $(this).addClass('danger');
            }
        });

        $('#delButton').click(function() {
            if (!$('#table3 tr').hasClass('danger')) {
                alert('please select a row first');
                //exit;
            }
            table3
                .row('.danger')
                .remove()
                .draw(false);
            counter--;
        });
    });
    $('#sample_5').dataTable({
        scrollY: '200px',
        dom: 'frtiS',
        deferRender: true,
        responsive: true,
    });
    $(document).ready(function() {
        var oTable;
        /* Apply the jEditable handlers to the table */
        $('#inline_edit tbody td').editable(
            function(sValue) {
                /* Get the position of the current data from the node */
                var aPos = oTable.fnGetPosition(this);

                /* Get the data array for this row */
                var aData = oTable.fnGetData(aPos[0]);

                /* Update the data array and return the value */
                aData[aPos[1]] = sValue;
                return sValue;
            }, {
                onblur: 'submit'
            }
        );
        /* Submit the form when bluring a field */

        /* Init DataTables */
        oTable = $('#inline_edit').dataTable();
    });
</script>
<script src="{{ asset('vendors/pickadate/js/picker.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/pickadate/js/picker.date.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/pickadate/js/picker.time.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/flatpickr/js/flatpickr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/airDatepicker/js/datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/airDatepicker/js/datepicker.en.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/pages/custom_datepicker.js') }}" type="text/javascript"></script>

<script>
    $(document).on('click', '#close-preview', function() {
        $('.image-preview').popover('hide');
        // Hover befor close the preview
        $('.image-preview').hover(
            function() {
                $('.image-preview').popover('show');
            },
            function() {
                $('.image-preview').popover('hide');
            }
        );
    });

    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type: "button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class", "close float-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger: 'manual',
            html: true,
            title: "<strong>Preview</strong>" + $(closebtn)[0].outerHTML,
            content: "There's no image",
            placement: 'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function() {
            $('.image-preview').attr("data-content", "").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("Browse");
        });
        // Create the preview image
        $(".image-preview-input input:file").change(function() {
            var img = {
                id: 'dynamic',
                width: 250,
                height: 200
            };
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function(e) {
                $(".image-preview-input-title").text("Change");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
            }
            reader.readAsDataURL(file);
        });
    });
</script>


@stop