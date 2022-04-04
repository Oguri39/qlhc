@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    Hóa Chất Đã Dùng
@stop
{{-- page level styles --}}
@section('header_styles')

    <link href="{{ asset('vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}"  rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendors/iCheck/css/all.css') }}"  rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendors/pickadate/css/default.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('vendors/pickadate/css/default.date.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('vendors/pickadate/css/default.time.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('vendors/airDatepicker/css/datepicker.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('vendors/flatpickr/css/flatpickr.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('css/pages/adv_date_pickers.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}" />
    <link rel="stylesheet" href="{{asset('vendors/datatables/css/buttons.bootstrap4.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/select2/css/select2-bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pages/tables.css') }}" />
    <style>

        .container{
            margin-top:20px;
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
            margin-left:2px;
        }
        .image_radius{
            border-top-right-radius: 4px !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 4px !important;
        }
        .fileinput .thumbnail > img{
            width:100%;
        }
        .color_a{
            color: #333;
        }
        .btn-file > input{
            width: auto;
        }
    </style>

@stop
{{-- Page content --}}
@section('content')

    <section class="content-header">
        <!--section starts-->
        <h1>Nhập Hóa Chất Đã Dùng</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                    Phòng Xét Nghiệm
                </a>
            </li>
            <li>
                <a href="#">Nhập Hóa Chất Sử Dủng</a>
            </li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content pl-3 pr-3">
        <!--main content-->
        <div class="row">
            <!--row starts-->
            <div class="col-sm-12 col-md-12 col-full-width-right">
                <!--lg-6 starts-->  
                <!--basic form starts-->
                <div class="my-3">
                <div class="card " id="hidepanel1">
                    <div class="card-header bg-primary text-white ">
                        <span>
                            <i class="livicon" data-name="clock" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                            Nhập Hóa Chất Đã Dùng
                        </span>
                                <span class="float-right">
                                    <i class="fa fa-chevron-up clickable"></i>
                                    <i class="fa fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="">
                        <form class="form-horizontal" action="#">
                            <!-- CSRF Token -->
                                <!-- Name input-->
                                <div class="col-lg-12 my-3">
            <div class="card filterable">
               
                <div class="">
                    <div class="card-body table-responsive-lg table-responsive-sm table-responsive-sm">
                        <table class="table table-striped table-bordered width100" id="table4">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên Hóa Chất</th>
                                <th>Quy Cách Đóng Gói </th>
                                <th>Đơn Vị Tính</th>
                                <th>Số Lượng Nhập Về</th>
                                <th>Số Lượng Đã Dùng</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-position" name="row-1-position" value="Môi trường vận chuyển virus hô hấp">
                                </td>
                                <td>
                                    <select size="1" id="row-1-office" name="row-1-office">
                                        <option value="Hộp" selected="selected">Ống</option>
                                        <option value="Ống">Hộp</option>
                                        <option value="Kit">Kit</option>
                                        <option value="Bộ">Bộ</option>
                                    </select>
                                </td>
                                <td>
                                    <select size="1" id="row-1-office" name="row-1-office">
                                        <option value="12 x 3ml" selected="selected">12 x 3ml</option>
                                        <option value="100rxn/Hộp">100rxn/Hộp</option>
                                        <option value="50 test/bộ">50 test/bộ</option>
                                        <option value="100 pư/bộ">100 pư/bộ</option>
                                        <option value="100 rxn/bộ">100 rxn/bộ</option>
                                        <option value="48 test">48 test</option>
                                        <option value="72 test">72 test</option>
                                        <option value="288 cái">288 cái</option>
                                    </select>
                                </td>
                                <td>5000</td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-number" name="row-1-number" value="3600">
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-position" name="row-1-position" value="SuperScript III Platinum One-Step qRT-PCR system">
                                </td>
                                <td>
                                    <select size="1" id="row-1-office" name="row-1-office">
                                        <option value="Hộp" selected="selected">Ống</option>
                                        <option value="Ống">Hộp</option>
                                        <option value="Kit">Kit</option>
                                        <option value="Bộ">Bộ</option>
                                    </select>
                                </td>
                                <td>
                                    <select size="1" id="row-1-office" name="row-1-office">
                                        <option value="12 x 3ml" selected="selected">12 x 3ml</option>
                                        <option value="100rxn/Hộp">100rxn/Hộp</option>
                                        <option value="50 test/bộ">50 test/bộ</option>
                                        <option value="100 pư/bộ">100 pư/bộ</option>
                                        <option value="100 rxn/bộ">100 rxn/bộ</option>
                                        <option value="48 test">48 test</option>
                                        <option value="72 test">72 test</option>
                                        <option value="288 cái">288 cái</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-number" name="row-1-number" value="3600">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-text" name="row-1-number" value="">
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-position" name="row-1-position" value="Platinum Hot start PCR 2X Master Mix">
                                </td>
                                <td>
                                    <select size="1" id="row-1-office" name="row-1-office">
                                        <option value="Hộp" selected="selected">Ống</option>
                                        <option value="Ống">Hộp</option>
                                        <option value="Kit">Kit</option>
                                        <option value="Bộ">Bộ</option>
                                    </select>
                                </td>
                                <td>
                                    <select size="1" id="row-1-office" name="row-1-office">
                                        <option value="12 x 3ml" selected="selected">12 x 3ml</option>
                                        <option value="100rxn/Hộp">100rxn/Hộp</option>
                                        <option value="50 test/bộ">50 test/bộ</option>
                                        <option value="100 pư/bộ">100 pư/bộ</option>
                                        <option value="100 rxn/bộ">100 rxn/bộ</option>
                                        <option value="48 test">48 test</option>
                                        <option value="72 test">72 test</option>
                                        <option value="288 cái">288 cái</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-number" name="row-1-number" value="3600">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-text" name="row-1-number" value="">
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-position" name="row-1-position" value="ExoSAP-IT For PCR Product Clean-Up">
                                </td>
                                <td>
                                    <select size="1" id="row-1-office" name="row-1-office">
                                        <option value="Hộp" selected="selected">Ống</option>
                                        <option value="Ống">Hộp</option>
                                        <option value="Kit">Kit</option>
                                        <option value="Bộ">Bộ</option>
                                    </select>
                                </td>
                                <td>
                                    <select size="1" id="row-1-office" name="row-1-office">
                                        <option value="12 x 3ml" selected="selected">12 x 3ml</option>
                                        <option value="100rxn/Hộp">100rxn/Hộp</option>
                                        <option value="50 test/bộ">50 test/bộ</option>
                                        <option value="100 pư/bộ">100 pư/bộ</option>
                                        <option value="100 rxn/bộ">100 rxn/bộ</option>
                                        <option value="48 test">48 test</option>
                                        <option value="72 test">72 test</option>
                                        <option value="288 cái">288 cái</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-number" name="row-1-number" value="3600">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-text" name="row-1-number" value="">
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-position" name="row-1-position" value="Bigdye terminator v3.1 sequencing kit">
                                </td>
                                <td>
                                    <select size="1" id="row-1-office" name="row-1-office">
                                        <option value="Hộp" selected="selected">Ống</option>
                                        <option value="Ống">Hộp</option>
                                        <option value="Kit">Kit</option>
                                        <option value="Bộ">Bộ</option>
                                    </select>
                                </td>
                                <td>
                                    <select size="1" id="row-1-office" name="row-1-office">
                                        <option value="12 x 3ml" selected="selected">12 x 3ml</option>
                                        <option value="100rxn/Hộp">100rxn/Hộp</option>
                                        <option value="50 test/bộ">50 test/bộ</option>
                                        <option value="100 pư/bộ">100 pư/bộ</option>
                                        <option value="100 rxn/bộ">100 rxn/bộ</option>
                                        <option value="48 test">48 test</option>
                                        <option value="72 test">72 test</option>
                                        <option value="288 cái">288 cái</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-number" name="row-1-number" value="3600">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-text" name="row-1-number" value="">
                                </td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-position" name="row-1-position" value="Bigdye terminator v3.1 sequencing kit">
                                </td>
                                <td>
                                    <select size="1" id="row-1-office" name="row-1-office">
                                        <option value="Hộp" selected="selected">Ống</option>
                                        <option value="Ống">Hộp</option>
                                        <option value="Kit">Kit</option>
                                        <option value="Bộ">Bộ</option>
                                    </select>
                                </td>
                                <td>
                                    <select size="1" id="row-1-office" name="row-1-office">
                                        <option value="12 x 3ml" selected="selected">12 x 3ml</option>
                                        <option value="100rxn/Hộp">100rxn/Hộp</option>
                                        <option value="50 test/bộ">50 test/bộ</option>
                                        <option value="100 pư/bộ">100 pư/bộ</option>
                                        <option value="100 rxn/bộ">100 rxn/bộ</option>
                                        <option value="48 test">48 test</option>
                                        <option value="72 test">72 test</option>
                                        <option value="288 cái">288 cái</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-number" name="row-1-number" value="3600">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="row-1-text" name="row-1-number" value="">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
                                <!-- Form actions -->
                                <div class="form-position">
                                    <div class="row">
                                    <div class="col-md-12  col-sm-12 col-12  col-lg-12 text-right">
                                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                    </div>
                                    </div>
                                </div>
                        </form>
                        </div>
                    </div>
                    </div>
                <!--basic form 2 starts-->
                
        </div>
        <!--main content ends--> </section>
    <!-- content -->
@stop

{{-- page level scripts --}}
@section('footer_scripts')

    <script src="{{ asset('vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" ></script>
    <script src="{{ asset('vendors/iCheck/js/icheck.js') }}"></script>
    <script src="{{ asset('js/pages/form_examples.js') }}"></script>
    <script src="{{ asset('vendors/pickadate/js/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendors/pickadate/js/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendors/pickadate/js/picker.time.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendors/flatpickr/js/flatpickr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendors/airDatepicker/js/datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendors/airDatepicker/js/datepicker.en.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/pages/custom_datepicker.js') }}" type="text/javascript"></script>
     <script type="text/javascript" src="{{ asset('vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.responsive.js') }}" ></script>
    <!--<script type="text/javascript" src="{{asset('vendors/datatables/js/buttons.bootstrap4.js')}}"></script>-->
    <script type="text/javascript" src="{{ asset('vendors/select2/js/select2.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('js/pages/table-advanced2.js') }}" ></script>
    <script>
        $(document).on('click', '#close-preview', function(){
            $('.image-preview').popover('hide');
            // Hover befor close the preview
            $('.image-preview').hover(
                function () {
                    $('.image-preview').popover('show');
                },
                function () {
                    $('.image-preview').popover('hide');
                }
            );
        });

        $(function() {
            // Create the close button
            var closebtn = $('<button/>', {
                type:"button",
                text: 'x',
                id: 'close-preview',
                style: 'font-size: initial;',
            });
            closebtn.attr("class","close float-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function(){
            $('.image-preview').attr("data-content","").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("Browse");
        });
        // Create the preview image
        $(".image-preview-input input:file").change(function (){
            var img = {
                id: 'dynamic',
                width:250,
                height:200
            };
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("Change");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
            }
            reader.readAsDataURL(file);
        });
        });


    </script>

@stop
