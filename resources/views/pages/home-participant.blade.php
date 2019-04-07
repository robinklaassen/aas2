@extends('master')

@section('title')
	Home
@endsection

@section('content')

<div class="jumbotron">
	@if (Auth::user()->profile_type == 'App\Member')
	<div class="row">
	<div class="col-md-8">
	@endif
		<h1>Welkom, {{ Auth::user()->profile->voornaam }}!</h1>
		<p>
			Leuk je weer te zien. 
			@if (Auth::user()->is_admin)
				Veel administratieplezier :)
			@else
				Ga je weer een keer mee op kamp?
			@endif
		</p>
		@if ($congrats == 1)
			<p>
				Enneh... <b>gefeliciteerd met je verjaardag!</b> <span class="glyphicon glyphicon-gift"></span>
			</p>
		@endif
		<hr/>
		<p style="font-size:120%;">Problemen bij het gebruik van AAS 2.0? Stuur dan even een mailtje naar de <a href="mailto:webmaster@anderwijs.nl">webmaster</a>.<p>
		<p style="font-size:120%;"><a href="{{ url('/info') }}">Meer informatie over AAS</a></p>
	@if (Auth::user()->profile_type == 'App\Member')
	</div>
	<div class="col-md-4 progress-container">
		@foreach ($thermo as $c)
		<h3>{{$c['naam']}} <small>(<a href="{{ url('/events', $c['id']) }}">link</a>)</small></h3>
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
	@endif
</div>

@endsection