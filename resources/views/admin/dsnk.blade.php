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
        <li>
            <a href="#">Danh Sách Hóa Chất</a>
        </li>
        <li class="active">Danh sách nhập kho</li>
    </ol>
</section>

<!-- Main content -->
<section class="content pr-3 pl-3">


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
<script type="text/javascript" src="{{ asset('vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.bootstrap4.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.responsive.js') }}"></script>

@stop