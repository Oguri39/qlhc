@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Trang Chủ
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

<!--hiệu ứng các nút -->
<link rel="stylesheet" href="{{ asset('vendors/animate/animate.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/hover/css/hover-min.css') }}" />
<link href="{{ asset('css/pages/transitions.css') }}" rel="stylesheet" />

<style type="text/css">
    table,
    th,
    td {
        border: 1px solid #ccc;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        text-align: left;
        padding: 10px;
    }

    tr:hover {
        background-color: #ddd;
        cursor: pointer;
    }
</style>

<style>
    #table1_filter {
        margin-bottom: 10px;
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
                <i class="livicon" id="load" data-name="home" data-size="14" data-loop="true"></i>
                Trang Chủ
            </a>
        </li>
    </ol>
</section>

<!-- Main content -->
<section id="abc" class="content pr-3 pl-3">

    <!---Layout_1 Gồm có bảng cảnh báo vào biểu đồ cột-->
    @include('admin.Quankho.index.layout_1')

    <!--Các bảng-->
    <div class="row">
        <div class="col-lg-12 my-3">
            <div class="card filterable">
                <div class="card-header bg-success text-white clearfix  ">
                    <div class="float-left">
                        <div class="caption">
                            <!-- <i class="livicon" data-name="camera" data-size="16" data-loop="true" data-c="#fff"
                                data-hc="white"></i> -->
                            Danh Sách Hóa Chất
                        </div>
                    </div>

                </div>

                <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">
                    <ul class="nav nav-tabs">
                        <li class=" nav-item ">
                            <a id="danhSachHoaChat" href="#tab_1" data-toggle="tab" class="nav-link active ">Danh Sách Hóa Chất</a>
                        </li>
                        <li class="nav-item">
                            <a id="danhSachNhapKho" href="#tab_2" data-toggle="tab" class="nav-link">Danh Sách Nhập Kho</a>
                        </li>
                        <li class="nav-item">
                            <a id="danhSachTonKho" href="#tab_3" data-toggle="tab" class="nav-link">Danh Sách Tồn Kho</a>
                        </li>
                        <li class="nav-item">
                            <a id="danhSachDuTru" href="#tab_4" data-toggle="tab" class="nav-link">Danh Sách Dự Trù</a>
                        </li>
                        <li class=" nav-item ml-auto">
                            <a href="#" class="text-muted nav-link">
                                <i class="fa fa-gear"></i>
                            </a>
                        </li>
                        <li class="float-right">
                            <a class="btn btn-primary btn-sm " href="quankho/1_nhap_hc">Nhập kho</a>
                            <a class="btn btn-primary btn-sm btn-large " data-toggle="modal" data-href="#full-width" href="#full-width">Phân kho</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="slim2">
                        <div class="tab-pane active" id="tab_1">
                            <!--Danh sách hóa chất bảng 1 -->
                            <table class="table table-striped table-bordered" id="table1" width="100%">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã hóa chất</th>
                                        <th>Tên hóa chất</th>
                                        <th>Phòng sử dụng</th>
                                        <th>Số lô</th>
                                        <th>Hạn sử dụng</th>
                                        <th>Đơn vị tính (ĐVT)</th>
                                        <th>ĐV đóng gói (ĐVĐG)</th>
                                        <th>SL đóng gói</th>
                                        <th>SL nhập trong tháng (ĐVT)</th>
                                        <th>SL hiện Có (ĐVT)</th>
                                        <th>SL đã sử dụng (ĐVT)</th>
                                        <th>SL hỏng(ĐVT)</th>
                                    </tr>
                                </thead>
                                <tbody id="nhap">

                                    @foreach($danhSachHoaChat as $danhSach)
                                    {{ logger($danhSach) }}

                                    <tr onclick="loadBieuDo({{ $danhSach['suDung']->ma_su_dung_hoa_chat }})">
                                        <td>
                                            {{ $danhSach['stt'] }}
                                        </td>
                                        <td>
                                            {{ $danhSach['hoaChat']->ma_danh_muc_hoa_chat }}
                                        </td>
                                        <td>
                                            {{ $danhSach['hoaChat']->ten_hoa_chat }}
                                        </td>
                                        <td>
                                            {{ $danhSach['phong']->ten_phong }}
                                        </td>
                                        <td>
                                            {{ $danhSach['lo']->so_lo }}
                                        </td>
                                        <td>
                                            {{ $danhSach['lo']->han_su_dung }}
                                        </td>
                                        <td>
                                            {{ $danhSach['hoaChat']->don_vi_tinh }}
                                        </td>
                                        <td>
                                            {{ $danhSach['lo']->so_luong_dong_goi }}
                                        </td>
                                        <td>
                                            {{ $danhSach['hoaChat']->don_vi_dong_goi }}
                                        </td>
                                        <td>
                                            <P class="money">{{ $danhSach['suDung']->so_luong_hoa_chat_nhap_ve }}</P>
                                        </td>
                                        <td>
                                            <P class="money">{{ $danhSach['tonKho'] }}</P>
                                        </td>
                                        <td>
                                            <P class="money">{{ $danhSach['soLuongDungTrongThang'] }}</P>
                                        </td>
                                        <td>
                                            <P class="money">{{ $danhSach['loi'] }}</P>
                                        </td>
                                    </tr>
                                    @endforeach



                                </tbody>
                            </table>

                        </div>
                        <!--End tab1-->


                        <div class="tab-pane" id="tab_2">
                            <!--danh sách nhập kho-->

                            <div class="float-right">
                                <div style="margin: 10px 0;">
                                    <a id="danhsach" class="btn btn-primary btn-sm" href="quankho/1_suaxoa_hc">Sửa Xóa</a>
                                </div>
                            </div id="nhapkho">
                            <table class="table table-striped table-bordered" id="table6" width="100%">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã hóa chất</th>
                                        <th>Tên hóa chất</th>
                                        <th>Tên nhà thầu</th>
                                        <th>Nước sản xuất</th>
                                        <th>Số lô</th>
                                        <th>Hạn sử dụng</th>
                                        <th>Đơn vị tính (ĐVT)</th>
                                        <th>SL tính</th>
                                        <th>ĐV đóng gói (ĐVĐG)</th>
                                        <th>SL đóng gói</th>
                                        <!-- <th>SL nhập trong tháng (ĐVT)</th> -->
                                    </tr>
                                </thead>
                                <tbody id="nhap2">
                                    @foreach( $nhapKho as $danhSachNhapKho)
                                    {{ logger($nhapKho) }}
                                    <tr>
                                        <td>
                                            {{$danhSachNhapKho['stt']}}
                                        </td>
                                        <td>
                                            {{$danhSachNhapKho['maHoaChat']}}
                                        </td>
                                        <td>
                                            {{$danhSachNhapKho['tenHoaChat']}}
                                        </td>
                                        <td>
                                            {{$danhSachNhapKho['tenNhaThau']}}
                                        </td>
                                        <td>
                                            {{$danhSachNhapKho['nuocSanXuat']}}
                                        </td>
                                        <td>
                                            {{$danhSachNhapKho['soLo']}}
                                        </td>
                                        <td>
                                            {{$danhSachNhapKho['donViTinh']}}
                                        </td>
                                        <td>
                                            {{$danhSachNhapKho['soLuongTinh']}}
                                        </td>

                                        <td>
                                            {{$danhSachNhapKho['donViDongGoi']}}

                                        </td>
                                        <td>
                                            <P class="money">{{$danhSachNhapKho['soLuongDongGoi']}}</P>

                                        </td>
                                        <td>
                                            {{$danhSachNhapKho['hanSuDung']}}
                                        </td>

                                    </tr>

                                    @endforeach


                                </tbody>
                            </table>

                        </div>
                        <!--End tab2-->

                        <div class="tab-pane" id="tab_3">
                            <!--danh sách tồn kho-->
                            <form method="post" action="quankho/dstonkho">
                                @csrf
                                <!-- <div class="float-right">
                                    <button type="submit" class="btn btn-primary btn-sm" value="gửi">Đặt Mức Độ Cảnh Báo</button>
                                </div> -->
                                <!--Danh sách tồn kho bảng 3-->
                                <table class="table table-striped table-bordered" id="table5" width="100%">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Mã hóa chất</th>
                                            <th>Tên hóa chất</th>
                                            <th>Tên nhà thầu</th>
                                            <th>Nước sản xuất</th>
                                            <th>Số lô</th>
                                            <th>Đơn vị tính (ĐVT)</th>
                                            <th>ĐV đóng gói (ĐVĐG)</th>
                                            <th>SL đóng gói</th>
                                            <!-- <th>SL dư trong kho (ĐVT)</th> -->
                                            <th>SL đã phân về các phòng (ĐVT)</th>
                                            <!-- <th>SL đã sử dụng (ĐVT)</th> -->
                                            <!-- <th>Hạn mức dự trù</th> -->
                                        </tr>
                                    </thead>
                                    <tbody id="nhap3">

                                        @foreach($danhSachTonKho as $danhSach)
                                        {{ logger($danhSach) }}
                                        <tr>
                                            <td>
                                                {{$danhSach['stt']}}
                                            </td>
                                            <td>
                                                {{$danhSach['hoaChat']->ma_danh_muc_hoa_chat}}
                                            </td>
                                            <td>
                                                {{$danhSach['hoaChat']->ten_hoa_chat}}
                                            </td>
                                            <td>
                                                {{$danhSach['congTy']->ten_cong_ty_cung_ung}}
                                            </td>
                                            <td>
                                                {{$danhSach['congTy']->noi_san_xuat}}
                                            </td>
                                            <td>
                                                {{$danhSach['lo']->so_lo}}
                                            </td>
                                            <td>
                                                {{$danhSach['hoaChat']->don_vi_tinh}}
                                            </td>
                                            <td>
                                                {{$danhSach['hoaChat']->don_vi_dong_goi}}
                                            </td>

                                            <td>
                                                <P class="money">{{$danhSach['lo']->so_luong_dong_goi}}</P>
                                            </td>
                                            <td>{{$danhSach['soLuongHienCo']}}</td>
                                            <!-- <td>{{$danhSach['lo']->so_luong_dong_goi -$danhSach['soLuongHienCo']}}</td> -->
                                            <!-- <td>
                                                <input type="number" class=" form-control" id="row-1-number" name="list[{{$danhSach['lo']->ma_lo_hoa_chat}}]" value="{{$danhSach['lo']->nguong_canh_bao}}" min="0">
                                                <input type="hidden" name="check[]" value="{{$danhSach['lo']->ma_lo_hoa_chat}}">
                                            </td> -->
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        <!--End tab3-->

                        <div class="tab-pane" id="tab_4">
                            <!--Gửi của danh sách dự trù-->
                            <form method="post" action="">
                                <!-- <div class="float-right">
                                    <button class="btn btn-primary btn-sm" value>Xác Nhận</button>
                                </div> -->
                                <!--Danh sách hóa chất dự trù bảng 4-->
                                <table class="table table-striped table-bordered" id="table4" width="100%">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên Hóa Chất</th>
                                            <th>ĐV tính (ĐVT)</th>
                                            <th>ĐV đóng gói (ĐVĐG)</th>
                                            <th>SL dự trù (ĐVT)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="nhap4">
                                        @foreach($danhSachDuTru as $ds)
                                        <tr>
                                            <td>
                                                {{$ds['stt']}}
                                            </td>
                                            <td>
                                                {{$ds['hoaChat']->ma_danh_muc_hoa_chat}}
                                            </td>
                                            <td>
                                                {{$ds['hoaChat']->ten_hoa_chat}}
                                            </td>
                                            <td>
                                                {{$ds['hoaChat']->don_vi_tinh}}
                                            </td>
                                            <td>
                                                <input type="number" class=" form-control" id="row-1-number" name="row-1-number" value="{{$ds['baoCao']->so_luong_du_tru}}" min="0">

                                            </td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        <!--End tab4-->
                    </div>
                    <!--End tab-->
                </div>
            </div>
        </div>
    </div>

    <!--Các thẻ modals như phân kho, sửa xóa, dự trù mới-->
    @include('admin.Quankho.index.modals_trangchu')
</section>
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


<script type="text/javascript">
    $(document).ready(function() {
        //table tools example
        $('#table1').DataTable({
            dom: '<"m-t-10"B><"m-t-10 pull-left"f><"m-t-10 pull-right"l>rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
                [5, 10, 20, -1],
                [5, 10, 20, "All"]
            ],
        });

        //re-order columns
        var table2 = $('#table2').dataTable({
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

        new $.fn.dataTable.ColReorder(table2);
        //re-order rows
        $('#rowReorder').DataTable({
            rowReorder: true,
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
                [3, 10, 20, -1],
                [3, 10, 20, "All"]
            ],
        });
        //total number of existing rows
        var counter = 18;

        //row addition code
        $('#addButton').on('click', function() {
            table3.row
                .add([
                    counter,
                    counter + ' new',
                    counter + ' user',
                    counter + '_unique_user',
                    counter + '_unique_user@domain.com',
                ])
                .draw();

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

<!--All checkbox-->
<script type="text/javascript">
    $(document).ready(function() {

        $('#selecctall').click(function(event) { //on click
            if (this.checked) { // check select status
                $('.checkbox1').each(function() { //loop through each checkbox
                    this.checked = true; //select all checkboxes with class "checkbox1"            
                });

            } else {
                $('.checkbox1').each(function() { //loop through each checkbox
                    this.checked =
                        false; //deselect all checkboxes with class "checkbox1"                    
                });
            }
        });

    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        //table tools example
        $('#table6').DataTable({
            dom: '<"m-t-10"B><"m-t-10 pull-left"f><"m-t-10 pull-right"l>rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
                [5, 10, 20, -1],
                [5, 10, 20, "All"]
            ],
        });
        $('#table5').DataTable({
            dom: '<"m-t-10"B><"m-t-10 pull-left"f><"m-t-10 pull-right"l>rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
        $('#table4').DataTable({
            dom: '<"m-t-10"B><"m-t-10 pull-left"f><"m-t-10 pull-right"l>rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
                [5, 10, 20, -1],
                [5, 10, 20, "All"]
            ],
        });

    });
</script>


<script type="text/javascript">
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

<!--Format số hàng nghìn có thêm dấu-->
<script type="text/javascript" src="{{ asset('js/format/jquery-3.2.1.slim.min.js') }}"></script>
<script type="text/javascript">
    (function($) {
        $.fn.simpleMoneyFormat = function() {
            this.each(function(index, el) {
                var elType = null; // input or other
                var value = null;
                // get value
                if ($(el).is('input') || $(el).is('textarea')) {
                    value = $(el).val().replace(/,/g, '');
                    elType = 'input';
                } else {
                    value = $(el).text().replace(/,/g, '');
                    elType = 'other';
                }
                // if value changes
                $(el).on('paste keyup', function() {
                    value = $(el).val().replace(/,/g, '');
                    formatElement(el, elType, value); // format element
                });
                formatElement(el, elType, value); // format element
            });

            function formatElement(el, elType, value) {
                var result = '';
                var valueArray = value.split('');
                var resultArray = [];
                var counter = 0;
                var temp = '';
                for (var i = valueArray.length - 1; i >= 0; i--) {
                    temp += valueArray[i];
                    counter++
                    if (counter == 3) {
                        resultArray.push(temp);
                        counter = 0;
                        temp = '';
                    }
                };
                if (counter > 0) {
                    resultArray.push(temp);
                }
                for (var i = resultArray.length - 1; i >= 0; i--) {
                    var resTemp = resultArray[i].split('');
                    for (var j = resTemp.length - 1; j >= 0; j--) {
                        result += resTemp[j];
                    };
                    if (i > 0) {
                        result += ','
                    }
                };
                if (elType == 'input') {
                    $(el).val(result);
                } else {
                    $(el).empty().text(result);
                }
            }
        };
    }(jQuery));
</script>

<!-- <script>
$("#danhsach").click(function(){

$.ajax({
    type: 'GET',
    url: 'quankho/dsnhapkho',
    success: function(data){
             if(data != null) $("#edit").html(data);
     }
 });
});
</script>

<script>
$("#danhSachHoaChat").ready(function(){

$.ajax({
    type: 'GET',
    url: 'quankho/dshoachat',
    success: function(data){
     
             if(data != null) $("#nhap").html(data);
     }
 });
});
</script>


<script>
$("#danhSachNhapKho").click(function(){

$.ajax({
    type: 'GET',
    url: 'quankho/dsnhapkho',
    success: function(data){
       
             if(data != null) $("#nhap2").html(data);
     }
 });
});
</script>


<script>
$("#danhSachTonKho").click(function(){

$.ajax({
    type: 'GET',
    url: 'quankho/dstonkho',
    success: function(data){
     
             if(data != null) $("#nhap3").html(data);
     }
 });
});
</script>


<script>
$("#danhSachDuTru").click(function(){

$.ajax({
    type: 'GET',
    url: 'quankho/dsdutru',
    success: function(data){
        
             if(data != null) $("#nhap4").html(data);
     }
 });
});
</script> -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    function loadBieuDo(URL) {
        $.ajax({
            type: 'GET',
            url: 'loadbieudo/' + URL,
            success: function(dataChart) {
                console.log(dataChart);
                if (dataChart != null) {
                    google.charts.load("current", {
                        packages: ["corechart"]
                    });
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable(dataChart[0]);

                        var options = {
                            legend: 'none',
                            series: {
                                0: {
                                    color: '#e2431e'
                                },
                                1: {
                                    color: '#e7711b'
                                },
                                2: {
                                    color: '#f1ca3a'
                                },
                                3: {
                                    color: '#6f9654'
                                },
                                4: {
                                    color: '#1c91c0'
                                },
                                5: {
                                    color: '#43459d'
                                },
                            }
                        };

                        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                        chart.draw(data, options);
                    }
                }
                document.getElementById("nameHC").innerHTML = dataChart[1];
            }
        });
    }
</script>
<script>
    $("#load").ready(function() {

        $.ajax({
            type: 'GET',
            url: 'loadcanhbao',
            success: function(data) {

                if (data != null) console.log(data);
            }
        });
    });
</script>


<script type="text/javascript">
    $('.money').simpleMoneyFormat();
</script>
@stop