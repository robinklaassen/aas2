<p>
	Lieve kampcommissie,
</p>

<p>
	Zojuist heeft <a href="{{ url('/members', $member->id) }}">{{ $member->voornaam }} {{ $member->tussenvoegsel }} {{ $member->achternaam }}</a> zich via {{ ($member->geslacht=='M') ? 'zijn' : 'haar' }} profiel aangemeld voor <a href="{{ url('/events', $event->id) }}">{{ $event->naam }}</a> ({{ $event->code }})!
</p>

<p>
	Met vriendelijke groet,<br/>
	AAS 2.0
</p>

<p><small>-dit is een geautomiseerd bericht, beantwoording is zinloos-</small></p>