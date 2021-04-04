<p>
	Beste {{ $member->volnaam }},
</p>

<p>
	Zojuist heb je je ingeschreven als nieuwe vrijwilliger bij Anderwijs. Van harte welkom! Onderaan deze mail vind je de informatie die je bij ons hebt opgegeven. Blok alvast het kamp en de training in je agenda, je vindt alle relevante data (en meer) in onze <a href="https://www.anderwijs.nl/vrijwilligers/ledenagenda/" target="_blank">ledenagenda</a>.
</p>

<p>
	Er is automatisch een account voor je aangemaakt. Hiermee kun je inloggen op ons <a href="https://aas2.anderwijs.nl" target="_blank">administratiesysteem</a> om je gegevens te beheren en in de toekomst makkelijk vaker op kamp te gaan. Het is handig om direct in te loggen en een nieuw, persoonlijk wachtwoord te kiezen.
	<br/><br/>
	Gebruikersnaam: {{$member->user->username}}<br/>
	Wachtwoord: {{$password}}
</p>

<p>
	Iemand van ons neemt binnenkort contact met je op om het verdere proces uit te leggen en eventuele vragen te beantwoorden. Eerder al contact? Stuur dan een mailtje naar de <a href="mailto:kamp@anderwijs.nl">kampcommissie</a>.
</p>

<p>
	Met vriendelijke groet,<br/>
	Anderwijs
</p>

<p>
	--------------------
</p>

<p>
	PERSOONS- EN CONTACTGEGEVENS<br/>
	Geboortedatum: {{ $member->geboortedatum->format('d-m-Y') }}<br/>
	Geslacht: {{ ($member->geslacht == 'M') ? 'man' : 'vrouw' }}<br/>
	Adres: {{ $member->adres }}<br/>
	Postcode: {{ $member->postcode }}<br/>
	Woonplaats: {{ $member->plaats }}<br/>
	Telefoonnummer: {{ $member->telefoon }}<br/>
	Emailadres: {{ $member->email }}
</p>

<p>
	KAMP<br/>
	Naam kamp: {{ $event->naam }}<br/>
	Locatie: {{ $event->location->plaats }}<br/>
	Startdatum: {{ $event->datum_start->format('d-m-Y') }}<br/>
	Einddatum: {{ $event->datum_eind->format('d-m-Y') }}
</p>

<p>
	BIJSPIJKERINFORMATIE<br/>
	Eindexamen: {{ $member->eindexamen }}<br/>
	Studie: {{ $member->studie }}<br/>
	Afgestudeerd: {{ ($member->afgestudeerd) ? 'ja' : 'nee' }}<br/>
	Vakken en niveaus: @foreach ($givenCourses as $course) {{ $course['naam'] }} ({{ $course['klas'] }}) @endforeach
</p>

<p>
	OVERIGE INFORMATIE<br/>
	Hoe bij Anderwijs terechtgekomen: {{ $member->hoebij }}<br/>
	Opmerkingen: {{ $member->opmerkingen }}
</p>