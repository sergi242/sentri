
@extends("admin.layouts.app")
@section("styles")
<style>
    body{
        background-color: #fff !important;
        background-image: none !important;
        color: black;
    }

@media print {
    @page {
        size: landscape;
        margin: 20mm; /* You can adjust the margin as needed */
    }

    /* body {
        transform: rotate(90deg);
        transform-origin: left top;
        width: 100vh;
        position: absolute;
        overflow-x: hidden;
    } */
}
</style>
@endsection
@section("content")

<div class="container-fluid">
    <div class="row mt-3">
        <div class="col"></div>
        <div class="col">
            <a class="btn btn-secondary btn-print" href="#">Imprimer</a>
        </div>
        <div class="col">
            <form action="" method="get" class="form-inline">
                {{-- @csrf --}}
                <div class="form-group">
                    <label for="date-one">Date de</label>
                    <input type="date" name="dtone" class="form-control">
                </div>
                <div class="form-group">
                    <label for="date-two">Date au</label>
                    <input type="date" name="dtwo" class="form-control">
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="Recherche" class="btn btn-primary">
                </div>
            </form>

        </div>
    </div>
    <hr>
    <div id="printable">
        <div class="row">
            <div class="col">
                <h5 class="text-center">MINISTERE DE L'INTERIEUR <br>CENTRALE D'INTELLIGENCE ET DE DOCUMENTATION <br>DEPARTEMENT DES MIGRATIONS ET DU CONTROLE DES ETRANGERS
                    <br> DIVISION DU CONTROLE DES ENTRANGERS <br>SECTION DES FLUX MIGRATOIRES ET DES STATISTIQUES</h5>
            </div>
            <div class="col">
                <h3 class="text-center">REPUBLIQUE DU CONGO</h3>
                    <p class="text-center">Unité * Travail * Progrès</p>
            </div>
        </div>

        <div class="content mt-2">
            <h3 class="text-center">TABLEAU SYNOPTIQUE DES STATISTIQUES DES ETRANGERS <br>DANS LES DIFFERENTS POSTES FRONTALIERS</h3>
            <p class="text-center">PERIODE : {{ date("d/m/Y",strtotime($dtone)) }} à {{ date("d/m/Y",strtotime($dtwo)) }}</p>

            <table class="table table-bordered">
                <tr>
                    <td>Nationalité</td>
                    @foreach ($fronts as $f)
                        <td colspan="2">{{$f->lib_frontiere}}</td>
                    @endforeach
                    <td>Total Entrée</td>
                    <td>Total Sortie</td>
                </tr>
                @php
                    $totalEntrées = 0;
                    $totalSorties = 0;
                @endphp
                @forelse ($pays as $item)
                    <tr>
                        <td>{{ $item->lib_pays }}</td>
                        @php
                            $totalEntréePays = 0;
                            $totalSortiePays = 0;
                        @endphp
                        @foreach ($fronts as $frontiere)
                            @php
                                $entrée = TechnoDev::joinFluxData($item->id, $frontiere->id, $dtone, $dtwo)->tentree ?? 0;
                                $sortie = TechnoDev::joinFluxData($item->id, $frontiere->id, $dtone, $dtwo)->tsortie ?? 0;
                                $totalEntréePays += $entrée;
                                $totalSortiePays += $sortie;
                                $totalEntrées += $entrée;
                                $totalSorties += $sortie;
                            @endphp
                            <td>{{$entrée}}</td>
                            <td>{{$sortie}}</td>
                        @endforeach
                        <td>{{$totalEntréePays}}</td>
                        <td>{{$totalSortiePays}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 2 * $fronts->count() + 2 }}">Aucune donnée disponible</td>
                    </tr>
<<<<<<< HEAD
                    @php
                        $fs = $fronts->pluck("id")->toArray();
                    @endphp
                   @forelse ($pays as $item)
                   <tr>
                       <td>{{ $item->lib_pays }}</td>
                       @for ($j=0; $j < $fronts->count(); $j++)
                           @php
                               $tentree = TechnoDev::joinFluxData($item->id, $fs[$j], $dtone, $dtwo)->tentree ?? 0;
                               $tsortie = TechnoDev::joinFluxData($item->id, $fs[$j], $dtone, $dtwo)->tsortie ?? 0;
                           @endphp
                           <td>{{ ($tentree != 0) ? $tentree : '' }}</td>
                           <td>{{ ($tsortie != 0) ? $tsortie : '' }}</td>
                       @endfor
                   </tr>
               @empty
                   <!-- Votre message en cas de liste vide -->
               @endforelse
                </table>
=======
                @endforelse
                <tr style="background-color: #b6b6b6">
                    <td> <strong>Total</strong></td>
                    <td colspan="{{ 2 * $fronts->count() }}"></td>
                    <td><strong>{{$totalEntrées}}</strong></td>
                    <td><strong>{{$totalSorties}}</strong></td>
                </tr>
            </table>
            
>>>>>>> ae561043251a314241e1cc3835dca85672bbc5c7
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script>
    $(function(){
        $("a.btn-print").on('click',function(){
            document.body.style.background = 'white';
            var titre ="";
            var footer = "";
            var content = document.getElementById('printable').innerHTML;
            var old = document.body.innerHTML;
            document.body.innerHTML = titre+content+footer;
            window.print();
            document.body.innerHTML = old;
            return false;
        });
    });

    // function printContent(content){
    //         document.body.style.background = 'white';
    //         $("body").css({
    //             "background-color":"white"
    //         });
    //         var titre ="";
    //         var footer = "";

    //         var old = $("body").html();
    //         $("body").html(titre+content+footer);
    //         window.print();
    //         $("body").html(old);
    // }
</script>
@endsection



