@extends('game-layout')

@section('content')
        <ul id="listJoueursA" class="w3-bar-item w3-ul w3-card w3-hoverable w3-container">
            <li class="w3-display-container w3-hover-white">
                <h4>Sélectionnez le joueur à changer</h4>
            </li>
                <li id="playerA" type="button" class="w3-button w3-bar w3-display-container" value="{{ $titulaires[4]['id'] }}">
                    <span class="w3-badge w3-teal w3-display-topleft">{{ $titulaires[4]['note'] }} / 100</span><br>
                    <div class="w3-bar-item">
                        <span class="w3-large w3-display-left">{{ $titulaires[4]['nom'] }}</span><br>
                        <span class="w3-small w3-display-bottomleft">{{ $titulaires[4]['age']}} ans</span>
                        <span id="posteA" class="w3-display-topright">{{ ucfirst($titulaires[4]['poste']) }}</span>
                        <span class="w3-display-right w3-small">Salaire: {{ $titulaires[4]['salaire'] }}ß/an</span>
                        <span class="w3-display-bottomright w3-small">Durée du contrat: {{ $titulaires[4]['dureeContrat'] }} ans</span>
                    </div>
                </li>
        </ul>
        <ul id="listJoueursB" class="w3-bar-item w3-ul w3-card w3-hoverable w3-container" style="display: none;">
            <li class="w3-display-container w3-hover-white">
                <h4>Sélectionnez le joueur par lequel le remplacer</h4>
            </li>
            <li class="w3-display-container w3-hover-white">
                <h5>Remplacants</h5>
            </li>
            @foreach($remplacants as $remplacant)
                <li id="playerB" type="button" class="w3-button w3-bar w3-display-container" value="{{ $remplacant['id'] }}" style="">
                    <span class="w3-badge w3-teal w3-display-topleft">{{ $remplacant['note'] }} / 100</span><br>
                    <div class="w3-bar-item">
                        <span class="w3-large w3-display-left">{{ $remplacant['nom'] }}</span><br>
                        <span class="w3-small w3-display-bottomleft">{{ $remplacant['age']}} ans</span>
                        <span id="posteB" class="w3-display-topright">{{ ucfirst($remplacant['poste']) }}</span>
                        <span class="w3-display-right w3-small">Salaire: {{ $remplacant['salaire'] }}ß/an</span>
                        <span class="w3-display-bottomright w3-small">Durée du contrat: {{ $remplacant['dureeContrat'] }} ans</span>
                    </div>
                </li>
            @endforeach
            @if($autres != -1)
                <li class="w3-display-container w3-hover-white">
                    <h5>Reste de l'effectif</h5>
                </li>
                @foreach($autres as $autre)
                    <li id="playerB" type="button" class="w3-button w3-bar w3-display-container" value="{{ $autre['id'] }}" style="">
                        <span class="w3-badge w3-teal w3-display-topleft">{{ $autre['note'] }} / 100</span><br>
                        <div class="w3-bar-item">
                            <span class="w3-large w3-display-left">{{ $autre['nom'] }}</span><br>
                            <span class="w3-small w3-display-bottomleft">{{ $autre['age']}} ans</span>
                            <span id="posteB" class="w3-display-topright">{{ ucfirst($autre['poste']) }}</span>
                            <span class="w3-display-right w3-small">Salaire: {{ $autre['salaire'] }}ß/an</span>
                            <span class="w3-display-bottomright w3-small">Durée du contrat: {{ $autre['dureeContrat'] }} ans</span>
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
    <script>
        $("[id=playerA]").click(function(){
            $("#listJoueursA").css("display", "none")
            $("#listJoueursB").css("display", "block")
            var A = $(this).val()
            $("[id=playerB]").click(function(){
                var B = $(this).val()
                var form = document.createElement('form');
                document.body.appendChild(form);
                form.id = 'form'
                form.method = 'post';
                form.action = '/changerJoueurs';

                $("#form").append( '{{ csrf_field() }}' )

                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'idJoueurA';
                input.value = A;
                form.appendChild(input);

                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'idJoueurB';
                input.value = B;
                form.appendChild(input);

                form.submit();
            })
        })

        $("[id=playerA]").click(function(){

            for (var player of $("[id=playerB]")) {
                if (player.children[2].children[3].innerText != '{{ $poste }}') {
                    player.attributes['style'].value = "display: none"
                }
            }
        })
    </script>
@endsection
