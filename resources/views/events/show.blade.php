@extends('master')

@section('title')
{{ $event->naam }}
@endsection

@section('header')
<style type="text/css">
	.btn.btn-sm {
		margin: 6px;
	}
</style>
@endsection

@section('content')
<!-- Dit is de pagina met gegevens voor een specifiek evenement -->

<!-- Volledige naam en knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>{{ $event->naam }}</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			@can("update", $event)
			<a class="btn btn-primary" type="button" href="{{ url('/events', [$event->id, 'edit']) }}"
				style="margin-top:21px;">Bewerken</a>
			@endcan
			@can("editAdvanced", $event)
			<a class="btn btn-info" type="button" href="{{ url('/events', [$event->id, 'join-members']) }}"
				style="margin-top:21px;">Leden koppelen</a>
			@endcan
			@can("viewReviewResults", $event)
			@if ($event->reviews->count() > 0)
			<a class="btn btn-success" type="button" href="{{ url('/events', [$event->id, 'reviews']) }}"
				style="margin-top:21px;">Resultaten enquêtes <span
					class="badge">{{ $event->reviews->count() }}</span></a>
			@endif
			@endcan
			@can("delete")
			<a class="btn btn-danger" type="button" href="{{ url('/events', [$event->id, 'delete']) }}"
				style="margin-top:21px;">Verwijderen</a>
			@endcan
		</p>
	</div>
</div>

<hr />

<div class="row">

	<!-- Evenementsgegevens -->
	<div class="col-md-6">
		<table class="table table-hover">
			<caption>Evenementsgegevens</caption>
			@can("showAdvanced", $event)
			<tr>
				<td>AAS ID</td>
				<td>{{ $event->id }}</td>
			</tr>
			<tr>
				<td>Code</td>
				<td>{{ $event->code }}</td>
			</tr>
			<tr>
				<td>Type</td>
				<td>{{ (\App\Event::class)::TYPE_DESCRIPTIONS[$event->type] }}</td>
			</tr>
			@if($event->package_type != null)
			<tr>
				<td>Pakket types</td>
				<td>{{ (\App\EventPackage::class)::TYPE_DESCRIPTIONS[$event->package_type] }}</td>
			</tr>
			@endif
			@endcan

			@can("showBasic", $event)
			@if (($event->type == 'kamp') && (\Auth::user()->profile_type != 'App\Participant'))
			<tr>
				<td>Start voordag(en)</td>
				<td>{{ Date::Format($event->datum_voordag) }}</td>
			</tr>
			@endif
			<tr>
				<td>Start evenement</td>
				<td>{{ Date::Format($event->datum_start) }} {{ $event->tijd_start }}</td>
			</tr>
			<tr>
				<td>Eind evenement</td>
				<td>{{ Date::Format($event->datum_eind) }} {{ $event->tijd_eind }}</td>
			</tr>
			<tr>
				<td>Locatie</td>
				@can ("showBasic", $event->location)
				<td><a href="{{ url('/locations', $event->location->id) }}">{{ $event->location->plaats }}</a></td>
				@else
				<td>{{ $event->location->naam }} ({{ $event->location->plaats }})</td>
				@endcan
			</tr>
			@endcan


			@if ($event->type == 'kamp')
			@can("showAdvanced", $event)
			<tr>
				<td>Kampprijs (zonder korting)</td>
				<td>€ {{ $event->prijs }}</td>
			</tr>
			@endcan
			@can("showBasic", $event)
			<tr>
				<td>Streeftal L / D</td>
				<td>{{ $event->streeftal }} / {{ ($event->streeftal - 1) * 3 }}</td>
			</tr>
			<tr>
				<td>Vol</td>
				<td>{{ ($event->vol) ? 'Ja' : 'Nee' }}</td>
			</tr>
			@endcan
			@can("showAdvanced", $event)
			<tr>
				<td>Openbaar</td>
				<td>{{ ($event->openbaar) ? 'Ja' : 'Nee' }}</td>
			</tr>
			<tr>
				<td>Beschrijving (website)</td>
				<td style="white-space:pre-wrap;">{{ $event->beschrijving }}</td>
			</tr>
			@endcan
			@endif
		</table>
	</div>

	<!-- Overzicht leiding -->
	<div class="col-md-6">
		<table class="table table-hover">
			<caption>
				@if ($event->type=='kamp')
				Leiding
				@elseif ($event->type=='training')
				Trainers
				@elseif ($event->type=='overig')
				Leden
				@endif
				({{ $event->members->count() }})
			</caption>
			@foreach($event->members()->orderBy('voornaam')->get() as $member)
			<tr>
				<td>
					@can("showBasic", $member)
					<a href="{{ url('/members', $member->id) }}">
						{{ $member->voornaam }} {{ $member->tussenvoegsel }} {{ $member->achternaam }}
					</a>
					@else
					{{ $member->voornaam }} {{ $member->tussenvoegsel }} {{ $member->achternaam }}
					@endcan
				</td>

				@if ($event->type == 'kamp')
				<td>
					@if ($member->pivot->wissel)
					<span class="glyphicon glyphicon-hourglass" aria-hidden="true" data-toggle="tooltip"
						title="Wisselleiding van {{DateTime::createFromFormat('Y-m-d',$member->pivot->wissel_datum_start)->format('d-m-Y')}} t/m {{DateTime::createFromFormat('Y-m-d',$member->pivot->wissel_datum_eind)->format('d-m-Y')}}"></span>
					@endif
				</td>
				@endif

				<td>{{ $member->pivot->created_at->format('d-m-Y') }}</td>

				@can("showAdministrative", $member)
				@if ($event->type=='kamp')
				<td>
					@if ($member->vog)
					<span class="glyphicon glyphicon-ok" aria-hidden="true" data-toggle="tooltip"
						title="VOG ingeleverd!"></span>
					@else
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true" data-toggle="tooltip"
						title="VOG nog niet ingeleverd!"></span>
					@endif
				</td>
				@endif
				@endcan

				@if ($event->type == 'kamp')
				@can("editMember", [$event, $member])
				<td><a href="{{ url('/events', [$event->id, 'edit-member', $member->id]) }}"><span
							class="glyphicon glyphicon-edit" aria-hidden="true" data-toggle="tooltip"
							title="Koppeling bewerken"></span></a></td>
				@else
				<td></td>
				@endcan

				@endif

				@can("removeMember", [$event, $member])
				<td><a href="{{ url('/events', [$event->id, 'remove-member', $member->id]) }}"><span
							class="glyphicon glyphicon-remove" aria-hidden="true" data-toggle="tooltip"
							title="Koppeling verwijderen"></a></td>
				@else
				<td></td>
				@endcan

			</tr>
			@endforeach
		</table>


		@can("showAdvanced", $event)
		@include('partials.comments', [ 'comments' => $event->comments, 'type' => 'App\Event', 'key' => $event->id ])
		@endcan
	</div>
</div>

@if (in_array($event->type, ['kamp', 'online']))
<hr />
<div style="display: flex; flex-wrap: wrap; justify-content: flex-end;">
	@can("subjectCheck", $event)
	<a role="button" class="btn btn-info btn-sm" href="{{ url('/events', [$event->id, 'check', 'all']) }}"><span
			class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> Vakdekking</a>
	@endcan
	@can("mailing", $event)
	<a role="button" class="btn btn-info btn-sm" href="{{ url('/events', [$event->id, 'email']) }}"><span
			class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Emailadressen</a>
	@endcan
	@can('nightRegister', $event)
	<a role="button" class="btn btn-info btn-sm" href="{{ url('/events', [$event->id, 'night-register']) }}"><span
			class="glyphicon glyphicon-tent" aria-hidden="true"></span> Nachtregister</a>
	@endcan
	@can("budget", $event)
	<a role="button" class="btn btn-success btn-sm" href="{{ url('/events', [$event->id, 'budget']) }}"><span
			class="glyphicon glyphicon-euro" aria-hidden="true"></span> Kampbudget</a>
	@endcan
	@can("paymentoverview", $event)
	<a role="button" class="btn btn-success btn-sm" href="{{ url('/events', [$event->id, 'payments']) }}"><span
			class="glyphicon glyphicon-usd" aria-hidden="true"></span> Betalingsoverzicht</a>
	@endcan
	@can("questionair")
	<a role="button" class="btn btn-primary btn-sm" href="{{ url('/enquete',[$event->id]) }}"><span
			class="glyphicon glyphicon-comment" aria-hidden="true"></span> Enqu&ecirc;te</a>
	@endcan
	@can("placement")
	<a role="button" class="btn btn-primary btn-sm" href="{{ url('/events', [$event->id, 'send']) }}"><span
			class="glyphicon glyphicon-send" aria-hidden="true"></span> Plaatsen</a>
	@endcan
	@can('exportParticipants', $event)
	<a role="button" class="btn btn-primary btn-sm" href="{{ url('/events', [$event->id, 'export']) }}"><span
			class="glyphicon glyphicon-file" aria-hidden="true"></span> Export</a>
	@endcan
</div>

@can('viewParticipants', $event)

<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#overview" aria-controls="overview" role="tab"
			data-toggle="tab">Overzicht</a></li>

	@role("kantoorci")
	<li role="presentation"><a href="#addresses" aria-controls="addresses" role="tab" data-toggle="tab">Adressen</a>
	</li>
	@endrole
</ul>

<div class="tab-content" style="margin: 0 20px;">

	<div role="tabpanel" class="tab-pane active" id="overview">
		<table class="table table-hover" id="participantsTable">
			<caption>
				Deelnemers ({{ $numberOfParticipants }})
			</caption>
			@if ($event->participants->count())
			<thead>
				<tr>
					@can("viewParticipantsAdvanced", $event)
					<th data-orderable>Naam</th>
					<th data-orderable>Niveau</th>
					<th></th>
					@if($event->package_type != null)
					<th data-orderable>Pakket</th>
					@endif
					<th>Vakken</th>
					<th data-orderable>Aanmelding</th>
					<th data-orderable>Betaling</th>
					<th data-orderable>Inkomensverklaring</th>
					<th data-orderable>Geplaatst</th>
					@else
					<th data-orderable>Naam</th>
					<th data-orderable>Niveau</th>
					<th>Telefoon ouder(s)</th>
					<th data-orderable>Woonplaats</th>
					<th data-orderable>Aanmelding</th>
					@endcan
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			@endif
			@foreach($event->participants()->orderBy('voornaam')->get() as $participant)
			@if( \Auth::user()->can("viewParticipantsAdvanced", $event) || $participant->pivot->geplaatst )
			<tr>
				<td>
					@can("showBasic", $participant)
					<a href="{{ url('/participants', $participant->id) }}">
						{{ $participant->volnaam }}
					</a>
					@else
					{{ $participant->volnaam }}
					@endcan

				</td>

				<td>
					{{ $participant->vol_niveau }}
				</td>

				@if($event->package_type != null)
				<td>{{ $participant->pivot->package->title }}</td>
				@endif

				@can("viewParticipantsAdvanced", $event)
				<td>
					@if ($participantIsNew[$participant->id] == 1)
					<span class="glyphicon glyphicon-baby-formula" data-toggle="tooltip" title="Nieuw dit kamp"></span>
					@endif
				</td>
				@endcan

				<td>{{ $participantCourseString[$participant->id] }}</td>

				@cannot("viewParticipantsAdvanced", $event)

				<td>{{ $participant->telefoon_ouder_vast }}</td>

				<td>{{ $participant->plaats }}</td>

				@endcannot

				<td>{{ $participant->pivot->created_at->format('Y-m-d') }}</td>

				@can("viewParticipantsAdvanced", $event)
				<td>
					@unless ($participant->pivot->datum_betaling == '0000-00-00')
					{{ substr($participant->pivot->datum_betaling,0,4) .'-'.substr($participant->pivot->datum_betaling,5,2).'-'.substr($participant->pivot->datum_betaling,8,2) }}
					@else
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true" data-toggle="tooltip"
						title="Deze deelnemer heeft nog niet betaald!"></span>
					@endunless
				</td>

				<td>
					@unless ($participant->inkomen == 0)
					@if ($participant->inkomensverklaring != null)
					{{ $participant->inkomensverklaring->format('Y-m-d') }}
					@else
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true" data-toggle="tooltip"
						title="Inkomensverklaring nog niet binnen!"></span>
					@endif
					@else
					<span class="glyphicon glyphicon-ok" aria-hidden="true" data-toggle="tooltip"
						title="Inkomensverklaring niet nodig"></span>
					@endunless
				</td>
				<td>{{ ($participant->pivot->geplaatst) ? 'Ja' : 'Nee' }}</td>
				@endcan

				@can("editParticipant", [$event, $participant])
				<td><a href="{{ url('/events', [$event->id, 'edit-participant', $participant->id]) }}"><span
							class="glyphicon glyphicon-edit" aria-hidden="true" data-toggle="tooltip"
							title="Inschrijving bewerken"></span></a></td>
				<td><a href="{{ url('/events', [$event->id, 'move-participant', $participant->id]) }}"><span
							class="glyphicon glyphicon-arrow-right" aria-hidden="true" data-toggle="tooltip"
							title="Verplaatsen naar ander kamp"></span></a></td>
				@else
				<td></td>
				<td></td>
				@endcan

				@can("removeParticipant", [$event, $participant])
				<td><a href="{{ url('/events', [$event->id, 'remove-participant', $participant->id]) }}"><span
							class="glyphicon glyphicon-remove" aria-hidden="true" data-toggle="tooltip"
							title="Inschrijving verwijderen"></span></a></td>
				@else
				<td></td>
				@endcan

			</tr>
			@endif
			@endforeach
		</table>
	</div>

	@can("showPrivateAny", \App\Participant::class)
	<div role="tabpanel" class="tab-pane" id="addresses">
		<table class="table table-hover" id="addressesTable">
			<caption>
				Geplaatste deelnemers ({{ $event->participants()->wherePivot('geplaatst', 1)->count() }})
			</caption>
			@if ($event->participants()->wherePivot('geplaatst', 1)->count())
			<thead>
				<tr>
					<th data-orderable>Naam</th>
					<th data-orderable>Alleen email</th>
					<th data-orderable>Adres</th>
					<th data-orderable>Postcode</th>
					<th data-orderable>Woonplaats</th>
					<th data-orderable>Email ouders</th>
					<th data-orderable>Email deelnemer</th>
				</tr>
			</thead>
			<tbody>
				@foreach($event->participants()->wherePivot('geplaatst', 1)->orderBy('voornaam')->get() as $participant)
				<tr>
					<td>
						<a href="{{ url('/participants', $participant->id) }}">
							{{ $participant->voornaam }} {{ $participant->tussenvoegsel }}
							{{ $participant->achternaam }}
						</a>
					</td>
					<td>
						@if($participant->information_channel === "only-email")
						<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
						@endif
					</td>
					<td>{{ $participant->adres }}</td>
					<td>{{ $participant->postcode }}</td>
					<td>{{ $participant->plaats }}</td>
					<td>{{ $participant->email_ouder }}</td>
					<td>{{ $participant->email_deelnemer }}</td>
				</tr>
				@endforeach
			</tbody>
			@endif
		</table>
	</div>
	@endcan
</div>
@endcan
@endif

@endsection

@section ('footer')
<script type="text/javascript">
	function getColumnOptions(tableHeaderSelector) {
		let columnOptions = [];
		$(tableHeaderSelector).each(function(i) {
			let option = ($(this).data('orderable')) ? null : {'orderable': false};
			columnOptions.push(option);
		})
		return columnOptions;
	}
	($(document).ready(function() {
		makeTableSortable("#participantsTable");
		makeTableSortable("#addressesTable");
		
	}));
</script>
@endsection