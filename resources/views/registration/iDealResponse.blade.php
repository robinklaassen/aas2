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

<hr/>

@if ($status == 'ok')
	<p>
		U heeft uw kind succesvol ingeschreven voor een Anderwijskamp en direct via iDeal betaald. Als het goed is heeft u reeds een automatische bevestigingsmail gekregen op het door u opgegeven emailadres, anders ontvangt u die binnenkort. Lees de mail goed door, er staat belangrijke informatie in met betrekking tot de inschrijving.
	</p>
@else
	<p>
		U heeft uw kind ingeschreven voor een Anderwijskamp, maar de betaling via iDeal is helaas mislukt. Als het goed is heeft u reeds een automatische bevestigingsmail gekregen op het door u opgegeven emailadres, anders ontvangt u die binnenkort. In die mail staat onder andere de betalingsinformatie. Om de inschrijving definitief te maken, dient u het kampgeld over te maken op onze rekening zoals in de mail vermeld. Plaatsing van deelnemers op een kamp gebeurt op volgorde van betaling, dus wacht hier niet te lang mee.
	</p>
@endif

@if ($participant->events()->count() == 1)
	@if ($participant->inkomen)
		<p>
			U heeft een korting op de kampprijs aangevraagd. Daarvoor dient u een inkomensverklaring van u en uw (eventuele) partner naar ons op te sturen. Dit kan naar:
		</p>
		<p>
			Vereniging Anderwijs<br/>
			T.a.v. de penningmeester<br/>
			Postbus 13228<br/>
			3507 LE Utrecht
		</p>
		<p>
			Na beoordeling van de kortingsaanvraag nemen wij zonodig contact met u op.
		</p>
	@endif

	<p>
		Heeft u een nieuwe deelnemer ingeschreven? Dan is er ook automatisch een account aangemaakt. Hiermee kunt u inloggen op ons <a href="http://aas2.anderwijs.nl">administratiesysteem</a> om de gegevens van uw kind te beheren. De details staan in de bevestigingsmail.
	</p>

	<p>
		<a href="http://www.anderwijs.nl">Terug naar de website van Anderwijs</a>
	</p>
@else
	<p>
		<a href="http://aas2.anderwijs.nl/profile">Terug naar uw profiel</a.
	</p>
@endif

@endsection