@extends('master')

@section('title')
	Home
@endsection

@section('header')
<style type="text/css">

.progress {
	height: 30px;
	width: 100%;
}

.progress .progress-bar {
	font-size: 140%;
	line-height: normal;
}

.progress-label-left {
	width: 20px;
	margin-right: 10px;
	font-size: 21px;
	font-weight: 200;
}

.progress-label-right {
	width: 4em;
	margin-left: 10px;
	font-size: 21px;
	font-weight: 200;
}

</style>
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
					<li>{{$member->voornaam}} {{$member->tussenvoegsel}} {{$member->achternaam}} ({{$member->leeftijd}}) @if ($member->ikjarig) <strong>&larr; dat ben jij! Gefeliciteerd!</strong> <span class="glyphicon glyphicon-gift"></span> @endif
				@endforeach
			</ul>
		@endif
	</div>
	<div class="col-md-4 progress-container">
		@foreach ($thermo as $c)
		<h3>{{$c['naam']}} @if ($c['klikbaar'])<small>(<a href="{{ url('/events', $c['id']) }}">link</a>)</small>@endif</h3>
		<div style="display:flex;">
			<div class="progress-label-left">L</div>
			<div class="progress">
				@if ($c['num_L_goed'] > 0)
				<div class="progress-bar progress-bar-success" role="progressbar" style="width: {{$c['perc_L_goed']}}%;">{{$c['num_L_goed']}}</div>
				@endif
				@if ($c['num_L_bijna'] > 0)
				<div class="progress-bar progress-bar-warning" role="progressbar" style="width: {{$c['perc_L_bijna']}}%;">{{$c['num_L_bijna']}}</div>
				@endif
			</div>
			<div class="progress-label-right">{{ $c['num_L_goed'] + $c['num_L_bijna'] }} /  {{$c['streef_L']}}</div>
		</div>
		<div style="display:flex;">
			<div class="progress-label-left">D</div>
			<div class="progress">
				@if ($c['num_D_goed'] > 0)
				<div class="progress-bar progress-bar-success" role="progressbar" style="width: {{$c['perc_D_goed']}}%;">{{$c['num_D_goed']}}</div>
				@endif
				@if ($c['num_D_bijna'] > 0)
				<div class="progress-bar progress-bar-warning" role="progressbar" style="width: {{$c['perc_D_bijna']}}%;">{{$c['num_D_bijna']}}</div>
				@endif
			</div>
			<div class="progress-label-right">{{ $c['num_D_goed'] + $c['num_D_bijna'] }} / {{$c['streef_D']}}</div>
		</div>
		@endforeach
	</div>
	</div>
</div>

@endsection