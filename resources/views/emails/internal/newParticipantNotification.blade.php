<p>
	Lieve kantoorcommissie,
</p>

<p>
	Zojuist heeft een nieuwe deelnemer, {{ $participant->voornaam }} {{ $participant->tussenvoegsel }} {{ $participant->achternaam }}, zich ingeschreven voor {{ $event->naam }} ({{ $event->code }}). {{ ($participant->geslacht == 'M') ? 'Zijn' : 'Haar' }} gegevens vind je <a href="{{ url('/participants', $participant->id) }}">hier</a>.
</p>

<p>
	Met vriendelijke groet,<br/>
	AAS 2.0
</p>

<p><small>-dit is een geautomiseerd bericht, beantwoording is zinloos-</small></p>