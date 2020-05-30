@extends('layout')

@section('content')
<div class="w3-display-middle w3-card-4">
    <header class="w3-display-container w3-container w3-indigo">
        <h1>{{ $infosJoueur['prenom'] . ' ' . $infosJoueur['nom'] }}</h1>
        @if($infosJoueur['poste'] === 'gardien')
            <img src="https://cdn4.iconfinder.com/data/icons/sports-3-1/48/150-512.png" alt="Avatar" style="width: 60px; margin-right: 10px;" class="w3-display-right w3-green w3-right w3-circle">
        @elseif($infosJoueur['poste'] === 'defense')
            <img src="https://cdn4.iconfinder.com/data/icons/sports-3-1/48/150-512.png" alt="Avatar" style="width: 60px; margin-right: 10px;" class="w3-display-right w3-blue w3-right w3-circle">
        @elseif($infosJoueur['poste'] === 'milieu')
            <img src="https://cdn4.iconfinder.com/data/icons/sports-3-1/48/150-512.png" alt="Avatar" style="width: 60px; margin-right: 10px;" class="w3-display-right w3-yellow w3-right w3-circle">
        @elseif($infosJoueur['poste'] === 'attaque')
            <img src="https://cdn4.iconfinder.com/data/icons/sports-3-1/48/150-512.png" alt="Avatar" style="width: 60px; margin-right: 10px;" class="w3-display-right w3-red w3-right w3-circle">
        @endif
    </header>
    <br>
    <div class="w3-container">
        <table class="w3-table w3-light-grey w3-border">
            <thead>
                <th>Âge</th>
                <th>Poste</th>
                <th>Note Globale</th>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $infosJoueur['age'] }} ans</td>
                    <td>{{ ucfirst($infosJoueur['poste']) }}</td>
                    <td>{{ $infosJoueur['noteGlobale'] }} / 100</td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="w3-table w3-border w3-bordered">
            <thead>
                <th>Tir</th>
                <th>Passe</th>
                <th>Technique</th>
                <th>Placement</th>
                <th>Vitesse</th>
                <th>Tacle</th>
                <th>Arrêt</th>
                <th>Endurance</th>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $infosJoueur['tir'] }}</td>
                    <td>{{ $infosJoueur['passe'] }}</td>
                    <td>{{ $infosJoueur['technique'] }}</td>
                    <td>{{ $infosJoueur['placement'] }}</td>
                    <td>{{ $infosJoueur['vitesse'] }}</td>
                    <td>{{ $infosJoueur['tacle'] }}</td>
                    <td>{{ $infosJoueur['arret'] }}</td>
                    <td>{{ $infosJoueur['endurance'] }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="w3-table w3-border w3-light-grey">
            <thead>
                <th>Durée du contrat</th>
                <th>Salaire</th>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $infosJoueur['dureeContrat'] }} ans</td>
                    <td>{{ $infosJoueur['salaire'] }}ß / an</td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <footer class="w3-container">
        <a href="/game" class="w3-block w3-button w3-light-grey">&larr; Retour</a>
    </footer>
    <br>
</div>
@endsection
