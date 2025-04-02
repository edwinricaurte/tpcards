<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Slice the Price Card">
    <meta name="author" content="Slice the Price Card">
    <meta name="robots" content="noindex,nofollow">
    <title>Login - Dynamic</title>
    <link rel="apple-touch-icon" href="/dynamic/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="/dynamic/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/vendors/css/vendors.min.css">

    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/css/components.css">

    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/css/core/colors/palette-gradient.css">

    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/vendors/js/sweetalert2/sweetalert2.css">
</head>

<body class="vertical-layout vertical-menu 1-column  bg-full-screen-image blank-page blank-page" data-open="click" data-menu="vertical-menu" data-color="bg-gradient-x-purple-blue" data-col="1-column">
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="flexbox-container">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="col-lg-4 col-md-6 col-10 box-shadow-2 p-0">
                        <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                            <div class="card-header border-0">
                                <div class="font-large-1 text-center"><img src="/dynamic/app-assets/images/dominos-logo.png" width="80" alt="Platinum Fundraising"></div>
                            </div>
                            <div class="card-content">

                                <div class="card-body">
                                    <form class="form-horizontal" action="/login" method="post">
                                        <fieldset class="form-group position-relative has-icon-left">
                                            <input type="text" class="form-control round" name="username" id="username" value="{{old('username')}}" placeholder="Username">
                                            <div class="form-control-position">
                                                <i class="ft-user"></i>
                                            </div>
                                        </fieldset>
                                        <fieldset class="form-group position-relative has-icon-left">
                                            <input type="password" class="form-control round" name="password" id="password" value="{{old('password')}}" placeholder="Password">
                                            <div class="form-control-position">
                                                <i class="ft-lock"></i>
                                            </div>
                                        </fieldset>
                                        <div class="form-group row">
                                            <div class="col-md-6 col-12 text-center text-sm-left">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" name="remember" id="remember_me_switch" @if(old('remember')) checked @endif>
                                                    <label class="custom-control-label" for="remember_me_switch">Remember</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12 float-sm-left text-center text-sm-right"></div>
                                        </div>
                                        <div class="form-group text-center">
                                            @csrf
                                            <button type="submit" class="btn round btn-block btn-glow btn-bg-gradient-x-purple-blue col-12 mr-1 mb-1">Sign In</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>

<script src="/dynamic/app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>

<script src="/dynamic/app-assets/js/core/app-menu.js" type="text/javascript"></script>
<script src="/dynamic/app-assets/js/core/app.js" type="text/javascript"></script>
<script src="/dynamic/app-assets/vendors/js/sweetalert2/sweetalert2.min.js"></script>
<script>
    @if(count($errors)>0)
    Swal.fire({
        title: 'Error',
        html: '{{$errors->first()}}',
        icon: 'error',
        confirmButtonText: 'Ok'
    });
    @endif
    @if(Session::get('message'))
    Swal.fire({
        title: '{!!Session::get('message.title') !!}',
        html: '{!!Session::get('message.description') !!}',
        icon: '{{Session::get('message.type')}}',
        confirmButtonText: 'Ok'
    });
    @endif
</script>
</body>
</html>