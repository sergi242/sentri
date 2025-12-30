<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Modern admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities with bitcoin dashboard.">
    <meta name="keywords" content="admin template, modern admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
    <meta name="author" content="PIXINVENT">
    <title>Création d'un compte</title>
    <link rel="apple-touch-icon" href="{{asset('logo.png')}}">
    <link rel="shortcut icon" type="image/png" href="{{asset('logo.png')}}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin-res/app-assets/vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin-res/app-assets/vendors/css/forms/icheck/icheck.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin-res/app-assets/vendors/css/forms/icheck/custom.css')}}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin-res/app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin-res/app-assets/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin-res/app-assets/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin-res/app-assets/css/components.css')}}">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin-res/app-assets/css/core/menu/menu-types/vertical-menu-modern.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin-res/app-assets/css/core/colors/palette-gradient.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin-res/app-assets/css/pages/login-register.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin-res/assets/css/style.css')}}">
    <!-- END: Custom CSS-->
    @toastr_css
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
                                    <div class="card-title text-center">
                                        <img src="{{asset('logo.png')}}" alt="branding logo">
                                    </div>
                                </div>
                                <div class="card-content">
                                    <h3 class="text-center">Connexion au compte</h3>
                                    <div class="card-body">
                                        <form class="form-horizontal" method="POST" action="{{route('custom.login.store')}}" novalidate>
                                            @csrf
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="email" class="form-control @error('email') is-invalid @enderror " name="email" placeholder="Votre Email" required>
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
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="user-password" placeholder="Votre mot de passe" name="password" required>
                                                <div class="form-control-position">
                                                    <i class="la la-key"></i>
                                                </div>
                                                @error('password')
                                                <div class="invalid-feedback">
                                                            {{$message}}
                                                  </div>
                                                @enderror
                                            </fieldset>
                                            <button type="submit" class="btn btn-info btn-block"><i class="ft-unlock"></i> Se connecter</button>
                                        </form>
                                    </div>
                                    <p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-1"><span>Vous avez déjà un compte ?
                                            ?</span></p>
                                    <div class="card-body">
                                        <a href="{{route('custom.single.register')}}" class=""><i class="la la-user"></i>
                                            Création d'un compte</a>
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
    <script src="{{asset('admin-res/app-assets/vendors/js/vendors.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{asset('admin-res/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
    <script src="{{asset('admin-res/app-assets/vendors/js/forms/icheck/icheck.min.js')}}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{asset('admin-res/app-assets/js/core/app-menu.js')}}"></script>
    <script src="{{asset('admin-res/app-assets/js/core/app.js')}}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{asset('admin-res/app-assets/js/scripts/forms/form-login-register.js')}}"></script>
    <!-- END: Page JS-->
    @toastr_js
    @toastr_render
</body>
<!-- END: Body-->

</html>