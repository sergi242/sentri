@extends('admin.layouts.app')
@section('title')
Liste des demandes
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
                            <h4 class="card-title">Liste des demandes</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                            </div>
                        </div>

                        <div class="card-content collapse show">
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Photo</th>
                                                <th>Impétrant</th>
                                                <th>Sexe</th>
                                                <th>Date de naissance</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $count = 1;
                                            @endphp
                                            @forelse ($impetrants as $impetrant)
                                                <tr>
                                                    <td>{{ $count++ }}</td>
                                                    <td>
                                                        @if(isset($impetrant->demandes[0]))
                                                            <img src="{{ asset('app/' . $impetrant->demandes[0]->photo) }}" width="60" height="60" alt="">
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>{{ $impetrant?->nomcomplet() }}</td>
                                                    <td>{{ $impetrant?->sexe }}</td>
                                                    <td>{{ $impetrant?->date_naissance }}</td>
                                                    <td>
                                                        <div class="col-xl-9 col-md-6 col-12">
                                                            <a href="{{ route('impetrants.demandes', $impetrant->id) }}" class="btn btn-dark btn-sm">
                                                                <i class="fas fa-folder"></i> Voir le dossier
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">Aucun impétrant trouvé</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>N°</th>
                                                <th>Photo</th>
                                                <th>Impétrant</th>
                                                <th>Sexe</th>
                                                <th>Date de naissance</th>
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
