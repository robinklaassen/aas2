@extends('master')

@section('title')
	Resultaten enquêtes {{ $event->naam }} {{ $event->datum_start->format('Y') }}
@endsection

@section('content')


<h1>Resultaten enquêtes {{ $event->naam }} {{ $event->datum_start->format('Y') }}</h1>

<hr/>

<div class="row">
	<div class="col-md-12">
		<div class="jumbotron">
			<h2>Het kamp krijgt uit {{ $event->reviews->count() }} enquêteresultaten gemiddeld een <strong>{{ round($event->reviews()->avg('cijfer'),1) }}</strong>!</h2>
		</div>
	</div>
</div>

<hr/>

<h2>Over het bijspijkeren</h2>

<h4>De deelnemers kregen volgens de enquêteresultaten gemiddeld <strong>{{ round($event->reviews()->avg('bs-uren'),1) }}</strong> bijspijkeruren per dag.</h4>

<div class="row">

	<div class="col-md-6">
		<h4>Hoe vond je het aantal bijspijkeruren?</h4>
		<div id="bs-mening-div"></div>
		@barchart('bs-mening', 'bs-mening-div')
	</div>
	
	<div class="col-md-6">
		<h4>Hoe tevreden ben je over wat je qua bijspijkeren hebt bereikt?</h4>
		<div id="bs-tevreden-div"></div>
		@barchart('bs-tevreden', 'bs-tevreden-div')
	</div>

</div>

<div class="row">
	
	<div class="col-md-6">
		<h4>Heb je de stof op een andere manier behandeld gekregen dan op school?</h4>
		<div id="bs-manier-div"></div>
		@barchart('bs-manier', 'bs-manier-div')
		
		<h4>Zo ja, hoe vond je dit?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('bs-manier-mening') as $m)
				@unless ($m == '')
					<li>{{ $m }}</li>
				@endunless
			@endforeach
		</ul>
		
	</div>
	
	<div class="col-md-6">
		<h4>Heb je themablokken gehad op dit kamp?</h4>
		<div id="bs-thema-div"></div>
		@barchart('bs-thema', 'bs-thema-div')
		
		<h4>Zo ja, waarover, en wat vond je ervan?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('bs-thema-wat') as $w)
				@unless ($w == '')
					<li>{{ $w }}</li>
				@endunless
			@endforeach
		</ul>
	</div>
	
</div>

<hr>

<h2>Over de rest van het kamp</h2>

<div class="row">
	<div class="col-md-6">

		<h4>Wat vond je van de leidingploeg?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('leidingploeg') as $w)
				@unless ($w == '')
					<li>{{ $w }}</li>
				@endunless
			@endforeach
		</ul>

		<h4>Wat vond je van het eten tijdens het kamp?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('eten') as $w)
				@unless ($w == '')
					<li>{{ $w }}</li>
				@endunless
			@endforeach
		</ul>
	</div>
	
	<div class="col-md-6">

		<h4>Hoeveel tijd had je om te slapen?</h4>
		<div id="slaaptijd-div"></div>
		@barchart('slaaptijd', 'slaaptijd-div')

		<h4>Als je (veel te) weinig tijd had om te slapen, hoe kwam dat dan?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('slaaptijd-hoe') as $w)
				@unless ($w == '')
					<li>{{ $w }}</li>
				@endunless
			@endforeach
		</ul>
		
		<h4>Wat vond je van de lengte van het kamp?</h4>
		<div id="kamplengte-div"></div>
		@barchart('kamplengte', 'kamplengte-div')

		<h4>Waarom?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('kamplengte-wrm') as $w)
				@unless ($w == '')
					<li>{{ $w }}</li>
				@endunless
			@endforeach
		</ul>

	</div>
</div>

<div class="row">

	<div class="col-md-6">
		<h4>Welk avondprogramma vond je het leukst en waarom?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('avond-leukst') as $w)
				@unless ($w == '')
					<li>{{ $w }}</li>
				@endunless
			@endforeach
		</ul>
		
		<h4>Welk avondprogramma vond je het minst leuk en waarom?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('avond-minst') as $w)
				@unless ($w == '')
					<li>{{ $w }}</li>
				@endunless
			@endforeach
		</ul>
	</div>

	<div class="col-md-6">
		<h4>Wat is het allerleukste dat je hebt gedaan of dat is gebeurd tijdens het kamp?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('allerleukst') as $w)
				@unless ($w == '')
					<li>{{ $w }}</li>
				@endunless
			@endforeach
		</ul>
		
		<h4>Wat is het allervervelendste dat is gebeurd tijdens het kamp?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('allervervelendst') as $w)
				@unless ($w == '')
					<li>{{ $w }}</li>
				@endunless
			@endforeach
		</ul>
	</div>
	
</div>

<div class="row">

	<div class="col-md-6">
	
		<h4>Zou je nog eens mee willen op kamp (en waarom)?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('nogeens') as $w)
				@unless ($w == '')
					<li>{{ $w }}</li>
				@endunless
			@endforeach
		</ul>
	
	</div>
	
	<div class="col-md-6">
	
		<h4>Heb je nog een tip voor Anderwijs als organisatie?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('tip') as $w)
				@unless ($w == '')
					<li>{{ $w }}</li>
				@endunless
			@endforeach
		</ul>
	
	</div>
	
</div>

<h4>Wil je verder nog iets kwijt?</h4>
<ul>
	@foreach ($event->reviews()->get()->shuffle()->pluck('verder') as $w)
		@unless ($w == '')
			<li>{{ $w }}</li>
		@endunless
	@endforeach
</ul>

@endsection