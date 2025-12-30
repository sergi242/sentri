@extends('admin.layouts.app')
@section('title')
    Gestion des frontières
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
                            <h4 class="card-title">Affichage des frontières</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a href="{{ route('frontieres.create') }}" class="btn btn-primary" style="color: white">Ajouter une frontière</a></li>
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
                                                <th>Nom</th>
                                                <th>Terminal</th>
                                                <th>Département</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        @php
                                            $count = 1;
                                        @endphp
                                        <tbody>
                                                @forelse ($frontieres as $frontiere)
                                                        <tr>
                                                            <td>{{ $count ++ }}</td>
                                                            <td>{{$frontiere->lib_frontiere}}</td>
                                                            <td>{{$frontiere->terminal}}</td>
                                                            <td>{{ $frontiere->departement?->lib_departement }}</td>
                                                            <td>
                                                                <div class="btn-group btn-block">
                                                                    <button type="button" class="btn btn-dark btn-sm">Action</button>
                                                                    <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                                                                    </button>
                                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuReference1">


                                                                      <a class="dropdown-item" href="{{route('frontieres.edit',$frontiere->id)}}">Modifier</a>

                                                                      {{-- <a class="dropdown-item a-del" href="{{route('frontieres.destroy',$frontiere->id)}}">Supprimer</a> --}}

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
                                                <th>Nom</th>
                                                <th>Terminal</th>
                                                <th>Département</th>
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
