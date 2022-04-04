<link href="{{ asset('vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/iCheck/css/all.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/pickadate/css/default.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/pickadate/css/default.date.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/pickadate/css/default.time.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/airDatepicker/css/datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/flatpickr/css/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/pages/adv_date_pickers.css') }}" rel="stylesheet" type="text/css" />
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


<div class="modal fade in" id="responsive" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Hóa Chất Dự Trù</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form class="form-horizontal" method="post" action="quanphong/loihong">
                @csrf
                <!--row starts-->
                <div class="col-sm-9 col-md-9 ">

                    <div class=" " id="hidepanel1">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 col-lg-3 col-12 control-label" for="name">Tên Hóa
                                    Chất</label>
                                <div class="col-md-9 col-lg-9 col-12">
                                    <select id="select21" name="ma_danh_muc_hoa_chat" class="form-control select2">
                                        <option value="">Select value...</option>
                                       @foreach($danhsachhong as $hong)
                                        <option value="{{$hong['hoachat']->ma_danh_muc_hoa_chat}}">{{$hong['hoachat']->ten_hoa_chat}}</option>
                                       @endforeach
                                        <option>
                                        </option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 col-lg-3 col-12 control-label" for="name">Số Lô</label>
                                <div class="col-md-9 col-lg-9 col-12">
                                    <input id="name" name="so_lo" type="text" placeholder="Số Lô" class="form-control" required >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 col-lg-3 col-12 control-label" for="name">Đơn Vị</label>
                                <div class="col-md-9 col-lg-9 col-12">
                                    <input id="name" name="don_vi_tinh" type="text" placeholder="Đơn vị"
                                        class="form-control" required >
                                </div>
                            </div>
                        </div>
                        <!-- name input-->
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 col-lg-3 col-12 control-label" for="name">Số
                                    Lượng</label>
                                <div class="col-md-9 col-lg-9 col-12">
                                    <input id="name" name="so_luong" type="number" placeholder="Số lượng"
                                        class="form-control" required >
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 col-lg-3 col-12 control-label" for="name">Lý Do Hỏng,
                                    Lỗi</label>
                                <div class="col-md-9 col-lg-9 col-12">
                                    <input id="name" name="ly_do_loi" type="text" placeholder="Lý do hỏng, lỗi"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-3 col-lg-3 col-12 control-label" for="upload">File
                                    Upload</label>
                                <div class="col-md-9 col-12 col-lg-9">
                                    <div class="input-group image-preview">
                                        <input type="text" class="form-control image-preview-filename"
                                            disabled="disabled">
                                        <!-- don't give a name === doesn't send on POST/GET -->
                                        <span class="input-group-btn">
                                            <!-- image-preview-clear button -->
                                            <button type="button" class="btn btn-secondary image-preview-clear"
                                                style="display:none; border-radius:0 !important; border: 1px solid rgba(0, 0, 0, 0.16);">
                                                <span class="fa  fa-times"></span> Clear
                                            </button>
                                            <!-- image-preview-input -->
                                            <div class="btn btn-secondary image_radius image-preview-input"
                                                style="margin-left:-3px;">
                                                <span class="fa fa-folder-open"></span>
                                                <span class="image-preview-input-title">Browse</span>
                                                <input type="file" accept="image/png, image/jpeg, image/gif" name="" />
                                                <!-- rename it -->
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
       
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm hvr-wobble-horizontal"
                    >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script src="{{ asset('vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"></script>
<script src="{{ asset('vendors/iCheck/js/icheck.js') }}"></script>
<script src="{{ asset('js/pages/form_examples.js') }}"></script>
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