@extends('game-layout')

@section('content')
<form action="#" method="post" style="margin-left: 20%; margin-right: 20%">
    {{ csrf_field() }}
    <table class="w3-table w3-hoverable w3-bordered">
        <thead class="w3-light-blue">
            <th>Nom du joueur</th>
            <th>Age</th>
            <th>Poste</th>
            <th>Note Globale ( / 100)</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($joueurs as $joueur)
                <tr>
                    <td>{{ $joueur['nom'] }}</td>
                    <td>{{ $joueur['age'] }}</td>
                    <td>{{ $joueur['poste'] }}</td>
                    <td>{{ $joueur['note'] }}</td>
                    <td><button class="w3-btn w3-white w3-border w3-border-green w3-hover-green w3-round-large" type="submit" value="0" name="selectedEquipe">Sélectionner</button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</form>
@endsection
