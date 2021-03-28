<p>
	Lieve kampcommissie,
</p>

<p>
	Zojuist heeft een nieuwe vrijwilliger, {{ $member->volnaam }}, zich ingeschreven voor {{ $event->naam }} ({{ $event->code }}). {{ ($member->geslacht == 'M') ? 'Zijn' : 'Haar' }} gegevens vind je <a href="{{ url('/members', $member->id) }}" target="_blank">hier</a>.
</p>

<p>
	Met vriendelijke groet,<br/>
	AAS 2.0
</p>