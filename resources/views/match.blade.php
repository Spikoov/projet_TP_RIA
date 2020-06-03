@extends('match-layout')

@section('content')
<div class="w3-display-topmiddle" style="margin-top: 43px;"><br>
    <div class="w3-display-container">
        <button id="start" class="w3-light-grey w3-button w3-display-middle" type="button">Commencer</button>
        <button id="pause" class="w3-light-grey w3-button w3-display-middle" type="button" style="display: none;">Pause</button>
    </div><br>

    <div>
        <span id="scoreA">0</span>
        <span id="scoreB">0</span>
    </div>

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

        var timer = setInterval(function() {
            if(minutes == end){
                clearInterval(timer)
            }

            if(minutes == 45 && seconds == 0){
                $("#start").css("display", "block")
                $("#start").text("Jouer la 2nde mi-temps")
                $("#pause").css("display", "none")

                clearInterval(timer)
            }

            if (seconds == 60) {
                minutes++
                seconds = 0
                updateFormeJoueurs()
            }

            if(minutes % 5 == 0 && seconds == 0 && minutes != 0){
                match();
            }

            if ((minutes == 40 || minutes == 60 || minutes == 90) && seconds == 0 ) {
                tir()
            }

            seconds+=1
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
        }, 16,667);

        $("#pause").click(function() {
            $(this).css("display", "none")
            $("#start").css("display", "block")

            clearInterval(timer)
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
                            var tacle0 = Math.ceil(Math.random()*100)
                            var tacle1 = Math.ceil(Math.random()*100)

                            if (tacle0 > df0B || tacle1 > df1B) { //tacles échoués
                                var tir = Math.ceil(Math.random()*100)
                                var arret = Math.ceil(Math.random()*100)

                                if (tir <= atA && arret > gkB ) { //tir réussi et arret raté
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

                                if ((tir <= atA && arret > gkB) || tir < 10) { //tir réussi et arret raté
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

        console.log(tituB);
        console.log(tituA);
        console.log(remplA);
    }
</script>
@endsection
