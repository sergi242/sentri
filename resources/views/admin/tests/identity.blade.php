@extends('admin.layouts.app')

@section('title', 'Tests de similarité – Duplication')

@section('content')
<div class="container-fluid">
    <h3 class="mb-3">🧪 Tests de duplication d’identité</h3>

    <div class="row">
        @foreach ($demandes as $demande)
            <div class="col-md-4">
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">

                        <div class="d-flex align-items-center mb-2">
                            <img src="{{ $demande->photo
                                ? asset('app/'.$demande->photo)
                                : asset('img/avatar-default.png') }}"
                                width="60" class="rounded-circle mr-2">

                            <div>
                                <strong>{{ $demande->impetrant->nom }}</strong><br>
                                {{ $demande->impetrant->prenom }}<br>
                                <small>{{ $demande->impetrant->date_naissance }}</small>
                            </div>
                        </div>

                        <form method="POST"
                              action="{{ route('tests.identity.duplicate', $demande->id) }}">
                            @csrf

                            <label><input type="checkbox" name="modifier_nom"> Modifier nom</label><br>
                            <label><input type="checkbox" name="modifier_prenom"> Modifier prénom</label><br>
                            <label><input type="checkbox" name="modifier_date"> Modifier date naissance</label><br>
                            <label><input type="checkbox" name="modifier_passeport"> Modifier passeport</label><br>

                            <button class="btn btn-sm btn-primary mt-2">
                                Dupliquer pour test
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
