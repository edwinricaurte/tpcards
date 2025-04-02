<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Neifers">
    <title>@yield('title','Platinum Fundraising')</title>

    <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/css/components.css">

    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="/dynamic/app-assets/vendors/js/sweetalert2/sweetalert2.css">

    <link rel="stylesheet" type="text/css" href="/dynamic/assets/css/style.css?v=1.1">
    @yield('head')
</head>
<body class="vertical-layout vertical-menu 2-columns fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-gradient-x-purple-blue" data-col="2-columns">

<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="collapse navbar-collapse show" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                    <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>
                    <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
                </ul>
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"> <span class="avatar avatar-online"><img src="/dynamic/app-assets/images/avatar.webp" alt="avatar"></span></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="arrow_box_right">
                                <a class="dropdown-item" href="javascript:;"><span class="avatar avatar-online"><img src="/dynamic/app-assets/images/avatar.webp" alt="Avatar"><span class="user-name text-bold-700 ml-1">{{Auth::user()->name}}</span></span></a>
                                <a class="dropdown-item" href="/logout"><i class="ft-power"></i> Logout</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

@yield('content')

<footer class="footer footer-static footer-light navbar-border navbar-shadow">
    <div class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">{{date('Y')}} &copy; Copyright <a class="text-bold-800 grey darken-2" href="javascript:;">Funeraria Puerta Divina</a></span>
        <ul class="list-inline float-md-right d-block d-md-inline-blockd-none d-lg-block mb-0">
            <li class="list-inline-item"><a class="my-1" href="mailto:edwin@neifers.com" target="_blank"> Support</a></li>
        </ul>
    </div>
</footer>

<script src="/dynamic/app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>

@yield('scripts')
<script>
    @if(count($errors)>0)
    Swal.fire({
        title: 'Error',
        html: '{{$errors->first()}}',
        icon: 'error'
    });
    @endif
    @if(Session::get('message'))
    Swal.fire({
        icon: '{{Session::get('message.icon')}}',
        title: '{{Session::get('message.title')}}',
        html: '{{Session::get('message.description')}}',
    });
    @endif
</script>
</body>
</html>