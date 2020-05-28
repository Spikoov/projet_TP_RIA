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
                <span id="btn-formation" type="button" class="w3-button w3-hover-cyan w3-display-right w3-small">Formation: @isset($equipe) {{ $equipe->getOrganisation() }} @endisset</span>
            </div>
            @isset($titulaires)
                <ul class="w3-bar-item w3-ul w3-card w3-hoverable">
                    <li><h4>Titulaires</h4></li>
                    @foreach($titulaires as $titulaire)
                        <li class="w3-bar w3-display-container">
                            <span class="w3-badge w3-teal">{{ $titulaire['note'] }} / 100</span>
                            <div class="w3-bar-item">
                                <span class="w3-large">{{ $titulaire['nom'] }}</span><br>
                                <span class="w3-small">{{ $titulaire['age']}} ans</span>
                                <span class="w3-display-topright">{{ ucfirst($titulaire['poste']) }}</span>
                                <span class="w3-display-right w3-small">Salaire: {{ $titulaire['salaire'] }}ß/an</span>
                                <span class="w3-display-bottomright w3-small">Durée du contrat: {{ $titulaire['dureeContrat'] }} ans</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endisset
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
        <div style="margin-top: 43px">
            @yield('content')
        </div>
    </body>
    <script>
        $("#btn-formation").click(function(){
            alert('Changer formation')
        })
    </script>
</html>
