@extends("admin.layouts.app")

@section("title")
Prise de la photo
@endsection
@section("styles")
<link href="{{ asset('res/cam/cam-style.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">

        <div class="content-body">
            <!-- Basic form layout section start -->
            <section id="horizontal-form-layouts">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    <form class="form form-horizontal" method="POST" action="{{route('demandes.store')}}">
                                        @csrf
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="ft-user"></i> Information de l'Impétrant</h4>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="nom">Caméra *</label>
                                                <div class="btn element col-md-3" data-toggle="modal" data-target="#myModal">
                                                    <i class="ft-camera"></i><span class="name"></span>
                                                      <input id="img-input" type="file" accept="image/*" capture name="photo" style="display: none">
                                                  </div>
                                                  <div class="image col-md-6" id="img-show-container" style="display: none">
                                                    <div class="fa fa-remove blue delete" onclick="resetImgUpl()"></div>
                                                    <canvas id="img-show" class="img-thumbnail img-response"></canvas>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="prenom">Prénom *</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input id="img-input" type="file" accept="image/*" name="photo">
                                                    @error('prenom')
                                                        <div class="invalid-feedback">
                                                                {{$message}}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-check-square-o"></i> Sauvegarder
                                            </button>
                                            <a href="{{route('demandes.index')}}" class="btn btn-warning">Retour</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- // Basic form layout section end -->
        </div>
    </div>
</div>
<!-- END: Content-->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="fa fa-3x close" onclick="stopWebcam();"  data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Prendre la photo</h4>
          </div>
          <div class="modal-body">
                    <!-- <h1>Take a snapshot of the current video stream</h1>
                    Click on the Start WebCam button.
                     <p>
                    <button onclick="startWebcam();">Start WebCam</button>
                    <button onclick="stopWebcam();">Stop WebCam</button>
                         <button onclick="snapshot();">Take Snapshot</button>
                    </p>
                    <video onclick="snapshot(this);" width=400 height=400 id="video" controls autoplay></video> -->
                    <div id="captured" class="" style="display:none">
                        <h3 class="text-primary">	Screenshots : <h3>
                        <canvas  id="myCanvas" width="400" height="350"></canvas>
                    </div>

                        <!--  -->
                        <div id="container-cam">
                            <button class="btn btn-warning" onclick="startWebcam();">Start WebCam</button>
                        <div id="vid_container">
                            <video id="video" autoplay playsinline></video>
                            <div id="video_overlay"></div>
                        </div>
                        <div id="gui_controls">
                            <button id="switchCameraButton" class="button" name="switch Camera" type="button" aria-pressed="false"></button>
                            <button id="takePhotoButton" class="button" name="take Photo" type="button"></button>
                            <button id="toggleFullScreenButton" class="button" name="toggle FullScreen" type="button" aria-pressed="false" style="display:none"></button>
                        </div>
                      </div>

          </div>
          <div class="modal-footer">
                    <button id="choose-img" type="button" onclick="choose(canvas); stopWebcam();" class="btn btn-success" data-dismiss="modal" style="display:none">Select Image</button>
            <button type="button" onclick="stopWebcam();" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
@endsection
  @section("scripts")
  <script src="{{ asset('res/cam/DetectRTC.min.js')}}"></script>
  <script src="{{ asset('res/cam/adapter.min.js')}}"></script>
  <script src="{{ asset('res/cam/screenfull.min.js')}}"></script>
  <script src="{{ asset('res/cam/howler.core.min.js')}}"></script>
  <script src="{{ asset('res/cam/main.js')}}"></script>
  <script>
    $(function(){
        navigator.getUserMedia = ( navigator.getUserMedia ||
											 navigator.webkitGetUserMedia ||
											 navigator.mozGetUserMedia ||
											 navigator.msGetUserMedia);

var video;
var webcamStream;
var canvas, ctx;
});
    function stopWebcam() {
	if (window.stream) {
     window.stream.getTracks().forEach(function (track) { track.stop(); });
	}
}
    function init() {
	// Get the canvas and obtain a context for
	// drawing in it
	canvas = document.getElementById("myCanvas");
	ctx = canvas.getContext('2d');
}
    function snapshot() {
	 // Draws current image from the video element into the canvas
	ctx.drawImage(video, 0,0, canvas.width, canvas.height);
}
  </script>
  @endsection
