@extends('master')

@section('title')

@if ($status == 'ok')
iDeal betaling succesvol
@else
iDeal betaling mislukt
@endif

@endsection

@section('content')


<h1>
	@if ($status == 'ok')
	iDeal betaling succesvol
	@else
	iDeal betaling mislukt
	@endif
</h1>

<hr />

@if ($status == 'ok')
<p>
	U heeft uw kind succesvol ingeschreven voor een Anderwijskamp en direct via iDeal betaald. U ontvangt per mail een automatische bevestiging van inschrijving en betaling (twee losse mails).
</p>
@else
<p>
	U heeft uw kind ingeschreven voor een Anderwijskamp, maar de betaling via iDeal is helaas mislukt. U ontvangt per mail een automatische bevestiging van inschrijving, daarin staat de informatie om het kampgeld via overboeking te betalen. Het is handig om dit zo snel mogelijk te doen, want plaatsing voor een kamp gebeurt op volgorde van betaling.
</p>
@endif

@if ($participant->events()->count() == 1)
	@if ($participant->inkomen)
		<p>
			U heeft een korting op de kampprijs aangevraagd. Daarvoor hebben wij een inkomensverklaring van u nodig. Kijk in de bevestigingsmail voor meer informatie hierover.
		</p>
	@endif

	<p>
		Heeft u een nieuwe deelnemer ingeschreven? Dan is er ook automatisch een account aangemaakt. Hiermee kunt u inloggen op ons <a href="{{ url('/') }}">administratiesysteem</a> om de gegevens te beheren. De details staan in de bevestigingsmail.
	</p>

	<p>
		<a href="https://www.anderwijs.nl">Terug naar de website van Anderwijs</a>
	</p>
@else
	<p>
		<a href="{{ url('profile') }}">Terug naar uw profiel</a>
	</p>
@endif

@endsection