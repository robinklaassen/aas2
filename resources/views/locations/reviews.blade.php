@extends('master')

@section('title')
	Kamphuis reviews vanuit {{ $event->naam }} {{ $event->datum_start->format('Y') }}
@endsection

@section('content')


<h1>Kamphuis reviews vanuit {{ $event->naam }} {{ $event->datum_start->format('Y') }}</h1>

<hr/>

<div class="row">

	<div class="col-md-6">
		<h4>Wat vond je van de slaapruimtes in het kamphuis?</h4>
		<div id="kh-slaap-div"></div>
		@barchart('kh-slaap', 'kh-slaap-div')
		
		<h4>Waarom?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('kh-slaap-wrm') as $m)
				@unless ($m == '')
					<li>{{ $m }}</li>
				@endunless
			@endforeach
		</ul>
	</div>
	
	<div class="col-md-6">
		<h4>Wat vond je van de bijspijkerruimtes in het kamphuis?</h4>
		<div id="kh-bijspijker-div"></div>
		@barchart('kh-bijspijker', 'kh-bijspijker-div')
		
		<h4>Waarom?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('kh-bijspijker-wrm') as $m)
				@unless ($m == '')
					<li>{{ $m }}</li>
				@endunless
			@endforeach
		</ul>
	</div>

</div>

<div class="row">

	<div class="col-md-6">
		<h4>Wat vond je van het kamphuis als geheel?</h4>
		<div id="kh-geheel-div"></div>
		@barchart('kh-geheel', 'kh-geheel-div')
		
		<h4>Waarom?</h4>
		<ul>
			@foreach ($event->reviews()->get()->shuffle()->pluck('kh-geheel-wrm') as $m)
				@unless ($m == '')
					<li>{{ $m }}</li>
				@endunless
			@endforeach
		</ul>
	</div>

</div>

@endsection