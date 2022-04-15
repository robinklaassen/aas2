@extends('master')

@php 
	use App\Helpers\DateHelper; 
	use Carbon\Carbon;
@endphp

@section('title')
	Home
@endsection

@section('content')

<div class="jumbotron">
	<h1>Welkom, {{ $participant->voornaam }}!</h1>

	<p>
		Leuk je weer te zien. 
		@if ($participant->geboortedatum->isBirthday())
			<b>Gefeliciteerd met je verjaardag!</b> <span class="glyphicon glyphicon-gift"></span>
		@endif
	</p>

	@forelse ($registeredCamps as $c)
	<div class="panel panel-default">
		<div class="panel-body">
			<h2>
				Status inschrijving <a href="{{ url('events', $c->id) }}">{{ $c->naam }}</a> ({{ DateHelper::Format($c->datum_start) }} t/m {{ DateHelper::Format($c->datum_eind) }}):
			</h2>
			<hr/>
			<p>
				@if ($c->pivot->isPaid())
					<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> &nbsp;
					Betaling ontvangen op {{ DateHelper::Format(new Carbon($c->pivot->datum_betaling)) }}.
				@else
					<span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> &nbsp;
					Betaling nog niet ontvangen! <a href="{{ url('pay', $c->id) }}">Betaal direct @money($c->pivot->createPayment()->getTotalAmount()) via iDeal.</a>
				@endif
			</p>
			<p>
				@if ($participant->inkomen == 0)
					<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> &nbsp;
					Geen inkomensverklaring nodig (geen korting).
				@elseif ($participant->inkomensverklaring !== null)
					<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> &nbsp;
					Inkomensverklaring ontvangen op {{ DateHelper::Format($participant->inkomensverklaring) }}.
				@else
					<span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> &nbsp;
					Inkomensverklaring nog niet ontvangen! Stuur deze z.s.m. naar <a href="mailto:penningmeester@anderwijs.nl">penningmeester@anderwijs.nl</a>.
				@endif
			</p>
			<p>
				@if ($c->pivot->geplaatst)
					<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> &nbsp;
					Geplaatst voor het kamp. Veel plezier!
				@else
					<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> &nbsp;
					Nog niet geplaatst &mdash; dit gebeurt ongeveer twee weken voor het kamp.
				@endif
			</p>
		</div>
		<div class="panel-footer">
			<p><small>
				Op onze website meer informatie over het <a href="https://anderwijs.nl/inschrijven/inschrijven-scholieren/" target="_blank">stappenplan voor inschrijving</a>. 
			</small></p>
		</div>
	</div>
	@empty
		<p>
			Ga je weer een keer mee op kamp? <a href="{{ url('profile/on-camp') }}">Schrijf je direct in!</a>
		</p>
	@endforelse

	<hr/>
	<p><small>
		Problemen bij het gebruik van AAS 2.0? Stuur dan even een mailtje naar de <a href="mailto:webmaster@anderwijs.nl">webmaster</a>.
	</small></p>
</div>

@endsection