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
            @for($i = 0; $i < $nombre; $i++)
                <tr>
                    <td>{{ $prenom[$i] }} {{ nom[$i] }}</td>
                    <td>{{ $age[$i] }}</td>
                    <td>{{ $poste[$i] }}</td>
                    <td>{{ $note[$i] }}</td>
                    <td><button class="w3-btn w3-white w3-border w3-border-green w3-hover-green w3-round-large" type="submit" value="{{ $equipe->getId() }}" name="selectedEquipe">Sélectionner</button></td>
                </tr>
            @endfor
        </tbody>
    </table>
</form>
@endsection
