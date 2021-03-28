<p>
	Lieve kantoorcommissie,
</p>

<p>
	Zojuist heeft een nieuwe deelnemer, {{ $participant->volnaam }}, zich ingeschreven voor {{ $event->naam }} ({{ $event->code }}). {{ ($participant->geslacht == 'M') ? 'Zijn' : 'Haar' }} gegevens vind je <a href="{{ url('/participants', $participant->id) }}" target="_blank">hier</a>.
</p>

<p>
	Met vriendelijke groet,<br/>
	AAS 2.0
</p>