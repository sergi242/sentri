<div class="btn-group btn-block">
    <button type="button" class="btn btn-dark btn-sm">Action</button>
    <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuReference1">
        @can("demandes.print")
        <a class="dropdown-item" href="{{route('demandes.fiche', $demande->id)}}">Voir la fiche</a>
        @endcan

        @can("demandes.details")
        <a class="dropdown-item" href="{{route('demandes.show', $demande->id)}}">Voir la demande</a>
        @endcan

        @can("demandes.edit")
        <a class="dropdown-item" href="{{route('demandes.edit', $demande->id)}}">Modifier</a>
        @if ($demande->photo == "")
        <a class="dropdown-item" href="{{route('takePhotoCamera.index', $demande->id)}}">Ajouter une photo</a>
        @else
        <a class="dropdown-item" href="{{route('takePhotoCamera.index', $demande->id)}}">Reprise photo</a>
        @endif
        @endcan

        @can("demandes.destroy")
        <form action="{{route('demandes.destroy', $demande->id)}}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="dropdown-item a-del">Supprimer</button>
        </form>
        @endcan
    </div>
</div>
