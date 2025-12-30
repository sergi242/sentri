@extends("admin.layouts.app")
@section("title")
    Test photo
@endsection
@section("styles")
<link rel="stylesheet" href="{{ asset("camera/jquery.camshoot.css") }}">
<link rel="stylesheet" type="text/css" href="{{ asset("res/app-assets/vendors/css/extensions/sweetalert2.min.css")}}">
@endsection
@section("content")
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">

        <div class="content-body">
            <!-- Basic form layout section start -->
            <section id="horizontal-form-layouts">

                <div class="row">
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-4">
                        <div class="card animated fadeIn">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                        <div class="row">
                                            <div class="col"></div>
                                            <div class="col">
                                                <h3>Prise de la photo</h3>
                                                <div id="cameraContainer"></div>
                                                <button id="stop" class="btn btn-sm btn-danger">Arrêter la camera</button>
                                                <button id="reset" class="btn btn-sm btn-warning">Réinitialisation</button>
                                            </div>
                                            <div class="col"></div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">

                    </div>
                </div>
            </section>
            <!-- // Basic form layout section end -->
        </div>
    </div>
</div>
@endsection
@section("scripts")
<script src="{{asset("res/app-assets/vendors/js/extensions/sweetalert2.all.min.js")}}"></script>
@include("admin.camera.jquery_camshoot")
<script type="text/javascript">
    $(function(){
        var anu = $("#cameraContainer").camshoot({
            height:200,
            width:240
        });

        $("#stop").click(function () {
            anu.stop();
        });
        $("#reset").click(function () {
            anu.reset();
        });
        anu.version();

        //notify

    });
    function notify(type,message,title){
        Swal.fire({
            title: title,
            text: message,
            type: type,
            confirmButtonClass: 'btn btn-primary',
            buttonsStyling: false,
        });
    }
</script>
@endsection
