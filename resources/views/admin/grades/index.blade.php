@extends('admin.layouts.app')
@section('title')
    Gestion des grades
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Revenue, Hit Rate & Deals -->
            <div class="row">

                <div class="col-xl-5 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Ajouter un grade</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>

                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body pt-0">
                                <form class="form form-horizontal" action="{{route('grade.store')}}" method="POST">
                                    @csrf
                                    <div class="form-body">
                                        <h4 class="form-section"><i class="ft-folder"></i> Information du grade</h4>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="grade">Grade</label>
                                            <div class="col-md-9 mx-auto">
                                                <input type="text" value="{{old('grade')}}" id="grade" class="form-control @error('grade') is-invalid @enderror" placeholder="Grade" name="grade" required>
                                                @error('grade')
                                                <div class="invalid-feedback">
                                                            {{$message}}
                                                  </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="la la-check-square-o"></i> Sauvegarder
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Affichage des grades</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Grade</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                @forelse ($grades as $grade)
                                                        <tr>
                                                            <td>{{$grade->grade}}</td>
                                                            <td>
                                                                <div class="btn-group btn-block">
                                                                    <button type="button" class="btn btn-dark btn-sm">Action</button>
                                                                    <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                                                                    </button>
                                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuReference1">


                                                                      <a class="dropdown-item" href="{{route('grade.edit',$grade->id)}}">Modifier</a>
                                                                    <form action="{{route('grade.destroy',$grade->id)}}" method="post">
                                                                        @csrf
                                                                        <input class="dropdown-item" value="Supprimer"/>
                                                                    </form>
                                                                    </div>
                                                                 </div>
                                                            </td>
                                                        </tr>
                                                @empty

                                                @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Grade</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Revenue, Hit Rate & Deals -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('res/app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script>
    $(function(){
        $('.zero-configuration').DataTable();
    });
</script>
@endsection
