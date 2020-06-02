@extends('match-layout')

@section('content')
<div class="w3-display-topmiddle" style="margin-top: 43px;"><br>
    <div class="w3-display-container">
        <button id="start" class="w3-light-grey w3-button w3-display-middle" type="button">Commencer</button>
        <button id="pause" class="w3-light-grey w3-button w3-display-middle" type="button" style="display: none;">Pause</button>
    </div><br>

    <table class="w3-center" style="background-image: url('/img/soccer_field.png'); background-size: cover" width="1000" height="661">
        <!--<img src="/img/soccer_field.png" alt="a soccer field" width="1000">-->
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
        <tbody style="font-size: 50px">
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
    var end = 90;

    var seconds = 0;
    var mseconds = 0;

    $("#start").click(function() {
        $(this).css("display", "none")
        $(this).text("Reprendre")
        $("#pause").css("display", "block")

        var timer = setInterval(function() {
            if(minutes == end){
                clearInterval(timer)
            }

            if (seconds == 60) {
                minutes++
                seconds = 0
                updateFormeJoueurs()
            }

            seconds+=1
            if (minutes < 10 && seconds < 10) {
                $("#timer").text("0" + minutes + ":0" + seconds)
            }
            else if (minutes < 10) {
                $("#timer").text("0" + minutes + ":" + seconds)
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

    $(document).ready(function(){
        //$("td").html('<img src="https://cdn4.iconfinder.com/data/icons/sports-3-1/48/150-512.png" style="width: 50px;" alt="Avatar">')
    })
</script>
@endsection
