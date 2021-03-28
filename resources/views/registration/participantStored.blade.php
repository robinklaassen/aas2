@extends('master')

@section('title')
	Deelnemer ingeschreven
@endsection

@section('content')

<h1>Bedankt voor uw voorlopige inschrijving!</h1>

<hr/>

<p>
	U heeft uw kind succesvol ingeschreven voor een Anderwijskamp. U ontvangt een automatische bevestigingsmail op het opgegeven emailadres. Om de inschrijving definitief te maken, dient u het kampgeld over te maken op onze rekening. 
	@if ($toPay == 0)
		<strong>Voor dit kamp is de prijs echter nog niet definitief vastgesteld.</strong> Zodra dat is gebeurd, ontvangt u daarover per email bericht.
	@else
		De betalingsinformatie vindt u in de bevestigingsmail. Het is handig om dit zo snel mogelijk te doen, want plaatsing voor een kamp gebeurt op volgorde van betaling.
	@endif
</p>

@if ($participant->inkomen)
	<p>
		U heeft een korting op de kampprijs aangevraagd. Daarvoor hebben wij een inkomensverklaring van u nodig. Kijk in de bevestigingsmail voor meer informatie hierover.
	</p>
@endif

<p>
	Er is ook automatisch een account aangemaakt. Hiermee kunt u inloggen op ons <a href="https://aas2.anderwijs.nl">administratiesysteem</a> om de gegevens van uw kind te beheren. De details staan in de bevestigingsmail.
</p>

<p>
	<a href="https://www.anderwijs.nl">Terug naar de website van Anderwijs</a>
</p>

@endsection