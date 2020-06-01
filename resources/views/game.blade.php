@extends('game-layout')

@section('content')
<div class="w3-display-middle">
    <h1>Prochain Match: {{ $prochainMatch }}</h1>
    <a href="/match" class="w3-button w3-block w3-light-grey">Jouer &rarr;</a>
</div>
@endsection
