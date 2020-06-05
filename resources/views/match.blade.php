@extends('match-layout')

@section('content')
<div class="w3-sidebar w3-card w3-bar-block w3-border w3-hoverable" style="width:20%; top: 43px">
    @isset($titulairesA)
        <ul id="t" class="w3-bar-item w3-ul w3-card w3-hoverable">
            <li class="w3-display-container w3-hover-white">
                <h4>Titulaires</h4>
                <button id="changementJoueur" type="button" class="w3-display-right w3-button w3-light-grey">Changement de joueur</button>
                <button id="changer" type="button" class="w3-display-right w3-button w3-light-grey" style="display: none">Changer</button>
            </li>
            @foreach($titulairesA as $titulaire)
                <li id="{{ $titulaire['id'] }}" class="w3-bar w3-display-container">
                    <span class="w3-badge w3-teal w3-display-topleft">{{ $titulaire['noteGlobale'] }} / 100</span><br>
                    <div class="w3-bar-item">
                        <span class="w3-large w3-display-left">{{ $titulaire['prenom'] . ' ' . $titulaire['nom'] }}</span><br>
                        <span class="w3-small w3-display-bottomleft">{{ $titulaire['age']}} ans</span>
                        <span class="w3-display-topright">{{ ucfirst($titulaire['poste']) }}</span>
                        <input class="w3-display-right" type="radio" id="{{ $titulaire['poste'] }}" name="joueurT" value="{{ $titulaire['id'] }}" onchange="testingPoste()">
                        <div class="w3-light-grey w3-display-bottom">
                            <div id="formet{{ $titulaire['id'] }}" value="{{$titulaire['endurance']}}" class="w3-green w3-right-align" style="height: 20px; width: 100%">100</div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endisset

    @isset($remplacants)
        <ul class="w3-bar-item w3-ul w3-card w3-hoverable">
        <li class="w3-display-container w3-hover-white">
            <h4>Remplaçants</h4>
        </li>
        @for($i = 0; $i < 3; $i++)
            @if($remplacants[$i] != -1)
                <li id="{{$remplacants[$i]['id']}}" class="w3-bar w3-display-container">
                    <span class="w3-badge w3-teal w3-display-topleft">{{ $remplacants[$i]['noteGlobale'] }} / 100</span><br>
                    <div class="w3-bar-item">
                        <span class="w3-large w3-display-left">{{ $remplacants[$i]['prenom'] . ' ' . $remplacants[$i]['nom'] }}</span><br>
                        <span class="w3-small w3-display-bottomleft">{{ $remplacants[$i]['age']}} ans</span>
                        <span class="w3-display-topright">{{ ucfirst($remplacants[$i]['poste']) }}</span>
                        <input class="w3-display-right" type="radio" id="{{ $remplacants[$i]['poste'] }}" name="joueurR" value="{{ $remplacants[$i]['id'] }}">
                        <div class="w3-light-grey w3-display-bottom">
                            <div id="former{{$remplacants[$i]['id']}}" value="{{$remplacants[$i]['endurance']}}" class="w3-green w3-right-align" style="height: 5px; width: 100%"></div>
                        </div>
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
        </ul><br><br>
    @endisset
</div>

<div class="w3-sidebar w3-card w3-bar-block w3-border w3-hoverable" style="width:20%; top: 43px; right: 0">
    <ul id="log" class="w3-ul">
    </ul>
</div>

<div class="w3-display-topmiddle" style="margin-top: 43px;"><br>
    <div class="w3-display-container w3-large">
        <button id="start" class="w3-light-grey w3-button w3-display-middle" type="button">Commencer</button>
        <button id="pause" class="w3-light-grey w3-button w3-display-middle" type="button" style="display: none;">Pause</button>
        <button id="finMatch" class="w3-light-grey w3-button w3-display-middle" type="button" style="display: none;">Terminer le match</button>
    </div><br><br>

    <div class="w3-display-container w3-xxlarge">
        <div id="score" class="w3-display-middle">
        </div>
    </div><br>

    <table class="w3-center" style="background-image: url('/img/soccer_field.png'); background-size: cover" width="1000" height="661">
        <thead hidden>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </thead>
        <tbody style="font-size: 30px;">
            <tr id="top">
                <td></td>
                <td id="dfGT"></td>
                <td id="mlGT"></td>
                <td id="atGT"></td>
                <td></td>
                <td id="atRT"></td>
                <td id="mlRT"></td>
                <td id="dfRT"></td>
                <td></td>
            </tr>
            <tr id="middle">
                <td id="gkG"></td>
                <td id="dfGM"></td>
                <td id="mlGM"></td>
                <td id="atGM"></td>
                <td>&#9917;</td>
                <td id="atRM"></td>
                <td id="mlRM"></td>
                <td id="dfRM"></td>
                <td id="gkR"></td>
            </tr>
            <tr id="bot">
                <td></td>
                <td id="dfGB"></td>
                <td id="mlGB"></td>
                <td id="atGB"></td>
                <td></td>
                <td id="atRB"></td>
                <td id="mlRB"></td>
                <td id="dfRB"></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="w3-display-container">
        <span class="w3-display-topmiddle w3-xxlarge" id="timer">00:00</span>
    </div>
</div>
<script>
    var flagChangeDone = false

    function testingPoste(){
      $("#changer").css("display", "none")
      $('input[name="joueurR"]').prop('checked', false)
      $('input[name="joueurR"]').removeAttr('disabled')
      var posteChecked = $("input[name='joueurT']:checked").attr('id')
      if(posteChecked == "gardien"){
        $("input[id='defense'][name='joueurR']").attr('disabled', 'disabled')
        $("input[id='milieu'][name='joueurR']").attr('disabled', 'disabled')
        $("input[id='attaque'][name='joueurR']").attr('disabled', 'disabled')
        $("input[id=posteChecked][name='joueurR']").removeAttr('disabled')
      }
      else if(posteChecked == "attaque"){
        $("input[id='defense'][name='joueurR']").attr('disabled', 'disabled')
        $("input[id='milieu'][name='joueurR']").attr('disabled', 'disabled')
        $("input[id='attaque'][name='joueurR']").removeAttr('disabled')
        $("input[id='gardien'][name='joueurR']").attr('disabled', 'disabled')
      }
      else if(posteChecked == "defense"){
        $("input[id='defense'][name='joueurR']").removeAttr('disabled')
        $("input[id='milieu'][name='joueurR']").attr('disabled', 'disabled')
        $("input[id='attaque'][name='joueurR']").attr('disabled', 'disabled')
        $("input[id='gardien'][name='joueurR']").attr('disabled', 'disabled')
      }
      else{
        $("input[id='defense'][name='joueurR']").attr('disabled', 'disabled')
        $("input[id='milieu'][name='joueurR']").removeAttr('disabled')
        $("input[id='attaque'][name='joueurR']").attr('disabled', 'disabled')
        $("input[id='gardien'][name='joueurR']").attr('disabled', 'disabled')
      }


      $("input[name='joueurR']").click(function(){
          if ($("input[name='joueurR']:checked").val()) {
              $("#changer").css("display", "block")
          }
      })

    }

    function changementJoueur(){
        var idTitulaire = $("input[name='joueurT']:checked").val();
        var idRemplacant = $("input[name='joueurR']:checked").val();

        var liRempl = $("#" + idRemplacant).html()
        $("#" + idRemplacant).html($("#" + idTitulaire).html())
        $("#" + idTitulaire).html(liRempl)

        $("#former" + idRemplacant).css("height", "20px")
        $("#former" + idRemplacant).text(100)

        $("#formet" + idTitulaire).css("height", "5px")
        $("#formet" + idTitulaire).text("")

        var newIDr = "formet" + idTitulaire
        var newIDt =  "former" + idRemplacant

        console.log(newIDt);

        $("#former" + idRemplacant).attr("id", "r")
        $("#formet" + idTitulaire).attr("id", "t")

        $("#r").attr("id", newIDr)
        $("#t").attr("id", newIDt)

        $("#changer").css("display", "none")
        flagChangeDone = true
    }

    $("#finMatch").click(function(){
        var scoreA = $("#scoreA").text()
        var scoreB = $("#scoreB").text()

        var form = document.createElement('form');
        document.body.appendChild(form);
        form.id = 'form'
        form.method = 'post';
        form.action = '/match';

        $("#form").append( '{{ csrf_field() }}' )

        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'scoreA';
        input.value = scoreA;
        form.appendChild(input);

        input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'scoreB';
        input.value = scoreB;
        form.appendChild(input);

        form.submit();
    })

    //--------------------------------------------------------------------------

    $('input[name="joueurT"]').attr('disabled', 'disabled')
    $('input[name="joueurR"]').attr('disabled', 'disabled')
    $('input[name="joueurT"]').prop('checked', false)
    $('input[name="joueurR"]').prop('checked', false)

    var tituB = []
    var tituA = []
    var remplA = []
    getInfos()

    var end = 90;

    var minutes = 0;
    var seconds = 0;

    $("#start").click(function() {
        $(this).css("display", "none")
        $(this).text("Reprendre")
        $("#pause").css("display", "block")
        if (flagChangeDone == true) {
            $("#changementJoueur").css("display", "none")
            $("#changer").css("display", "none")
        }
        else {
            $("#changementJoueur").css("display", "block")
            $("#changer").css("display", "none")
        }

        $('input[name="joueurT"]').prop('checked', false)
        $('input[name="joueurR"]').prop('checked', false)
        $('input[name="joueurT"]').attr('disabled', 'disabled')
        $('input[name="joueurR"]').attr('disabled', 'disabled')

        var timer = setInterval(function() {
            if(minutes == end){
                $(this).css("display", "none")
                $("#pause").css("display", "none")
                $("#finMatch").css("display", "block")

                clearInterval(timer)
            }

            if(minutes == 45 && seconds == 0){
                $("#start").css("display", "block")
                $("#start").text("Jouer la 2nde mi-temps")
                $("#pause").css("display", "none")

                clearInterval(timer)
            }

            if(minutes == 45 && seconds == 9){
                console.log("bient");
                miTemps()
            }

            if (seconds == 60) {
                minutes++
                seconds = 0
                updateFormeJoueurs(minutes)
                functionThatDoesNotExists()
            }

            if(minutes % 5 == 0 && seconds == 0 && minutes != 0){
                match();
            }

            if ((minutes == 40 || minutes == 60 || minutes == 90) && seconds == 0 ) {
                tir()
            }

            seconds++
            if (minutes < 10 && seconds < 10) {
                $("#timer").text("0" + minutes + ":0" + seconds)
            }
            else if (minutes < 10) {
                $("#timer").text("0" + minutes + ":" + seconds)
            }
            else if (minutes >= 10 && seconds < 10) {
                $("#timer").text(minutes + ":0" + seconds)
            }
            else {
                $("#timer").text(minutes + ":" + seconds)
            }
        }, 0.3);

        $("#pause").click(function() {
            $(this).css("display", "none")
            $("#start").css("display", "block")

            clearInterval(timer)
        })

        $("#changementJoueur").click(function(){
            $("#pause").css("display", "none")
            $("#start").css("display", "block")
            $('input[name="joueurT"]').removeAttr('disabled')
            $(this).css("display", "none")
            $("#changer").css("display", "none")

            clearInterval(timer)
            $("#changer").click(function(){
                changementJoueur()
            })
        })
    })

    function tir() {
        if (orgaA == "1-1-2") {
            var at0A = tituA[3][5]
            var at1A = tituA[4][5]

            var gkB = tituB[0][11]

            var tir0 = Math.ceil(Math.random()*100)
            var tir1 = Math.ceil(Math.random()*100)
            var arret = Math.ceil(Math.random()*100)

            if ((tir0 <= at0A ||tir1 <=at1A) && arret > gkB ) { //tir réussi et arret raté
                $("#scoreA").text(parseInt($("#scoreA").text()) + 1)
            }
        }
        else {
            var atA = tituA[3][5]

            var gkB = tituB[0][11]

            var tir = Math.ceil(Math.random()*100)
            var arret = Math.ceil(Math.random()*100)

            if (tir <= atA && arret > gkB ) { //tir réussi et arret raté
                $("#scoreA").text(parseInt($("#scoreA").text()) + 1)
            }
        }

        if (orgaB == "1-1-2") {
            var at0B = tituB[3][5]
            var at1B = tituB[4][5]

            var gkA = tituA[0][11]

            var tir0 = Math.ceil(Math.random()*100)
            var tir1 = Math.ceil(Math.random()*100)
            var arret = Math.ceil(Math.random()*100)

            if ((tir0 <= at0B ||tir1 <=at1B) && arret > gkA ) { //tir réussi et arret raté
                $("#scoreB").text(parseInt($("#scoreB").text()) + 1)
            }
        }
        else {
            var atB = tituB[3][5]

            var gkA = tituA[0][11]

            var tir = Math.ceil(Math.random()*100)
            var arret = Math.ceil(Math.random()*100)

            if (tir <= atB && arret > gkA ) { //tir réussi et arret raté
                $("#scoreB").text(parseInt($("#scoreB").text()) + 1)
            }
        }
    }

    function match() {
        if ('{{ $isDomi }}' == 'domi') {
            if (minutes%10 != 0) { //equA
                if (orgaA == "2-1-1") {
                    var mlA = tituA[2][6]
                    var atA = tituA[3][5]

                    if (orgaB == "2-1-1") {
                        var df0B = tituB[1][8]
                        var df1B = tituB[4][8]
                        var gkB = tituB[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlA) { //passe reussie
                            $("#log").html("<li id=\"ac" + minutes + "\" class=\"w3-light-blue\">" + $("#log").html())
                            $("#ac" + minutes).html("<div>Action engagée pour {{ $nomEquipe }}</div>")
                            var tacle0 = Math.ceil(Math.random()*100)
                            var tacle1 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0B || tacle1 > df1B) { //tacles échoués
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant de {{ $nomEquipe }} esquive les défenseurs adverses !</div>")
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atA && arret > gkB ) { //tir réussi et arret raté
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>BUT POUR {{ $nomEquipe }} !!</div>")
                                    $("#scoreA").text(parseInt($("#scoreA").text()) + 1)
                                }
                                else{
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>Arrêt du gardien de {{ $nomEquipeAdv }} !!</div>")
                                }
                            }
                            else {
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant est taclé par la défense de {{$nomEquipeAdv}} !</div>")
                            }
                        }
                        $("#log").html("</li>" + $("#log").html())
                        $("#ac" + (minutes - 10)).removeClass("w3-light-blue").addClass("w3-pale-blue")
                    }
                    else {
                        var df0B = tituB[1][8]
                        var gkB = tituB[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlA) { //passe reussie
                            $("#log").html("<li id=\"ac" + minutes + "\" class=\"w3-light-blue\">" + $("#log").html())
                            $("#ac" + minutes).html("<div>Action engagée pour {{ $nomEquipe }}</div>")
                            var tacle0 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0B) { //tacle échoué
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant de {{ $nomEquipe }} traverse la défense adverse !</div>")
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if ((tir <= atA && arret > gkB) || tir < 10) { //tir réussi et arret raté
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>BUT POUR {{ $nomEquipe }} !!</div>")
                                    $("#scoreA").text(parseInt($("#scoreA").text()) + 1)
                                }
                                else {
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>Arrêt du gardien de {{ $nomEquipeAdv }} !!</div>")
                                }
                            }
                            else {
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant est taclé par la défense de {{$nomEquipeAdv}} !</div>")
                            }
                            $("#log").html("</li>" + $("#log").html())
                            $("#ac" + (minutes - 10)).removeClass("w3-light-blue").addClass("w3-pale-blue")
                        }
                    }
                }
                else if (orgaA == "1-2-1") {
                    var ml0A = tituA[2][6]
                    var ml1A = tituA[4][6]
                    var atA = tituA[3][5]

                    if (orgaB == "2-1-1") {
                        var df0B = tituB[1][8]
                        var df1B = tituB[4][8]
                        var gkB = tituB[0][11]

                        var passe0 = Math.ceil(Math.random()*100)
                        var passe1 = Math.ceil(Math.random()*100)

                        if (passe0 <= ml0A || passe1 <= ml1A) { //passe reussie
                            $("#log").html("<li id=\"ac" + minutes + "\" class=\"w3-light-blue\">" + $("#log").html())
                            $("#ac" + minutes).html("<div>Action engagée pour {{ $nomEquipe }}</div>")
                            var tacle0 = Math.ceil(Math.random()*100)
                            var tacle1 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0B || tacle1 > df1B) { //tacles échoués
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant de {{ $nomEquipe }} traverse la défense adverse !</div>")
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atA && arret > gkB) { //tir réussi et arret raté
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>BUT POUR {{ $nomEquipe }} !!</div>")
                                    $("#scoreA").text(parseInt($("#scoreA").text()) + 1)
                                }
                                else {
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>Arrêt du gardien de {{ $nomEquipeAdv }} !!</div>")
                                }
                            }
                            else {
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant est taclé par la défense de {{$nomEquipeAdv}} !</div>")
                            }
                            $("#log").html("</li>" + $("#log").html())
                            $("#ac" + (minutes - 10)).removeClass("w3-light-blue").addClass("w3-pale-blue")
                        }
                    }
                    else {
                        var df0B = tituB[1][8]
                        var gkB = tituB[0][11]

                        var passe0 = Math.ceil(Math.random()*100)
                        var passe1 = Math.ceil(Math.random()*100)

                        if (passe0 <= ml0A || passe1 <= ml1A) { //passe reussie
                            $("#log").html("<li id=\"ac" + minutes + "\" class=\"w3-light-blue\">" + $("#log").html())
                            $("#ac" + minutes).html("<div>Action engagée pour {{ $nomEquipe }}</div>")
                            var tacle0 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0B) { //tacle échoué
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant de {{ $nomEquipe }} traverse la défense adverse !</div>")
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atA && arret > gkB) { //tir réussi et arret raté
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>BUT POUR {{ $nomEquipe }} !!</div>")
                                    $("#scoreA").text(parseInt($("#scoreA").text()) + 1)
                                }
                                else {
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>Arrêt du gardien de {{ $nomEquipeAdv }} !!</div>")
                                }
                            }
                            else {
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant est taclé par la défense de {{$nomEquipeAdv}} !</div>")
                            }
                            $("#log").html("</li>" + $("#log").html())
	                        $("#ac" + (minutes - 10)).removeClass("w3-light-blue").addClass("w3-pale-blue")
                        }
                    }
                }
                else {
                    var mlA = tituA[2][6]
                    var at1A = tituA[4][5]
                    var at0A = tituA[3][5]

                    if (orgaB == "2-1-1") {
                        var df0B = tituB[1][8]
                        var df1B = tituB[4][8]
                        var gkB = tituB[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlA) { //passe reussie
                            var tacle0 = Math.ceil(Math.random()*100)
                            var tacle1 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0B || tacle1 > df1B) { //tacles échoués
                                var tir0 = Math.ceil(Math.random()*100)
                                var tir1 = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if ((tir0 <= at0A || tir1 <= at1A) && arret > gkB) { //tir réussi et arret raté
                                    $("#scoreA").text(parseInt($("#scoreA").text()) + 1)
                                }
                            }
                        }
                    }
                    else {
                        var df0B = tituB[1][8]
                        var gkB = tituB[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlA) { //passe reussie
                            var tacle0 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0B) { //tacle échoué
                                var tir0 = Math.ceil(Math.random()*100)
                                var tir1 = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if ((tir0 <= at0A || tir1 <= at1A) && arret > gkB) { //tir réussi et arret raté
                                    $("#scoreA").text(parseInt($("#scoreA").text()) + 1)
                                }
                            }
                        }
                    }
                }
            }
            else { //equB
                if (orgaB == "2-1-1") {
                    var mlB = tituB[2][6]
                    var atB = tituB[3][5]

                    if (orgaA == "2-1-1") {
                        var df0A = tituA[1][8]
                        var df1A = tituA[4][8]
                        var gkA = tituA[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlB) { //passe reussie
                            var tacle0 = Math.ceil(Math.random()*100)
                            var tacle1 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0A || tacle1 > df1A) { //tacles échoués
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atB && arret > gkA) { //tir réussi et arret raté
                                    $("#scoreB").text(parseInt($("#scoreB").text()) + 1)
                                }
                            }
                        }
                    }
                    else {
                        var dfA = tituA[1][8]
                        var gkA = tituA[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlB) { //passe reussie
                            var tacle = Math.ceil(Math.random()*100)

                            if (tacle > dfA) { //tacle échoué
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atB && arret > gkA) { //tir réussi et arret raté
                                    $("#scoreB").text(parseInt($("#scoreB").text()) + 1)
                                }
                            }
                        }
                    }
                }
                else if (orgaB == "1-2-1") {
                    var ml0B = tituB[2][6]
                    var ml1B = tituB[4][6]
                    var atB = tituB[3][5]

                    if (orgaA == "2-1-1") {
                        var df0A = tituA[1][8]
                        var df1A = tituA[4][8]
                        var gkA = tituA[0][11]

                        var passe0 = Math.ceil(Math.random()*100)
                        var passe1 = Math.ceil(Math.random()*100)

                        if (passe0 <= ml0B || passe1 <= ml1B) { //passe reussie
                            var tacle0 = Math.ceil(Math.random()*100)
                            var tacle1 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0A || tacle1 > df1A) { //tacles échoués
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atB && arret > gkA) { //tir réussi et arret raté
                                    $("#scoreB").text(parseInt($("#scoreB").text()) + 1)
                                }
                            }
                        }
                    }
                    else {
                        var dfA = tituA[1][8]
                        var gkA = tituA[0][11]

                        var passe0 = Math.ceil(Math.random()*100)
                        var passe1 = Math.ceil(Math.random()*100)

                        if (passe0 <= ml0B || passe1 <= ml1B) { //passe reussie
                            var tacle = Math.ceil(Math.random()*100)

                            if (tacle > dfA) { //tacle échoué
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atB && arret > gkA) { //tir réussi et arret raté
                                    $("#scoreB").text(parseInt($("#scoreB").text()) + 1)
                                }
                            }
                        }
                    }
                }
                else {
                    var mlB = tituB[2][6]
                    var at1B = tituB[4][5]
                    var at0B = tituB[3][5]

                    if (orgaA == "2-1-1") {
                        var df0A = tituA[1][8]
                        var df1A = tituA[4][8]
                        var gkA = tituA[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlB) { //passe reussie
                            var tacle0 = Math.ceil(Math.random()*100)
                            var tacle1 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0A || tacle1 > df1A) { //tacles échoués
                                var tir0 = Math.ceil(Math.random()*100)
                                var tir1 = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if ((tir0 <= at0B || tir1 <= at1B) && arret > gkA) { //tir réussi et arret raté
                                    $("#scoreB").text(parseInt($("#scoreB").text()) + 1)
                                }
                            }
                        }
                    }
                    else {
                        var dfA = tituA[1][8]
                        var gkA = tituA[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlB) { //passe reussie
                            var tacle = Math.ceil(Math.random()*100)

                            if (tacle > dfA) { //tacle échoué
                                var tir0 = Math.ceil(Math.random()*100)
                                var tir1 = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if ((tir0 <= at0B || tir1 <= at1B) && arret > gkA) { //tir réussi et arret raté
                                    $("#scoreB").text(parseInt($("#scoreB").text()) + 1)
                                }
                            }
                        }
                    }
                }
            }
        }
        else{
            if (minutes%10 != 0) { //equB
                if (orgaB == "2-1-1") {
                    var mlB = tituB[2][6]
                    var atB = tituB[3][5]

                    if (orgaA == "2-1-1") {
                        var df0A = tituA[1][8]
                        var df1A = tituA[4][8]
                        var gkA = tituA[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlB) { //passe reussie
                          $("#log").html("<li id=\"ac" + minutes + "\" class=\"w3-red\">" + $("#log").html())
                          $("#ac" + minutes).html("<div>Action engagée pour {{ $nomEquipeAdv }}</div>")
                            var tacle0 = Math.ceil(Math.random()*100)
                            var tacle1 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0A || tacle1 > df1A) { //tacles échoués
                              $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant de {{ $nomEquipeAdv }} perce la défense adverse !</div>")
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atB && arret > gkA) { //tir réussi et arret raté
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>BUT POUR {{ $nomEquipeAdv }} !!</div>")
                                    $("#scoreB").text(parseInt($("#scoreB").text()) + 1)
                                }
                                else {
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>Arrêt du gardien de {{ $nomEquipe }} !!</div>")
                                }
                                }
                            else {
                              $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant est taclé par la défense de {{$nomEquipe}} !</div>")
                            }
                            $("#log").html("</li>" + $("#log").html())
                          	$("#ac" + (minutes - 10)).removeClass("w3-red").addClass("w3-pale-red")
                            }
                        }
                    }
                    else {
                        var dfA = tituA[1][8]
                        var gkA = tituA[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlB) { //passe reussie
                            $("#log").html("<li id=\"ac" + minutes + "\" class=\"w3-red\">" + $("#log").html())
                            $("#ac" + minutes).html("<div>Action engagée pour {{ $nomEquipeAdv }}</div>")
                            var tacle = Math.ceil(Math.random()*100)

                            if (tacle > dfA) { //tacle échoué
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant de {{ $nomEquipeAdv }} traverse la défense adverse !</div>")
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atB && arret > gkA) { //tir réussi et arret raté
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>BUT POUR {{ $nomEquipeAdv }} !!</div>")
                                    $("#scoreB").text(parseInt($("#scoreB").text()) + 1)
                                }
                                else {
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>Arrêt du gardien de {{ $nomEquipe }} !!</div>")
                                }
                            }
                            else {
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant est taclé par la défense de {{$nomEquipe}} !</div>")
                            }
                            $("#log").html("</li>" + $("#log").html())
                            $("#ac" + (minutes - 10)).removeClass("w3-red").addClass("w3-pale-red")
                        }
                    }
                }
                else if (orgaB == "1-2-1") {
                    var ml0B = tituB[2][6]
                    var ml1B = tituB[4][6]
                    var atB = tituB[3][5]

                    if (orgaA == "2-1-1") {
                        var df0A = tituA[1][8]
                        var df1A = tituA[4][8]
                        var gkA = tituA[0][11]

                        var passe0 = Math.ceil(Math.random()*100)
                        var passe1 = Math.ceil(Math.random()*100)

                        if (passe0 <= ml0B || passe1 <= ml1B) { //passe reussie
                          $("#log").html("<li id=\"ac" + minutes + "\" class=\"w3-red\">" + $("#log").html())
                          $("#ac" + minutes).html("<div>Action engagée pour {{ $nomEquipeAdv }}</div>")
                            var tacle0 = Math.ceil(Math.random()*100)
                            var tacle1 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0A || tacle1 > df1A) { //tacles échoués
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant de {{ $nomEquipeAdv }} esquive la défense adverse !</div>")
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atB && arret > gkA) { //tir réussi et arret raté
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>BUT POUR {{ $nomEquipeAdv }} !!</div>")
                                    $("#scoreB").text(parseInt($("#scoreB").text()) + 1)
                                }
                                else {
                                  $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>Arrêt du gardien de {{ $nomEquipe }} !!</div>")
                                }
                            }
                            else {
                              $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant est taclé par la défense de {{$nomEquipe}} !</div>")
                            }
                            $("#log").html("</li>" + $("#log").html())
                          	$("#ac" + (minutes - 10)).removeClass("w3-red").addClass("w3-pale-red")
                            }
                        }
                    }
                    else {
                        var dfA = tituA[1][8]
                        var gkA = tituA[0][11]

                        var passe0 = Math.ceil(Math.random()*100)
                        var passe1 = Math.ceil(Math.random()*100)

                        if (passe0 <= ml0B || passe1 <= ml1B) { //passe reussie
                          $("#log").html("<li id=\"ac" + minutes + "\" class=\"w3-red\">" + $("#log").html())
                          $("#ac" + minutes).html("<div>Action engagée pour {{ $nomEquipeAdv }}</div>")
                            var tacle = Math.ceil(Math.random()*100)

                            if (tacle > dfA) { //tacle échoué
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant de {{ $nomEquipeAdv }} traverse la défense adverse !</div>")
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atB && arret > gkA) { //tir réussi et arret raté
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>BUT POUR {{ $nomEquipeAdv }} !!</div>")
                                    $("#scoreB").text(parseInt($("#scoreB").text()) + 1)
                                }
                                else {
                                  $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>Arrêt du gardien de {{ $nomEquipe }} !!</div>")
                                }
                              }
                              else {
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant est taclé par la défense de {{$nomEquipe}} !</div>")
                              }
                              $("#log").html("</li>" + $("#log").html())
                            	$("#ac" + (minutes - 10)).removeClass("w3-red").addClass("w3-pale-red")
                            }
                        }
                    }
                }
                else {
                    var mlB = tituB[2][6]
                    var at1B = tituB[4][5]
                    var at0B = tituB[3][5]

                    if (orgaA == "2-1-1") {
                        var df0A = tituA[1][8]
                        var df1A = tituA[4][8]
                        var gkA = tituA[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlB) { //passe reussie
                          $("#log").html("<li id=\"ac" + minutes + "\" class=\"w3-red\">" + $("#log").html())
                          $("#ac" + minutes).html("<div>Action engagée pour {{ $nomEquipeAdv }}</div>")
                            var tacle0 = Math.ceil(Math.random()*100)
                            var tacle1 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0A || tacle1 > df1A) { //tacles échoués
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant de {{ $nomEquipeAdv }} traverse la défense adverse !</div>")
                                var tir0 = Math.ceil(Math.random()*100)
                                var tir1 = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if ((tir0 <= at0B || tir1 <= at1B) && arret > gkA) { //tir réussi et arret raté
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>BUT POUR {{ $nomEquipeAdv }} !!</div>")
                                    $("#scoreB").text(parseInt($("#scoreB").text()) + 1)
                                }
                                else {
                                  $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>Arrêt du gardien de {{ $nomEquipe }} !!</div>")
                                }
                              }
                              else {
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant est taclé par la défense de {{$nomEquipe}} !</div>")
                              }
                              $("#log").html("</li>" + $("#log").html())
                            	$("#ac" + (minutes - 10)).removeClass("w3-red").addClass("w3-pale-red")
                            }
                        }
                    }
                    else {
                        var dfA = tituA[1][8]
                        var gkA = tituA[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlB) { //passe reussie
                          $("#log").html("<li id=\"ac" + minutes + "\" class=\"w3-red\">" + $("#log").html())
                          $("#ac" + minutes).html("<div>Action engagée pour {{ $nomEquipeAdv }}</div>")
                            var tacle = Math.ceil(Math.random()*100)

                            if (tacle > dfA) { //tacle échoué
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant de {{ $nomEquipeAdv }} traverse la défense adverse !</div>")
                                var tir0 = Math.ceil(Math.random()*100)
                                var tir1 = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if ((tir0 <= at0B || tir1 <= at1B) && arret > gkA) { //tir réussi et arret raté
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>BUT POUR {{ $nomEquipeAdv }} !!</div>")
                                    $("#scoreB").text(parseInt($("#scoreB").text()) + 1)
                                }
                                else {
                                  $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>Arrêt du gardien de {{ $nomEquipe }} !!</div>")
                                }
                              }
                              else {
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant est taclé par la défense de {{$nomEquipe}} !</div>")
                              }
                              $("#log").html("</li>" + $("#log").html())
                            	$("#ac" + (minutes - 10)).removeClass("w3-red").addClass("w3-pale-red")
                            }
                        }
                    }
                }
            }
            else { //equA
                if (orgaA == "2-1-1") {
                    var mlA = tituA[2][6]
                    var atA = tituA[3][5]

                    if (orgaB == "2-1-1") {
                        var df0B = tituB[1][8]
                        var df1B = tituB[4][8]
                        var gkB = tituB[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlA) { //passe reussie
                            var tacle0 = Math.ceil(Math.random()*100)
                            var tacle1 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0B || tacle1 > df1B) { //tacles échoués
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atA && arret > gkB) { //tir réussi et arret raté
                                    $("#scoreA").text(parseInt($("#scoreA").text()) + 1)
                                }
                            }
                        }
                    }
                    else {
                        var df0B = tituB[1][8]
                        var gkB = tituB[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlA) { //passe reussie
                            var tacle0 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0B) { //tacle échoué
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atA && arret > gkB) { //tir réussi et arret raté
                                    $("#scoreA").text(parseInt($("#scoreA").text()) + 1)
                                }
                            }
                        }
                    }
                }
                else if (orgaA == "1-2-1") {
                    var ml0A = tituA[2][6]
                    var ml1A = tituA[4][6]
                    var atA = tituA[3][5]

                    if (orgaB == "2-1-1") {
                        var df0B = tituB[1][8]
                        var df1B = tituB[4][8]
                        var gkB = tituB[0][11]

                        var passe0 = Math.ceil(Math.random()*100)
                        var passe1 = Math.ceil(Math.random()*100)

                        if (passe0 <= ml0A || passe1 <= ml1A) { //passe reussie
                          $("#log").html("<li id=\"ac" + minutes + "\" class=\"w3-light-blue\">" + $("#log").html())
                          $("#ac" + minutes).html("<div>Action engagée pour {{ $nomEquipe }}</div>")
                            var tacle0 = Math.ceil(Math.random()*100)
                            var tacle1 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0B || tacle1 > df1B) { //tacles échoués
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant de {{ $nomEquipe }} traverse la défense adverse !</div>")
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atA && arret > gkB) { //tir réussi et arret raté
                                    $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>BUT POUR {{ $nomEquipe }} !!</div>")
                                    $("#scoreA").text(parseInt($("#scoreA").text()) + 1)
                                }
                                else {
                                  $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>Arrêt du gardien de {{ $nomEquipeAdv }} !!</div>")
                                }
                              }
                              else {
                                $("#ac" + minutes).html($("#ac" + minutes).html() + "<div>L'attaquant est taclé par la défense de {{$nomEquipeAdv}} !</div>")
                              }
                              $("#log").html("</li>" + $("#log").html())
                              $("#ac" + (minutes - 10)).removeClass("w3-light-blue").addClass("w3-pale-blue")
                            }
                        }
                    }
                    else {
                        var df0B = tituB[1][8]
                        var gkB = tituB[0][11]

                        var passe0 = Math.ceil(Math.random()*100)
                        var passe1 = Math.ceil(Math.random()*100)

                        if (passe0 <= ml0A || passe1 <= ml1A) { //passe reussie
                            var tacle0 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0B) { //tacle échoué
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atA && arret > gkB) { //tir réussi et arret raté
                                    $("#scoreA").text(parseInt($("#scoreA").text()) + 1)
                                }
                            }
                        }
                    }
                }
                else {
                    var mlA = tituA[2][6]
                    var at1A = tituA[4][5]
                    var at0A = tituA[3][5]

                    if (orgaB == "2-1-1") {
                        var df0B = tituB[1][8]
                        var df1B = tituB[4][8]
                        var gkB = tituB[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlA) { //passe reussie
                            var tacle0 = Math.ceil(Math.random()*100)
                            var tacle1 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0B || tacle1 > df1B) { //tacles échoués
                                var tir0 = Math.ceil(Math.random()*100)
                                var tir1 = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if ((tir0 <= at0A || tir1 <= at1A) && arret > gkB) { //tir réussi et arret raté
                                    $("#scoreA").text(parseInt($("#scoreA").text()) + 1)
                                }
                            }
                        }
                    }
                    else {
                        var df0B = tituB[1][8]
                        var gkB = tituB[0][11]

                        var passe = Math.ceil(Math.random()*100)

                        if (passe <= mlA) { //passe reussie
                            var tacle0 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0B) { //tacle échoué
                                var tir0 = Math.ceil(Math.random()*100)
                                var tir1 = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if ((tir0 <= at0A || tir1 <= at1A) && arret > gkB) { //tir réussi et arret raté
                                    $("#scoreA").text(parseInt($("#scoreA").text()) + 1)
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    var orgaA = "{{ $equipeA->getOrganisation() }}"
    var orgaB = "{{ $equipeB->getOrganisation() }}"

    if ('{{ $isDomi }}' == 'domi') {
        //joue à domicile
        $("#score").html("<span class=\"w3-text-indigo\" id=\"scoreA\">0</span> - <span class=\"w3-text-red\" id=\"scoreB\">0</span>")
        if (orgaA == "1-2-1") {
            $("#gkG").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#dfGM").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#mlGT").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#mlGB").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#atGM").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
        }
        else if (orgaA == "2-1-1") {
            $("#gkG").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#dfGT").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#dfGB").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#mlGM").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#atGM").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
        }
        else {
            $("#gkG").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#dfGM").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#mlGM").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#atGT").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#atGB").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
        }


        if (orgaB == "1-2-1") {
            $("#gkR").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#dfRM").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#mlRT").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#mlRB").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#atRM").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
        }
        else if (orgaB == "2-1-1") {
            $("#gkR").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#dfRT").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#dfRB").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#mlRM").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#atRM").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
        }
        else {
            $("#gkR").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#dfRM").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#mlRM").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#atRT").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#atRB").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
        }
    }
    else {
        //joue à l'extérieur
        $("#score").html("<span class=\"w3-text-red\" id=\"scoreB\">0</span> - <span class=\"w3-text-indigo\" id=\"scoreA\">0</span>")
        if (orgaB == "1-2-1") {
            $("#gkG").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#dfGM").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#mlGT").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#mlGB").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#atGM").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
        }
        else if (orgaB == "2-1-1") {
            $("#gkG").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#dfGT").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#dfGB").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#mlGM").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#atGM").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
        }
        else {
            $("#gkG").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#dfGM").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#mlGM").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#atGT").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
            $("#atGB").html('<img src="/img/player-red.png" style="width: 50px;" alt="Avatar">')
        }


        if (orgaA == "1-2-1") {
            $("#gkR").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#dfRM").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#mlRT").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#mlRB").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#atRM").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
        }
        else if (orgaA == "2-1-1") {
            $("#gkR").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#dfRT").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#dfRB").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#mlRM").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#atRM").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
        }
        else {
            $("#gkR").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#dfRM").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#mlRM").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#atRT").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
            $("#atRB").html('<img src="/img/player-blue.png" style="width: 50px;" alt="Avatar">')
        }
    }

    function getInfos() {
        @foreach($titulairesB as $tb)
            var infos = []
            @foreach($tb as $tbinfos)
                infos.push("{{ $tbinfos }}")
            @endforeach
            tituB.push(infos)
        @endforeach


        @foreach($titulairesA as $ta)
            var infos = []
            @foreach($ta as $tainfos)
                infos.push("{{ $tainfos }}")
            @endforeach
            tituA.push(infos)
        @endforeach


        @foreach($remplacants as $r)
            var infos = []
            @foreach($r as $rinfos)
                infos.push("{{ $rinfos }}")
            @endforeach
            remplA.push(infos)
        @endforeach
    }

    function miTemps() {
        var formeActu0 = Math.round($("#formet{{ $titulairesA[0]['id'] }}").width() / $("#formet{{ $titulairesA[0]['id'] }}").parent().width() * 100)
        var formeActu1 = Math.round($("#formet{{ $titulairesA[1]['id'] }}").width() / $("#formet{{ $titulairesA[1]['id'] }}").parent().width() * 100)
        var formeActu2 = Math.round($("#formet{{ $titulairesA[2]['id'] }}").width() / $("#formet{{ $titulairesA[2]['id'] }}").parent().width() * 100)
        var formeActu3 = Math.round($("#formet{{ $titulairesA[3]['id'] }}").width() / $("#formet{{ $titulairesA[3]['id'] }}").parent().width() * 100)
        var formeActu4 = Math.round($("#formet{{ $titulairesA[4]['id'] }}").width() / $("#formet{{ $titulairesA[4]['id'] }}").parent().width() * 100)

        $("#formet{{ $titulairesA[0]['id'] }}").width(formeActu0 + ($("#formet{{ $titulairesA[0]['id'] }}").attr("value")/10) + '%')
        $("#formet{{ $titulairesA[0]['id'] }}").text(formeActu0 + ($("#formet{{ $titulairesA[0]['id'] }}").attr("value")/10))
        $("#formet{{ $titulairesA[1]['id'] }}").width(formeActu1 + ($("#formet{{ $titulairesA[1]['id'] }}").attr("value")/10) + '%')
        $("#formet{{ $titulairesA[1]['id'] }}").text(formeActu1 +($("#formet{{ $titulairesA[1]['id'] }}").attr("value")/10))
        $("#formet{{ $titulairesA[2]['id'] }}").width(formeActu2 + ($("#formet{{ $titulairesA[2]['id'] }}").attr("value")/10) + '%')
        $("#formet{{ $titulairesA[2]['id'] }}").text(formeActu2 +($("#formet{{ $titulairesA[2]['id'] }}").attr("value")/10))
        $("#formet{{ $titulairesA[3]['id'] }}").width(formeActu3 + ($("#formet{{ $titulairesA[3]['id'] }}").attr("value")/10) + '%')
        $("#formet{{ $titulairesA[3]['id'] }}").text(formeActu3 +($("#formet{{ $titulairesA[3]['id'] }}").attr("value")/10))
        $("#formet{{ $titulairesA[4]['id'] }}").width(formeActu4 + ($("#formet{{ $titulairesA[4]['id'] }}").attr("value")/10) + '%')
        $("#formet{{ $titulairesA[4]['id'] }}").text(formeActu4 +($("#formet{{ $titulairesA[4]['id'] }}").attr("value")/10))

        if ($("#formet{{ $titulairesA[0]['id'] }}").text() >= 100) {
            $("#formet{{ $titulairesA[0]['id'] }}").width(100 + '%')
            $("#formet{{ $titulairesA[0]['id'] }}").text(100)
        }

        if ($("#formet{{ $titulairesA[1]['id'] }}").text() >= 100) {
            $("#formet{{ $titulairesA[1]['id'] }}").width(100 + '%')
            $("#formet{{ $titulairesA[1]['id'] }}").text(100)
        }

        if ($("#formet{{ $titulairesA[2]['id'] }}").text() >= 100) {
            $("#formet{{ $titulairesA[2]['id'] }}").width(100 + '%')
            $("#formet{{ $titulairesA[2]['id'] }}").text(100)
        }

        if ($("#formet{{ $titulairesA[3]['id'] }}").text() >= 100) {
            $("#formet{{ $titulairesA[3]['id'] }}").width(100 + '%')
            $("#formet{{ $titulairesA[3]['id'] }}").text(100)
        }

        if ($("#formet{{ $titulairesA[4]['id'] }}").text() >= 100) {
            $("#formet{{ $titulairesA[4]['id'] }}").width(100 + '%')
            $("#formet{{ $titulairesA[4]['id'] }}").text(100)
        }
    }

    function updateFormeJoueurs() {
        let endu0 = $("#formet{{ $titulairesA[0]['id'] }}").attr("value")
        let endu1 = $("#formet{{ $titulairesA[1]['id'] }}").attr("value")
        let endu2 = $("#formet{{ $titulairesA[2]['id'] }}").attr("value")
        let endu3 = $("#formet{{ $titulairesA[3]['id'] }}").attr("value")
        let endu4 = $("#formet{{ $titulairesA[4]['id'] }}").attr("value")

        var formeActu0 = Math.round($("#formet{{ $titulairesA[0]['id'] }}").width() / $("#formet{{ $titulairesA[0]['id'] }}").parent().width() * 100)
        var formeActu1 = Math.round($("#formet{{ $titulairesA[1]['id'] }}").width() / $("#formet{{ $titulairesA[1]['id'] }}").parent().width() * 100)
        var formeActu2 = Math.round($("#formet{{ $titulairesA[2]['id'] }}").width() / $("#formet{{ $titulairesA[2]['id'] }}").parent().width() * 100)
        var formeActu3 = Math.round($("#formet{{ $titulairesA[3]['id'] }}").width() / $("#formet{{ $titulairesA[3]['id'] }}").parent().width() * 100)
        var formeActu4 = Math.round($("#formet{{ $titulairesA[4]['id'] }}").width() / $("#formet{{ $titulairesA[4]['id'] }}").parent().width() * 100)

        var newForme0 = (formeActu0/endu0)*0.7
        var newForme1 = (formeActu1/endu1)*0.7
        var newForme2 = (formeActu2/endu2)*0.7
        var newForme3 = (formeActu3/endu3)*0.7
        var newForme4 = (formeActu4/endu4)*0.7

        $("#formet{{ $titulairesA[0]['id'] }}").width(parseInt(formeActu0 - parseFloat(newForme0.toFixed(2))) + '%')
        $("#formet{{ $titulairesA[0]['id'] }}").text(formeActu0 - parseFloat(newForme0.toFixed(1)))
        $("#formet{{ $titulairesA[1]['id'] }}").width(parseInt(formeActu1 - parseFloat(newForme1.toFixed(2))) + '%')
        $("#formet{{ $titulairesA[1]['id'] }}").text(formeActu1 - parseFloat(newForme1.toFixed(1)))
        $("#formet{{ $titulairesA[2]['id'] }}").width(parseInt(formeActu2 - parseFloat(newForme2.toFixed(2))) + '%')
        $("#formet{{ $titulairesA[2]['id'] }}").text(formeActu2 - parseFloat(newForme2.toFixed(1)))
        $("#formet{{ $titulairesA[3]['id'] }}").width(parseInt(formeActu3 - parseFloat(newForme3.toFixed(2))) + '%')
        $("#formet{{ $titulairesA[3]['id'] }}").text(formeActu3 - parseFloat(newForme3.toFixed(1)))
        $("#formet{{ $titulairesA[4]['id'] }}").width(parseInt(formeActu4 - parseFloat(newForme4.toFixed(2))) + '%')
        $("#formet{{ $titulairesA[4]['id'] }}").text(formeActu4 - parseFloat(newForme4.toFixed(1)))


        for (var variable of $("[id^=\"formet\"]")) {
            if(parseFloat(variable.innerText) <= 30){
                variable.className = "w3-right-align w3-deep-orange"
            }
            else if(parseFloat(variable.innerText) <= 70 && parseFloat(variable.innerText) > 30){
                variable.className = "w3-right-align w3-yellow"
            }
        }
    }
</script>
@endsection
