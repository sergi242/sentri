@extends('admin.layouts.app')
@section('title')
    Utilisateurs
@endsection
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('res/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
@section('content')
@can("users.view")
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
                            <h4 class="card-title">Affichage Utilisateurs</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    @can("users.create")
                                    <li><a href="{{ route('users.create') }}" class="btn btn-primary" style="color: white">Ajouter un utilisateur</a></li>
                                    @endcan
                                </ul>

                            </div>
                        </div>

                        <div class="card-content collapse show">
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Prénom</th>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Grade</th>
                                                <th>Photo</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($users as $user)
                                                <tr>
                                                    <td>{{$user->id}}</td>
                                                    <td>{{ $user->prenom }}</td>
                                                    <td>{{ $user->nom }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->role?->lib_role }}</td>
                                                    <td>{{ $user->grade?->grade }}</td>
                                                    <td>
                                                        @if($user->photo)
    <img src="{{ asset('uploads/users/'.$user->photo) }}"
         width="40"
         class="rounded-circle">
@else
    <span class="badge badge-secondary">N/A</span>
@endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-block">
                                                            <button type="button" class="btn btn-dark btn-sm">Action</button>
                                                            <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuReference1">

                                                              <a class="dropdown-item" href="{{route('users.edit',$user->id)}}">Modifier</a>

                                                              <a class="dropdown-item a-del" href="{{route('users.destroy',$user->id)}}">Supprimer</a>

                                                            </div>
                                                          </div>
                                                    </td>
                                                </tr>
                                            @empty

                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Id</th>
                                                <th>Prénom</th>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Grade</th>
                                                <th>Photo</th>
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
@endcan
@endsection

@section('scripts')
<script src="{{asset('res/app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script>
    $(function(){
        $('.zero-configuration').DataTable();
    });
</script>
@endsection
