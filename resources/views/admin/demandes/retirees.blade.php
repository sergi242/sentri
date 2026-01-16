@extends('admin.layouts.app')
@section('title')

@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
@section('content')
<div class="container-fluid">

    <h3 class="mb-4 text-danger">
        <i class="fa fa-trash"></i> Demandes supprimées
    </h3>

    @if($demandes->count() === 0)
        <div class="alert alert-info">
            Aucune demande supprimée.
        </div>
    @else
        <div class="card shadow">
            <div class="card-body">
                <table class="table table-striped table-bordered table-sm aligne-middle table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Impétrant</th>
                            <th>Type</th>
                            <th>Numero du ST</th>
                            <th>Date demande</th>
                            <th>Retirée le</th>
                            <th>Retirée par</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demandes as $key => $demande)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                {{ $demande->impetrant->nom }}
                                {{ $demande->impetrant->prenom }}
                            </td>
                            <td>{{ $demande->type_demande }}</td>
                             <td>
                             {{ $demande->soitTransmis
                                    ? $demande->soitTransmis->numero 
                                    : '-' }}   

                             </td>
                            <td>{{ $demande->date_demande }}</td>
                            <td class="text-danger fw-bold">
                                {{ $demande->retire_le }}
                            </td>
                            <td> {{ $demande->retire_par 
                                    ? \App\Models\User::find($demande->retire_par)?->prenom 
                                    : '-' }}
                                
                                {{ $demande->retire_par 
                                    ? \App\Models\User::find($demande->retire_par)?->nom 
                                    : '-' }}
                            </td>
                            <td>
                                <form method="POST" action="{{ route('demandes.restaurer', $demande->id) }}">
                                    @csrf
                                    <button class="btn btn-outline-success btn-sm"
                                        onclick="return confirm('Restaurer cette demande ?')">
                                        <i class="fa fa-undo"></i> Restaurer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $demandes->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
