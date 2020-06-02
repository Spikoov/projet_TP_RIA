@extends('match-layout')

@section('content')
<div class="w3-display-topmiddle" style="margin-top: 43px;">
    <button id="start" type="button">Commencer</button>
    <button id="pause" type="button" style="display: none;">Pause</button>

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
                <td></td>
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
        $("#pause").css("display", "block")

        var timer = setInterval(function() {
            if(seconds == end){
                clearInterval(timer)
            }

            if (mseconds == 100) {
                seconds++
                mseconds = 0
            }

            mseconds+=1
            if (seconds < 10) {
                $("#timer").text("0" + seconds + ":" + mseconds)
            }
            else {
                $("#timer").text(seconds + ":" + mseconds)
            }
        }, 10);

        $("#pause").click(function() {
            $(this).css("display", "none")
            $("#start").css("display", "block")

            clearInterval(timer)
        })
    })

    $(document).ready(function(){
        $("td").html('<img src="https://cdn4.iconfinder.com/data/icons/sports-3-1/48/150-512.png" style="width: 50px;" alt="Avatar">')
    })
</script>
@endsection
