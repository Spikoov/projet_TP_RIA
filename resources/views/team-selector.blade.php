@extends('game-layout')

@section('content')
<form action="/teamSelector" method="post">
    {{ csrf_field() }}
    <table class="w3-table w3-hoverable w3-bordered">
        <thead class="w3-light-blue">
            <th>Nom de l'équipe</th>
            <th>Note Globale ( / 100)</th>
            <th></th>
        </thead>
        <tbody>
            @foreach($equipes as $equipe)
                <tr>
                    <td>{{ $equipe->getNom() }}</td>
                    <td>{{ $equipe->getNotes()['absolue'] }}</td>
                    <td><button class="w3-btn w3-white w3-border w3-border-green w3-hover-green w3-round-large" type="submit" value="{{ $equipe->getId() }}" name="selectedEquipe">Sélectionner</button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</form>
@endsection
