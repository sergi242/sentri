<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="PIXINVENT">
    <title>Se Connecter</title>
    <link rel="apple-touch-icon" href="{{asset('logo.png')}}">
    <link rel="shortcut icon" type="image/png" href="{{asset('logo.png')}}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/vendors/css/forms/icheck/icheck.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/vendors/css/forms/icheck/custom.css')}}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/css/components.css')}}">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/css/core/menu/menu-types/vertical-menu-modern.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/css/core/colors/palette-gradient.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/css/pages/login-register.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('res/assets/css/style.css')}}">
    <!-- END: Custom CSS-->
    {{-- @toastr_css --}}
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 1-column  bg-full-screen-image blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="row flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-lg-4 col-md-8 col-10 box-shadow-2 p-0">
                            <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                                <div class="card-header border-0">
                                    <div class="card-title">
                                        <h2><strong>CID <br> DEPARTEMENT DES MIGRATIONS ET DU CONTROLE DES ETRANGERS</strong></h2>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form-horizontal" method="POST" action="{{route('users.authenticate')}}" novalidate>
                                            @csrf
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="email" class="form-control @error('email') is-invalid @enderror " name="email" value="{{old('email')}}" placeholder="Votre Email" required>
                                                <div class="form-control-position">
                                                    <i class="la la-user"></i>
                                                </div>
                                                @error('email')
                                                <div class="invalid-feedback">
                                                            {{$message}}
                                                  </div>
                                                @enderror
                                            </fieldset>
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="" placeholder="Votre mot de passe" required>
                                                <div class="form-control-position">
                                                    <i class="la la-key"></i>
                                                </div>
                                                @error('password')
                                                <div class="invalid-feedback">
                                                            {{$message}}
                                                  </div>
                                                @enderror
                                            </fieldset>
                                            {{-- <div class="form-group row">
                                                <div class="col-sm-6 col-12 text-center text-sm-left pr-0">
                                                    <fieldset>
                                                        <input type="checkbox" name="remember" class="chk-remember">
                                                        <label for="remember-me"> Se souvenir de moi</label>
                                                    </fieldset>
                                                </div>
                                                @if (Route::has('password.request'))
                                                        <div class="col-sm-6 col-12 float-sm-left text-center text-sm-right"><a href="{{ route('password.request') }}" class="card-link">Mot de passe oublié ?</a></div>
                                                @endif

                                            </div> --}}
                                            <button type="submit" class="btn btn-secondary btn-block"><i class="ft-unlock"></i> Se connecter</button>
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
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{asset('res/app-assets/vendors/js/vendors.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{asset('res/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
    <script src="{{asset('res/app-assets/vendors/js/forms/icheck/icheck.min.js')}}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{asset('res/app-assets/js/core/app-menu.js')}}"></script>
    <script src="{{asset('res/app-assets/js/core/app.js')}}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{asset('res/app-assets/js/scripts/forms/form-login-register.js')}}"></script>
    <!-- END: Page JS-->
    {{-- @toastr_js
    @toastr_render --}}
</body>
<!-- END: Body-->

</html>
