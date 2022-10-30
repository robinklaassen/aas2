@extends('master')

@section('title')
	Resultaten enquêtes {{ $event->naam }} {{ $event->datum_start->format('Y') }}
@endsection

@section('content')


<h1>Resultaten enquêtes {{ $event->naam }} {{ $event->datum_start->format('Y') }}</h1>

<hr/>

<p class="well">Dit zijn jouw persoonlijke reviews. <a href="{{ url('/events', [$event->id, 'reviews']) }}">Klik hier</a> om de algemene resultaten van het kamp te bekijken.</p>

<div class="row">

	<div class="col-md-6">
		<h4>Hoe legt de Anderwijzer de stof uit?</h4>
		<div id="stof-div"></div>
		@barchart('stof', 'stof-div')
	</div>
	
	<div class="col-md-6">
		<h4>Hoeveel aandacht gaf de Anderwijzer je tijdens de blokjes?</h4>
		<div id="aandacht-div"></div>
		@barchart('aandacht', 'aandacht-div')
	</div>

</div>

<div class="row">

	<div class="col-md-6">
		<h4>Hoe vond je het om door de Anderwijzer bijgespijkerd te worden?</h4>
		<div id="mening-div"></div>
		@barchart('mening', 'mening-div')
	</div>
	
	<div class="col-md-6">
		<h4>Hoe tevreden ben je over wat je met de Anderwijzer hebt bereikt?</h4>
		<div id="tevreden-div"></div>
		@barchart('tevreden', 'tevreden-div')
	</div>

</div>

<div class="row">
	<h4>Wil je nog iets persoonlijk kwijt aan {{ $member->voornaam }}?</h4>
	<ul>
	@foreach ($member->reviews()->where('event_id', $event->id)->get()->shuffle() as $r)	
		@if ($r->pivot->bericht != null)
			<li>{{ $r->pivot->bericht }}</li>
		@endif
	@endforeach
	</ul>
</div>

@endsection