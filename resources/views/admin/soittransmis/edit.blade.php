@extends('admin.layouts.app')
@section('title')
    Modifier un Soit-Transmis
@endsection
@section('styles')
    <link rel="stylesheet" href="{{asset('res/app-assets/vendors/css/forms/selects/selectivity-full.min.css')}}">
    <link rel="stylesheet" href="{{asset('res/app-assets/css/plugins/forms/selectivity/selectivity.css')}}">

@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">

        <div class="content-body">
            <!-- Basic form layout section start -->
            <section id="horizontal-form-layouts">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-content collpase show">
                                <div class="card-body">
                                    <form class="form form-horizontal" method="POST" action="{{ route('soit-transmis.update', ['id' => $soit_transmis->id]) }}"">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-body">
                                            <h4 class="form-section"><i class="la la-folder-open"></i> Modification du Soit-Transmis</h4>
                                            <div class="form-group row">
                                                <input type="hidden" name="id" value="{{$soit_transmis->id}}">
                                                <label class="col-md-3 label-control" for="numero">Numéro :</label>
                                                <div class="col-md-9 mx-auto">
                                                    <input type="text" name="numero" value="{{$soit_transmis->numero}}" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="users_id">Signataire</label>
                                                <div class="col-md-9 mx-auto">
                                                    <select class="select2-theme form-control" id="users_id" name="users_id">
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}" {{ $user->id == $soit_transmis->user->id ? 'selected' : '' }}>
                                                                {{ $user->prenom }}&nbsp;{{ $user->nom }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('users_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="description">Description</label>
                                                <div class="col-md-9 mx-auto">
                                                    <textarea cols="30" rows="10" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description" name="description">{{ old('description') }}{{$soit_transmis->description}}
                                                    </textarea>
                                                    @error('description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-check-square-o"></i> Modifier
                                            </button>
                                        </div>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- // Basic form layout section end -->
        </div>
    </div>
</div>
<!-- END: Content-->

@endsection
@section('scripts')
<script src="{{asset('res/app-assets/vendors/js/forms/select/selectivity-full.min.js')}}"></script>
<script>
    $(function(){
        $('.single-input').selectivity({
            allowClear: true,
            placeholder: 'No city selected',
            query: queryFunction,
            searchInputPlaceholder: 'Type to search a city'
        });

        var cities = $('#single-select-box').find('option').map(function () {
            return this.textContent;
        }).get();
    });
</script>
@endsection
