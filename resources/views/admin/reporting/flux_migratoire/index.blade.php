@extends('admin.layouts.app')

@section('title')
    Recherche avancée
@endsection

@section('styles')
    <link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('img/editorial.css')}}" type="text/css">
@endsection

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <!-- Basic form layout section start -->
                <section id="horizontal-form-layouts">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card animated fadeIn">
                                <div class="card-content collpase show">
                                    <div class="card-body">
                                        <img src="{{asset('img/batiment.png')}}" alt="" style="width: 6%;">
                                        <h3>Reporting flux migratoire</h3>
                                        <form action="{{ route('flux_migratoire.pdf') }}" method="GET" target="_blank" class="form">
                                            <div class="row">
                                                <!-- Date de d�but -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="dtone">Date de début</label>
                                                        <input type="date" name="dtone" id="dtone" class="form-control" required>
                                                    </div>
                                                </div>
                                        
                                                <!-- Date de fin -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="dtwo">Date de fin</label>
                                                        <input type="date" name="dtwo" id="dtwo" class="form-control" required>
                                                    </div>
                                                </div>
                                        
                                                <!-- D�partement -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="departement">Département</label>
                                                        <select name="departement_id" id="département" class="form-control" required>
                                                            <option value="">-- Sélectionnez un departement --</option>
                                                            @foreach ($departements as $departement)
                                                                <option value="{{ $departement->id }}">{{ $departement->lib_departement }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <!-- Bouton submit -->
                                            <div class="mt-3">
                                                <button type="submit" class="btn btn-secondary">
                                                    <i class="la la-download"></i> Exporter en PDF
                                                </button>
                                            </div>
                                        </form>                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
