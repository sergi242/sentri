@extends("admin.layouts.app")
@section("title")
    Approbation
@endsection
@section("content")
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <!-- Social cards section start -->
            <section id="simple-user-cards" class="row">
                <div class="col-12">
                    <h4 class="text-uppercase">Liste des similarités</h4>
                    <p>Une liste des personnes ayant une similarité de plus de 70 %</p>
                </div>
                @forelse ($sims as $item)
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card">
                        <div class="text-center">
                            <div class="card-body">
                                <img src="{{ asset("app/".$item->demandes->last()->photo) }}" class="rounded-circle  height-150" alt="Card image">
                            </div>
                            <div class="card-body">
                                <h4 class="card-title">
                                    @if (ceil(TechnoDev::tauxSimilarity($item->nom,$demande->impetrant?->nom)) > 75)
                                        <del>{{$item->nom}}</del>
                                    @else
                                                  {{$item->nom}}
                                    @endif
                                    <br>
                                    @if (ceil(TechnoDev::tauxSimilarity($item->prenom,$demande->impetrant?->prenom)) > 75)
                                    <del>{{$item->prenom}}</del>
                                    @else
                                             {{$item->prenom}}
                                    @endif
                                </h4>
                                <h6 class="card-subtitle text-muted">{{ $item->demandes->last()->profession }}</h6>
                            </div>
                            <div class="text-center">
                                {{-- <a href="#" class="btn btn-social-icon mr-1 mb-1 btn-outline-facebook"><span class="la la-facebook"></span></a> --}}
                                <p>Taux de similarité :  <strong>{{ ceil(TechnoDev::tauxSimilarity($item->unique_string,$demande->impetrant?->unique_string)) }} %</strong></p>
                                <a href="#" class="btn btn-social-icon mr-1 mb-1 btn-outline-twitter"><span class="la la-eye"></span></a>
                                {{-- <a href="#" class="btn btn-social-icon mb-1 btn-outline-linkedin"><span class="la la-linkedin font-medium-4"></span></a> --}}
                            </div>
                        </div>
                    </div>
                </div>
                @empty

                @endforelse

            </section>
            <!-- // Social cards section end -->

        </div>
    </div>
</div>
@endsection
