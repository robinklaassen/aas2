@extends('master')

@section('title')
@if ($viewType == 'admin')
{{ $participant->volnaam }}
@elseif ($viewType == 'profile')
Mijn profiel
@endif
@endsection

@section('content')
<!-- Dit is de pagina met gegevens voor een specifieke deelnemer -->

<!-- Volledige naam en knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>{{ $participant->volnaam }}</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			@if ($viewType == 'profile')
			
			@can("update", $participant)
			<a class="btn btn-primary" type="button" href="{{ url('/profile/edit') }}" style="margin-top:21px;">Bewerken</a>
			@endcan
			@can("onEvent", $participant)
			<a class="btn btn-info" type="button" href="{{ url('/profile/on-camp') }}" style="margin-top:21px;">Op kamp</a>
			@endcan

			@can("changePassword", $participant)
			<a class="btn btn-warning" type="button" href="{{ url('/profile/password') }}" style="margin-top:21px;">Nieuw wachtwoord</a>
			@endcan
			@elseif ($viewType == 'admin')

			@can("update", $participant)
			<a class="btn btn-primary" type="button" href="{{ url('/participants', [$participant->id, 'edit']) }}" style="margin-top:21px;">Bewerken</a>
			<a class="btn btn-info" type="button" href="{{ url('/participants', [$participant->id, 'on-event']) }}" style="margin-top:21px;">Op kamp</a>
			@endcan

			@can("changePassword", $participant)
			<a class="btn btn-warning" type="button" href="{{ url('/users', [$participant->user->id, 'password']) }}" style="margin-top:21px;">Nieuw wachtwoord</a>
			@endcan

			@can("delete", $participant)
			<a class="btn btn-danger" type="button" href="{{ url('/participants', [$participant->id, 'delete']) }}" style="margin-top:21px;">Verwijderen</a>
			@endcan

			@endif
		</p>
	</div>
</div>

<hr />

<div class="row">

	<!-- Linker kolom -->
	<div class="col-md-6">
		<!-- Profieltabel -->
		<table class="table table-hover">
			<caption>Profiel</caption>
			@can("showPrivate", $participant)
			<tr>
				<td>Geboortedatum</td>
				<td>{{ $participant->geboortedatum->format('d-m-Y') }}</td>
			</tr>
			<tr>
				<td>Geslacht</td>
				<td>{{ $participant->geslacht }}</td>
			</tr>
			<tr>
				<td>Adres</td>
				<td>{{ $participant->adres }}</td>
			</tr>
			<tr>
				<td>Postcode</td>
				<td>{{ $participant->postcode }}</td>
			</tr>
			@endcan
			<tr>
				<td>Woonplaats</td>
				<td>{{ $participant->plaats }}</td>
			</tr>
			@can("showPrivate", $participant)
			<tr>
				<td>Telefoonnummer ouder (vast)</td>
				<td>{{ $participant->telefoon_ouder_vast }}</td>
			</tr>
			<tr>
				<td>Telefoonnummer ouder (mobiel)</td>
				<td>{{ $participant->telefoon_ouder_mobiel }}</td>
			</tr>
			<tr>
				<td>Telefoonnummer deelnemer</td>
				<td>{{ $participant->telefoon_deelnemer }}</td>
			</tr>
			<tr>
				<td>Emailadres ouder</td>
				<td><a href="mailto:{{$participant->email_ouder}}">{{ $participant->email_ouder }}</a></td>
			</tr>
			<tr>
				<td>Emailadres deelnemer</td>
				<td><a href="mailto:{{$participant->email_deelnemer}}">{{ $participant->email_deelnemer }}</a></td>
			</tr>
			<tr>
				<td>
					Informatie ontvangen via 
					<span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" title="Informatie over deelname aan het kamp kunt u via email en post ontvangen.">
				</td>
				<td>{{ $participant->information_channel_description }}</td>
			</tr>
			@endcan
			@can("showFinance", $participant)
			<tr>
				<td>Inkomen</td>
				<td>{{ $income[$participant->inkomen] }}</td>
			</tr>
			@endcan
			@can("showPractical", $participant)
			<tr>
				<td>School</td>
				<td>{{ $participant->school }}</td>
			</tr>
			<tr>
				<td>Niveau</td>
				<td>{{ $participant->niveau }}</td>
			</tr>
			<tr>
				<td>Klas</td>
				<td>{{ $participant->klas }}</td>
			</tr>
			@endcan
			<tr>
				<td>Hoe bij Anderwijs</td>
				<td>{{ $participant->hoebij }}</td>
			</tr>
			@can("showPrivate", $participant)
			<tr>
				<td>Overige informatie</td>
				<td style="white-space:pre-wrap;">{{ $participant->opmerkingen }}</td>
			</tr>
			@endcan
		</table>


	</div>

	<!-- Rechter kolom -->
	<div class="col-md-6">
		<!-- Administratietabel -->
		<table class="table table-hover">
			<caption>Administratie</caption>
			@can("showAdministrative", $participant)
			<tr>
				<td>AAS ID</td>
				<td>{{ $participant->id }}</td>
			</tr>
			<tr>
				<td>Account(naam)</td>
				<td>@if($participant->user()->first()) {{ $participant->user()->first()->username }} @else -geen- @endif</td>
			</tr>
			<tr>
				<td>Ingeschreven op</td>
				<td>{{ $participant->created_at->format('d-m-Y') }}</td>
			</tr>
			<tr>
				<td>Laatste update</td>
				<td>{{ $participant->updated_at->format('d-m-Y') }}</td>
			</tr>
			@endcan
			@can("showFinance", $participant)
			<tr>
				@unless ($participant->inkomen == 0)
				<td>Inkomensverklaring</td>
				<td>
					@if ($participant->inkomensverklaring != null)
					{{ $participant->inkomensverklaring->format('d-m-Y') }}
					@else
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true" data-toggle="tooltip" title="Inkomensverklaring nog niet binnen!"></span>
					@endif
				</td>
				@endunless
			</tr>
			@endcan
		</table>

		<table class="table table-hover">
			<caption>Promotie</caption>

			<tr>
				<td>
					Mailings ontvangen 
					<span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" title="Dit gaat alleen om nieuwsbrieven en kortingsacties. Bij deelname aan een kamp ontvangt u altijd mail."></span>
				</td>
				<td>{{ $participant->mag_gemaild ? "Ja" : "Nee" }}</td>
			</tr>
		</table>
		<!-- Kampen -->
		<table class="table table-hover">
			<caption>Kampen</caption>
			@forelse ($participant->events()->whereIn('type', ['kamp', 'online'])->orderBy('datum_start', 'desc')->get() as $event)
			<tr>
				<td><a href="{{ url('/events', $event->id) }}">{{ $event->naam }}</a></td>
				<td>
					@can("showAdvanced", $event)
					{{ $event->code }}
					@else
					{{ $event->datum_start->format('d-m-Y') }}
					@endcan
				</td>
				<td>
					@if ($courseOnCamp != [])
					@foreach ($courseOnCamp[$event->id] as $course) {{ $course['naam'] }} <br /> @endforeach
					@endif
				</td>
				<td>
					@can("editParticipant", [$event, $participant])
					<a href="{{ $viewType == 'admin' ? url('/participants', [$participant->id, 'edit-event', $event->id]) : url('/profile/edit-camp', $event->id)}}">
						<span class="glyphicon glyphicon-edit" data-toggle="tooltip" title="Vakken bewerken" aria-hidden="true"></span></a>
					@endcan
					</td>
			</tr>
			@empty
			<tr>
				<td>Geen kampen gevonden</td>
			</tr>
			@endforelse
		</table>

		@if ($viewType == 'profile')
		<p>De opgegeven informatie per vak kunt u bekijken door op 'vakken bewerken' te klikken.</p>
		@endif

		@can("showAdministrative", $participant)
			<div>
				@include('partials.comments', [ 'comments' => $participant->comments, 'type' => 'App\Participant', 'key' => $participant->id ])
			</div>
		@endcan
	</div>



</div>

@endsection