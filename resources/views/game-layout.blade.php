<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <meta charset="utf-8">
    <title>Projet TP RIA</title>
  </head>
  <body>
        <div class="w3-bar w3-indigo">
            @if(isset($nomEquipe))
                <div class="w3-bar-item">{{ $nomEquipe }}</div>
                <div class="w3-bar-item">{{ $budgetEquipe }}ß</div>
            @endif
            <div class="w3-bar-item w3-display-topmiddle">M1DFS La Primera Liga</div>
            <div class="w3-bar-item w3-right">Saison: {{ 0 }}</div>
        </div>

        <div class="w3-sidebar w3-card w3-bar-block w3-border w3-hoverable" style="width:15%">
            <div class="w3-light-blue w3-bar-item">Effectif</div>
            @if(isset($titulaires))
                <table class="w3-bar-item w3-table">
                    <thead>
                        <th>Titulaires</th>
                    </thead>
                    <tbody>
                        @foreach($titulaires as $titulaire)
                            <tr>
                                <td class="w3-card">
                                    <div class="w3-container">
                                        {{ $titulaire['nom'] }}
                                        {{ $titulaire['poste'] }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="w3-sidebar w3-card w3-bar-block w3-border" style="width:15%; right:0">
            <a href="#" class="w3-bar-item w3-button">Link 1</a>
            <a href="#" class="w3-bar-item w3-button">Link 2</a>
            <a href="#" class="w3-bar-item w3-button">Link 3</a>
        </div>
      @yield('content')
  </body>
</html>
