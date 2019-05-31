<p>
	Beste {{ $member->voornaam }} {{ $member->tussenvoegsel }} {{ $member->achternaam }},
</p>

<p>
	Zojuist heb je je ingeschreven als nieuwe vrijwilliger bij Anderwijs. Welkom! De volgende informatie is door jou opgegeven:
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

<p>
	--------------------
</p>

<p>
	Er is automatisch een account voor je aangemaakt. Hiermee kun je inloggen op ons <a href="http://aas2.anderwijs.nl">administratiesysteem</a> om je gegevens te beheren en in de toekomst makkelijk vaker op kamp te gaan. Je wordt geadviseerd om direct in te loggen en een nieuw, persoonlijk wachtwoord te kiezen.
	<br/><br/>
	Gebruikersnaam: {{$member->user()->username}}<br/>
	Wachtwoord: {{$password}}
</p>

<p>
	Iemand van ons neemt binnenkort contact met je op om het verdere proces uit te leggen en eventuele vragen te beantwoorden. Eerder al contact? Stuur dan een mailtje naar de <a href="mailto:kamp@anderwijs.nl">kampcommissie</a>.
</p>

<p>
	Met vriendelijke groet,<br/>
	Anderwijs
</p>

<p><small>-dit is een geautomiseerd bericht, beantwoording is zinloos-</small></p>