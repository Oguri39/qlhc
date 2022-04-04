@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Danh Sách Hóa Chất
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
                <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                Quản Kho
            </a>
        </li>
        <li>
            <a href="#">Danh Sách Hóa Chất</a>
        </li>
        <li class="active">Danh sách hóa chất</li>
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
                            <i class="livicon" data-name="camera" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                            TableTools
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">
                    <table class="table table-striped table-bordered" id="table1" width="100%">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên Hóa Chất</th>
                                <th>Quy Cách Đóng Gói</th>
                                <th>Đơn Vị Tính</th>
                                <th>Tồn Đầu Tháng Trước</th>
                                <th>Nhập Trong Tháng</th>
                                <th>SL Sử Dụng Trong 1 Tháng Trước</th>
                                <th>Tồn Kho Hiện Tại</th>
                                <th>Số Lượng Dự Trù</th>
                                <th>Tên Máy Móc, Thiết Bị</th>
                                <th>Dự Kiến Thời Gian Dự Trù</th>
                                <th>Ghi Chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Môi trường vận chuyển virus hô hấp</td>
                                <td>12 x 3ml</td>
                                <td>Ống</td>
                                <td>5600</td>
                                <td>0</td>
                                <td>3600</td>
                                <td>2000</td>
                                <td>3000</td>
                                <td>Lấy mẫu bệnh phẩm Covid</td>
                                <td>1-2 tháng</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>CAP-G/CTM HBV 72T</td>
                                <td>72 test</td>
                                <td>Hộp</td>
                                <td>11</td>
                                <td>0</td>
                                <td>10</td>
                                <td>1</td>
                                <td>25</td>
                                <td>COBAS CAP/CTM 96</td>
                                <td>1-2 tháng</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>CAP-G/CTM HCV v2.0</td>
                                <td>72 test</td>
                                <td>Hộp</td>
                                <td>4</td>
                                <td>4</td>
                                <td>5</td>
                                <td>3</td>
                                <td>5</td>
                                <td>COBAS CAP/CTM 96</td>
                                <td>1-2 tháng</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>CAP-G/CTM HIV-1 V2.0</td>
                                <td>48 test</td>
                                <td>CAP-G/CTM HIV-1 V2.0</td>
                                <td>Hộp</td>
                                <td>0</td>
                                <td>1</td>
                                <td>0</td>
                                <td>2</td>
                                <td>COBAS CAP/CTM 96</td>
                                <td>1-2 tháng</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>SPU</td>
                                <td>288 cái</td>
                                <td>Hộp</td>
                                <td>11</td>
                                <td>0</td>
                                <td>6</td>
                                <td>5</td>
                                <td>5</td>
                                <td>COBAS CAP/CTM 96</td>
                                <td>1-2 tháng</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>TUBE-S BOX OF 12X24 AMPLIP</td>
                                <td>288 cái</td>
                                <td>Hộp</td>
                                <td>11</td>
                                <td>0</td>
                                <td>6</td>
                                <td>5</td>
                                <td>5</td>
                                <td>COBAS CAP/CTM 96</td>
                                <td>1-2 tháng</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>TUBE-K BOX 12x96</td>
                                <td>1152 cái</td>
                                <td>Hộp</td>
                                <td>3</td>
                                <td>0</td>
                                <td>1</td>
                                <td>2</td>
                                <td>1</td>
                                <td>COBAS CAP/CTM 96</td>
                                <td>1-2 tháng</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>TIP-K 1,2MM/12x36</td>
                                <td>432 cái</td>
                                <td>Hộp</td>
                                <td>10</td>
                                <td>0</td>
                                <td>5</td>
                                <td>5</td>
                                <td>4</td>
                                <td>COBAS CAP/CTM 96</td>
                                <td>1-2 tháng</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>KIT CAP-G/CTM WASH RGT 5.1L</td>
                                <td>5.1 lít</td>
                                <td>Hộp</td>
                                <td>27</td>
                                <td>0</td>
                                <td>15</td>
                                <td>12</td>
                                <td>20</td>
                                <td>COBAS CAP/CTM 96</td>
                                <td>1-2 tháng</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>COBAS TaqMan MTB test</td>
                                <td>48 test/hộp</td>
                                <td>Hộp</td>
                                <td>3</td>
                                <td>0</td>
                                <td>2</td>
                                <td>1</td>
                                <td>5</td>
                                <td>COBAS CTM 48</td>
                                <td>1-2 tháng</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>MagNA Pure 96 DNA and Viral NA Small Volume Kit</td>
                                <td>Bộ 3x192 isolations</td>
                                <td>Hộp</td>
                                <td>5</td>
                                <td>11</td>
                                <td>11</td>
                                <td>5</td>
                                <td>5</td>
                                <td>Magna Pure 96</td>
                                <td>1-2 tháng</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>MagNA Pure 96 Processing Cartridge</td>
                                <td>Hộp 36 Cartridges </td>
                                <td>Hộp</td>
                                <td>7</td>
                                <td>7</td>
                                <td>6</td>
                                <td>8</td>
                                <td>5</td>
                                <td>Magna Pure 96</td>
                                <td>1-2 tháng</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Cobas® 4800 System Sample Preparation Kit 2</td>
                                <td>240 test/hộp</td>
                                <td>Hộp</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>7</td>
                                <td>Cobas 4800</td>
                                <td>1-2 tháng</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Cobas® 4800 System Lysis Kit 2</td>
                                <td>240 test/hộp</td>
                                <td>Hộp</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>4</td>
                                <td>Cobas 4800</td>
                                <td>1-2 tháng</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- row-->

    <!-- /.modal ends here -->
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
@stop