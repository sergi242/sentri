@extends('admin.layouts.app')
@section('title')
    Gestion du flux migratoire
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



                <div class="col-xl-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Affichage des données</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a href="{{ route('flux.create') }}" class="btn btn-primary" style="color: white">Ajouter une donnée</a></li>
                                </ul>

                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Frontière</th>
                                                <th>Total entrée</th>
                                                <th>Total sortie</th>
                                                <th>Nationalité</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        @php
                                            $count = 1;
                                        @endphp
                                        <tbody>
                                                @forelse ($flux as $flu)
                                                        <tr>
                                                            <td>{{ $count ++ }}</td>
                                                            <td>{{$flu->frontiere?->lib_frontiere}}</td>
                                                            <td>{{$flu->total_entree}}</td>
                                                            <td>{{$flu->total_sortie}}</td>
                                                            <td>{{ $flu->pays?->lib_pays }}</td>
                                                            <td>{{ $flu->date_movement }}</td>
                                                            <td>
                                                                <div class="btn-group btn-block">
                                                                    <button type="button" class="btn btn-dark btn-sm">Action</button>
                                                                    <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                                                                    </button>
                                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuReference1">
                                                                      <a class="dropdown-item" href="{{route('flux.edit',$flu->id)}}">Modifier</a>
                                                                    </div>
                                                                 </div>
                                                            </td>
                                                        </tr>
                                                @empty

                                                @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>N°</th>
                                                <th>Frontière</th>
                                                <th>Total entrée</th>
                                                <th>Total sortie</th>
                                                <th>Nationalité</th>
                                                <th>Date</th>
                                                <th>Action</th>
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
