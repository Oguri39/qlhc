@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Josh Admin Template
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}" />
    <link rel="stylesheet" href="{{asset('vendors/datatables/css/buttons.bootstrap4.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/select2/css/select2-bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pages/tables.css') }}" />

@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
    <!--section starts-->
    <h1>Quản Lý Hóa CHất</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                Quản Kho
            </a>
        </li>

    </ol>
</section>

<!-- Main content -->
<section class="content pr-3 pl-3">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-12 my-3">
            <!-- Stack charts strats here-->
            <div class="card ">
                <div class="card-header bg-primary text-white ">
                    <span>
                        <i class="livicon" data-name="barchart" data-size="16" data-loop="true" data-c="#fff"
                            data-hc="#fff"></i> Hóa Chất
                    </span>
                    <span class="float-right">
                        <i class="fa fa-chevron-up showhide clickable"></i>
                        <i class="fa fa-times removepanel clickable"></i>
                    </span>
                </div>
                <div class="card-body">
                    <div class="app">
                        {!! $line->container() !!}
                    </div>
                    <!-- End Of Main Application -->

                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-6 col-12 my-3">
            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="portlet box bg-danger text-white mb-4 card">
                <div class="portlet-title ">
                    <div class="caption">
                        <i class="livicon" data-name="warning" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i> Cảnh Báo
                    </div>
                </div>
                <div class="portlet-body bg-white p-2">
                    <div class="table-scrollable">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Phòng Xét Nghiệm</th>
                                <th>Tên Hóa Chất</th>
                                <th>Số Lượng</th>
                                <th>Ngày Hết Hạn</th>
                                <th>Tình Trạng</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>Airi Satou</td>
                                <td>COBAS TaqMan MTB test</td>
                                <td>10</td>
                                <td>20/08/2020</td>
                                <td>
                                    <span class="label label-sm bg-warning text-white">Số lượng</span>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Angelica</td>
                                <td>CAP-G/CTM HIV-1 V2.0</td>
                                <td>100</td>
                                <td>17/08/2021</td>
                                <td>
                                    <span class="label label-sm bg-warning text-white ">Hết hạn</span>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Airi Satou</td>
                                <td>COBAS TaqMan MTB test</td>
                                <td>0</td>
                                <td>20/08/2020</td>
                                <td>
                                    <span class="label label-sm bg-danger text-white">Số lượng</span>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Angelica</td>
                                <td>CAP-G/CTM HIV-1 V2.0</td>
                                <td>100</td>
                                <td>3/08/2021</td>
                                <td>
                                    <span class="label label-sm bg-danger text-white ">Hết hạn</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END SAMPLE TABLE PORTLET-->
        </div>
    </div>

    
    <div class="row">
        <div class="col-lg-12 my-3">
            <div class="card ">
                <div class="card-header bg-info text-white  clearfix">
                    <span>
                        <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        Danh Sách Hóa Chất
                    </span>
                    <span class="float-right ">
                        <i class="fa fa-chevron-up clickable"></i>
                    </span>

                </div>
                <div class="card-body table-responsive-lg table-responsive-md table-responsive-sm">
                    <strong>
                        Toggle column:
                    </strong>
                    <div class="btn-group" style="margin:10px 0;">
                        <button type="button" class="toggle-vis btn btn-secondary" data-column="0">STT</button>
                        <button type="button" class="toggle-vis btn btn-secondary" data-column="1">Tên hóa chất
                        </button>                        
                        <button type="button" class="toggle-vis btn btn-secondary" data-column="2">Quy cách đóng gói
                        </button>
                        <button type="button" class="toggle-vis btn btn-secondary" data-column="3">Đơn vị tính
                        </button>
                        <button type="button" class="toggle-vis btn btn-secondary" data-column="4">Nước sản xuất
                        </button>
                        <button type="button" class="toggle-vis btn btn-secondary" data-column="5">Công ty cung ứng
                        </button>
                        <button type="button" class="toggle-vis btn btn-secondary" data-column="6">Số lượng tồn kho
                        </button>
                    </div>
                    <table class="table table-striped table-bordered width100" id="example">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên Hóa Chất</th>
                            <th>Quy Cách Đóng Gói</th>
                            <th>Đơn Vị Tính</th>
                            <th>Nước Sản Xuất</th>
                            <th>Công Ty Cung Ứng</th>
                            <th>Số Lượng Tồn Kho</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        <tr>
                            <td>13</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        <tr>
                            <td>14</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        <tr>
                            <td>15</td>
                            <td>Môi trường vận chuyển virus hô hấp</td>
                            <td>Ống</td>
                            <td>12 x 3ml</td>
                            <td>Việt Nam</td>
                            <td>Nam Việt - Lintech</td>
                            <td>3600</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    


</section>
<!-- content -->
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script src="{{ asset('vendors/frappe/frappe-charts.min.iife.js') }}"></script>
<script src="{{ asset('vendors/highcharts/highcharts.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.responsive.js') }}" ></script>
    <!--<script type="text/javascript" src="{{asset('vendors/datatables/js/buttons.bootstrap4.js')}}"></script>-->
    <script type="text/javascript" src="{{ asset('vendors/select2/js/select2.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('js/pages/table-advanced2.js') }}" ></script>

{!! $line->script() !!}

@stop