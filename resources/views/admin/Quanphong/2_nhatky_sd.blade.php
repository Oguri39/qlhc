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
</style>
@stop

{{-- Page content --}}
@section('content')

<section class="content-header">

    <!--section starts-->
    <h1>Nhật Ký Sử Dụng</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                Phòng Xét Nghiệm
            </a>
        </li>
        <li>
            <a href="#">Nhật Ký Sử Dụng</a>
        </li>
    </ol>
</section>
<!--section ends-->
<section class="content pl-3 pr-3">
    <div class="row">
        <div class="col-lg-12 my-3">
            <div class="card filterable">
                <div class="card-header bg-primary text-white clearfix">
                    <div class="float-left">
                        <div class="caption">
                            <i class="livicon" data-name="calendar" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                            Nhật Ký Sử Dụng Hàng Ngày
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive-lg table-responsive-sm table-responsive-md">
                    <div class="form-group">
                        <label>Chọn Ngày:</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text"> <i class="livicon" data-name="laptop" data-size="16" data-c="#555555" data-hc="#555555" data-loop="true"></i></span>
                            </div>
                            <form action="">
                                <input type="text" class="form-control" id="rangepicker4" />
                            </form>

                        </div>
                        <!-- /.input group -->
                    </div>
                    <table class="table table-striped table-bordered" id="table1" width="100%">
                        <thead>
                            <tr>

                                <th>STT</th>
                                <th>Tên Hóa Chất</th>
                                <th>Quy Cách Đóng Gói </th>
                                <th>Đơn Vị Tính</th>
                                <th>Dùng Trong Ngày</th>
                                <th>Còn Lại Trong Ngày</th>
                                <td>Thời Gian Cập Nhập</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($danhsach as $ds)

                            <tr>

                                <td>1</td>
                                <td>{{$ds['danhmuchoachat']->ten_hoa_chat}}</td>
                                <td>{{$ds['danhmuchoachat']->don_vi_dong_goi}}</td>
                                <td>{{$ds['danhmuchoachat']->don_vi_tinh}}</td>
                                <td>{{$ds['nhatKy']->so_luong_su_dung_trong_ngay}}-{{$ds['danhmuchoachat']->don_vi_dong_goi}}</td>
                                <td>{{$ds['nhatKy']->so_luong_con_lai}}-{{$ds['danhmuchoachat']->don_vi_dong_goi}}</td>
                                <td>{{$ds['nhatKy']->ngay_cap_nhap}}</td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
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
<script src="{{ asset('vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/clockface/js/clockface.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/pages/datepicker.js') }}" type="text/javascript"></script>

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
                [5, 10, 20, -1],
                [5, 10, 20, "All"]
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

@stop