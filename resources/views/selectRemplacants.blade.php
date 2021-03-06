@extends('game-layout')

@section('content')
<form action="/setRemplacants" method="post">
    {{ csrf_field() }}
    <table id="table" class="w3-table w3-hoverable w3-bordered">
        <thead class="w3-light-blue">
            <th id="resetSort" class="w3-button w3-hover-cyan" type="button">&#8634;<br><div class="w3-badge w3-green" id="budgetRempl">0ß</div></th>
            <th>Nom du joueur</th>
            <th id="sort0" class="w3-button w3-hover-cyan" type="button">Age &#8597;</th>
            <th id="sort1" class="w3-button w3-hover-cyan" type="button">Poste &#8597;</th>
            <th id="sort2" class="w3-button w3-hover-cyan" type="button">Note Globale ( / 100) &#8597;</th>
            <th><input class="w3-btn w3-white w3-border w3-border-green w3-hover-green w3-round-large" type="submit" id="boutonValider" value="Valider" name="valider"></th>
        </thead>
        <tbody>
            @foreach ($joueurs as $joueur)
                <tr>
                    <td value="{{ $joueur['id'] }}"><span class="w3-badge w3-lime">{{ floor($joueur['note'] / 2) }}ß</span></td>
                    <td>{{ $joueur['nom'] }}</td>
                    <td>{{ $joueur['age'] }} ans</td>
                    <td>{{ ucfirst($joueur['poste']) }}</td>
                    <td>{{ $joueur['note'] }}</td>
                    <td><input type="checkbox" onchange="countChecked()" id="{{ floor($joueur['note'] / 2) }}" class="testChecked" name="idJoueur[]" value="{{ $joueur['id'] }}"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</form>
<script>
//------------------------------------------------------------------------------

function countChecked(){
  var troisChecked = $('input[name="idJoueur[]"]:checked').length == 3;

  if(troisChecked){
    $('input.testChecked:not(:checked)').attr('disabled', 'disabled');
    $('#boutonValider').removeAttr('disabled');
  }
  else {
    $('input.testChecked').removeAttr('disabled');
    $('#boutonValider').attr('disabled', 'disabled');
  }

  var budgetRemplacants = 0;
      $.each($('input[name="idJoueur[]"]:checked'), function() {
          var note = parseInt($(this).attr('id'));
          budgetRemplacants = budgetRemplacants + note;
      });

      $('#budgetRempl').text(budgetRemplacants + "ß");

      if(budgetRemplacants>{{ $budgetEquipe }})
      {
        $('#boutonValider').attr('disabled', 'disabled');

        $('#budgetRempl').removeClass("w3-green");
        $('#budgetRempl').addClass("w3-red");
      }
      else{
          $('#budgetRempl').removeClass("w3-red");
          $('#budgetRempl').addClass("w3-green");
      }
}

//------------------------------------------------------------------------------

$(document).ready(function(){
  $('#boutonValider').attr('disabled', 'disabled');
  $("input.testChecked"). prop("checked", false);
  $('input.testChecked:not(:checked)').removeAttr('disabled');
})

//------------------------------------------------------------------------------

$(document).ready(function(){
    $('#sort0, #sort1, #sort2').click(function() {
        var lastLetter = $(this).text()[$(this).text().length-1]
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

    //--------------------------------------------------------------------------

    $('#resetSort').click(function() {
        $('#sort0, #sort1, #sort2').map(function(){
            $(this).text($(this).text().slice(0, -1))
        })
        $('#sort0, #sort1, #sort2').not(this).append("↕")

        var table, rows, switching, i, x, y, shouldSwitch;
        table = document.getElementById("table");
        switching = true;
        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = parseInt(rows[i].getElementsByTagName("TD")[0].getAttribute('value'));
                y = parseInt(rows[i + 1].getElementsByTagName("TD")[0].getAttribute('value'));
                if (x > y) {
                    shouldSwitch = true;
                    break;
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
            }
        }
    })
})

//------------------------------------------------------------------------------

function sortTable(t) {
    tb = t.slice(4)
    ti = parseInt(tb) + 2

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

//------------------------------------------------------------------------------

function rsortTable(t) {
    tb = t.slice(4)
    ti = parseInt(tb) + 2
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

$(this).load(alert("Vous devez selectionner 3 remplançants au début de la partie"))
</script>
@endsection
