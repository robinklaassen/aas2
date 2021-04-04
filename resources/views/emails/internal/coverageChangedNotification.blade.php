<p>
	Lieve kampcommissie,
</p>

<p>
	<a href="{{ url('/members', $member->id) }}" target="_blank">{{ $member->volnaam }}</a>, die binnenkort op <a href="{{ url('/events', $event->id) }}" target="_blank">{{ $event->naam }}</a> ({{ $event->code }}) gaat, heeft zojuist het volgende gewijzigd in de vakdekking:
</p>

@if ($courseLevelFrom == 0)
	<p>
		<strong>{{ $course->naam }} (niveau {{ $courseLevelTo }}) toegevoegd</strong>
	</p>
@elseif ($courseLevelTo == 0)
	<p>
		<strong>{{ $course->naam }} (niveau {{ $courseLevelFrom }}) verwijderd</strong>
	</p>
@else
	<p>
		<strong>{{ $course->naam }} van niveau {{ $courseLevelFrom }} naar niveau {{ $courseLevelTo }}</strong>
	</p>
@endif

<p>
	Hierdoor is de <a href="{{ url('events', [$event->id, 'check', 'all']) }}" target="_blank">vakdekking voor het kamp</a> <strong>{{ ($statusAfter) ? 'verbeterd' : 'verslechterd' }}</strong>.
</p>

<p>
	Met vriendelijke groet,<br/>
	AAS 2.0
</p>