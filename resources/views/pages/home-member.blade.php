@extends('master')

@section('title')
	Home
@endsection

@section('content')

<div class="jumbotron">
	<div class="row">
	<div class="col-md-8">
		<h1>Welkom, {{ Auth::user()->profile->voornaam }}!</h1>
		<p>
			Leuk je weer te zien. 
			@if (Auth::user()->is_admin)
				Veel administratieplezier :)
			@else
				Ga je weer een keer mee op kamp?
			@endif
		</p>
		@if ($birthdays->isNotEmpty())
			<hr/>
			<h3>Vandaag jarig:</h3>
			<ul style="font-size:120%;">
				@foreach ($birthdays as $m)
					<li>
						{{$m->volnaam}} ({{$m->geboortedatum->age}}) 
						@if (Auth::user()->profile == $m) 
							<strong>&larr; dat ben jij! Gefeliciteerd!</strong> <span class="glyphicon glyphicon-gift"></span> 
						@endif
					</li>
				@endforeach
			</ul>
		@endif
	</div>
	<div class="col-md-4 progress-container">
		@foreach ($thermometerCamps as $c)

			@can('view', $c)
			<a href="{{ url('/events', $c->id) }}">
				<h3>{{ $c->naam }}</h3>
			</a>
			@else
			<h3>{{ $c->naam }}</h3>
			@endcan

			@if (Auth::user()->profile->events->contains($c))
				<h5>Jij gaat mee op dit kamp, wat tof!</h5>
			@endif

			<camp-thermometer-bar 
				label="L" 
				:number-full="{{ $c->getFulltimeMembersWhereVOG(true)->count() }}" 
				:number-partial="{{ $c->getFullTimeMembersWhereVOG(false)->count() }}" 
				:target-number="{{ $c->streeftal }}">
			</camp-thermometer-bar>
			<camp-thermometer-bar 
				label="D" 
				:number-full="{{ $c->getParticipantsWherePaid(true)->count() }}" 
				:number-partial="{{ $c->getParticipantsWherePaid(false)->count() }}" 
				:target-number="{{ $c->streeftal_deelnemers }}">
			</camp-thermometer-bar>
		@endforeach
	</div>
	</div>
</div>

@endsection