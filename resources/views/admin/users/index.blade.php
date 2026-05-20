@extends('admin.layouts.app')

@section('title')
    Gestion des Utilisateurs
@endsection

@section('styles')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.95);
        --user-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    .user-card-wrapper { animation: fadeInScale 0.4s ease backwards; }

    .user-card {
        border: none;
        border-radius: 25px;
        background: var(--glass-bg);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 30px;
    }
    .user-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }

    .user-header {
        height: 90px;
        background: var(--user-gradient);
        position: relative;
    }

    .user-avatar-container {
        position: absolute;
        bottom: -35px;
        left: 20px;
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 22px;
        border: 4px solid #fff;
        object-fit: cover;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        background: #eee;
    }

    .user-body { padding: 45px 20px 20px 20px; }
    
    .user-name { font-size: 1.15rem; font-weight: 800; color: #2d3436; }
    .user-email { font-size: 0.85rem; color: #636e72; }

    .role-badge {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        padding: 4px 12px;
        border-radius: 8px;
        font-weight: 700;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px dashed #eee;
    }

    .info-item label { font-size: 0.65rem; color: #b2bec3; text-transform: uppercase; margin-bottom: 2px; display: block; }
    .info-item span { font-size: 0.85rem; font-weight: 600; color: #2d3436; }

    .card-actions {
        position: absolute;
        top: 15px;
        right: 15px;
    }

    .btn-action-round {
        width: 35px; height: 35px;
        border-radius: 10px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        display: flex; align-items: center; justify-content: center;
        transition: 0.3s;
    }
    .btn-action-round:hover { background: white; color: #764ba2; }

    .popover { border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 12px; }
    .pop-item { 
        padding: 8px 15px; display: block; color: #2d3436; border-radius: 8px; 
        transition: 0.2s; text-decoration: none !important;
    }
    .pop-item:hover { background: #f1f3f9; color: #667eea; padding-left: 20px; }
    .pop-item.text-danger:hover { background: #fff5f5; }
</style>
@endsection

@section('content')
@can("users.view")
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="font-weight-bold"><i class="la la-users"></i> Annuaire des Utilisateurs</h2>
                <p class="text-muted">Gérez les accès et les profils de la plateforme.</p>
            </div>
            <div class="col-md-6 text-md-right">
                <div class="d-flex justify-content-md-end align-items-center" style="gap: 15px;">
                    <input type="text" id="userSearch" class="form-control border-0 shadow-sm round" placeholder="Rechercher un membre..." style="width: 250px;">
                    @can("users.create")
                    <a href="{{ route('users.create') }}" class="btn btn-primary round shadow-sm px-2">
                        <i class="la la-user-plus"></i> Nouvel Utilisateur
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="content-body">
           <div class="row" id="userContainer">
    @forelse ($users as $key => $user)
    <div class="col-xl-3 col-lg-4 col-md-6 user-card-wrapper" style="animation-delay: {{ $key * 0.05 }}s">
        <div class="card user-card shadow-sm">
            <div class="user-header">
                <div class="card-actions">
                    <button class="btn-action-round" 
                            data-toggle="popover" 
                            data-html="true" 
                            data-placement="bottom"
                            data-content='
                                <a href="{{ route('users.edit', $user->id) }}" class="pop-item"><i class="la la-edit"></i> Modifier</a>
                                <a href="{{ route('users.destroy', $user->id) }}" class="pop-item text-danger a-del"><i class="la la-trash"></i> Supprimer</a>
                            '>
                        <i class="la la-ellipsis-h"></i>
                    </button>
                </div>
                
                {{-- Lien sur la photo de profil --}}
                <div class="user-avatar-container">
                    <a href="{{ route('users.show', $user->id) }}" class="d-inline-block">
                        @if($user->photo)
                            <img src="{{ asset('uploads/users/'.$user->photo) }}" class="user-avatar" title="Voir le profil de {{ $user->prenom }}">
                        @else
                            <div class="user-avatar d-flex align-items-center justify-content-center bg-light text-muted" title="Voir le profil">
                                <i class="la la-user" style="font-size: 2rem;"></i>
                            </div>
                        @endif
                    </a>
                </div>
            </div>

            <div class="user-body">
                <div class="mb-1">
                    <span class="role-badge">{{ $user->role?->lib_role ?? 'Sans Rôle' }}</span>
                </div>
                
                {{-- Lien sur le nom de l'utilisateur --}}
                <h5 class="user-name mb-0">
                    <a href="{{ route('users.show', $user->id) }}" style="color: inherit; text-decoration: none;">
                        {{ $user->prenom }} {{ $user->nom }}
                    </a>
                </h5>
                <p class="user-email mb-0">{{ $user->email }}</p>

                <div class="info-grid">
                    <div class="info-item">
                        <label>Grade</label>
                        <span>{{ $user->grade?->grade ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <label>ID Système</label>
                        <span>#{{ $user->id }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    @endforelse
</div>
        </div>
    </div>
</div>
@endcan
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        // Initialisation Popover
        $('[data-toggle="popover"]').popover({ trigger: 'click', sanitize: false });
        
        // Fermer au clic ailleurs
        $('body').on('click', function (e) {
            $('[data-toggle="popover"]').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });

        // Recherche temps réel
        $("#userSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#userContainer .user-card-wrapper").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endsection