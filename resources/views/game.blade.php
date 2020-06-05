@extends('game-layout')

@section('content')
<div class="w3-display-middle">
    @if($journee > 9)
        <h1>Prochain Match: {{ $prochainMatch }} (retour)</h1>
    @else
        <h1>Prochain Match: {{ $prochainMatch }} (aller)</h1>
    @endif
    <a href="/match" class="w3-button w3-block w3-light-grey">Jouer &rarr;</a>
</div>
@endsection
