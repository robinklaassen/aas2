<p>
	Lieve kampcommissie,
</p>

<p>
	<a href="{{ url('/members', $member->id) }}">{{ $member->voornaam }} {{ $member->tussenvoegsel }} {{ $member->achternaam }}</a>, die binnenkort op <a href="{{ url('/events', $camp->id) }}">{{ $camp->naam }}</a> ({{ $camp->code }}) gaat, heeft zojuist het volgende gewijzigd in {{ ($member->geslacht == 'M') ? 'zijn' : 'haar' }} vakdekking:
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
	Hierdoor is de vakdekking voor het kamp <strong>{{ ($statusAfter) ? 'verbeterd' : 'verslechterd' }}</strong>, klik <a href="{{ url('events', [$camp->id, 'check', 'all']) }}">hier</a> om die te bekijken.
</p>

<p>
	Met vriendelijke groet,<br/>
	AAS 2.0
</p>

<p><small>-dit is een geautomiseerd bericht, beantwoording is zinloos-</small></p>