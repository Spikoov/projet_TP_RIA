<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta charset="utf-8">
        <title>Projet TP RIA</title>
    </head>
    <body>
        <div class="w3-bar w3-indigo" style="overflow: hidden; position: fixed; top: 0; width: 100%;">
            @isset($nomEquipe)
                <div class="w3-bar-item w3-large">{{ $nomEquipe }}</div>
                <div class="w3-bar-item w3-large">Budget: {{ $budgetEquipe }}ß</div>
            @endisset
            <div class="w3-bar-item w3-display-topmiddle w3-large">M1DFS La Primera Liga</div>
            <div class="w3-bar-item w3-right w3-large">Saison: {{ 0 }}</div>
        </div>

        <div class="w3-sidebar w3-card w3-bar-block w3-border w3-hoverable" style="width:20%; top: 43px">
            <div class="w3-light-blue w3-bar-item w3-display-container">
                <span>Effectif</span>
                @isset($nomEquipe)
                    <span type="button" id="btn-formation" class="w3-button w3-hover-cyan w3-small w3-display-right">
                        Formation: {{ $equipe->getOrganisation() }}
                    </span>
                @endisset
            </div>
            @isset($titulaires)
                <ul class="w3-bar-item w3-ul w3-card w3-hoverable">
                    <li class="w3-display-container w3-hover-white">
                        <h4>Titulaires</h4>
                        @empty($changer)
                            <a href="/changerTitulaire"type="button" class="w3-display-right w3-button w3-light-grey">Changer</a>
                        @endempty
                    </li>
                    @foreach($titulaires as $titulaire)
                        <li class="w3-bar w3-display-container">
                            <span class="w3-badge w3-teal w3-display-topleft">{{ $titulaire['note'] }} / 100</span><br>
                            <div class="w3-bar-item">
                                <span class="w3-large w3-display-left">{{ $titulaire['nom'] }}</span><br>
                                <span class="w3-small w3-display-bottomleft">{{ $titulaire['age']}} ans</span>
                                <span class="w3-display-topright">{{ ucfirst($titulaire['poste']) }}</span>
                                <span class="w3-display-right w3-small">Salaire: {{ $titulaire['salaire'] }}ß/an</span>
                                <span class="w3-display-bottomright w3-small">Durée du contrat: {{ $titulaire['dureeContrat'] }} ans</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endisset
            @isset($remplacants)
                <ul class="w3-bar-item w3-ul w3-card w3-hoverable">
                    <li class="w3-display-container w3-hover-white">
                        <h4>Remplaçants</h4>
                        @empty($changer)
                            <a href="/changerRemplacant"type="button" class="w3-display-right w3-button w3-light-grey">Changer</a>
                        @endempty
                    </li>
                    @for($i = 0; $i < 3; $i++)
                        @if($remplacants[$i] != -1)
                            <li class="w3-bar w3-display-container">
                                <span class="w3-badge w3-teal w3-display-topleft">{{ $remplacants[$i]['note'] }} / 100</span><br>
                                <div class="w3-bar-item">
                                    <span class="w3-large w3-display-left">{{ $remplacants[$i]['nom'] }}</span><br>
                                    <span class="w3-small w3-display-bottomleft">{{ $remplacants[$i]['age']}} ans</span>
                                    <span class="w3-display-topright">{{ ucfirst($remplacants[$i]['poste']) }}</span>
                                    <span class="w3-display-right w3-small">Salaire: {{ $remplacants[$i]['salaire'] }}ß/an</span>
                                    <span class="w3-display-bottomright w3-small">Durée du contrat: {{ $remplacants[$i]['dureeContrat'] }} ans</span>
                                </div>
                            </li>
                        @else
                            <li class="w3-bar w3-display-container">
                                <div class="w3-bar-item">
                                    <span class="w3-display-middle w3-large">+</span>
                                </div>
                            </li>
                        @endif
                    @endfor
                </ul>
            @endisset
            @isset($autres)
                <ul class="w3-bar-item w3-ul w3-card w3-hoverable">
                    <li class="w3-hover-white"><h4>Reste de l'effectif</h4></li>
                    @if($autres != -1)
                        @foreach($autres as $autre)
                            <li class="w3-bar w3-display-container">
                                <span class="w3-badge w3-teal w3-display-topleft">{{ $autre['note'] }} / 100</span><br>
                                <div class="w3-bar-item">
                                    <span class="w3-large w3-display-left">{{ $autre['nom'] }}</span><br>
                                    <span class="w3-small w3-display-bottomleft">{{ $autre['age']}} ans</span>
                                    <span class="w3-display-topright">{{ ucfirst($autre['poste']) }}</span>
                                    <span class="w3-display-right w3-small">Salaire: {{ $autre['salaire'] }}ß/an</span>
                                    <span class="w3-display-bottomright w3-small">Durée du contrat: {{ $autre['dureeContrat'] }} ans</span>
                                </div>
                            </li>
                        @endforeach
                    @endif
                    <li class="w3-bar w3-display-container">
                        <a href="/selectEffectif" class="w3-button w3-bar-item">
                            <span class="w3-display-middle w3-large">+</span>
                        </a>
                    </li>
                </ul>
                <br><br>
            @endisset
        </div>

        <div id="changerFormation" class="w3-sidebar w3-card w3-bar-block w3-border" style="display: none; top: 43px; width:20%;">
            <div class="w3-light-blue w3-bar-item">
                <span id="close-btn-formation" type="button" class="w3-button w3-hover-cyan">&larr;</span>
                <span>Selectionner nouvelle formation:</span>
            </div>
            <form action="/changerFormation" method="post">
                {{ csrf_field() }}
                <input class="w3-button w3-bar" name="nForm" type="submit" value="1-2-1">
                <input class="w3-button w3-bar" name="nForm" type="submit" value="2-1-1">
                <input class="w3-button w3-bar" name="nForm" type="submit" value="1-1-2">
            </form>
        </div>

        <div class="w3-sidebar w3-card w3-bar-block w3-border" style="width:20%; right:0; top: 43px">
            <div class="w3-light-blue w3-bar-item w3-right">
                <span class="w3-right">Classement</span>
            </div>
            @isset($classementEquipes)
                <table class="w3-table-all w3-hoverable">
                    <thead>
                        <th>/</th>
                        <th>Club</th>
                        <th>Points</th>
                    </thead>
                    <tbody>
                        @foreach($classementEquipes as $equ)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $equ['nom'] }}</td>
                                <td>{{ $equ['points'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endisset
        </div>
        <div style="margin-top: 43px; margin-left: 20%; margin-right: 20%;">
            @yield('content')
        </div>
    </body>
    @isset($equipe)
    <script>
        $("#btn-formation").click(function(){
            $("input[value={{ $equipe->getOrganisation() }}]").attr("disabled", "true")
            $("#changerFormation").css("display", "block")
        })

        $("#close-btn-formation").click(function(){
            $("#changerFormation").css("display", "none")
        })
    </script>
    @endisset
</html>
