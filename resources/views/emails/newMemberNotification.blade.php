<p>
	Lieve kampcommissie,
</p>

<p>
	Zojuist heeft een nieuwe vrijwilliger, {{ $member->voornaam }} {{ $member->tussenvoegsel }} {{ $member->achternaam }}, zich ingeschreven voor {{ $camp->naam }} ({{ $camp->code }}). {{ ($member->geslacht == 'M') ? 'Zijn' : 'Haar' }} gegevens vind je <a href="{{ url('/members', $member->id) }}">hier</a>.
</p>

<p>
	Met vriendelijke groet,<br/>
	AAS 2.0
</p>

<p><small>-dit is een geautomiseerd bericht, beantwoording is zinloos-</small></p>