<p>
	Lieve kantoorcommissie,
</p>

<p>
	Zojuist is <a href="{{ url('/participants', $participant->id) }}" target="_blank">{{ $participant->volnaam }}</a> via het AAS-profiel aangemeld voor <a href="{{ url('/events', $event->id) }}" target="_blank">{{ $event->naam }}</a> ({{ $event->code }})!
</p>

<p>
	Met vriendelijke groet,<br/>
	AAS 2.0
</p>