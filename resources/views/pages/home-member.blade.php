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
		@if ($today)
			<hr/>
			<h3>Vandaag jarig:</h3>
			<ul style="font-size:120%;">
				@foreach ($today as $member)
					<li>{{$member->volnaam}} ({{$member->leeftijd}}) @if ($member->ikjarig) <strong>&larr; dat ben jij! Gefeliciteerd!</strong> <span class="glyphicon glyphicon-gift"></span> @endif
				@endforeach
			</ul>
		@endif
	</div>
	<div class="col-md-4 progress-container">
		@foreach ($thermo as $c)

			@can('view', $c['camp'])
			<a href="{{ url('/events', $c['camp']['id']) }}">
				<h3>{{$c['camp']['naam']}}</h3>
			</a>
			@else
			<h3>{{$c['camp']['naam']}}</h3>
			@endcan

			@if (Auth::user()->profile->events->contains($c['camp']))
				<h5>Jij gaat mee op dit kamp, wat tof!</h5>
			@endif

			<camp-thermometer-bar label="L" :number-full="{{ $c['num_L_goed'] }}" :number-partial="{{ $c['num_L_bijna'] }}" :target-number="{{ $c['streef_L'] }}"></camp-thermometer-bar>
			<camp-thermometer-bar label="D" :number-full="{{ $c['num_D_goed'] }}" :number-partial="{{ $c['num_D_bijna'] }}" :target-number="{{ $c['streef_D'] }}"></camp-thermometer-bar>
		@endforeach
	</div>
	</div>
</div>

@endsection