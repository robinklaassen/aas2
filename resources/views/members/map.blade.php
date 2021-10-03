@extends('master')

@section('title')
	Ledenkaart
@endsection


@section('content')

<h1>Ledenkaart</h1>

<hr/>

<p><b>Noot</b>: de markers worden een voor een geladen omdat Google een limiet stelt aan het aantal queries per seconde. Je moet dus even geduld hebben.</p>

<p>Legenda: rood = normaal, groen = aspirant, blauw = info.</p>

<div id="map-canvas" style="width:100%; height:1000px; margin-bottom:10px;"></div>

@foreach ($members as $m)

	<p>{{ $m->volnaam }} -- {{ $m->geolocatie }}</p>

@endforeach

@endsection
