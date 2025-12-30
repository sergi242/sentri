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
            <!-- Section des informations détaillées -->
            <div class="row">
                <div class="col-xl-12 col-12">
                    <div class="card">
                        <div class="card-header" style="background-color: #f0f0f0;">
                            <h4 class="card-title text-primary"><i class="la la-info-circle"></i> Informations du Document</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Section :</strong></p>
                                    <p class="text-muted">{!! $section !!}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Signataire :</strong></p>
                                    <p class="text-muted">{{ $signataire->getNomPrenom() }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Commentaires :</strong></p>
                                    <p class="text-muted">{{ $commentaire }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section du formulaire et de la table -->
            <div class="row">
                <div class="col-xl-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="card-title"><i class="la la-database"></i> Liste des Impétrants</h4>
                            <form action="{{ route('reporting.impetrant.liste.pdf') }}" method="GET" target="_blank">
                                <!-- Champs cachés -->
                                <input type="hidden" name="nom_document" value="{{ $nom_document }}">
                                <input type="hidden" name="pays_id" value="{{ $pays_id }}">
                                <input type="hidden" name="age_a" value="{{ $age_a }}">
                                <input type="hidden" name="age_de" value="{{ $age_de }}">
                                <input type="hidden" name="etat_civil" value="{{ $etat_civil }}">
                                <input type="hidden" name="genre" value="{{ $genre }}">
                                <input type="hidden" name="section" value="{{ $section }}">
                                <input type="hidden" name="signataire" value="{{ $signataire->id }}">
                                <input type="hidden" name="commentaire" value="{{ $commentaire }}">
                                <input type="hidden" name="entete" value="{{ $entete }}">
                                
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    <i class="la la-file-pdf-o"></i> Exporter PDF
                                </button>
                            </form>
                        </div>

                        <div class="card-content collapse show">
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead class="thead-dark">
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
                                                        @if(isset($impetrant->photo))
                                                            <img src="{{ asset('app/' . $impetrant->photo) }}" class="img-thumbnail" style="width: 60px; height: 60px;" alt="Photo">
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $impetrant?->nom }}</td>
                                                    <td>{{ $impetrant?->sexe }}</td>
                                                    <td>{{ $impetrant?->date_naissance }}</td>
                                                    <td>
                                                        <a href="{{ route('impetrants.demandes', $impetrant->id) }}" class="btn btn-dark btn-sm">
                                                            <i class="fas fa-folder"></i> Voir le dossier
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">Aucun impétrant trouvé</td>
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


