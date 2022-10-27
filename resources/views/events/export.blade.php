@php use App\ValueObjects\Gender; @endphp
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

		h3.c-participants {
			page-break-before: always;
		}

		h2 {
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

		.c-comment {

		}

		.c-comment__text {
			margin: 0;
			padding: 0;
			margin-bottom: 12px;
		}

		.c-comment__date {
			font-size: 12px;
			color: grey;
		}

		.table-title {
			font-weight: bold;
			font-size: 18px;
			padding-top: 0.5rem;
		}

		.table-margin-after td {
			padding-bottom: 1rem;
		}
	</style>
</head>
<body>

<h1>{{ $event->naam }} {{ $event->datum_start->format('Y') }} ({{ $event->code }})</h1>
<small>Informatie alleen voor leidingploeg - vernietigen na kamp!</small>

<h2>Kampgegevens</h2>
<table>
	<tbody>
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
		<td colspan="2" class="table-title">Leeftijdsverdeling</td>
	</tr>
	@foreach ($age_freq as $age => $freq)
		<tr>
			<td>{{ $age }}</td>
			<td>{{ $freq }}</td>
		</tr>
	@endforeach

	<tr>
		<td colspan="2" class="table-title">Overige statistieken</td>
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
		<td>Non-binairen</td>
		<td>{{ $stats['num_non-binaries'] }}</td>
	</tr>
	<tr class="table-margin-after">
		<td>Onbekent</td>
		<td>{{ $stats['num_unknown'] }}</td>
	</tr>
	<tr>
		<td>VMBO</td>
		<td>{{ $stats['num_VMBO'] }}</td>
	</tr>
	<tr>
		<td>HAVO</td>
		<td>{{ $stats['num_HAVO'] }}</td>
	</tr>
	<tr class="table-margin-after">
		<td>VWO</td>
		<td>{{ $stats['num_VWO'] }}</td>
	</tr>
	<tr>
		<td>Nieuw dit kamp</td>
		<td>{{ $stats['num_new'] }}</td>
	</tr>
	<tr class="table-margin-after">
		<td>Ervaren</td>
		<td>{{ $stats['num_old'] }}</td>
	</tr>
	<tr>
		<td>Examenkandidaten</td>
		<td>{{ $stats['num_exam'] }}</td>
	</tr>
	</tbody>
</table>

@foreach ($participants as $participant)

	<h3 class="c-participants">{{ $participant->voornaam }} {{ $participant->tussenvoegsel }} {{ $participant->achternaam }}

		@unless ($participant->pivot->geplaatst)
			<br/>
			<small>
				nog niet geplaatst
			</small>

		@endunless

	</h3>

	<table>
		<tbody>

		<tr>
			<td colspan="2" class="table-title">Persoons- en contactgegevens deelnemer</td>
		</tr>
		<tr>
			<td style="width:200px;">Geboortedatum</td>
			<td>{{ $participant->geboortedatum->format('d-m-Y') }}</td>
		</tr>
		<tr>
			<td>Geslacht</td>
			<td>{{ Gender::fromString($participant->geslacht) }}</td>
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
			<td colspan="2" class="table-title">Contactgegevens ouder</td>
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
			<td colspan="2" class="table-title">Bijspijkerinformatie</td>
		</tr>
		<tr>
			<td>Naam school</td>
			<td>{{ $participant->school }}</td>
		</tr>
		<tr>
			<td>Klas</td>
			<td>{{ $participant->klas }} {{ $participant->niveau }}</td>
		</tr>

		@foreach ($participantCourses[$participant->id] as $i => $course)
			<tr class="table-margin-after">
				<td>Vak {{$i+1}}: {{ $course['naam'] }}</td>
				<td>{{ $course['info'] }}</td>
			</tr>
		@endforeach

		<tr>
			<td colspan="2" class="table-title">Overige informatie</td>
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
			<td>
				@foreach($participant->comments()->public()->get() as $comment)
					<div class="c-comment">
						<span class="c-comment__date">Door {{ $comment->user->volnaam }} op {{ $comment->updated_at ?? 'n.v.t' }}</span>
						<p class="c-comment__text">{{ $comment->text }}</p>
					</div>
				@endforeach
			</td>
		</tr>

		</tbody>
	</table>

@endforeach

</body>
</html>
