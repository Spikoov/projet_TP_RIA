<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <title>Projet TP RIA</title>
    </head>
    <body>
        <div class="w3-bar w3-indigo" style="overflow: hidden; position: fixed; top: 0; width: 100%;">
            @isset($nomEquipe)
                <div class="w3-hover-deep-purple w3-button w3-bar-item w3-large">{{ $nomEquipe }}</div>
                <div class="w3-bar-item w3-large">Budget: {{ $budgetEquipe }}ß</div>
            @endisset
            <div class="w3-bar-item w3-display-topmiddle w3-large">M1DFS La Primera Liga</div>
            @isset($saison)
                <div class="w3-bar-item w3-right w3-large">Saison: {{ $saison }}</div>
            @endisset
        </div>

        <div style="margin-top: 43px;">
            @yield('content')
        </div>
    </body>
</html>
