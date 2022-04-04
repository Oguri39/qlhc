@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Nhật Ký Sử Dụng
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/buttons.bootstrap4.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/colReorder.bootstrap4.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/rowReorder.bootstrap4.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/scroller.bootstrap4.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pages/tables.css') }}" />
    <link href="{{ asset('vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{ asset('vendors/clockface/css/clockface.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <style>

        #table1_filter{
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
                            <div class="card-header bg-primary text-white clearfix  ">
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
                                    <span class="input-group-text"> <i class="livicon" data-name="laptop" data-size="16"
                                            data-c="#555555" data-hc="#555555" data-loop="true"></i></span>
                                </div>
                                <input type="text" class="form-control" id="rangepicker4" />
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
                                            <th>Đã Dùng</th>
                                            <td>Ghi Chú</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>

                                            <td>1</td>
                                            <td>Môi trường vận chuyển virus hô hấp</td>
                                            <td>Ống</td>
                                            <td>12 x 3ml</td>
                                            <td>3600</td>
                                            
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>SuperScript III Platinum One-Step qRT-PCR system</td>
                                            <td>Hộp</td>
                                            <td>100rxn/Hộp</td>
                                            <td>23</td>
                                            
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Platinum Hot start PCR 2X Master Mix</td>
                                            <td>Hộp</td>
                                            <td>50 test/bộ</td>
                                            <td>1</td>
                                            
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>ExoSAP-IT For PCR Product Clean-Up</td>
                                            <td>Kit</td>
                                            <td>100 pư/bộ</td>
                                            <td>3</td>
                                            
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Bigdye terminator v3.1 sequencing kit</td>
                                            <td>Bộ</td>
                                            <td>100 rxn/bộ</td>
                                            <td>1</td>
                                            
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>CAP-G/CTM HBV 72T</td>
                                            <td>Hộp</td>
                                            <td>72 test</td>
                                            <td>10</td>
                                            
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>CAP-G/CTM HCV v2.0</td>
                                            <td>Hộp</td>
                                            <td>72 test</td>
                                            <td>5</td>
                                            
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>CAP-G/CTM HIV-1 V2.0</td>
                                            <td>Hộp</td>
                                            <td>48 test</td>
                                            <td>1</td>
                                            
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>SPU</td>
                                            <td>Hộp</td>
                                            <td>288 cái</td>
                                            <td>6</td>
                                            
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>TUBE-S BOX OF 12X24 AMPLIP</td>
                                            <td>Hộp</td>
                                            <td>288 cái</td>
                                            <td>6</td>
                                            
                                            <td></td>
                                        </tr>
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

    <script type="text/javascript" src="{{ asset('vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/jeditable/js/jquery.jeditable.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.buttons.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.colReorder.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.responsive.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.rowReorder.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/buttons.colVis.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/buttons.html5.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/buttons.print.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/buttons.bootstrap4.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/pdfmake.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/vfs_fonts.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.scroller.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('js/pages/table-advanced.js') }}" ></script>
    <script src="{{ asset('vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/clockface/js/clockface.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/pages/datepicker.js') }}" type="text/javascript"></script>
@stop
