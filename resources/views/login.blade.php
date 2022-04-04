<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- global level css -->
    <!-- <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" /> -->
    <!-- end of global level css -->
    <!-- page level css -->
    <link href="{{ asset('vendors/iCheck/css/minimal/blue.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/pages/login2.css') }}" rel="stylesheet" />
    <!-- styles of the page ends-->
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Nunito", sans-serif;
    }

    body {
        width: 100%;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(149deg, rgba(255, 221, 118, 1) 27%, rgba(252, 131, 43, 1) 89%);
        font-family: "Nunito", sans-serif;
    }

    input,
    button {
        border: none;
        outline: none;
        background: none;
    }

    .cont {
        overflow: hidden;
        position: relative;
        border-radius: 30px;
        border: 0.5px solid grey;
        width: 900px;
        height: 550px;
        background: #fff;
        box-shadow: 0 19px 38px rgba(0, 0, 0, 0.3),
            0 15px 12px rgba(0, 0, 0, 0.22);
    }

    .form {
        position: relative;
        margin: 50px 0px 0px 0px;
        width: 400px;
        height: 100%;
        padding: 50px 30px;
    }

    h2 {
        width: 100%;
        font-size: 30px;
        text-align: center;
        color: #fc832b;
    }

    label {
        display: block;
        width: 260px;
        margin: 25px auto 0;
        text-align: center;
    }

    label span {
        font-size: 14px;
        font-weight: 600;
        color: #505f75;
        text-transform: uppercase;
    }

    input {
        display: block;
        width: 100%;
        margin-top: 5px;
        font-size: 16px;
        padding-bottom: 5px;
        border-bottom: 1px solid rgba(109, 93, 93, 0.4);
        text-align: center;
        font-family: "Nunito", sans-serif;
    }

    button {
        display: block;
        margin: 0 auto;
        width: 260px;
        height: 36px;
        border-radius: 30px;
        color: #fff;
        font-size: 15px;
        cursor: pointer;
    }

    .submit {
        margin-top: 40px;
        margin-bottom: 30px;
        text-transform: uppercase;
        font-weight: 600;
        font-family: "Nunito", sans-serif;
        background: linear-gradient(149deg, rgba(255, 221, 118, 1) 27%, rgba(252, 131, 43, 1) 89%);
    }

    .submit:hover {
        background: linear-gradient(0deg, rgba(255, 221, 118, 1) 27%, rgba(252, 131, 43, 1) 89%);
    }

    .sub-cont {
        overflow: hidden;
        position: absolute;
        left: 400px;
        top: 0;
        width: 900px;
        height: 100%;
        padding-left: 260px;
        background: #fff;
    }

    .img {
        overflow: hidden;
        z-index: 2;
        position: absolute;
        left: 0;
        top: 0;
        padding-top: 360px;
        width: 900px;
        height: 100%;
        background-image: url(images/13.jpg);
        background-size: cover;
    }

    .sign-in {
        padding-top: 65px;
        -webkit-transition-timing-function: ease-out;
        transition-timing-function: ease-out;
    }
</style>

<body>
    <div class="cont">
        <div class="sub-cont">
            <div class="img"></div>
        </div>
        <div class="form sign-in">
            <h2>Đăng nhập</h2>
            <form action="{{ route('signin') }}" id="authentication" autocomplete="on" method="post" role="form">
                <label>
                    <span>Email Address</span>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="form-group {{ $errors->first('email', 'has-error') }}">
                        <input name="email" type="text" value="{!! old('email') !!}" />
                        <div class="help-block">
                            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </label>
                <label>
                    <span>Mật khẩu</span>
                    <div class="form-group {{ $errors->first('password', 'has-error') }}">
                        <input name="password" type="password" />
                        <div class="help-block">
                            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </label>
                <div class="form-group">
                    <label>
                        <input name="remember-me" type="checkbox" value="Remember Me" class="minimal-blue" />
                        Ghi nhớ đăng nhập
                    </label>
                </div>
                <button class="submit" type="submit" value="Sign In">Sign In</button>
            </form>
        </div>

    </div>
</body>
<!-- global js -->
<script src="{{ asset('js/admin.js') }}" type="text/javascript"></script>
<!-- end of global js -->
<!-- begining of page level js-->
<script src="{{ asset('js/TweenLite.min.js') }}"></script>
<script src="{{ asset('vendors/iCheck/js/icheck.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/pages/login2.js') }}" type="text/javascript"></script>
<!-- end of page level js-->
</body>

<script>
    $("#authentication").ready(function() {

        $.ajax({
            type: 'GET',
            url: 'loadcanhbao',
            success: function(data) {

                if (data != null) console.log(data);
            }
        });
    });
</script>

</html>