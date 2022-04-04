@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Sửa Xóa Hóa Chất
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link href="{{ asset('css/pages/tables.css') }}" rel="stylesheet" type="text/css" />


<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/buttons.bootstrap4.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/colReorder.bootstrap4.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/rowReorder.bootstrap4.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/scroller.bootstrap4.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/pages/tables.css') }}" />
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>Quản Lý Hóa Chất </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Trang Chủ
            </a>
        </li>
        <li><a href="#">Sửa xóa hóa chất </a></li>
    </ol>
</section>

<!-- Main content -->
<section class="content pl-3 pr-3">
    <div class="row">
        <div class="col-12">
            <div class="card ">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title my-2 float-left"> <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        Danh Sách Hóa Chất
                    </h4>
                    <!-- <a href="{{ URL('admin/bulk_import_users') }}" class="float-right btn btn-success import_btn">
                                <i class="fa fa-plus fa-fw"></i>Y
                            Trang Chủ </a> -->
                </div>
                <div class="card-body">
                    <div class=" table-responsive-lg table-responsive-sm table-responsive-md">
                        <table class="table table-striped table-bordered" id="table1" width="100%">
                            <thead>
                                <tr class="filters">
                                    <th>STT</th>
                                    <th>Mã Hóa Chất</th>
                                    <th>Tên Hóa Chất</th>
                                    <th>Tên Nhà Thầu</th>
                                    <th>Nước Sản Xuất</th>
                                    <th>Số Lô</th>
                                    <th>Đơn Vị Tính</th>
                                    <th>Số Lượng Tính</th>
                                    <th>Đơn Vị Đóng Gói</th>
                                    <th>Số Lượng Gói</th>
                                    <th>Hạn Sử Dụng</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($danhSach as $ds)
                                <tr>
                                    <td>{{$ds['stt']}}</td>
                                    <td>{{$ds['maHoaChat']}}</td>
                                    <td>{{$ds['tenHoaChat']}}</td>
                                    <td>{{$ds['tenNhaThau']}}</td>
                                    <td>{{$ds['nuocSanXuat']}}</td>
                                    <td>{{$ds['soLo']}}</td>
                                    <td>{{$ds['donViTinh']}}</td>
                                    <td>{{$ds['donViDongGoi']}}</td>
                                    <td>{{$ds['soLuongTinh']}}</td>
                                    <td>{{$ds['soLuong']}}</td>
                                    <td>{{$ds['hanSuDung']}}</td>
                                    <td>
                                        <a href='1_suaxoa_hc/edit/{{$ds['maLoHoaChat']}}'><i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="sửa hóa chất"></i></a>
                                        <a href='1_suaxoa_hc/delete/{{$ds['maLoHoaChat']}}' data-id=""><i class="livicon" data-name="user-remove" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="xóa hóa chất"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- row-->
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

    });
</script>

<!-- <script>
    $(function() {
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.users.data') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'first_name', name: 'first_name' },
                { data: 'last_name', name: 'last_name' },
                { data: 'email', name: 'email' },
                { data: 'status', name: 'status'},
                { data: 'created_at', name:'created_at'},
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });
        table.on( 'draw', function () {
            $('.livicon').each(function(){
                $(this).updateLivicon();
            });
        } );
    });

</script> -->

<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteLabel">Delete User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                Are you sure to delete this User? This operation is irreversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a type="button" class="btn btn-danger Remove_square">Delete</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<!-- /.modal-dialog -->
<script>
    // $(function () {
    // 	$('body').on('hidden.bs.modal', '.modal', function () {
    // 		$(this).removeData('bs.modal');
    // 	});
    // });
    // var $url_path = '{!! url('/') !!}';
    // $('#delete_confirm').on('show.bs.modal', function (event) {
    //     var button = $(event.relatedTarget)
    //     var $recipient = button.data('id');
    //     var modal = $(this)
    //     modal.find('.modal-footer a').prop("href",$url_path+"/admin/users/"+$recipient+"/delete");
    // })
</script>


@stop