@extends('master')

@section('title')
Lijsten
@endsection

@section('content')

<h1>Lijsten</h1>

<hr />

<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#stats" aria-controls="stats" role="tab" data-toggle="tab">Statistieken</a></li>
	@can("showFinanceAny", \App\Participant::class)
	<li role="presentation"><a href="#onbetaald" aria-controls="onbetaald" role="tab" data-toggle="tab">Betalingen</a></li>
	@endcan

	@role("kampci")
	<li role="presentation"><a href="#kmg" aria-controls="kmg" role="tab" data-toggle="tab">KMG</a></li>
	@endrole
	
	@role(["kantoorci", "kampci"])
	<li role="presentation"><a href="#eventloos" aria-controls="eventloos" role="tab" data-toggle="tab">Kamploos</a></li>
	@endrole

	@can("showPrivateAny", \App\Participant::class)
	<li role="presentation"><a href="#mailing" aria-controls="mailing" role="tab" data-toggle="tab">Mailing</a></li>
	@endcan

	@can("viewAny", \App\Member::class)
	<li role="presentation"><a href="#aspirant" aria-controls="aspirant" role="tab" data-toggle="tab">Aspiranten</a></li>
	@endcan

	@can("viewAny", \App\Member::class)
	<li role="presentation"><a href="#old-members" aria-controls="mailing" role="tab" data-toggle="tab">Oud-leden</a></li>
	@endcan

	@can("showPrivateAny", \App\Member::class)
	<li role="presentation"><a href="#ranonkeltje" aria-controls="ranonkeltje" role="tab" data-toggle="tab">Ranonkeltje</a></li>
	@endcan

	@can("showSpecialAny", \App\Member::class)
	<li role="presentation"><a href="#trainers" aria-controls="trainers" role="tab" data-toggle="tab">Ervaren trainers</a></li>
	@endcan

	@can("viewAny", \App\Member::class)
	<li role="presentation"><a href="#verjaardag" aria-controls="verjaardag" role="tab" data-toggle="tab">Verjaardagen</a></li>
	@endcan

	<li role="presentation"><a href="#more" aria-controls="more" role="tab" data-toggle="tab">Meer lijsten?</a></li>
</ul>

<div class="tab-content" style="margin-top:20px;">

	<div role="tabpanel" class="tab-pane active" id="stats">
		<h3>Willekeurige AAS 2.0 statistieken</h3>
		<table class="table table-hover">
			<tbody>
				@foreach ($stats['most'] as $type => $thing)
				<tr>
					<th>Meeste {{ $types[$type] }} ({{ $thing['count'] }})</th>
					<td>
						@foreach ($thing['members'] as $m)
						{{ $m->volnaam }}
						<br />
						@endforeach
					</td>
				</tr>
				@endforeach
				<tr>
					<th>Gemiddeld aantal dagen tussen registratie en kamp (deelnemers)</th>
					<td>{{ $stats['average_days_reg'] }}
				</tr>
				<tr>
					<th>Gemiddeld cijfer uit enquêtes</th>
					<td>{{ $stats['average_review_score'] }} (uit {{ $stats['num_reviews'] }} enquêtes)
				</tr>
			</tbody>
		</table>
	</div>

	@can("showFinanceAny", \App\Participant::class)
	<div role="tabpanel" class="tab-pane" id="onbetaald">
		<h3>Deelnemers die nog niet betaald hebben</h3>
		@if ($unpaidList)
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Deelnemer</th>
					<th>Kamp</th>
					<th>Inschrijfdatum</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($unpaidList as $unpaid)
				<tr>
					<td><a href="{{ url('/participants', $unpaid->participant_id) }}">{{ $unpaid->voornaam }}
							{{ $unpaid->tussenvoegsel }} {{ $unpaid->achternaam }}</a></td>
					<td><a href="{{ url('/events', $unpaid->event_id) }}">{{ $unpaid->naam }} ({{ $unpaid->code }})</a>
					</td>
					<td>{{ substr($unpaid->inschrijving,8,2).'-'.substr($unpaid->inschrijving,5,2).'-'.substr($unpaid->inschrijving,0,4) }}
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@else
		<p>Alle deelnemers hebben betaald. Gelukkig!</p>
		@endif
	</div>
	@endcan

	@role("kampci"))
	<div role="tabpanel" class="tab-pane" id="kmg">
		<h3>Leden die nog geen KMG gehad hebben</h3>
		@if ($kmgList->count())
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Soort lid</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($kmgList as $member)
				<tr>
					<td><a href="{{ url('/members', $member->id) }}">{{ $member->voornaam }}
							{{ $member->tussenvoegsel }} {{ $member->achternaam }}</a></td>
					<td>{{ $member->soort }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@else
		<p>Alle leden hebben een KMG gehad. Gelukkig!</p>
		@endif
	</div>
	@endrole
	
	@role(["kantoorci", "kampci"]))
	<div role="tabpanel" class="tab-pane" id="eventloos">

		<p>
			Let op, een inschrijfdatum van 01-01-2000 betekent 'datum onbekend' (inschrijving komt dan van vóór AAS 2.0)
		</p>

		<div class="row">
			@can("viewAny", \App\Member::class)
			<div class="col-md-6">
				<h3>Evenementloze leden<br /><small>Exclusief oud-leden</small></h3>
				@if ($membersWithoutEvents->count())
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Naam</th>
							<th>Soort lid</th>
							<th>Geregistreerd op</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($membersWithoutEvents as $member)
						<tr>
							<td><a href="{{ url('/members', $member->id) }}">{{ $member->volnaam }}</a></td>
							<td>{{ $member->soort }}</td>
							<td>{{ $member->created_at->format('d-m-Y') }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				@else
				<p>Geen geregistreerde leden zonder evenement. Super!</p>
				@endif
			</div>
			@endcan
			
			@can("viewAny", \App\Participant::class)
			<div class="col-md-6">
				<h3>Kamploze deelnemers</h3>
				@if ($participantsWithoutCamps->count())
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Naam</th>
							<th>Geregistreerd op</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($participantsWithoutCamps as $part)
						<tr>
							<td><a href="{{ url('/participants', $part->id) }}">{{ $part->volnaam }}</a></td>
							<td>{{ $part->created_at->format('d-m-Y') }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				@else
				<p>Geen geregistreerde deelnemers zonder kamp. Super!</p>
				@endif
			</div>
			@endcan
			
		</div>

	</div>
	@endrole
	
	@can("showPrivateAny", \App\Participant::class)
	<div role="tabpanel" class="tab-pane" id="mailing">
		<h3>Mailing aan deelnemers</h3>
		<p>{{ $participantMailingList->count() }} deelnemers jonger dan 19, die gemaild mogen worden.</p>
		<p>Denk eraan, adressen <strong>altijd in de BCC</strong> zetten!</p>

		<h4>Emailadressen ouders</h4>

		<div class="list">
			<div class="copy-btn">
				<button class="btn" onclick="copyToClipboard(this, '#list-parents')">Copy</button>
			</div>
			<pre id="list-parents">{{ implode(", ", $participantMailingList->pluck('email_ouder')->toArray()) }}</pre>
		</div>

		<h4>Emailadressen deelnemers</h4>
		
		<div class="list">
			<div class="copy-btn">
				<button class="btn" onclick="copyToClipboard(this, '#list-participants')">Copy</button>
			</div>
			<pre id="list-participants">{{ implode(", ", $participantMailingList->pluck('email_deelnemer')->toArray()) }}</pre>
		</div>
		
	</div>

	<div role="tabpanel" class="tab-pane" id="old-members">
		<h3>Oud-leden</h3>
		<p>{{ $oldMembers->count() }} oud-leden.</p>

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Geregistreerd op</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($oldMembers as $memb)
					<tr>
						<td>{{ $memb->volnaam }}</td>
						<td>{{ $memb->created_at }}</td>
					</tr>	
				@endforeach
			</tbody>
		</table>
		
		
		<h4>Emailadressen</h4>
		<p>Denk eraan, adressen <strong>altijd in de BCC</strong> zetten!</p>

		<div class="list">
			<div class="copy-btn">
				<button class="btn" onclick="copyToClipboard(this, '#list-old-members')">Copy</button>
			</div>
			<pre id="list-old-members">{{ implode(", ", $oldMembers->pluck('email')->toArray()) }}</pre>
		</div>

	</div>
	@endcan
	
	@can("viewAny", \App\Member::class)
	<div role="tabpanel" class="tab-pane" id="aspirant">
		<h3>Aspirant-leden</h3>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Emailadres Anderwijs</th>
					<th>Lid sinds</th>
					<th>Laatste update</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($aspirantList as $member)
				<tr>
					<td><a href="{{ url('/members', $member->id) }}">{{ $member->voornaam }}
							{{ $member->tussenvoegsel }} {{ $member->achternaam }}</a></td>
					<td><a href="mailto:{{$member->email_anderwijs}}">{{ $member->email_anderwijs }}</a></td>
					<td>{{ $member->created_at->format('d-m-Y') }}</td>
					<td>{{ $member->updated_at->format('d-m-Y') }}</td>
				</tr>
				@empty
				<p>Alle leden hebben een KMG gehad. Gelukkig!</p>
				@endforelse
			</tbody>
		</table>
	</div>
	@endcan

	@can("showPrivateAny", \App\Member::class)
	<div role="tabpanel" class="tab-pane" id="ranonkeltje">
		<h3>Leden die Ranonkeltje op papier willen ontvangen ({{ count($ranonkeltjePapier) }})</h3>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Adres</th>
					<th>Postcode</th>
					<th>Woonplaats</th>
					<th>Soort lid</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($ranonkeltjePapier as $member)
				<tr>
					<td><a href="{{ url('/members', $member->id) }}">{{ $member->volnaam }}</a></td>
					<td>{{ $member->adres }}</td>
					<td>{{ $member->postcode }}</td>
					<td>{{ $member->plaats }}</td>
					<td>{{ $member->soort }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		<h3>Leden die Ranonkeltje digitaal willen ontvangen ({{ count($ranonkeltjeDigitaal) }})</h3>
		<p>Een makkelijk te kopiëren mailinglijst staat onder de tabel.</p>

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Emailadres AW</th>
					<th>Soort lid</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($ranonkeltjeDigitaal as $member)
				<tr>
					<td><a href="{{ url('/members', $member->id) }}">{{ $member->volnaam }}</a></td>
					<td>{{ $member->email_anderwijs }}</td>
					<td>{{ $member->soort }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		<h4>Mailinglijst</h4>
		<p>{{ implode(', ', $ranonkeltjeDigitaal->pluck('email_anderwijs')->toArray()) }}</p>
	</div>
	@endcan
	
	@can("showSpecialAny", \App\Member::class)
	<div role="tabpanel" class="tab-pane" id="trainers">

		<p>Geeft iemand aan niet meer te willen trainen? Haal dan het vinkje 'ervaren trainer' bij die persoon weg.</p>

		<h3>Ervaren trainers (huidige leden)</h3>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Telefoon</th>
					<th>Email Anderwijs</th>
					<th>Soort lid</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($trainerList as $m)
				<tr>
					<td><a href="{{ url('/members', $m->id) }}">{{ $m->volnaam }}</a></td>
					<td>{{ $m->telefoon }}</td>
					<td><a href="mailto:{{$m->email_anderwijs}}">{{ $m->email_anderwijs }}</a></td>
					<td>{{ $m->soort }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		<h4>Mailinglijst</h4>
		
		<div class="list">
			<div class="copy-btn">
				<button class="btn" onclick="copyToClipboard(this, '#list-experienced-trainers')">Copy</button>
			</div>
			<pre id="list-experienced-trainers">{{ implode(', ', $trainerList->pluck('email_anderwijs')->toArray()) }}</pre>
		</div>

		<h3>Ervaren trainers (oud-leden)</h3>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Telefoon</th>
					<th>Email</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($oldTrainerList as $m)
				<tr>
					<td><a href="{{ url('/members', $m->id) }}">{{ $m->volnaam }}</a></td>
					<td>{{ $m->telefoon }}</td>
					<td><a href="mailto:{{$m->email}}">{{ $m->email }}</a></td>
				</tr>
				@endforeach
			</tbody>
		</table>

		<h4>Mailinglijst <small>(denk aan de BCC!)</small></h4>

		<div class="list">
			<div class="copy-btn">
				<button class="btn" onclick="copyToClipboard(this, '#list-old-trainers')">Copy</button>
			</div>
			<pre id="list-old-trainers">{{ implode(', ', $oldTrainerList->pluck('email')->toArray()) }}</pre>
		</div>
	</div>
	@endcan
	
	@can("viewAny", \App\Member::class)
	<div role="tabpanel" class="tab-pane" id="verjaardag">
		<h3>Verjaardagskalender</h3>
		<p><i>Normale en aspirantleden</i></p>
		<div class="row">
			<div class="col-md-6">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Naam</th>
							<th colspan="2">Verjaardag</th>
							<th>is nu</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($birthdayList as $member)
						<tr class="{{ ($member['vandaag']) ? 'info' : '' }}">
							<td><a href="{{ url('/members', $member['id']) }}">{{ $member['naam'] }}</a></td>
							<td>{{ $member['dag'] }}</td>
							<td>{{ $monthName[$member['maand']] }}</td>
							<td>{{ $member['leeftijd']}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@endcan
	
	<div role="tabpanel" class="tab-pane" id="more">
		Wil je een nieuwe lijst in dit rijtje? Vraag het even lief aan een <a href="mailto:aasbazen@anderwijs.nl">aasbaas</a>.
		En: hoe specifieker je verzoek, hoe beter.
	</div>

</div>

<style>
	.copy-btn {
		float: right;
		position: relative;
	}
	.copy-btn .btn {
		position: absolute;
		right:0;
		top: 0;
		background: transparent;
		color: #818a91;
		font-size: 12px;
		padding: 4px 8px;
	}

	.copy-btn .btn:hover {
		background: #027de7;
		color: white;
	}


</style>
<script>
	function copyToClipboard(el, id) {
		var copyEl = document.querySelector(id);
		var copyText = copyEl.textContent;

		var range = document.createRange();
		range.selectNodeContents(copyEl);
		var sel = window.getSelection();
		sel.removeAllRanges();
		sel.addRange(range);

		document.execCommand("copy");

		$(el).tooltip({
			title: "Gekopieerd",
			trigger: "manual"
		});
		$(el).tooltip("show");

		setTimeout(function() {
			$(el).tooltip("hide");
		}, 3000);
		
	}
</script>

@endsection
