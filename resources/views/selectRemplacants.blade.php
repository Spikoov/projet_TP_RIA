@extends('game-layout')

@section('content')
<form action="#" method="post" style="margin-left: 20%; margin-right: 20%">
    {{ csrf_field() }}
    <table id="table" class="w3-table w3-hoverable w3-bordered">
        <thead class="w3-light-blue">
            <th>Nom du joueur</th>
            <th id="sort0" class="w3-button w3-hover-cyan" type="button">Age &#8597;</th>
            <th id="sort1" class="w3-button w3-hover-cyan" type="button">Poste &#8597;</th>
            <th id="sort2" class="w3-button w3-hover-cyan" type="button">Note Globale ( / 100) &#8597;</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($joueurs as $joueur)
                <tr>
                    <td>{{ $joueur['nom'] }}</td>
                    <td>{{ $joueur['age'] }} ans</td>
                    <td>{{ ucfirst($joueur['poste']) }}</td>
                    <td>{{ $joueur['note'] }}</td>
                    <td><button class="w3-btn w3-white w3-border w3-border-green w3-hover-green w3-round-large" type="submit" value="0" name="selectedEquipe">Sélectionner</button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</form>
<script>
$(document).ready(function(){
    $('#sort0, #sort1, #sort2').click(function() {
        var lastLetter = $(this).text()[$(this).text().length-1]
/*
        $('#sort0, #sort1, #sort2').map(function(){
            $(this).text($(this).text().slice(0, -1))
        })
        $('#sort0, #sort1, #sort2').not(this).append("↕")
*/
        $(this).text($(this).text().slice(0, -1))

        if (lastLetter == "↕" || lastLetter == "↑") {
            sortTable($(this).attr('id'))
            $(this).append("↓")
        }
        else if (lastLetter == "↓") {
            rsortTable($(this).attr('id'))
            $(this).append("↑")
        }
    })
})

function sortTable(t) {
    tb = t.slice(4)
    ti = parseInt(tb)
    ti++
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("table");
    switching = true;
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[ti];
            y = rows[i + 1].getElementsByTagName("TD")[ti];
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                shouldSwitch = true;
                break;
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
}

function rsortTable(t) {
    tb = t.slice(4)
    ti = parseInt(tb)
    ti++
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("table");
    switching = true;
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[ti];
            y = rows[i + 1].getElementsByTagName("TD")[ti];
            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                shouldSwitch = true;
                break;
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
}
</script>
@endsection
