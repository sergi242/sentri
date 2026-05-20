@extends('admin.layouts.app')

@section('title')
    Rapport Global
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
.form-card {
    max-width: 800px;
    margin: 0 auto;
}
.period-shortcuts {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 15px;
}
.period-shortcuts .btn {
    flex: 1;
    min-width: 120px;
}
</style>
@endsection

@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card form-card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="la la-file-pdf"></i> Générer un Rapport Global
                            </h4>
                        </div>
                        
                        <div class="card-content">
                            <div class="card-body">
                                <form action="{{ route('rapports.global.pdf') }}" method="POST" target="_blank">
                                    @csrf

                                    <!-- Période -->
                                    <div class="form-group">
                                        <label><i class="la la-calendar"></i> Période du rapport</label>
                                        
                                        <!-- Raccourcis période -->
                                        <div class="period-shortcuts">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setPeriod('week')">
                                                Cette semaine
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setPeriod('month')">
                                                Ce mois
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setPeriod('quarter')">
                                                Ce trimestre
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setPeriod('year')">
                                                Cette année
                                            </button>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Date début</label>
                                                <input type="text" 
                                                       name="date_debut" 
                                                       id="date_debut" 
                                                       class="form-control flatpickr @error('date_debut') is-invalid @enderror" 
                                                       placeholder="Sélectionner la date de début"
                                                       required>
                                                @error('date_debut')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label>Date fin</label>
                                                <input type="text" 
                                                       name="date_fin" 
                                                       id="date_fin" 
                                                       class="form-control flatpickr @error('date_fin') is-invalid @enderror" 
                                                       placeholder="Sélectionner la date de fin"
                                                       required>
                                                @error('date_fin')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Autorité signataire -->
                                    <div class="form-group">
                                        <label><i class="la la-user"></i> Autorité signataire</label>
                                        <select name="signataire_id" class="form-control @error('signataire_id') is-invalid @enderror" required>
                                            <option value="">-- Sélectionner un signataire --</option>
                                            @foreach($signataires as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->grade->grade ?? '' }} {{ $user->getNomPrenom() }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('signataire_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Commentaire -->
                                    <div class="form-group">
                                        <label><i class="la la-comment"></i> Commentaire / Observations (optionnel)</label>
                                        <textarea name="commentaire" 
                                                  class="form-control @error('commentaire') is-invalid @enderror" 
                                                  rows="4" 
                                                  placeholder="Ajouter des observations ou commentaires sur ce rapport...">{{ old('commentaire') }}</textarea>
                                        @error('commentaire')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Actions -->
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="la la-file-pdf"></i> Générer le rapport PDF
                                        </button>
                                        <a href="{{ url('/home') }}" class="btn btn-secondary btn-lg">
                                            <i class="la la-times"></i> Annuler
                                        </a>
                                    </div>
                                </form>
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<script>
// Initialiser Flatpickr
flatpickr('.flatpickr', {
    locale: 'fr',
    dateFormat: 'Y-m-d',
    defaultDate: 'today'
});

// Définir les périodes rapidement
function setPeriod(type) {
    const today = new Date();
    let dateDebut, dateFin;

    switch(type) {
        case 'week':
            const day = today.getDay();
            const diff = today.getDate() - day + (day == 0 ? -6 : 1);
            dateDebut = new Date(today.setDate(diff));
            dateFin = new Date();
            break;
            
        case 'month':
            dateDebut = new Date(today.getFullYear(), today.getMonth(), 1);
            dateFin = new Date();
            break;
            
        case 'quarter':
            const quarter = Math.floor(today.getMonth() / 3);
            dateDebut = new Date(today.getFullYear(), quarter * 3, 1);
            dateFin = new Date();
            break;
            
        case 'year':
            dateDebut = new Date(today.getFullYear(), 0, 1);
            dateFin = new Date();
            break;
    }

    document.getElementById('date_debut').value = dateDebut.toISOString().split('T')[0];
    document.getElementById('date_fin').value = dateFin.toISOString().split('T')[0];
}
</script>
@endsection