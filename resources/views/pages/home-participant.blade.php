@extends('master')

@section('title')
	Home
@endsection

@section('content')

<div class="jumbotron">
	<h1>Welkom, {{ Auth::user()->profile->voornaam }}!</h1>
	<p>
		Leuk je weer te zien. Ga je weer een keer mee op kamp?
	</p>
	@if ($congrats == 1)
		<p>
			Enneh... <b>gefeliciteerd met je verjaardag!</b> <span class="glyphicon glyphicon-gift"></span>
		</p>
	@endif
	<hr/>
	<p style="font-size:120%;">Problemen bij het gebruik van AAS 2.0? Stuur dan even een mailtje naar de <a href="mailto:webmaster@anderwijs.nl">webmaster</a>.<p>
</div>

@endsection