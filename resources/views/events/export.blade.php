<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<style type="text/css">
		@page {
			margin: 80px;
		}

		h1 {
			text-align: center;
			font-size: 300%;
			margin-top: 1em;
		}

		h3 {
			font-size: 150%;
			text-align: center;
		}

		.frontpage {
			margin-left: 100em;
		}

		h2 {
			page-break-before: always;
		}

		h2 small {
			font-weight: normal;
			font-size: 120%;
		}

		table {
			margin: 10px 0;
		}

		tr {
			vertical-align: top;
		}

		.divider {
			height: 0.5em;
		}
	</style>
</head>

<body>

<h1>{{ $event->naam }} {{ $event->datum_start->format('Y') }} ({{ $event->code }})</h1>
<h3>deelnemerinformatie</h3>

<table class="frontpage">
	<tbody>
	<tr>
		<td colspan="2">KAMPGEGEVENS</td>
	</tr>
	<tr>
		<td style="width:11em;">Start voordag(en)</td>
		<td style="width:40em;">{{ $event->datum_voordag->format('d-m-Y') }}</td>
	</tr>
	<tr>
		<td>Start kamp</td>
		<td>{{ $event->datum_start->format('d-m-Y') }}</td>
	</tr>
	<tr>
		<td>Eind kamp</td>
		<td>{{ $event->datum_eind->format('d-m-Y') }}</td>
	</tr>
	<tr>
		<td>Locatie</td>
		<td>{{ $event->location->naam }} ({{ $event->location->plaats }})</td>
	</tr>
	<tr>
		<td>Aantal deelnemers</td>
		<td>{{ count($participants) }} (waarvan nu {{ $num_participants_placed }} geplaatst)</td>
	</tr>

	<tr>
		<td colspan="2" class="divider"></td>
	</tr>

	<tr>
		<td colspan="2">LEEFTIJDSVERDELING</td>
	</tr>
	@foreach ($age_freq as $age => $freq)
	<tr>
		<td>{{ $age }}</td>
		<td>{{ $freq }}</td>
	</tr>
	@endforeach

	<tr>
		<td colspan="2" class="divider"></td>
	</tr>

	<tr>
		<td colspan="2">OVERIGE STATISTIEKEN</td>
	</tr>
	<tr>
		<td>Mannen</td>
		<td>{{ $stats['num_males'] }}</td>
	</tr>
	<tr>
		<td>Vrouwen</td>
		<td>{{ $stats['num_females'] }}</td>
	</tr>
	<tr>
		<td colspan="2" class="divider"></td>
	</tr>
	<tr>
		<td>VMBO</td>
		<td>{{ $stats['num_VMBO'] }}</td>
	</tr>
	<tr>
		<td>HAVO</td>
		<td>{{ $stats['num_HAVO'] }}</td>
	</tr>
	<tr>
		<td>VWO</td>
		<td>{{ $stats['num_VWO'] }}</td>
	</tr>
	<tr>
		<td colspan="2" class="divider"></td>
	</tr>
	<tr>
		<td>Nieuw dit kamp</td>
		<td>{{ $stats['num_new'] }}</td>
	</tr>
	<tr>
		<td>Ervaren</td>
		<td>{{ $stats['num_old'] }}</td>
	</tr>
	<tr>
		<td colspan="2" class="divider"></td>
	</tr>
	<tr>
		<td>Examenkandidaten</td>
		<td>{{ $stats['num_exam'] }}</td>
	</tr>
	</tbody>
</table>

@foreach ($participants as $participant)

<h2>{{ $participant->voornaam }} {{ $participant->tussenvoegsel }} {{ $participant->achternaam }}

@unless ($participant->pivot->geplaatst)
<br/>
<small>
	nog niet geplaatst
</small>

@endunless

</h2>

<table>
	<tbody>

	<tr>
		<td colspan="2">PERSOONS- EN CONTACTGEGEVENS DEELNEMER</td>
	</tr>
	<tr>
		<td style="width:200px;">Geboortedatum</td>
		<td>{{ $participant->geboortedatum->format('d-m-Y') }}</td>
	</tr>
	<tr>
		<td>Geslacht</td>
		<td>{{ ($participant->geslacht=='M') ? 'man' : 'vrouw' }}</td>
	</tr>
	<tr>
		<td>Telefoonnummer</td>
		<td>{{ $participant->telefoon_deelnemer }}</td>
	</tr>
	<tr>
		<td>Emailadres</td>
		<td>{{ $participant->email_deelnemer }}</td>
	</tr>

	<tr>
		<td colspan="2" class="divider"></td>
	</tr>

	<tr>
		<td colspan="2">CONTACTGEGEVENS OUDER</td>
	</tr>

	<tr>
		<td>Kamp informatie</td>
		<td>{{ $participant->information_channel_description }}</td>
	</tr>

	<tr>
		<td>Adres</td>
		<td>{{ $participant->adres }}</td>
	</tr>
	<tr>
		<td>Postcode</td>
		<td>{{ $participant->postcode }}</td>
	</tr>
	<tr>
		<td>Woonplaats</td>
		<td>{{ $participant->plaats }}</td>
	</tr>
	<tr>
		<td>Telefoonnummer vast</td>
		<td>{{ $participant->telefoon_ouder_vast }}</td>
	</tr>
	<tr>
		<td>Telefoonnummer mobiel</td>
		<td>{{ $participant->telefoon_ouder_mobiel }}</td>
	</tr>
	<tr>
		<td>Emailadres</td>
		<td>{{ $participant->email_ouder }}</td>
	</tr>

	<tr>
		<td colspan="2" class="divider"></td>
	</tr>

	<tr>
		<td colspan="2">BIJSPIJKERINFORMATIE</td>
	</tr>
	<tr>
		<td>Naam school</td>
		<td>{{ $participant->school }}</td>
	</tr>
	<tr>
		<td>Klas</td>
		<td>{{ $participant->klas }} {{ $participant->niveau }}</td>
	</tr>

	<tr>
		<td colspan="2" class="divider"></td>
	</tr>

	@foreach ($participantCourses[$participant->id] as $i => $course)
	<tr>
		<td>Vak {{$i+1}}: {{ $course['naam'] }}</td>
		<td>{{ $course['info'] }}</td>
	</tr>
	<tr>
		<td colspan="2" class="divider"></td>
	</tr>
	@endforeach

	<tr>
		<td colspan="2">OVERIGE INFORMATIE</td>
	</tr>
	<tr>
		<td>Hoe bij Anderwijs</td>
		<td>{{ $participant->hoebij }}</td>
	</tr>
	<tr>
		<td>Opmerkingen deelnemer</td>
		<td>{{ $participant->opmerkingen }}</td>
	</tr>
	<tr>
		<td>Opmerkingen administratie</td>
		<td>{{ $participant->opmerkingen_admin }}</td>
	</tr>

	</tbody>
</table>

@endforeach

</body>
</html>