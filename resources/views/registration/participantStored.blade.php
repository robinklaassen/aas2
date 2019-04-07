@extends('master')

@section('title')
	Deelnemer ingeschreven
@endsection

@section('content')

<h1>Bedankt voor uw voorlopige inschrijving!</h1>

<hr/>

@if ($camp->prijs == 0)
	<p>
		U heeft uw kind succesvol ingeschreven voor een Anderwijskamp. Binnen enkele momenten ontvangt u een automatische bevestigingsmail op het door u opgegeven emailadres. Om de inschrijving definitief te maken, dient u het kampgeld over te maken op onze rekening. <strong>Voor dit kamp is het kampgeld echter nog niet definitief vastgesteld.</strong> Zodra dat is gebeurd, ontvangt u daarover per e-mail bericht.
	</p>
@else
	<p>
		U heeft uw kind succesvol ingeschreven voor een Anderwijskamp. Binnen enkele momenten ontvangt u een automatische bevestigingsmail op het door u opgegeven emailadres. Om de inschrijving definitief te maken, dient u het kampgeld zoals onderstaand over te maken op onze rekening. De betalingsinformatie vindt u ook terug in de bevestigingsmail. Plaatsing van deelnemers op een kamp gebeurt op volgorde van betaling, dus wacht hier niet te lang mee.
	</p>

	<div class="row">
		<div class="col-sm-6">
			<table class="table table-hover">
			<caption>Betalingsinformatie</caption>
			<tr>
				<td>Volledige kampprijs</td>
				<td>€ {{ $camp->prijs }}</td>
			</tr>
			<tr>
				<td>Opgegeven maandinkomen en korting</td>
				<td>{{ $incomeTable[$participant->inkomen] }}</td>
			</tr>
			<tr>
				<td><strong>Te betalen bedrag</strong></td>
				<td><strong>€ {{ $toPay }}</strong></td>
			</tr>
			<tr>
				<td>Rekeningnummer</td>
				<td>NL68 TRIO 0198 4197 83</td>
			</tr>
			<tr>
				<td>Ten name van</td>
				<td>Vereniging Anderwijs te Utrecht</td>
			</tr>
			<tr>
				<td>Onder vermelding van</td>
				<td>naam deelnemer + deze kampcode: {{ $camp->code }}</td>
			</tr>
			</table>
		</div>
	</div>
@endif

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
	Er is ook automatisch een account aangemaakt. Hiermee kunt u inloggen op ons <a href="https://aas2.anderwijs.nl">administratiesysteem</a> om de gegevens van uw kind te beheren. De details staan in de bevestigingsmail.
</p>

<p>
	<a href="http://www.anderwijs.nl">Terug naar de website van Anderwijs</a>
</p>

@endsection