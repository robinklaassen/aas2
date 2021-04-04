<p>
	Beste {{ $member->volnaam }},
</p>

<p>
	Zojuist heb je je via <a href="https://aas2.anderwijs.nl" target="_blank">AAS 2.0</a> aangemeld als leiding voor het volgende kamp:
</p>

<p>
	Naam kamp: {{ $event->naam }}<br/>
	Locatie: {{ $event->location->plaats }}<br/>
	Voordag: {{ $event->datum_voordag->format('d-m-Y') }}<br/>
	Startdatum: {{ $event->datum_start->format('d-m-Y') }}<br/>
	Einddatum: {{ $event->datum_eind->format('d-m-Y') }}
</p>

<p>
	Vergeet niet om naast het volledige kamp ook de <b>training</b> in je agenda te blokken! De datum daarvan vind je in onze <a href="https://www.anderwijs.nl/vrijwilligers/ledenagenda/" target="_blank">ledenagenda</a>.
</p>

<p>
	Je ontvangt vanzelf meer informatie over de training en het kamp. Heb je eerder al een vraag, stuur dan een mailtje naar de <a href="mailto:kamp@anderwijs.nl">kampcommissie</a>.
</p>

<p>
	Met vriendelijke groet,<br/>
	Anderwijs
</p>