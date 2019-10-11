<p>
	Lieve kantoorcommissie,
</p>

<p>
	Zojuist heeft de ouder/verzorger van <a href="{{ url('/participants', $participant->id) }}">{{ $participant->voornaam }} {{ $participant->tussenvoegsel }} {{ $participant->achternaam }}</a> {{ ($participant->geslacht=='M') ? 'hem' : 'haar' }} via {{ ($participant->geslacht=='M') ? 'zijn' : 'haar' }} profiel aangemeld voor <a href="{{ url('/events', $event->id) }}">{{ $event->naam }}</a> ({{ $event->code }})!
</p>

<p>
	Met vriendelijke groet,<br/>
	AAS 2.0
</p>

<p><small>-dit is een geautomiseerd bericht, beantwoording is zinloos-</small></p>